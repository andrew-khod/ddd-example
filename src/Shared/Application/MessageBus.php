<?php

namespace App\Shared\Application;

interface MessageBus
{
    public function dispatch(object $event);
}
