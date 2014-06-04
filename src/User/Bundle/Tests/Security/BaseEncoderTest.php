<?php
namespace User\Bundle\Tests\Security;

use User\Bundle\Security\BaseEncoder;

class BaseEncoderTest extends \PHPUnit_Framework_TestCase
{

    public function testEncodePassword()
    {
        $encoder = new BaseEncoder();

        $this->assertEquals($encoder->encodePassword('test', '123'), 'ac8de3b5b736fd627b42c91071c5c2a6ec963a89');
    }

}