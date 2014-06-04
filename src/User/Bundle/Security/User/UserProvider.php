<?php
namespace User\Bundle\Security\User;

class UserProvider extends \User\Bundle\Security\BaseProvider
{

    //configuration
    public $path_login = 'manage_login';
    public $path_logout = 'manage_logout';
    public $path_redirect = 'manage_demo_home';
    public $token_session_key = '_security_manage';
    public $user_class = '\User\Bundle\Entity\User';
    public $encoder_class = '\User\Bundle\Security\BaseEncoder';
    public $token_class = '\User\Bundle\Security\BaseAuthenticateToken';

}