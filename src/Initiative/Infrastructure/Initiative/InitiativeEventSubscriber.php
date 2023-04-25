<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Initiative;

use App\Identity\Application\Customer\UseCase\DeleteCustomer\InitiativesArchived;
use App\Initiative\Application\Event\EventEntityManager;
use App\Initiative\Application\Initiative\Query\InitiativeByCriteriaQuery;
use App\Initiative\Application\Initiative\Query\InitiativeByIdCriteria;
use App\Initiative\Application\Initiative\UseCase\ArchiveInitiative\InitiativeArchived;
use App\Initiative\Application\Initiative\UseCase\CommentInitiative\InitiativeCommented;
use App\Initiative\Application\Initiative\UseCase\FavouriteInitiative\InitiativeFavourited;
use App\Initiative\Application\Initiative\UseCase\JoinInitiative\InitiativeJoined;
use App\Initiative\Application\Initiative\UseCase\UpdateInitiative\InitiativeUpdated;
use App\Initiative\Domain\Event\Event;
use App\Initiative\Domain\Event\InitiativeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class InitiativeEventSubscriber implements EventSubscriberInterface
{
    private InitiativeByCriteriaQuery $query;
    private EventEntityManager $manager;

    public function __construct(InitiativeByCriteriaQuery $query, EventEntityManager $manager)
    {
        $this->query = $query;
        $this->manager = $manager;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            InitiativeCommented::class  => 'handle',
            InitiativeArchived::class  => 'handle',
            InitiativeUpdated::class  => 'handle',
            InitiativeJoined::class  => 'handle',
            InitiativeFavourited::class  => 'handle',
            InitiativesArchived::class  => 'handleMultiple',
        ];
    }

    public function handle(InitiativeEvent $event)
    {
        //todo think about persisting through Initiative aggregate
        $initiative = $this->query->queryOne(new InitiativeByIdCriteria($event->initiativeId()));
        $this->manager->create(new Event($initiative, $event));
        $this->manager->persist();
    }

    public function handleMultiple(InitiativesArchived $event)
    {
        //todo think about persisting through Initiative aggregate
        $initiatives = $this->query->queryMultipleV2(new InitiativeByIdCriteria(...$event->initiatives()));

        foreach ($initiatives->toArray() as $initiative) {
            $this->manager->create(new Event($initiative, $event));
        }

        $this->manager->persist();
    }
}