<?php
namespace User\Bundle\Security\Staff;

class StaffAuthenticationListener extends \User\Bundle\Security\BaseAuthenticationListener {

    public $user_provider_class = '\User\Bundle\Security\Staff\StaffProvider';
    public $user_authorize_class = '\User\Bundle\Security\Staff\StaffAuthorize';

}