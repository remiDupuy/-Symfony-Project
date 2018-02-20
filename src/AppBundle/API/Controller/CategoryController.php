<?php
/**
 * Created by PhpStorm.
 * User: remidupuy
 * Date: 20/02/18
 * Time: 11:36
 */

namespace AppBundle\API\Controller;


use AppBundle\Entity\Category;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class CategoryController
 * @package AppBundle\API\Controller
 * @Route("/category")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/", name="api_list_category")
     * @Method("GET")
     */
    public function getAllAction(SerializerInterface $serializer) {
        $cats = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        $data = $serializer->serialize($cats, 'json');
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{id}", name="api_get_category")
     * @Method("GET")
     */
    public function getAction(Category $category, SerializerInterface $serializer) {
        $data = $serializer->serialize($category, 'json');
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/", name="api_post_cat")
     * @Method("POST")
     */
    public function postAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator) {
        $category = $serializer->deserialize($request->getContent(), Category::class, 'json');

        $constraintValidationList = $validator->validate($category);

        if($constraintValidationList->count() == 0) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($category);
            $em->flush();

            return new Response('Category created', Response::HTTP_CREATED);
        }

        return new Response($serializer->serialize($constraintValidationList, 'json'), Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="api_post_cat")
     * @Method("PUT")
     */
    public function putAction(Category $category, Request $request, SerializerInterface $serializer, ValidatorInterface $validator) {
        $newCategory = $serializer->deserialize($request->getContent(), Category::class, 'json');

        $constraintValidationList = $validator->validate($category);

        if($constraintValidationList->count() == 0) {
            $category->update($newCategory);
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return new Response('Category created', Response::HTTP_CREATED);
        }

        return new Response($serializer->serialize($constraintValidationList, 'json'), Response::HTTP_CREATED);
    }
}