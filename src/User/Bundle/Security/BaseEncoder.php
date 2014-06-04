<?php
namespace User\Bundle\Security;

class BaseEncoder
{

    /**
     * @param string $raw_password
     * @param string $salt
     * @return string
     */
    public function encodePassword($raw_password, $salt)
    {
        return sha1($salt . $raw_password);
    }

}