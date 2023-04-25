<?php

declare(strict_types=1);

namespace App\Identity\Domain\AuthVendor;

use App\Identity\Domain\User\BaseUser;
use App\Identity\Domain\User\UserId;

class AuthVendor
{
    private string $vendor_user_id;

    private string $vendor_type;

    private UserId $user_id;

    public function __construct(string $vendorUserId,
                                $vendorType,
                                BaseUser $user)
    {
        $this->vendor_user_id = $vendorUserId;
        $this->vendor_type = $vendorType;
        $this->user_id = $user->id();
    }

    public function vendorUserId(): string
    {
        return $this->vendor_user_id;
    }

    public function userId(): UserId
    {
        return $this->user_id;
    }

    public function vendorType(): string
    {
        return $this->vendor_type;
    }

    public function linkWithUser(BaseUser $user)
    {
        $this->user_id = $user->id();
    }
}
