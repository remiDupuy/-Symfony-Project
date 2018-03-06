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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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


    /**
     * @Route("/", name="api_post_user")
     * @Method("POST")
     */
    public function postAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EncoderFactoryInterface $encoder_factory) {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        $encoder = $encoder_factory->getEncoder($user);
        $user->setPassword($encoder->encodePassword($user->getPassword(), 'boby'));

        $constraintValidationList = $validator->validate($user);

        if($constraintValidationList->count() == 0) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();

            return new Response('User created', Response::HTTP_CREATED);
        }

        return new Response($serializer->serialize($constraintValidationList, 'json'), Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="api_put_user")
     * @Method("PUT")
     */
    public function putAction(User $user, Request $request, SerializerInterface $serializer, ValidatorInterface $validator) {
        $newUser = $serializer->deserialize($request->getContent(), User::class, 'json');

        $constraintValidationList = $validator->validate($user);

        if($constraintValidationList->count() == 0) {
            $user->update($newUser);
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return new Response('User updated', Response::HTTP_OK);
        }

        return new Response($serializer->serialize($constraintValidationList, 'json'), Response::HTTP_BAD_REQUEST);
    }
}