<?php
namespace User\Bundle\Security\Staff;

class StaffAuthenticationProvider extends \User\Bundle\Security\BaseAuthenticationProvider
{

    public $user_provider_class = '\User\Bundle\Security\Staff\StaffProvider';

}