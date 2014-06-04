<?php
namespace User\Bundle\Security\User;

class UserAuthenticationListener extends \User\Bundle\Security\BaseAuthenticationListener {

    public $user_provider_class = '\User\Bundle\Security\User\UserProvider';
    public $user_authorize_class = '\User\Bundle\Security\User\UserAuthorize';

}