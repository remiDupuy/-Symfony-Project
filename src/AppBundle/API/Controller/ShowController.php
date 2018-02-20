<?php
/**
 * Created by PhpStorm.
 * User: remidupuy
 * Date: 20/02/18
 * Time: 11:36
 */

namespace AppBundle\API\Controller;


use AppBundle\Entity\Category;
use AppBundle\Entity\Show;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ShowController
 * @package AppBundle\API\Controller
 * @Route("/show")
 */
class ShowController extends Controller
{
    /**
     * @Route("/", name="api_list_show")
     * @Method("GET")
     */
    public function getAllAction(SerializerInterface $serializer) {
        $shows = $this->getDoctrine()->getRepository(Show::class)->findAll();

        $data = $serializer->serialize($shows, 'json');
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{id}", name="api_get_show")
     * @Method("GET")
     */
    public function getAction(Show $show, SerializerInterface $serializer) {

        $data = $serializer->serialize($show, 'json');
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}