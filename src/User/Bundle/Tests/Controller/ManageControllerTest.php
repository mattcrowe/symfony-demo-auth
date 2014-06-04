<?php

namespace User\Bundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ManageControllerTest extends WebTestCase
{

    private function login($username)
    {
        $client = static::createClient();
        $client->request('GET', "/auth/manage/login");
        $form = $client->getCrawler()->selectButton('login')->form(array(
            'username' => $username,
            'password' => 'beerme',
        ));
        $client->submit($form);
        $client->followRedirect();
        return $client;
    }

    public function testAccessAsSuper()
    {

        //login as super admin
        $client = $this->login('someguy');

        $this->assertGreaterThan(0, $client->getCrawler()->filter('html:contains("Manage Home")')->count());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}