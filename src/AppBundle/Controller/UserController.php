<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
    public function createAction()
    {
        return $this->render('user/create.html.twig', array(
            // ...
        ));
    }

}
