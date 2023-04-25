<?php

declare(strict_types=1);

namespace App\Initiative\Domain\Initiative;

use App\Identity\Domain\Customer\AbstractCustomer;
use App\Identity\Domain\Customer\Customer;
use App\Identity\Domain\Customer\CustomerCollection;
use App\Identity\Domain\Customer\Following;
use App\Identity\Domain\User\BaseUser;
use App\Initiative\Application\Initiative\InitiativeException;
use App\Initiative\Domain\Category\Category;
use App\Initiative\Domain\Category\CategoryCollection;
use App\Initiative\Domain\Comment\Comment;
use App\Initiative\Domain\Comment\CommentCollection;
use App\Initiative\Domain\Image\Image;
use App\Initiative\Domain\Image\ImageCollection;
use App\Initiative\Domain\Questionnaire\Option;
use App\Initiative\Domain\Questionnaire\Questionnaire;
use App\Shared\Domain\Entity;
use App\Shared\Domain\PreUploadedImage\PreUploadedImage;
use App\Shared\Domain\PreUploadedImage\PreUploadedImageCollection;
use CrEOF\Spatial\PHP\Types\Geometry\Point;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

class Initiative implements Entity
{
    private InitiativeId $id;

    private string $type;

    private string $title;

    private string $description;

    private ?Point $location = null;

    private ?string $location_name = null;

    private ?float $location_radius_value = null;

    private ?string $location_radius_unit = null;

    private DateTime $date_start;

    private DateTime $date_end;

    private ?int $minimal_joined_people = null;

    private Collection $categories;

    private Collection $images;

    private ?AbstractCustomer $customer;

    private Collection $participants;
    private Collection $followers;

    private DateTime $created;

    private DateTime $updated;

    private Collection $comments;

    private Collection $favourited;

    private string $status = StatusList::PUBLISHED;

    private bool $is_archived = false;
//    private array $statuses = [];
    private Collection $questionnaires;

    public function __construct(InitiativeId $id,
                                Customer $customer,
                                CategoryCollection $categories,
                                PreUploadedImageCollection $images,
                                Briefing $briefing,
                                Duration $duration,
                                Location $location = null)
    {
        $this->id = $id;
        $this->customer = $customer;
        $this->rebrief($briefing);
        $this->reschedule($duration);
        $this->categories = new ArrayCollection($categories->toArray());
        $this->participants = new ArrayCollection([$customer]);
        $this->followers = new ArrayCollection([new Following($this, $customer)]);
        $this->comments = new ArrayCollection();

        if ($location) {
            $this->relocate($location);
        }

        $this->images = new ArrayCollection();
        $this->addImages($images);

        $this->questionnaires = new ArrayCollection();
    }

    public function id(): InitiativeId
    {
        return $this->id;
    }

    // TODO get rid of exposing VO's like Duration
    // TODO make methods using VOs inside Initiative Aggregate instead, like Initiative::duration but return not Duration but string
    // TODO think more about that
    public function duration(): Duration
    {
        return new Duration(
            $this->date_start->format('Y-m-d H:i:s'),
            $this->date_end->format('Y-m-d H:i:s'),
        );
    }

    public function images(): ImageCollection
    {
        return new ImageCollection(...$this->images->toArray());
    }

//    public function author(): Customer
    public function author(): AbstractCustomer
    {
        return $this->customer;
    }

    public function createdAt(): DateTime
    {
        return $this->created;
    }

    public function isOwnedBy(AbstractCustomer $customer): bool
    {
        return $this->customer->equals($customer);
    }

    public function briefing(): Briefing
    {
        return new Briefing(
            $this->type,
            $this->title,
            $this->description,
            $this->minimal_joined_people
        );
    }

    public function rebrief(Briefing $briefing): void
    {
        $this->type = $briefing->type();
        $this->title = $briefing->title();
        $this->description = $briefing->description();
        $this->minimal_joined_people = $briefing->minimalJoinedPeople();
    }

    public function reschedule(Duration $duration): void
    {
        $this->date_start = $duration->start();
        $this->date_end = $duration->end();
    }

    public function location(): ?Location
    {
        return $this->location
            ? new Location(
                (string) $this->location,
                $this->location_name ?? '',
                $this->location_radius_value,
                $this->location_radius_unit
            )
            : null;
    }

    public function relocate(Location $location): void
    {
        $this->location = new Point($location->latitude(), $location->longitude());
        $this->location_name = $location->name();
        $this->location_radius_value = $location->radiusValue();
        $this->location_radius_unit = $location->radiusUnit();
    }

    public function moveToCategories(CategoryCollection $categories): void
    {
        $this->categories = new ArrayCollection($categories->toArray());
    }

    public function category(): Category
    {
        return $this->categories->get(0);
    }

    public function addImages(PreUploadedImageCollection $images): void
    {
        array_map(function (PreUploadedImage $preUploadedImage) {
            $this->images->add(new Image(
                    $preUploadedImage->id(),
                    $this,
                    $preUploadedImage->name()
                ));
        }, $images->toArray());
    }

    public function unlocate(): void
    {
        $this->location = null;
        $this->location_name = null;
        $this->location_radius_unit = null;
        $this->location_radius_value = null;
    }

    public function removeImage(Image $image): void
    {
        $this->images->removeElement($image);
    }

    public function equals(Initiative $initiative): bool
    {
        return $this->id->equals($initiative->id());
    }

    public function participants(): CustomerCollection
    {
        return new CustomerCollection(...$this->participants->toArray());
    }

