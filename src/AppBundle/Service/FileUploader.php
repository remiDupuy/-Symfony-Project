<?php
namespace AppBundle\Service;

use AppBundle\Model\FileUploaderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file, FileUploaderInterface $object)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        //$this->deleteFile($object);
        $file->move($this->getTargetDir(), $fileName);


        return $fileName;
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }


    public function deleteFile(FileUploaderInterface $object) {
        $property_name = $object->getNameProperty();

        echo $object->getBasePath();
        echo $object->{'get'.ucfirst($property_name)}();
        die;
        @unlink($object->getBasePath().$object->{$property_name});
    }
}