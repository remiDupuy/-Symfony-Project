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
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table("`user`")
 *
 * @Serializer\ExclusionPolicy("all")
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Expose()
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     *
     * @Serializer\Expose()
     */
    private $fullname;


    /**
     * @ORM\Column(type="json_array", nullable=true)
     *
     * @Serializer\Groups({"user"})
     * @Serializer\Expose()
     */
    private $roles;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     *
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     *
     * @Serializer\Expose()
     */
    private $email;

    /**
     * One Product has Many Features.
     * @OneToMany(targetEntity="Show", mappedBy="author")
     */
    private $shows;



    public function __construct()
    {
        $this->shows = new ArrayCollection();
    }

    public function getShows()
    {
        return $this->shows;
    }

    public function setShows($shows)
    {
        $this->shows = $shows;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFullname()
    {
        return $this->fullname;
    }

    public function setFullname($fullname)
    {
        $this->fullname = $fullname;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }



    /* UserInterface methods */
    public function getSalt(){ }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials() { }



    /* One-to-many relationship */
    public function addShow(Show $show) {
        if($this->shows->contains($show))
            return;

        $this->shows->add($show);
    }

    public function removeShow(Show $show) {
        $this->shows->remove($show);
    }

    public function update(User $newCategory) {
        $this->setEmail($newCategory->getEmail());
        $this->setFullname($newCategory->getFullname());
        $this->setRoles($newCategory->getRoles());
        $this->setShows($newCategory->getShows());
    }
}