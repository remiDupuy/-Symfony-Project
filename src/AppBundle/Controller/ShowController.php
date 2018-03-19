<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Show;
use AppBundle\ShowFinder\ShowFinder;
use AppBundle\Type\ShowType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

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

    /**
     * @Route("/{id}", requirements={"id"="\d+"})
     */
    public function viewAction($id) {
        $show = $this->getDoctrine()->getRepository(Show::class)->find($id);
        return $this->render('show/view.html.twig', [
            'show' => $show
        ]);
    }

    /**
     * @Route("/{id}/update", requirements={"id"="\d+"}, name="update_show")
     */
    public function updateAction(Request $request, $id) {
        $show = $this->getDoctrine()->getRepository(Show::class)->find($id);

        if(!$show) {
            throw $this->createNotFoundException(
                'No products found for '.$id
            );
        }

        /* Keep main_picture name */
        $picture = $show->getPathMainPicture()->getFilename();

        $form = $this->createForm(ShowType::class, $show);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /* If picture not change, populate path picture with old picture */
            if(!$show->getPathMainPicture()) {
                $show->setPathMainPicture($picture);
            }


            $em = $this->getDoctrine()->getManager();
            $em->merge($show);
            $em->flush();

            $this->addFlash(
                'success',
                'Show updated'
            );

            return $this->redirectToRoute('list_show');


        }

        return $this->render('show/update.html.twig', [
            'form' => $form->createView(),
            'show' => $show,
        ]);
    }

    /**
     * @Route("/delete", name="delete_show")
     * @Method("POST")
     */
    public function deleteAction(Request $request, CsrfTokenManagerInterface $csrfTokenManager) {
        $doctrine = $this->getDoctrine();



        $id_show = $request->get('show_id');
        if($id_show == null) {
            return $this->redirectToRoute('list_show');
        }

        $repository = $this->getDoctrine()->getRepository('AppBundle:Show');

        $show = $repository->findOneById($id_show);

        if(!$show) {
            $this->createNotFoundException(
                'No product to delete for id : '.$id_show
            );
        }

        $csrf = new CsrfToken('delete_show', $request->get('_csrf_token'));
        if(!$csrfTokenManager->isTokenValid($csrf)) {

            $this->addFlash('danger', 'The csrf token is not valid.');

        } else {
            $doctrine->getManager()->remove($show);
            $doctrine->getManager()->flush();

            $this->addFlash('success', 'Show has been removed');
        }

        return $this->redirectToRoute('list_show');
    }


    /**
     * @Route("/create", name="create_show")
     */
    public function createAction(Request $request) {

        $show = new Show();
        $form = $this->createForm(ShowType::class, $show, ['validation_groups' => 'creation']);


        $form->handleRequest($request);

        $show->setAuthor($this->getUser());
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($show);
            $em->flush();

            $this->addFlash(
                'success',
                'Show created'
            );

            return $this->redirectToRoute('list_show');
        }

        return $this->render('show/create.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/search", name="show_search")
     * @Method("POST")
     */
    public function searchAction(Request $request) {
        $request->getSession()->set('query_search_value', $request->get('query'));

        return $this->redirectToRoute('list_show');
    }


    public function categoriesAction() {
        $repository = $this->getDoctrine()->getRepository(Category::class);

        $categories = $repository->findAll();

        return $this->render('show/categories.html.twig', [
            'categories' => $categories
        ]);
    }
}
