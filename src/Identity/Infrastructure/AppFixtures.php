<?php

namespace App\Identity\Infrastructure;

use App\Identity\Domain\Permission\Permission;
use App\Identity\Domain\Permission\PermissionCollection;
use App\Identity\Domain\Role\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $initiativeListPermission = new Permission('initiative:list');
        $adminListPermission = new Permission('admin:list');
        $adminAddPermission = new Permission('admin:add');
        $customerAllPermission = new Permission('customer:*');
        $customizationAllPermission = new Permission('customization:*');

        $administratorRole = new Role('administrator', new PermissionCollection(
            $initiativeListPermission,
            $adminListPermission,
            $adminAddPermission,
            $customerAllPermission,
            $customizationAllPermission,
        ));
        $moderatorRole = new Role('moderator', new PermissionCollection($initiativeListPermission));

        $manager->persist($administratorRole);
        $manager->persist($moderatorRole);

        $manager->flush();
    }
}
