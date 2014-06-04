<?php

namespace User\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use User\Bundle\Form\ContactType;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class AuthController extends Controller
{
    /**
     * Finds and displays a Staff entity.
     *
     * @Route("/admin/login", name="admin_login")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function adminLoginAction(Request $request)
    {

        $post = $request->request->all();

        if (!empty($post)) {
            if (!empty($post['username']) && !empty($post['password'])) {
                $security = $this->get('staff.security');
                $security->attemptAuthentication($post['username'], $post['password']);
                if ($security->getUser()) {
                    return $this->redirect($this->generateUrl($security->path_redirect));
                } else {
                    $this->get('session')->getFlashBag()->add('flash', 'Bummer! Your username/password combination failed');
                }

            }
        }

    }

    /**
     * Finds and displays a Staff entity.
     *
     * @Route("/admin/logout", name="admin_logout")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function adminLogoutAction()
    {

        $security = $this->get('staff.security');

        $security->logout();

        $this->get('session')->getFlashBag()->add('flash', 'Logout successful');

        return $this->redirect($this->generateUrl($security->path_login));
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/manage/login", name="manage_login")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function manageLoginAction(Request $request)
    {

        $post = $request->request->all();

        if (!empty($post)) {
            if (!empty($post['username']) && !empty($post['password'])) {
                $security = $this->get('user.security');
                $security->attemptAuthentication($post['username'], $post['password']);
                if ($security->getUser()) {
                    return $this->redirect($this->generateUrl($security->path_redirect));
                } else {
                    $this->get('session')->getFlashBag()->add('flash', 'Bummer! Your username/password combination failed');
                }

            }
        }

    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/manage/logout", name="manage_logout")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function manageLogoutAction()
    {

        $security = $this->get('user.security');

        $security->logout();

        $this->get('session')->getFlashBag()->add('flash', 'Logout successful');

        return $this->redirect($this->generateUrl($security->path_login));
    }

}