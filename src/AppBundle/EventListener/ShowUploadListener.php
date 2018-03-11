<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Show;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use AppBundle\Service\FileUploader;
use Symfony\Component\Routing\RouterInterface;

class ShowUploadListener
{
    private $uploader;

    public function __construct(RouterInterface $router, FileUploader $uploader)
    {
        $this->uploader = $uploader;
        $this->router = $router;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        // Couldn't find a better way to do the job
        if(strpos($this->router->getContext()->getPathInfo(),'/api') !== false)
            return;

        $entity = $args->getEntity();

        if (!$entity instanceof Show) {
            return;
        }

        if ($fileName = $entity->getPathMainPicture()) {

            $entity->setPathMainPicture(new File($this->uploader->getTargetDir().'/'.$fileName));
        }
    }

    private function uploadFile($entity)
    {
        // upload only works for Product entities
        if (!$entity instanceof Show) {
            return;
        }

        $file = $entity->getPathMainPicture();

        // only upload new files
        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file, $entity);
            $entity->setPathMainPicture($fileName);
        }
    }
}
