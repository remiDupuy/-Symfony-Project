<?php
/**
 * Created by PhpStorm.
 * User: remidupuy
 * Date: 12/02/18
 * Time: 10:46
 */

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table("`user`")
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $fullname;


    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $roles;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * One Product has Many Features.
     * @OneToMany(targetEntity="Show", mappedBy="user")
     */
    private $shows;

    /**
     * @return mixed
     */
    public function getShows()
    {
        return $this->shows;
    }

    /**
     * @param mixed $shows
     */
    public function setShows($shows)
    {
        $this->shows = $shows;
    }


    public function __construct()
    {
        $this->shows = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * @param mixed $fullname
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt(){ }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials() { }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function addShow(Show $show) {
        if($this->shows->contains($show))
            return;

        $this->shows->add($show);
    }

    public function removeShow(Show $show) {
        $this->shows->remove($show);
    }
}