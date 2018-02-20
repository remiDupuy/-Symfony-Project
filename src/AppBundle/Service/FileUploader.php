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

        $file->move($this->getTargetDir(), $fileName);


        return $fileName;
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }
}