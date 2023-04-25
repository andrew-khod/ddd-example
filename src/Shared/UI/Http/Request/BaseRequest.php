<?php

namespace App\Shared\UI\Http\Request;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseRequest
{
    // Can't extend Symfony Request class so let's proxy it, because Symfony injects simple Request if requested Listener Argument is subtype of Symfony Request :)
    private Request $request;

    // Can't extend Symfony Request class so let's proxy it, because Symfony injects simple Request if requested Listener Argument is subtype of Symfony Request :)
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    abstract public static function getConstraints(ContainerInterface $container);
//    abstract public static function getConstraints(): Constraint;

    public function get(string $key)
    {
        return $this->request->get($key);
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
