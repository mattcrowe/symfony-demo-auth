<?php
namespace User\Bundle\Security\Staff;

class StaffAuthorize extends \User\Bundle\Security\BaseAuthorize
{

    /**
     * @return bool
     */
    public function isPermissionGranted()
    {

        /**
         * ROLE_SUPER is always allowed
         */
        $allowed = array('ROLE_SUPER');

        /**
         * Find additional allowed roles
         * @var $roleObjects \User\Bundle\Entity\RoleObject[]
         */
        $roleObjects = $this->findRoleObjects();
        foreach($roleObjects as $roleObject) {
            $allowed[] = $roleObject->getRole()->getName();
        }

        /**
         * See if authenticated user's role collection has an allowed role
         */
        foreach($this->user->getRoles() as $role) {
            if (in_array($role->getRole(), $allowed)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function findRoleObjects() {
        return $this->em->getRepository('UserBundle:RoleObject')->findByObject($this->getControllerAlias());
    }

}