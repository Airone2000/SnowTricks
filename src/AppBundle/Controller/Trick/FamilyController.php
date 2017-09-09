<?php

namespace AppBundle\Controller\Trick;

use AppBundle\Entity\Trick\Family;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Family controller.
 *
 * @Route("figures/familles")
 */
class FamilyController extends Controller
{
    /**
     * Lists all family entities.
     *
     * @Route("/", name="figures_familles_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $families = $em->getRepository('AppBundle:Trick\Family')->findAll();

        return $this->render('trick/family/index.html.twig', array(
            'families' => $families,
        ));
    }

    /**
     * Creates a new family entity.
     *
     * @Route("/new", name="figures_familles_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $family = new Family();
        $form = $this->createForm('AppBundle\Form\Trick\FamilyType', $family);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($family);
            $em->flush();

            return $this->redirectToRoute('figures_familles_show', array('id' => $family->getId()));
        }

        return $this->render('trick/family/new.html.twig', array(
            'family' => $family,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a family entity.
     *
     * @Route("/{id}", name="figures_familles_show")
     * @Method("GET")
     */
    public function showAction(Family $family)
    {
        $deleteForm = $this->createDeleteForm($family);

        return $this->render('trick/family/show.html.twig', array(
            'family' => $family,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing family entity.
     *
     * @Route("/{id}/edit", name="figures_familles_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Family $family)
    {
        $deleteForm = $this->createDeleteForm($family);
        $editForm = $this->createForm('AppBundle\Form\Trick\FamilyType', $family);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('figures_familles_edit', array('id' => $family->getId()));
        }

        return $this->render('trick/family/edit.html.twig', array(
            'family' => $family,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a family entity.
     *
     * @Route("/{id}", name="figures_familles_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Family $family)
    {
        $form = $this->createDeleteForm($family);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($family);
            $em->flush();
        }

        return $this->redirectToRoute('figures_familles_index');
    }

    /**
     * Creates a form to delete a family entity.
     *
     * @param Family $family The family entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Family $family)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('figures_familles_delete', array('id' => $family->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