    public function followers(): CustomerCollection
    {
        return new CustomerCollection(...$this->followers->map(
            fn(Following $following) => $following->customer()
        )->toArray());
//        return new CustomerCollection(...$this->followers->toArray());
    }

    public function comment(Comment $comment): void
    {
        $customer = $comment->author();

        $this->comments->add($comment);

        //fixme
        if (!$this->isFollowing($customer)) {
            $this->addFollower($customer);
        }
    }

//    public function removeComment(Comment $comment): void
//    {
//        $this->comments->removeElement($comment);
//    }

    public function comments(): CommentCollection
    {
        return new CommentCollection(...$this->comments->toArray());
    }

    public function join(Customer $customer): void
    {
        if ($this->isExpired()) {
           throw InitiativeException::joinToExpired();
        }

        $this->participants->add($customer);

        if (!$this->isFollowing($customer)) {
            $this->addFollower($customer);
        }
    }

    public function quit(Customer $customer): void
    {
        $this->participants->removeElement($customer);
        $this->followers->removeElement($customer);
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

//    public function getStatus(): array
//    {
//        return $this->statuses;
//    }

//    public function setStatus(array $statuses): void
//    {
//        $this->statuses = $statuses;
//    }

    // TODO use InitiativeStatus type objects instead string
//    public function setStatus(string $status): void
//    public function setStatusTESTING(Status $status, InitiativeWorkflow $workflow): void
//    {
//        if ($workflow->can($this, $status->transaction())) {
//            $workflow->apply($this, $status->transaction());
//            //            $this->status = (string) $status;
//        }
//    }

//    public function archive(): void
//    {
////        if ($workflow->can($this, StatusTransaction::ARCHIVE)) {
//            $this->status = StatusList::ARCHIVED;
//            // TODO OR
//            $this->is_archived = true;
////        }
//    }
    public function isExpired(): bool
    {
        return $this->duration()->isExpired();
    }

    public function isArchived(): bool
    {
        return $this->is_archived;
    }

    public function archive(): void
    {
        $this->is_archived = true;
    }

    public function restore(): void
    {
        $this->is_archived = false;
    }

    public function addFollower(Customer $customer): void
    {
        $this->followers->add(new Following($this, $customer));
    }

    public function removeFollower(Customer $customer): void
    {
        $following = $this->followers->filter(
            fn(Following $following) => $following->customer()->equals($customer) && $following->initiative()->equals($this)
        )->first();
        $this->followers->remove($this->followers->indexOf($following));
        $following->delete();
        $a=1;
//        $this->followers->removeElement($customer);
    }

    public function isJoined(Customer $customer): bool
    {
        return $this->participants->contains($customer);
    }

    public function isFollowing(Customer $customer): bool
    {
        return (new ArrayCollection($this->followers()->toArray()))->contains($customer);
    }

//    public function updateQuestionnaires(Questionnaire ...$questionnaires): void
//    {
//        $oldQuestionnaire = $oldOption = null;
//        $k = $k1 = 0;
//
//        foreach ($questionnaires as $k => $questionnaire) {
//            /** @var Questionnaire $oldQuestionnaire */
//            $oldQuestionnaire = $this->questionnaires->get($k);
//
//            if (!$oldQuestionnaire) {
//                $this->questionnaires->add($questionnaire);
//                continue;
//            }
//
//            if ($oldQuestionnaire->question() !== $questionnaire->question()) {
//                $oldQuestionnaire->changeQuestion($questionnaire->question());
//            }
//
//            if ($oldQuestionnaire->type() !== $questionnaire->type()) {
//                $oldQuestionnaire->changeType($questionnaire->type());
//            }
//
//            foreach ($questionnaire->options() as $k1 => $option) {
//                /** @var Option $oldOption */
//                $oldOption = $oldQuestionnaire->options()->get($k1);
//
//                if (!$oldOption) {
//                    $oldQuestionnaire->options()->add($option);
//                    continue;
//                }
//
//                if ($oldOption->title() !== $option->title()) {
//                    $oldOption->changeTitle($option->title());
//                }
//            }
//
//            if ($oldOption) {
//                /** @var Option $oldOption */
//                while ($oldOption = $oldQuestionnaire->options()->get(++$k1)) {
//                    $oldQuestionnaire->options()->remove($k1);
////                    $oldOption->remove();
//                }
//            }
//        }
//
//        if ($oldQuestionnaire) {
//            /** @var Questionnaire $oldQuestionnaire */
//            while ($oldQuestionnaire = $this->questionnaires->get(++$k)) {
//                $this->questionnaires->remove($k);
////                $oldQuestionnaire->remove();
//            }
//        }
//    }

    public function assignQuestionnaires(Questionnaire ...$questionnaires): void
    {
        $this->questionnaires = new ArrayCollection($questionnaires);
    }

    public function removeQuestionnairesByIDs(Uuid ...$ids): void
    {
        $ids = array_map(fn(Uuid $id) => $id->toRfc4122(), $ids);

        $questionnaires = $this->questionnaires->filter(
            fn(Questionnaire $questionnaire) => in_array($questionnaire->id()->toRfc4122(), $ids)
        );

        foreach ($questionnaires as $questionnaire) {
            $this->questionnaires->removeElement($questionnaire);
        }
    }

    public function addQuestionnaires(Questionnaire ...$questionnaires): void
    {
        foreach ($questionnaires as $questionnaire) {
            $this->questionnaires->add($questionnaire);
        }
    }

    public function questionnaires(): Collection
    {
        return $this->questionnaires;
    }
}
