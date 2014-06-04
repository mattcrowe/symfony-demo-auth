<?php

namespace User\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Admin controller.
 *
 * @Route("/")
 */
class AdminController extends Controller
{
    /**
     * @Route("/home", name="admin_demo_home")
     * @Template()
     */
    public function homeAction()
    {
        return array();
    }

    /**
     * @Route("/agent", name="admin_demo_agent")
     * @Template()
     */
    public function agentAction()
    {
        return array();
    }

    /**
     * @Route("/editor", name="admin_demo_editor")
     * @Template()
     */
    public function editorAction()
    {
        return array();
    }

}