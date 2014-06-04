<?php

namespace User\Bundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use User\Bundle\Entity\Role;
use User\Bundle\Entity\RoleObject;

class RoleObjectTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \User\Bundle\Entity\RoleObject::setId
     * @covers \User\Bundle\Entity\RoleObject::getId
     */
    public function testId()
    {
        $roleObject = new RoleObject();
        $this->assertNull($roleObject->getId());
        $roleObject->setId(1);
        $this->assertEquals(1, $roleObject->getId());
    }

    /**
     * @covers \User\Bundle\Entity\RoleObject::setRole
     * @covers \User\Bundle\Entity\RoleObject::getRole
     */
    public function testRole()
    {
        $roleObject = new RoleObject();
        $roleObject->setRole(new Role('test'));
        $this->assertEquals('TEST', $roleObject->getRole()->getName());
    }

    /**
     * @covers \User\Bundle\Entity\RoleObject::setObject
     * @covers \User\Bundle\Entity\RoleObject::getObject
     */
    public function testRoleObject()
    {
        $roleObject = new RoleObject();
        $roleObject->setObject('test');
        $this->assertEquals('test', $roleObject->getObject());
    }

}