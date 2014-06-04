<?php
namespace User\Bundle\Security;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class BaseAuthenticateToken extends AbstractToken
{

    /**
     * {@inheritdoc }
     */
    public function __construct($roles = null)
    {
        if ($roles === null) {
            $roles = new ArrayCollection();
        }

        parent::__construct($roles->toArray());
    }

    /**
     * {@inheritdoc }
     */
    public function getCredentials()
    {
        return array();
    }

}