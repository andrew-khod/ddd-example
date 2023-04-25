<?php

namespace App\Initiative\UI\Web\Initiative\Normalizer;

use App\Identity\Application\User\AuthenticatedCustomer;
use App\Identity\Application\User\Exception\UserException;
use App\Identity\Domain\Customer\Customer;
use App\Identity\UI\Web\Customer\Normalizer\CustomerCollectionNormalizer;
use App\Identity\UI\Web\Customer\Normalizer\CustomerNormalizer;
use App\Initiative\Domain\Initiative\Initiative;
use App\Initiative\Domain\Questionnaire\Answer;
use App\Initiative\Domain\Questionnaire\Option;
use App\Initiative\Domain\Questionnaire\Questionnaire;
use App\Initiative\UI\Web\Category\Normalizer\CategoryNormalizer;
use App\Initiative\UI\Web\Image\Normalizer\ImageCollectionNormalizer;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class InitiativeNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private ImageCollectionNormalizer $imageCollectionNormalizer;
    private CustomerNormalizer $customerNormalizer;
    private CategoryNormalizer $categoryNormalizer;
    private LocationNormalizer $locationNormalizer;
    private CustomerCollectionNormalizer $customerCollectionNormalizer;
    private CommentCollectionNormalizer $commentCollectionNormalizer;
    private ContainerInterface $container;

    public function __construct(ImageCollectionNormalizer $imageCollectionNormalizer,
                                CustomerNormalizer $customerNormalizer,
                                CommentCollectionNormalizer $commentCollectionNormalizer,
                                CustomerCollectionNormalizer $customerCollectionNormalizer,
                                CategoryNormalizer $categoryNormalizer,
                                ContainerInterface $container,
                                LocationNormalizer $locationNormalizer)
    {
        $this->imageCollectionNormalizer = $imageCollectionNormalizer;
        $this->customerNormalizer = $customerNormalizer;
        $this->categoryNormalizer = $categoryNormalizer;
        $this->locationNormalizer = $locationNormalizer;
        $this->customerCollectionNormalizer = $customerCollectionNormalizer;
        $this->commentCollectionNormalizer = $commentCollectionNormalizer;
        $this->container = $container;
    }

    /**
     * @param Initiative $initiative
     * @param null       $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($initiative, $format = null, array $context = []): array
    {
        /** @var ?Customer $user */
        $user = null;

        try {
            $user = $this->container->get(AuthenticatedCustomer::class)->user();
        } catch (UserException $exception) {

        }

        // TODO use specific AuthorNormalizer and ParticipantCollectionNormalizer to define & present different Customer contexts
        return parent::normalize([
            'id' => (string) $initiative->id(),
            'title' => $initiative
                ->briefing()
                ->title(),
            'description' => $initiative
                ->briefing()
                ->description(),
            'minimal_joined_people' => $initiative
                ->briefing()
                ->minimalJoinedPeople(),
            'location' => $initiative->location()
                ? $this->locationNormalizer
                    ->setWrapped(false)
                    ->normalize($initiative->location(), $format, $context)
                : null,
            'images' => $this->imageCollectionNormalizer
                ->setWrapped(false)
                ->normalize($initiative->images(), $format, $context),
            'date_start' => $initiative
                ->duration()
                ->start(),
            'date_end' => $initiative
                ->duration()
                ->end(),
            'created' => $initiative->createdAt(),
            'category' => $this->categoryNormalizer
                ->setWrapped(false)
                ->normalize($initiative->category(), $format, $context),
            'author' => $this->customerNormalizer
                ->setWrapped(false)
                ->normalize($initiative->author(), $format, $context),
            'participants' => $this->customerCollectionNormalizer
                ->setWrapped(false)
                ->normalize($initiative->participants(), $format, $context),
            'comments' => $this->commentCollectionNormalizer
                ->setWrapped(false)
                ->normalize($initiative->comments(), $format, $context),
            'questionnaires' => $initiative->questionnaires()->map(
                function (Questionnaire $questionnaire) use ($user) {
                    $result = [
                        'id' => $questionnaire->id()->toRfc4122(),
                        'type' => $questionnaire->type(),
                        'question' => $questionnaire->question(),
                        'answer' => null,
                    ];

                    if (!$questionnaire->isFreetext()) {
                        $result['options'] = $questionnaire->options()->map(
                            fn(Option $option) => [
                                'id' => $option->id()->toRfc4122(),
                                'option' => $option->title(),
                                'answers' => $questionnaire->answersCountByOption($option),
                            ],
                        )->toArray();
                    }

                    if ($user && ($answersOfCustomer = $questionnaire->answersOfCustomer($user)) && $answersOfCustomer->count()) {
                        if ($questionnaire->isFreetext()) {
                            $result['answer'] = $answersOfCustomer->first()->text();
                        } elseif ($questionnaire->isSingleSelect()) {
                            $result['answer'] = $answersOfCustomer->first()->option()->id()->toRfc4122();
                        } elseif ($questionnaire->isMultiSelect()) {
                            $result['answer'] = array_values($answersOfCustomer->map(
                                fn(Answer $answer) => $answer->option()->id()->toRfc4122()
                            )->toArray());
                        }
                    }

                    return $result;
                }
            )->toArray(),
            'is_archived' => $initiative->isArchived(),
            'is_expired' => $initiative->duration()->isExpired(),
            'is_favourite' => $user && $user->isFavourite($initiative),
            'is_owned_by_me' => $user && $initiative->author()->equals($user),
            'is_joined' => $user && $initiative->isJoined($user),
            'is_followed' => $user && $initiative->isFollowing($user),
        ], $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Initiative;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
