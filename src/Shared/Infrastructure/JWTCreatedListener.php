<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure;

use App\Identity\Domain\Customer\Customer;
use App\Identity\Domain\User\User;
use App\Identity\Infrastructure\User\Persistence\Doctrine\UserIdentityByTenantRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\Security\Core\User\User as CoreUser;

class JWTCreatedListener
{
//    private RequestStack $requestStack;

//    public function __construct(RequestStack $requestStack, UserByCriteriaQuery $query)
//    private UserByCriteriaQuery $query;

//    public function __construct(UserByCriteriaQuery $query)
    private UserIdentityByTenantRepository $query;

    public function __construct(UserIdentityByTenantRepository $query)
    {
//        $this->requestStack = $requestStack;
        $this->query = $query;
    }

    public function onJWTCreated(JWTCreatedEvent $event)
    {
//        $request = $this->requestStack->getCurrentRequest();
        $payload = $event->getData();
        $payload[] = ['name' => ''];
        $user = $event->getUser();

        if ($user instanceof User || $user instanceof Customer) {
            $payload['name'] = $event->getUser()->username();
        }

        if ($user instanceof CoreUser) {
//            $payload['name'] = $this->query->queryOneV2(
//                new UserByEmailCriteria(
//                    $user->getUsername()
//                )
//            );
            $payload['name'] = $this->query->loadUserByUsername($user->getUsername());
        }

        $event->setData($payload);

//        $header        = $event->getHeader();
//        $header['cty'] = 'JWT';
//
//        $event->setHeader($header);
    }
}