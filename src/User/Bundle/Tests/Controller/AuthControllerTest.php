<?php

namespace User\Bundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthControllerTest extends WebTestCase
{
    public function testAdminLogin()
    {

        $client = static::createClient();
        $client->request('GET', "/auth/admin/login");

        //login page loads
        $this->assertGreaterThan(0, $client->getCrawler()->filter('html:contains("Username:")')->count());

        //login attempt fails
        $form = $client->getCrawler()->selectButton('login')->form(array(
            'username' => 'super',
            'password' => 'badpassword',
        ));
        $client->submit($form);

        $this->assertGreaterThan(0, $client->getCrawler()->filter('html:contains("Bummer!")')->count());

        //login attempt succeeds
        $form = $client->getCrawler()->selectButton('login')->form(array(
            'username' => 'super',
            'password' => 'beerme',
        ));
        $client->submit($form);
        $client->followRedirect();

        $this->assertGreaterThan(0, $client->getCrawler()->filter('html:contains("Admin Home")')->count());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //logout
        $client->request('GET', "/auth/admin/logout");
        $client->followRedirect();
        $this->assertGreaterThan(0, $client->getCrawler()->filter('html:contains("Logout successful")')->count());
    }

    public function testManageLogin()
    {

        $client = static::createClient();
        $client->request('GET', "/auth/manage/login");

        //login page loads
        $this->assertGreaterThan(0, $client->getCrawler()->filter('html:contains("Username:")')->count());

        //login attempt fails
        $form = $client->getCrawler()->selectButton('login')->form(array(
            'username' => 'someguy',
            'password' => 'badpassword',
        ));
        $client->submit($form);

        $this->assertGreaterThan(0, $client->getCrawler()->filter('html:contains("Bummer!")')->count());

        //login attempt succeeds
        $form = $client->getCrawler()->selectButton('login')->form(array(
            'username' => 'someguy',
            'password' => 'beerme',
        ));
        $client->submit($form);
        $client->followRedirect();

        $this->assertGreaterThan(0, $client->getCrawler()->filter('html:contains("Manage Home")')->count());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //logout
        $client->request('GET', "/auth/manage/logout");
        $client->followRedirect();
        $this->assertGreaterThan(0, $client->getCrawler()->filter('html:contains("Logout successful")')->count());
    }

}