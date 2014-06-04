<?php

namespace User\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="role_objects")
 */

class RoleObject
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="\User\Bundle\Entity\Role")
     */
    protected $role;

    /**
     * @ORM\Column(type="string", length=255)
     */

    protected $object;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     * @return RoleObject
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param Role $role
     * @return RoleObject
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param string $object
     * @return RoleObject
     */
    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }

}