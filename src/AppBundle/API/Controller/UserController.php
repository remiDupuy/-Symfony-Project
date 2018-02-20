<?php
/**
 * Created by PhpStorm.
 * User: remidupuy
 * Date: 20/02/18
 * Time: 11:36
 */

namespace AppBundle\API\Controller;


use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package AppBundle\API\Controller
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="api_list_user")
     * @Method("GET")
     */
    public function getAllAction(SerializerInterface $serializer) {
        $cats = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();

        $data = $serializer->serialize($cats, 'json');
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{id}", name="api_get_user")
     * @Method("GET")
     */
    public function getAction(User $user, SerializerInterface $serializer) {
        $data = $serializer->serialize($user, 'json');
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}