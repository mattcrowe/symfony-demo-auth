<?php
namespace User\Bundle\Security;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

class BaseAuthenticationListener implements ListenerInterface
{

    public $user_provider_class = '\User\Bundle\Security\User\UserProvider';
    public $user_authorize_class = '\User\Bundle\Security\User\UserAuthorize';

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @var \Symfony\Component\Security\Core\SecurityContext
     */
    protected $securityContext;

    /**
     * @var \Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager
     */
    protected $apm;

    /**
     * @var BaseAuthorize
     */
    protected $authorizationManager;

    /**
     * @var BaseProvider
     */
    protected $userProvider;

    /**
     * @param EntityManager $em
     * @param ContainerInterface $container
     * @param AuthenticationProviderManager $apm
     */
    public function __construct(EntityManager $em, ContainerInterface $container, AuthenticationProviderManager $apm)
    {
        $this->em = $em;
        $this->container = $container;
        $this->apm = $apm;

        $this->setUserProvider(new $this->user_provider_class($this->em, $container));
    }

    /**
     * @param GetResponseEvent $event
     * @return bool
     */
    public function handle(GetResponseEvent $event)
    {

        $token = $this->getUserProvider()->getToken();

        /**
         * User doesn't have a token (so they're not authenticated). Redirect to login page.
         */
        if (!$token) {
            $router = $this->container->get('router');
            $this->container->get('session')->getFlashBag()->add('flash', 'Bummer! You must be logged in!');
            $event->setResponse(new RedirectResponse($router->generate($this->getUserProvider()->path_login)));
            return false;
        }

        /**
         * Do they have a right to be in this particular spot?
         */
//        $user = $this->getUserProvider()->loadUserByUsername($token->getUser());
        $user = $this->getUserProvider()->getUser();

        if (!$user) {
            $this->deny($event);
            return false;
        }

        $this->initAuthorizationManager($event, $user);

        if (!$this->getAuthorizationManager()->isPermissionGranted($user)) {
            $this->deny($event);
            return false;
        }

        return true;
    }

    /**
     * @param GetResponseEvent $event
     * @return bool
     */
    private function deny(GetResponseEvent $event)
    {
        // Deny authentication with a '403 Forbidden' HTTP response
        $response = new Response();
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        $response->setContent('This content is protected.');
        $event->setResponse($response);

        return false;
    }

    /**
     * @param BaseProvider
     * @return BaseAuthenticationListener
     */
    public function setUserProvider(BaseProvider $userProvider)
    {
        $this->userProvider = $userProvider;

        return $this;
    }

    /**
     * @return BaseProvider
     */
    public function getUserProvider()
    {
        return $this->userProvider;
    }

    /**
     * @param GetResponseEvent $event
     * @param UserInterface $user
     * @return BaseAuthenticationListener
     */
    public function initAuthorizationManager(GetResponseEvent $event, UserInterface $user)
    {
        $this->setAuthorizationManager(new $this->user_authorize_class($this->em, $this->container, $event, $user));

        return $this;
    }

    /**
     * @param BaseAuthorize $authorizationManager
     * @return BaseAuthenticationListener
     */
    public function setAuthorizationManager(BaseAuthorize $authorizationManager)
    {
        $this->authorizationManager = $authorizationManager;

        return $this;
    }

    /**
     * @return BaseAuthorize
     */
    public function getAuthorizationManager()
    {
        return $this->authorizationManager;
    }

}