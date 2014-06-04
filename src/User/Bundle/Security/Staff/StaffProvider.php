<?php
namespace User\Bundle\Security\Staff;

class StaffProvider extends \User\Bundle\Security\BaseProvider
{

    //configuration
    public $path_login = 'admin_login';
    public $path_logout = 'admin_logout';
    public $path_redirect = 'admin_demo_home';
    public $token_session_key = '_security_admin';
    public $user_class = 'User\Bundle\Entity\Staff';
    public $encoder_class = '\User\Bundle\Security\BaseEncoder';
    public $token_class = '\User\Bundle\Security\BaseAuthenticateToken';

}