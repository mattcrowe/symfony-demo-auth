<?php
namespace User\Bundle\Tests\Security;

use User\Bundle\Entity\Role;
use Doctrine\Common\Collections\ArrayCollection;
use User\Bundle\Security\BaseAuthenticateToken;

class BaseAuthenticateTokenTest extends \PHPUnit_Framework_TestCase
{

    public function testConstruct()
    {
        $token = new BaseAuthenticateToken();

        $roles = $token->getRoles();

        $this->assertEquals($roles, array());

        $roles = new ArrayCollection();
        $roles->add(new Role('test'));

        $token = new BaseAuthenticateToken($roles);

        $this->assertEquals($roles->toArray(), $token->getRoles());
    }

    public function testGetCredentials()
    {
        $token = new BaseAuthenticateToken();

        $this->assertEquals($token->getCredentials(), array());
    }

}