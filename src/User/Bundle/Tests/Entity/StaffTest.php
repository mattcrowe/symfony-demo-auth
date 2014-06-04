<?php

namespace User\Bundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use User\Bundle\Entity\Staff;

class StaffTest extends \PHPUnit_Framework_TestCase
{

    public function test__construct()
    {
        $staff = new Staff();
        $this->assertNotNull($staff->getSalt());
        $this->assertTrue($staff->isEnabled());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $staff->getRoles());
    }

    public function testsetId()
    {
        $staff = new Staff();
        $this->assertNull($staff->getId());
        $staff->setId(1);
        $this->assertEquals(1, $staff->getId());
    }

    public function testsetEnabled()
    {
        $staff = new Staff();
        $this->assertTrue($staff->isEnabled());
        $staff->setEnabled(false);
        $this->assertFalse($staff->isEnabled());
    }

    public function testsetPassword()
    {
        $staff = new Staff();
        $staff->setPassword('test');
        $this->assertEquals('test', $staff->getPassword());
    }

    public function testsetUsername()
    {
        $staff = new Staff();
        $staff->setUsername('test');
        $this->assertEquals('test', $staff->getUsername());
    }

    public function testsetSalt()
    {
        $staff = new Staff();
        $staff->setSalt('TEST');
        $this->assertEquals('TEST', $staff->getSalt());
    }

    public function testisAccountNonExpired()
    {
        $staff = new Staff();
        $this->assertTrue($staff->isAccountNonExpired());
    }

    public function testeraseCredentials()
    {
        $staff = new Staff();
        $this->assertNull($staff->eraseCredentials());
    }

    public function testisAccountNonLocked()
    {
        $staff = new Staff();
        $this->assertTrue($staff->isAccountNonLocked());
    }

    public function testisCredentialsNonExpired()
    {
        $staff = new Staff();
        $this->assertTrue($staff->isCredentialsNonExpired());
    }

    public function testisEnabled()
    {
        $staff = new Staff();
        $this->assertEquals($staff->isEnabled(), $staff->isEnabled());
    }

    public function testisEqualTo()
    {

        $staff = new Staff();
        $staff->setUsername('GOOD');
        $staff->setSalt('GOOD');
        $staff->setPassword('GOOD');

        $staff_to_test = clone $staff;
        $this->assertTrue($staff->isEqualTo($staff_to_test));

        $staff_to_test = clone $staff;
        $staff_to_test->setSalt('BAD');
        $this->assertFalse($staff->isEqualTo($staff_to_test));

        $staff_to_test = clone $staff;
        $staff_to_test->setPassword('BAD');
        $this->assertFalse($staff->isEqualTo($staff_to_test));

    }

    public function testaddRole()
    {

        $staff = new Staff();
        $this->assertTrue($staff->getRoles()->isEmpty());

        $role = new \User\Bundle\Entity\Role();
        $role->setName('TEST');

        $staff->addRole($role);
        $this->assertTrue($staff->getRoles()->contains($role));

        $staff->removeRole($role);
        $this->assertFalse($staff->getRoles()->contains($role));
    }

}