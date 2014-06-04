<?php
namespace User\Bundle\Security;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BaseAuthenticationProvider implements AuthenticationProviderInterface
{

    public $user_provider_class = '\User\Bundle\Security\User\UserProvider';

    /**
     * @var BaseProvider
     */
    public $userProvider;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @param EntityManager $em
     * @param ContainerInterface $container
     */
    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $this->container = $container;
        $this->em = $em;
        $this->userProvider = new $this->user_provider_class($em, $container);
    }

    /**
     * @param TokenInterface $token
     * @return BaseAuthenticateToken
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByUsername($token->getUser());
        if ($user) {
            $authenticatedToken = $this->userProvider->createToken($user->getRoles());
            $authenticatedToken->setUser($user->getUsername());
            $authenticatedToken->setAuthenticated(true);
            return $authenticatedToken;
        }

        throw new AuthenticationException('Authentication failed.');
    }

    /**
     * @param TokenInterface $token
     * @return bool
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof $this->userProvider->token_class;
    }
}