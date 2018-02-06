<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Type\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CategoryController
 * @package AppBundle\Controller
 * @Route("/category")
 */
class CategoryController extends Controller
{

    /**
     * @Route("/", name="list_categories")
     */
    public function listAction() {
        $repository = $this->getDoctrine()->getRepository(Category::class);

        $categories = $repository->findAll();

        return $this->render('category/list.html.twig', [
            'list_categories' => $categories
        ]);
    }

    /**
     * @Route("/create")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(CategoryType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('list_categories');
        }

        return $this->render('category/create.html.twig', [
                'form' => $form->createView()
            ]);
    }

    /**
     * @Route("/{id}")
     */
    public function viewAction($id) {
        $em = $this->getDoctrine()->getManager();

        $category = $em->find('AppBundle\Entity\Category', $id);
        
        return $this->render('show/list.html.twig', [
            'list_shows' => $category->getShows()
        ]);
    }

}
