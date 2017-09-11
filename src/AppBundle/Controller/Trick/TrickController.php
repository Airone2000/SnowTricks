<?php

namespace AppBundle\Controller\Trick;

use AppBundle\Entity\Trick\Comment;
use AppBundle\Entity\Trick\Trick;
use AppBundle\Form\Trick\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Trick controller.
 */
class TrickController extends Controller
{
    /**
     * @Route("/figures/commentaires/{id}/modifier", name="edit_comment", requirements={"id":"\d+"})
     */
    public function editCommentAction(Comment $comment, Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $form = $this->createForm(CommentType::class, $comment);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid())
            {
                $em = $this->getDoctrine()->getManager()->flush();
                $view = $this->renderView('trick/trick/comment.html.twig', ['comment' => $comment]);
                return new JsonResponse(['view' => $view, 'status' => 'OK_2']);
            }


            $view = $this->renderView('trick/trick/comment_type.html.twig', ['form' => $form->createView(), 'comment' => $comment]);
            return new JsonResponse(['view' => $view, 'status' => 'OK_1']);
        }

        throw new NotFoundHttpException();
    }

    /**
     * @Route("/figures/commentaires/{id}/supprimer", name="remove_comment", requirements={"id":"\d+"})
     * @Method("DELETE")
     */
    public function removeComment(Comment $comment, Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();

            return new JsonResponse(['status' => 'OK', 'msg' => 'Commentaire supprimé']);
        }

        throw new NotFoundHttpException();
    }

    /**
     * @Route("figures/{id}/commentaires/{page}", name="trick_comments", requirements={"id":"\d+", "page":"\d+"})
     * @Method("GET")
     */
    public function getTrickCommentsAction(Trick $trick, Request $request, $page)
    {
        if($request->isXmlHttpRequest())
        {
            # Get comments
            $repository = $this->getDoctrine()->getRepository( Comment::class );
            $comments = $repository->getPaginatedComments($page);

            # Return jsonResponse
            $view = $this->renderView('trick/trick/comments.html.twig', ['comments' => $comments]);
            $jsonResponse = new JsonResponse(['totalComments' => count($comments), 'view' => $view]);
            return $jsonResponse;
        }

        # Impossible d'accéder à cette page autrement que par Ajax
        throw new NotFoundHttpException();
    }

    /**
     * Lists all trick entities.
     *
     * @Route("/", name="figures_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tricks = $em->getRepository('AppBundle:Trick\Trick')->findAll();

        return $this->render('trick/trick/index.html.twig', array(
            'tricks' => $tricks,
        ));
    }

    /**
     * Creates a new trick entity.
     *
     * @Route("figures/nouvelle-figure", name="figures_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $trick = new Trick();
        $form = $this->createForm('AppBundle\Form\Trick\TrickType', $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();

            return $this->redirectToRoute('figures_show', array('id' => $trick->getId()));
        }

        return $this->render('trick/trick/new.html.twig', array(
            'trick' => $trick,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a trick entity.
     *
     * @Route("figures/{id}", name="figures_show")
     * @Method({"GET", "POST"} )
     */
    public function showAction(Trick $trick, Request $request)
    {
        # Formulaire de suppression
        $deleteForm = $this->createDeleteForm($trick);

        # Formulaire d'ajout de commentaires
        $comment = (new Comment())->setTrick($trick);
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if($commentForm->isSubmitted() && $commentForm->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Votre commentaire a été enregistré !');
            return $this->redirectToRoute('figures_show', ['id' => $trick->getId()]);
        }


        return $this->render('trick/trick/show.html.twig', array(
            'trick' => $trick,
            'delete_form' => $deleteForm->createView(),
            'comment_form' => $commentForm->createView()
        ));
    }

    /**
     * Displays a form to edit an existing trick entity.
     *
     * @Route("figures/{id}/modifier", name="figures_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Trick $trick)
    {
        $deleteForm = $this->createDeleteForm($trick);
        $editForm = $this->createForm('AppBundle\Form\Trick\TrickType', $trick);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('figures_edit', array('id' => $trick->getId()));
        }

        return $this->render('trick/trick/edit.html.twig', array(
            'trick' => $trick,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a trick entity.
     *
     * @Route("figure/{id}", name="figures_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Trick $trick)
    {
        $form = $this->createDeleteForm($trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($trick);
            $em->flush();
        }

        return $this->redirectToRoute('figures_index');
    }

    /**
     * Creates a form to delete a trick entity.
     *
     * @param Trick $trick The trick entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Trick $trick)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('figures_delete', array('id' => $trick->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
