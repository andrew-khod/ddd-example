<?php

declare(strict_types=1);

namespace App\Initiative\Domain\Event;

use App\Initiative\Domain\Initiative\Initiative;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

//class Event implements Entity
class Event
{
    private Uuid $id;
//    private InitiativeId $initiative_id;
    private Initiative $initiative;
    private Collection $readStatus;
    public string $event;
//    private InitiativeEvent $event;
    private DateTime $created;

//    public function __construct(Initiative $initiative, object $event)
    public function __construct(Initiative $initiative, InitiativeEvent $event)
    {
        $this->id = Uuid::v4();
//        $this->initiative_id = $event->initiative();
        $this->initiative = $initiative;
        $this->event = serialize([
            'type' => get_class($event),
            'payload' => $event->__serialize(),
        ]);
        $this->readStatus = new ArrayCollection();
//        $this->event = $event;
    }

    public function initiative(): Initiative
    {
        return $this->initiative;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

//    public function event(): InitiativeEvent
    public function event(): array
    {
        return unserialize($this->event);
    }

    public function created(): DateTime
    {
        return $this->created;
    }
}
