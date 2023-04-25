<?php

namespace App\Shared\UI\Http\Request;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class BaseRequestListener implements ArgumentValueResolverInterface
{
    private ValidatorInterface $validator;
    private ContainerInterface $container;

    public function __construct(ValidatorInterface $validator, ContainerInterface $container)
    {
        $this->validator = $validator;
        $this->container = $container;
    }

    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return is_subclass_of($argument->getType(), BaseRequest::class);
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $data = [];
        $type = $argument->getType();
//        $a=$this->container->get($type);
        $constraints = call_user_func([$type, 'getConstraints'], $this->container);

        // todo remove this conversion, it's raw and there are a lot of bugs
        if (false !== stripos($request->headers->get('content-type'), 'multipart/form-data')) {
//            $data = $request->request->all();
//            array_walk($data, function (&$value, $key) use ($request) {
//                if ('' === $value) {
//                    $value = null;
//                }
//
//                if (is_numeric($value)) {
//                    if (stripos($value, '.') || stripos($value, ',')) {
//                        $value = (float) $value;
//                    } else {
//                        $value = (double) $value;
////                        $value = intval($value);
//                    }
//                }
//
//                $request->request->set($key, $value);
//            });
        } else {
            $data = $request->toArray();
        }

        if ($request->files->count()) {
            $data = array_merge($data, $request->files->all());
        }

        $violations = $this->validator->validate($data, $constraints);

        if ($violations->count()) {
            throw new BadRequestException($violations);
        }

        yield new $type($request);
    }
}
