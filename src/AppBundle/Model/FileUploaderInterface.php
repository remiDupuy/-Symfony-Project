<?php
/**
 * Created by PhpStorm.
 * User: remidupuy
 * Date: 08/02/18
 * Time: 20:52
 */

namespace AppBundle\Model;


/**
 * Model FileUploaderInterface
 * @package AppBundle\Model
 */
interface FileUploaderInterface
{
    /**
     * Get name of the image_path property for auto delete
     * @return mixed
     */
    public function getNameProperty();

    /**
     * @return mixed
     */
    public function getBasePath();
}