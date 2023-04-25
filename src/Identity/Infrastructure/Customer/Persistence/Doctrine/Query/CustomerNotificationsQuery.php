<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Customer\Persistence\Doctrine\Query;

use App\Identity\Application\Customer\Query\CustomerByIdCriteria;
use App\Identity\Application\Customer\Query\CustomerNotificationsQuery as CustomerNotificationsQueryAlias;
use App\Identity\Application\Customer\UseCase\DeleteCustomer\InitiativesArchived;
use App\Identity\Domain\Customer\Customer;
use App\Identity\Infrastructure\Company\SwitchableActiveTenant;
use App\Initiative\Application\Comment\Query\CommentByCriteriaQuery;
use App\Initiative\Application\Comment\Query\CommentByIdCriteria;
use App\Initiative\Application\Initiative\UseCase\ArchiveInitiative\InitiativeArchived;
use App\Initiative\Application\Initiative\UseCase\CommentInitiative\InitiativeCommented;
use App\Initiative\Application\Initiative\UseCase\FavouriteInitiative\InitiativeFavourited;
use App\Initiative\Application\Initiative\UseCase\JoinInitiative\InitiativeJoined;
use App\Initiative\Application\Initiative\UseCase\UpdateInitiative\InitiativeUpdated;
use App\Initiative\Domain\Event\Event;
use App\Initiative\Domain\Initiative\InitiativeId;
use App\Shared\Infrastructure\Doctrine\Query\SwitchableTenantBaseQuery;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

final class CustomerNotificationsQuery extends SwitchableTenantBaseQuery implements CustomerNotificationsQueryAlias
{
    private CommentByCriteriaQuery $commentByCriteriaQuery;
    private CustomerByCriteriaQuery $customerByCriteriaQuery;

    public function __construct(
        CommentByCriteriaQuery $commentByCriteriaQuery,
        CustomerByCriteriaQuery $customerByCriteriaQuery,
        ManagerRegistry        $managerRegistry,
        PaginatorInterface     $paginator,
        SwitchableActiveTenant $switchableActiveTenant)
    {
        parent::__construct($managerRegistry, $paginator, $switchableActiveTenant);

        $this->commentByCriteriaQuery = $commentByCriteriaQuery;
        $this->customerByCriteriaQuery = $customerByCriteriaQuery;
    }

    // TODO DRY METHODS
    public function queryAsPayload(Customer $customer): array
    {
        $events = $this->events($customer);
        $result = [];

        /** @var Event $event */
        foreach ($events as $event) {
            $original = $event->event();
            $payload = [];

            if (in_array($original['type'], [
                        InitiativeArchived::class,
                        InitiativesArchived::class,
                        InitiativeUpdated::class,
                    ])
                    && $event->initiative()->isOwnedBy($customer)
            ) {
                continue;
            }

            if (in_array($original['type'], [
                    InitiativeJoined::class,
                    InitiativeFavourited::class
                ])) {
                if (!$event->initiative()->isOwnedBy($customer)) {
                    continue;
                }

                $actor = $this->customerByCriteriaQuery->queryOne(
                    new CustomerByIdCriteria($original['payload']['customer'])
                );

                if (!$actor) {
                    continue;
                }

                $payload['customer'] = [
                    'name' => (string) $actor->username(),
                    'photo' => (string) $actor->photo(),
                ];
            }

            if (InitiativeCommented::class === $original['type']
                && key_exists('comment', $original['payload'])
            ) {
                // fixme n+1 query: store comments in memory and make 1 query in the end
                // if current customer is author of comment, don't show
                $comment = $this->commentByCriteriaQuery->queryOne(new CommentByIdCriteria($original['payload']['comment']));
                // fixme now we store
//                $comment = $original['payload']['comment'];

                if (!$comment || $customer->equals($comment->author()) || !$comment->author() instanceof Customer) {
                    continue;
                }

                $payload['is_author'] = $event->initiative()->isOwnedBy($customer);
                $payload['customer'] = [
                    'name' => (string) $comment->author()->username(),
                    'photo' => (string) $comment->author()->photo(),
                ];
            } elseif (InitiativeUpdated::class === $original['type']) {
                $payload['changes'] = $original['payload']['changes'];
            }

            $result[] = [
                'type' => $original['type']::alias(),
                'initiative' => (string) $event->initiative()->id(),
                'is_read' => $customer->hasReadNotification($event),
                'created' => $event->created(),
                'payload' => $payload,
//                'payload' => array_map(
//                    // todo cast to array instead of string?
//                    fn($value) => is_object($value) ? (string) $value : $value,
//                    $original['payload']
//                ),
            ];
        }

        return $result;

//        return $this->createQueryBuilder('event')
//            ->andWhere('event.initiative IN (:following) OR event.initiative IN (:author)')
//            ->setParameter('following', array_map(fn(InitiativeId $id) => $id->toBinary(), $customer->following()->toIDs()))
//            ->setParameter('author', array_map(fn(InitiativeId $id) => $id->toBinary(), $customer->initiatives()->toIDs()))
//            ->orderBy('event.created', 'DESC')
//            ->getQuery()->getResult();
    }

