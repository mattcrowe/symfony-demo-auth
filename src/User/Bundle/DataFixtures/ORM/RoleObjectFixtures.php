<?php

namespace User\Bundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use User\Bundle\Entity\Role;
use User\Bundle\Entity\RoleObject;

class RoleObjectFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        $roleObject = new RoleObject();
        $roleObject->setRole($this->getReference('role-admin'));
        $roleObject->setObject('UserBundleAdmin::home');
        $manager->persist($roleObject);

        $roleObject = new RoleObject();
        $roleObject->setRole($this->getReference('role-editor'));
        $roleObject->setObject('UserBundleAdmin::editor');
        $manager->persist($roleObject);

        $roleObject = new RoleObject();
        $roleObject->setRole($this->getReference('role-agent'));
        $roleObject->setObject('UserBundleAdmin::agent');
        $manager->persist($roleObject);

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}