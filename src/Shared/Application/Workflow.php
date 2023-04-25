<?php

namespace App\Shared\Application;

interface Workflow
{
    public function getMarking(object $subject);

    public function can(object $subject, string $transitionName);

    public function buildTransitionBlockerList(object $subject, string $transitionName);

    public function apply(object $subject, string $transitionName, array $context = []);

    public function getEnabledTransitions(object $subject);

    public function getName();

    public function getDefinition();

    public function getMarkingStore();

    public function getMetadataStore();
}