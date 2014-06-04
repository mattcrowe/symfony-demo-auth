<?php

namespace User\Bundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use User\Bundle\Entity\Role;

class RoleFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        $role = new Role();
        $role->setName('ROLE_USER');
        $manager->persist($role);
        $this->addReference('role-user', $role);

        $role = new Role();
        $role->setName('ROLE_ADMIN');
        $manager->persist($role);
        $this->addReference('role-admin', $role);

        $role = new Role();
        $role->setName('ROLE_AGENT');
        $manager->persist($role);
        $this->addReference('role-agent', $role);

        $role = new Role();
        $role->setName('ROLE_EDITOR');
        $manager->persist($role);
        $this->addReference('role-editor', $role);

        $role = new Role();
        $role->setName('ROLE_SUPER');
        $manager->persist($role);
        $this->addReference('role-super', $role);

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}