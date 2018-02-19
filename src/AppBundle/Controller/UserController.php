<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    public function createAction(Request $request, EncoderFactoryInterface $encoder_factory)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isValid()) {

            $encoder = $encoder_factory->getEncoder($user);
            $user->setPassword($encoder->encodePassword($user->getPassword(), 'boby'));

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

    /**
     * @Route("/")
     */
    public function listAction() {
        $repoUser = $this->getDoctrine()->getRepository(User::class);

        return $this->render('user/list.html.twig', [
            'users' => $repoUser->findAll()
        ]);
    }

}