    public function query(Customer $customer): array
    {
        $events = $this->events($customer);
        $result = [];

        /** @var Event $event */
        foreach ($events as $event) {
            $original = $event->event();

            if ((in_array($original['type'], [
                    InitiativeArchived::class,
                    InitiativesArchived::class,
                    InitiativeUpdated::class,
                ])
                && $event->initiative()->isOwnedBy($customer))
                || (in_array($original['type'], [
                        InitiativeJoined::class,
                        InitiativeFavourited::class,
                    ])
                    && !$event->initiative()->isOwnedBy($customer))
            ) {
                continue;
            }

            if (InitiativeCommented::class === $original['type']
                && key_exists('comment', $original['payload'])
            ) {
                // fixme n+1 query: store comments in memory and make 1 query in the end
                // if current customer is author of comment, don't show
                $comment = $this->commentByCriteriaQuery->queryOne(new CommentByIdCriteria($original['payload']['comment']));

                if (!$comment || $customer->equals($comment->author()) || !$comment->author() instanceof Customer) {
                    continue;
                }
            }

            $result[] = $event;
        }

        return $result;
    }

    protected function getClass(): string
    {
        return Event::class;
    }

    private function events(Customer $customer): array
    {
        //fixme solve n+1 issues
        return $this->createQueryBuilder('event')
//            ->select('event.name')
//            ->addSelect('event.event')
//            ->addSelect('event.created')
//            ->select('event AS e')
//            ->addSelect('CASE WHEN customer = :customer THEN TRUE ELSE FALSE END AS is_author')
            ->join('event.initiative', 'initiative')
            ->join('initiative.customer', 'customer')
            ->leftJoin('initiative.followers', 'following', Join::WITH, 'following.customer = :customer')
            ->leftJoin('event.readStatus', 'readStatus')
            ->where('(initiative IN (:following) OR initiative IN (:created))')
//            ->andWhere('CASE WHEN customer != :customer THEN event.created > following.created ELSE TRUE END')
            ->andWhere('customer = :customer OR event.created > following.created')
            ->andWhere('readStatus IS NULL OR readStatus.is_hidden != TRUE')
            ->orderBy('event.created', 'DESC')
            ->setParameter('following', array_map(fn(InitiativeId $id) => $id->toBinary(), $customer->following()->toIDs()))
            ->setParameter('created', array_map(fn(InitiativeId $id) => $id->toBinary(), $customer->initiatives()->toIDs()))
            ->setParameter('customer', $customer->id()->toBinary())
            ->getQuery()->getResult();
//            ->getQuery()->getArrayResult();
    }
}
