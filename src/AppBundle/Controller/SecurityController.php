<?php

namespace AppBundle\Controller;

use AppBundle\ShowFinder\ShowFinder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(AuthenticationUtils $authUtils)
    {
        return $this->render('login/login.html.twig', [
            'error' => $authUtils->getLastAuthenticationError(),
            'lastUsername' => $authUtils->getLastUsername()
        ]);
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction() {

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction() {

    }

    /**
     * @Route("/", name="home")
     */
    public function listAction(Request $request, ShowFinder $showFinder)
    {

        $repository = $this->getDoctrine()->getRepository('AppBundle:Show');
        $sessions = $request->getSession();

        if($sessions->has('query_search_value')) {
            $query = $sessions->get('query_search_value');
            $shows = $showFinder->searchByName($query);

            $sessions->remove('query_search_value');
        } else {
            $shows = $repository->findAll();
        }


        return $this->render('show/list.html.twig', [
            'list_shows' => $shows,
            'default_image' => $this->getParameter('picture_show_directory').'/default.jpg'
        ]);
    }

}
