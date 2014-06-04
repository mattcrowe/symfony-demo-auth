<?php

namespace User\Bundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use User\Bundle\Entity\Staff;

class StaffFixtures extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {

        $encoder = new \User\Bundle\Security\Staff\StaffEncoder();

        $staff = new Staff();
        $staff->setUsername('super');
        $staff->setEnabled(true);
        $staff->setPassword($encoder->encodePassword('beerme', $staff->getSalt()));
        $staff->addRole($this->getReference('role-admin'));
        $staff->addRole($this->getReference('role-super'));
        $manager->persist($staff);
        $this->addReference('staff-super', $staff);

        $staff = new Staff();
        $staff->setUsername('agent');
        $staff->setEnabled(true);
        $staff->setPassword($encoder->encodePassword('beerme', $staff->getSalt()));
        $staff->addRole($this->getReference('role-admin'));
        $staff->addRole($this->getReference('role-agent'));
        $manager->persist($staff);
        $this->addReference('staff-agent', $staff);

        $staff = new Staff();
        $staff->setUsername('editor');
        $staff->setEnabled(true);
        $staff->setPassword($encoder->encodePassword('beerme', $staff->getSalt()));
        $staff->addRole($this->getReference('role-admin'));
        $staff->addRole($this->getReference('role-editor'));
        $manager->persist($staff);
        $this->addReference('staff-editor', $staff);

        $staff = new Staff();
        $staff->setUsername('fired');
        $staff->setEnabled(false);
        $staff->setPassword($encoder->encodePassword('beerme', $staff->getSalt()));
        $staff->addRole($this->getReference('role-admin'));
        $manager->persist($staff);
        $this->addReference('staff-fired', $staff);

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }

}