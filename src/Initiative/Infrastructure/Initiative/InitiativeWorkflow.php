<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Initiative;

use App\Initiative\Domain\Initiative\InitiativeWorkflow as InitiativeWorkflowInterface;
use Symfony\Component\Workflow\WorkflowInterface;

class InitiativeWorkflow implements InitiativeWorkflowInterface
{
    private WorkflowInterface $initiativeWorkflow;

    public function __construct(WorkflowInterface $initiativeStateMachine)
//    public function __construct(WorkflowInterface $initiativeWorkflow)
    {
        $this->initiativeWorkflow = $initiativeStateMachine;
//        $this->initiativeWorkflow = $initiativeWorkflow;
    }

    public function getMarking(object $subject)
    {
        return $this->initiativeWorkflow->getMarking($subject);
    }

    public function can(object $subject, string $transitionName)
    {
        return $this->initiativeWorkflow->can($subject, $transitionName);
    }

    public function buildTransitionBlockerList(object $subject, string $transitionName)
    {
        return $this->initiativeWorkflow->buildTransitionBlockerList($subject, $transitionName);
    }

    public function apply(object $subject, string $transitionName, array $context = [])
    {
        return $this->initiativeWorkflow->apply($subject, $transitionName, $context);
    }

    public function getEnabledTransitions(object $subject)
    {
        return $this->initiativeWorkflow->getEnabledTransitions($subject);
    }

    public function getName()
    {
        return $this->initiativeWorkflow->getName();
    }

    public function getDefinition()
    {
        return $this->initiativeWorkflow->getDefinition();
    }

    public function getMarkingStore()
    {
        return $this->initiativeWorkflow->getMarkingStore();
    }

    public function getMetadataStore()
    {
        return $this->initiativeWorkflow->getMetadataStore();
    }
}