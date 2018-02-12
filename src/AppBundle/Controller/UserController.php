<?php

namespace AppBundle\Controller;

use AppBundle\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 * @package AppBundle\Controller
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/create")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(UserType::class);

        $form->handleRequest($request);

        if($form->isValid()) {
            $user = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success' ,'The user has been added');

            return $this->redirectToRoute('list_show');
        }
        return $this->render('user/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
