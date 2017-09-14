<?php

namespace AppBundle\Controller\Trick;

use AppBundle\Entity\Trick\Family;
use AppBundle\Service\Common\Slugify;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     *
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
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request, Slugify $slugify)
    {
        $family = new Family();
        $form = $this->createForm('AppBundle\Form\Trick\FamilyType', $family);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            # Générer un slug pour cette famille
            # Pourrait être fait en tant que service
            # Mais peut également se faire ici
            $family->setSlug( $slugify->exec($family->getName()) );

            $em = $this->getDoctrine()->getManager();
            $em->persist($family);
            $em->flush();

            return $this->redirectToRoute('figures_familles_show', array('id' => $family->getId(), 'slug' => $family->getSlug()));
        }

        return $this->render('trick/family/new.html.twig', array(
            'family' => $family,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a family entity.
     *
     * @Route("/{id}/{slug}.html", name="figures_familles_show", requirements={"slug" : "[a-z0-9\-]+"})
     * @Method("GET")
     * @ParamConverter("family", options={"mapping" : {"id" : "id", "slug" : "slug"}})
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
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, Family $family, Slugify $slugify)
    {

        $deleteForm = $this->createDeleteForm($family);
        $editForm = $this->createForm('AppBundle\Form\Trick\FamilyType', $family);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            # SLugifier le nom
            $family->setSlug( $slugify->exec($family->getName()) );

            # Sauvegarder le tout
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success_edit_family', 'Le groupe a été mis à jour ! <a href="'. $this->generateUrl('figures_familles_show', ['id' => $family->getId(), 'slug' => $family->getSlug()]) .'" class="text-bold"">Voir le groupe</a>');

            # Redirect ...
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
     * @Route("/supprimer/{id}", name="figures_familles_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
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
