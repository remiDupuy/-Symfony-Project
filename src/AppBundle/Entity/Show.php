<?php
/**
 * Created by PhpStorm.
 * User: remidupuy
 * Date: 05/02/18
 * Time: 15:46
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ShowRepository")
 * @ORM\Table(name="show")
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Show
{
    public static $IMAGE_DIR = 'uploads/picture_show';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Expose()
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     *
     * @Serializer\Expose()
     */
    private $name;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="shows")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @Serializer\Expose()
     */
    private $author;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     *
     * @Serializer\Expose()
     */
    private $published_date;

    /**
     * @ORM\Column(type="string", length=2)
     * @Assert\NotBlank()
     *
     * @Serializer\Expose()
     */
    private $iso_country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File(mimeTypes={ "image/jpeg" })
     */
    private $path_main_picture;

    /**
     * @var \Doctrine\Common\Collections\Collection|Category[]
     *
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="shows")
     * @ORM\JoinTable(
     *  name="categories_shows",
     *  joinColumns={
     *      @ORM\JoinColumn(name="show_id", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     *  }
     * )
     */
    private $categories;




    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor(User $author)
    {
        $this->author = $author;

        return $this;
    }

    public function getPublishedDate()
    {
        return $this->published_date;
    }

    public function setPublishedDate($published_date)
    {
        $this->published_date = $published_date;
    }

    public function getIsoCountry()
    {
        return $this->iso_country;
    }

    public function setIsoCountry($iso_country)
    {
        $this->iso_country = $iso_country;
    }

    public function getPathMainPicture()
    {
        return $this->path_main_picture;
    }

    public function setPathMainPicture($path_main_picture)
    {
        $this->path_main_picture = $path_main_picture;
    }

    public function getPublicThumbnail() {
        if($this->getId()) {
            return '/uploads/picture_show/'.$this->getPathMainPicture()->getFilename();
        }

        return $this->getPathMainPicture();
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    public function update(Show $newShow) {
        $this->setName($newShow->getName());
        $this->setAuthor($newShow->getAuthor());
        $this->setCategories($newShow->getCategories());
        $this->setIsoCountry($newShow->getIsoCountry());
        $this->setPublishedDate($newShow->getPublishedDate());
        $this->setPathMainPicture($newShow->getPathMainPicture());
    }
}