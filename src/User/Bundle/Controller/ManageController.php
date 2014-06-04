<?php

namespace User\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use User\Bundle\Form\ContactType;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Manage controller.
 *
 * @Route("/")
 */
class ManageController extends Controller
{
    /**
     * @Route("/home", name="manage_demo_home")
     * @Template()
     */
    public function homeAction()
    {

        return array();
    }

}