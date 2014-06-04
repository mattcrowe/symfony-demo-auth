<?php
namespace User\Bundle\Tests\Security;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class TestToken extends AbstractToken
{

    public function getCredentials() {
        return array();
    }

}