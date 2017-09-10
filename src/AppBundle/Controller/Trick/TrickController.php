<?php

namespace AppBundle\Controller\Trick;

use AppBundle\Entity\Trick\Trick;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Trick controller.
 */
class TrickController extends Controller
{
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
     * @Method("GET")
     */
    public function showAction(Trick $trick)
    {
        $deleteForm = $this->createDeleteForm($trick);

        return $this->render('trick/trick/show.html.twig', array(
            'trick' => $trick,
            'delete_form' => $deleteForm->createView(),
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
