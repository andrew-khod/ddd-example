<?php

declare(strict_types=1);

namespace App\Identity\Domain\UserCompany;

use App\Identity\Domain\Company\Company;
use App\Identity\Domain\User\Email;
use App\Identity\Domain\User\User;
use App\Identity\Domain\User\UserId;
use Symfony\Component\Uid\Uuid;

class UserCompany
{
    private Uuid $id;
//    private BaseId $id;

//    private UserId $user_id;

    private Company $company;

    private Email $email;

//    private bool $is_active_company = false;

    private User $user;

//    public function __construct(BaseUser $user, Company $company)
    public function __construct(User $user, Company $company)
    {
        $this->id = Uuid::v1();
        $this->user = $user;
//        $this->user_id = $user->id();
        $this->company = $company;
        $this->email = $user->email();
    }

    public function userId(): UserId
    {
        return $this->user->id();
//        return $this->user_id;
    }

    public function company(): Company
    {
        return $this->company;
    }
}
