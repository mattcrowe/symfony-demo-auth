<?php
//namespace Metric\Bundle\Tests\Controller;
//
//use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
//
//class AdvertiserControllerTest extends WebTestCase
//{
//
//    /**
//     * @var \Symfony\Bundle\FrameworkBundle\Client
//     */
//    protected $client;
//    protected $entity_id;
//
//    private function login()
//    {
//        if (is_null($this->client)) {
//            $this->client = static::createClient();
//            $crawler = $this->client->request('GET', '/staff/login');
//            $form = $crawler->selectButton('login')->form(array(
//                'username' => 'super',
//                'password' => 'beerme',
//            ));
//            $this->client->submit($form);
//            $this->client->followRedirect();
//        }
//    }
//
//    private function getFormToken($intention = 'advertiser')
//    {
//        return $this->client->getContainer()
//        ->get('form.csrf_provider')
//        ->generateCsrfToken($intention);
//    }
//
//    private function setEntityId()
//    {
//        /* @var $router \Symfony\Bundle\FrameworkBundle\Routing\Router */
//        $router = $this->client->getContainer()->get('router');
//        $this->entity_id = str_replace('/admin/metrics/advertisers/', '', $router->getContext()->getPathInfo());
//    }
//
//    public function testsuite()
//    {
//        $this->login();
//        $this->_testindexAction();
//        $this->_testnewAction();
//        $this->_testcreateAction();
//        $this->_testshowAction();
//        $this->_testeditAction();
//        $this->_testupdateAction();
//        $this->_testmenuAction();
//        $this->_testdeleteAction();
//    }
//
//    private function _testindexAction()
//    {
//        $this->client->request('GET', '/admin/metrics/advertisers/');
//        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
//    }
//
//    private function _testnewAction()
//    {
//        $this->client->request('GET', '/admin/metrics/advertisers/new');
//        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
//    }
//
//    private function _testcreateAction()
//    {
//
//        $data = array(
//            'advertiser' => array(
//                '_token' => $this->getFormToken(),
//                                'name' => 'Vulputate augue quisque',
//                                'alias' => 'fusce',
//                            ),
//            'submit' => '',
//        );
//
//        //$crawler = $this->client->request('POST', '/admin/metrics/advertisers/', $data);
//        //$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
//        //$this->assertGreaterThan(0, $crawler->filter('li:contains("This value should not be blank.")')->count());
//        //$data['staff']['email'] = 'test@test.com';
//
//        $this->client->request('POST', '/admin/metrics/advertisers/', $data);
//        $this->client->followRedirect();
//        $this->assertTrue($this->client->getResponse()->isSuccessful());
//        $this->setEntityId();
//    }
//
//    public function _testshowAction()
//    {
//        $this->client->request('GET', '/admin/metrics/advertisers/this-does-not-exist');
//        $this->assertTrue($this->client->getResponse()->isNotFound());
//
//        $this->client->request('GET', '/admin/metrics/advertisers/' . $this->entity_id);
//        $this->assertTrue($this->client->getResponse()->isSuccessful());
//    }
//
//    public function _testeditAction()
//    {
//        $this->client->request('GET', '/admin/metrics/advertisers/this-does-not-exist/edit');
//        $this->assertTrue($this->client->getResponse()->isNotFound());
//
//        $this->client->request('GET', '/admin/metrics/advertisers/' . $this->entity_id . '/edit');
//        $this->assertTrue($this->client->getResponse()->isSuccessful());
//    }
//
//    public function _testupdateAction()
//    {
//        $data = array(
//            'advertiser' => array(
//                '_token' => $this->getFormToken(),
//                                'name' => 'Nec leo ut',
//                                'alias' => 'integer',
//                                'submit' => '',
//            ),
//        );
//
//        $this->client->request('PUT', '/admin/metrics/advertisers/this-does-not-exist', $data);
//        $this->assertTrue($this->client->getResponse()->isNotFound());
//
//        //$crawler = $this->client->request('PUT', '/admin/metrics/advertisers/' . $this->entity_id, $data);
//        //$this->assertTrue($this->client->getResponse()->isSuccessful());
//        //$this->assertGreaterThan(0, $crawler->filter('li:contains("This value should not be blank.")')->count());
//        //$data['advertiser']['email'] = 'test2@test.com';
//        $this->client->request('PUT', '/admin/metrics/advertisers/' . $this->entity_id, $data);
//
//        $this->client->followRedirect();
//        $this->assertTrue($this->client->getResponse()->isSuccessful());
//    }
//
//    public function _testmenuAction()
//    {
//        $this->client->request('GET', '/admin/metrics/advertisers/this-does-not-exist/menu');
//        $this->assertTrue($this->client->getResponse()->isNotFound());
//
//        $this->client->request('GET', '/admin/metrics/advertisers/' . $this->entity_id . '/menu');
//        $this->assertTrue($this->client->getResponse()->isSuccessful());
//    }
//
//    public function _testdeleteAction()
//    {
//
//        $data = array(
//            'form' => array(
//            '_token' => $this->getFormToken('form'),
//            'submit' => '',
//            ),
//        );
//        $this->client->request('DELETE', '/admin/metrics/advertisers/this-does-not-exist', $data);
//        $this->assertTrue($this->client->getResponse()->isNotFound());
//
//        $this->client->request('DELETE', '/admin/metrics/advertisers/' . $this->entity_id, $data);
//        $this->client->followRedirect();
//        $this->assertTrue($this->client->getResponse()->isSuccessful());
//    }
//
//}