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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    /**
     * @Route("/", name="api_post_show")
     * @Method("POST")
     */
    public function postAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator) {

        $show_array = json_decode($request->getContent(), 1);
        $show = new Show();
        $show->parseFromArray($show_array, $this->getDoctrine()->getManager());

        $constraintValidationList = $validator->validate($show);

        if($constraintValidationList->count() == 0) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($show);
            $em->flush();

            return new Response('Show created', Response::HTTP_OK);
        }

        return new Response($serializer->serialize($constraintValidationList, 'json'), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{id}", name="api_put_show")
     * @Method("PUT")
     */
    public function putAction(Show $show, Request $request, SerializerInterface $serializer, ValidatorInterface $validator) {
        // I tried to use events with  JMS Serializer (cf.. ShowDeserializerEventListener) but doesn't work
        //$newShow = $serializer->deserialize($request->getContent(), Show::class, 'json');

        $show_array = json_decode($request->getContent(), 1);
        $show->parseFromArray($show_array, $this->getDoctrine()->getManager());

        $constraintValidationList = $validator->validate($show);

        if($constraintValidationList->count() == 0) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return new Response('Show updated', Response::HTTP_OK);
        }

        return new Response($serializer->serialize($constraintValidationList, 'json'), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{id}", name="api_delete_show")
     * @Method("DELETE")
     */
    public function deleteAction(Show $show) {

        if($show) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($show);
            $em->flush();

            return new Response('Show deleted', Response::HTTP_OK);
        }

        return new Response('Couldn\'t be deleted', Response::HTTP_BAD_REQUEST);
    }
}