<?php

namespace User\Bundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use User\Bundle\Entity\Role;

class RoleTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \User\Bundle\Entity\Role::setId
     * @covers \User\Bundle\Entity\Role::getId
     */
    public function testId()
    {
        $role = new Role();
        $this->assertNull($role->getId());
        $role->setId(1);
        $this->assertEquals(1, $role->getId());
    }

    /**
     * @covers \User\Bundle\Entity\Role::setName
     * @covers \User\Bundle\Entity\Role::getName
     * @covers \User\Bundle\Entity\Role::getRole
     */
    public function testName()
    {
        $role = new Role();
        $role->setName('test');
        $this->assertEquals('TEST', $role->getName());
        $this->assertEquals('TEST', $role->getRole());
    }

    /**
     * @covers \User\Bundle\Entity\Role::__toString
     */
    public function test__toString()
    {
        $role = new Role();
        $role->setName('test');
        $this->assertEquals('TEST', $role->__toString());
    }

    /**
     * @covers \User\Bundle\Entity\Role::__construct
     */
    public function test__construct()
    {
        $role = new Role('test');
        $this->assertEquals('TEST', $role->__toString());
    }

}