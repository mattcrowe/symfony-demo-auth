<?php

namespace User\Bundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use User\Bundle\Entity\User;

class UserFixtures extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {

        $encoder = new \User\Bundle\Security\User\UserEncoder();

        $user = new User();
        $user->setUsername('someguy');
        $user->setEnabled(true);
        $user->setPassword($encoder->encodePassword('beerme', $user->getSalt()));
        $user->addRole($this->getReference('role-user'));

        $manager->persist($user);

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }

}