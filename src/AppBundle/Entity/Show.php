<?php
/**
 * Created by PhpStorm.
 * User: remidupuy
 * Date: 05/02/18
 * Time: 15:46
 */

namespace AppBundle\Entity;

use AppBundle\Model\FileUploaderInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="show")
 */
class Show implements FileUploaderInterface
{
    public static $IMAGE_DIR = 'uploads/picture_show';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $author;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
    private $published_date;

    /**
     * @ORM\Column(type="string", length=2)
     * @Assert\NotBlank()
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

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getPublishedDate()
    {
        return $this->published_date;
    }

    /**
     * @param mixed $published_date
     */
    public function setPublishedDate($published_date)
    {
        $this->published_date = $published_date;
    }


    /**
     * @return mixed
     */
    public function getIsoCountry()
    {
        return $this->iso_country;
    }

    /**
     * @param mixed $iso_country
     */
    public function setIsoCountry($iso_country)
    {
        $this->iso_country = $iso_country;
    }

    /**
     * @return mixed
     */
    public function getPathMainPicture()
    {
        return $this->path_main_picture;
    }

    /**
     * @param mixed $path_main_picture
     */
    public function setPathMainPicture($path_main_picture)
    {
        $this->path_main_picture = $path_main_picture;
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param mixed $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }


    public function getNameProperty()
    {
        return 'pathMainPicture';
    }

    /**
     * @return mixed
     */
    public function getBasePath()
    {
        return $this->getBasePath().self::$IMAGE_DIR;
    }
}