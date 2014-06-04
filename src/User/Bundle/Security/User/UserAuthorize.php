<?php
namespace User\Bundle\Security\User;

class UserAuthorize extends \User\Bundle\Security\BaseAuthorize
{

    /**
     * @return bool
     */
    public function isPermissionGranted()
    {
        return true;
    }


}