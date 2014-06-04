<?php

namespace User\Bundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    private function login($username)
    {
        $client = static::createClient();
        $client->request('GET', "/auth/admin/login");
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
        $client = $this->login('super');

        $this->assertGreaterThan(0, $client->getCrawler()->filter('html:contains("Admin Home")')->count());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //goes to the secure agent page
        $client->request('GET', '/admin/demo/agent');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //goes to the secure editor page
        $client->request('GET', '/admin/demo/editor');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAccessAsAgent()
    {

        //login as super admin
        $client = $this->login('agent');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //goes to the secure agent page
        $client->request('GET', '/admin/demo/agent');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //goes to the secure editor page
        $client->request('GET', '/admin/demo/editor');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testAccessAsEditor()
    {

        //login as super admin
        $client = $this->login('editor');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //goes to the secure agent page
        $client->request('GET', '/admin/demo/agent');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());

        //goes to the secure editor page
        $client->request('GET', '/admin/demo/editor');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }


}
