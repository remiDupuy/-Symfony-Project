<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Show;
use AppBundle\Type\ShowType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class ShowController
 * @package AppBundle\Controller
 * @Route("/show")
 */
class ShowController extends Controller
{
    /**
     * @Route("/", name="list_show")
     */
    public function listAction() {
        return $this->render('show/list.html.twig');
    }

    /**
     * @Route("/create", name="create_show")
     */
    public function createAction(Request $request) {


        $form = $this->createForm(ShowType::class);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $show = $form->getData();

            // for example, if Task is a Doctrine entity, save it!
            $em = $this->getDoctrine()->getManager();
            $em->persist($show);
            $em->flush();

            return $this->redirectToRoute('list_show');
        }

        return $this->render('show/create.html.twig', ['form' => $form->createView()]);

    }


    public function categoriesAction() {
        return $this->render('show/categories.html.twig', [
            'categories' => ['Web design', 'HTML', 'Freebies', 'Javascript', 'CSS', 'Tutorials']
        ]);
    }
}
