<?php
/**
 * Created by PhpStorm.
 * User: remidupuy
 * Date: 19/03/18
 * Time: 08:44
 */

namespace AppBundle\API\Controller;


use AppBundle\Entity\Media;
use AppBundle\Entity\MediaType;
use AppBundle\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MediaController
 * @package AppBundle\API\Controller
 * @Route("/media")
 */
class MediaController extends Controller
{
    /**
     * @Route("/upload", name="api_media_upload")
     * @Method("POST")
     */
    public function uploadAction(Request $request, FileUploader $fileUploader) {
        $media = new Media();
        $media->setFile($request->files->get('file'));

        $generatedFileName = $fileUploader->upload($media->getFile(), time());
        $path = $this->getParameter('picture_show_directory_public');
        $media->setPath($path.'/'.$generatedFileName);

        $em = $this->getDoctrine()->getManager();
        $em->persist($media);
        $em->flush();

        return new Response('File uploaded', Response::HTTP_NO_CONTENT);
    }
}