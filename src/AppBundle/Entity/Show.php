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
use phpDocumentor\Reflection\File;
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
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     *
     * @Serializer\Expose()
     */
    private $author;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     * @Assert\DateTime()
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
     * @Assert\File(mimeTypes={ "image/jpeg" }, groups={"creation"})
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
            if($this->getPathMainPicture() instanceof \Symfony\Component\HttpFoundation\File\File)
                return '/uploads/picture_show/'.$this->getPathMainPicture()->getFilename();
            else
                return $this->getPathMainPicture();
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

    public function parseFromArray($show_array, $em)
    {
        isset($show_array['name']) && $show_array['name'] ? $this->setName($show_array['name']) : null;


        if (isset($show_array['author']) && $show_array['author']) {
            $user = $em->getRepository(User::class)->find((int)$show_array['author']);
            if($user)
                $this->setAuthor($user);
        }

        isset($show_array['categories']) && $show_array['categories'] ? $this->setCategories($show_array['categories']) : null;
        isset($show_array['iso_country']) && $show_array['iso_country'] ? $this->setIsoCountry($show_array['iso_country']) : null;
        isset($show_array['published_date']) && $show_array['published_date'] ? $this->setPublishedDate(new \DateTime($show_array['published_date'])) : null;
        isset($show_array['path_main_picture']) && $show_array['path_main_picture'] ? $this->setPathMainPicture($show_array['path_main_picture']) : null;
    }
}