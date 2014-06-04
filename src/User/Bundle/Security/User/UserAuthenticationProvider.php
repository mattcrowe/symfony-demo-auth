<?php
namespace User\Bundle\Security\User;

class UserAuthenticationProvider extends \User\Bundle\Security\BaseAuthenticationProvider
{

    public $user_provider_class = '\User\Bundle\Security\User\UserProvider';

}