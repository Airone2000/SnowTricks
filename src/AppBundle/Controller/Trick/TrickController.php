<?php

namespace AppBundle\Controller\Trick;

use AppBundle\Entity\Trick\Comment;
use AppBundle\Entity\Trick\Family;
use AppBundle\Entity\Trick\Trick;
use AppBundle\Form\Trick\CommentType;
use AppBundle\Service\Common\Slugify;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Trick controller.
 *
 * @Route("figures")
 */
class TrickController extends Controller
{
    /**
     * @Route("commentaires/{id}/modifier", name="edit_comment", requirements={"id":"\d+"})
     * @Security("has_role('ROLE_ADMIN') or user == comment.getUser()")
     */
    public function editCommentAction(Comment $comment, Request $request)
    {
        # Si Ajax ...
        if($request->isXmlHttpRequest())
        {
            # Renvoyer un formulaire et écouter la requête
            $form = $this->createForm(CommentType::class, $comment);
            $form->handleRequest($request);

            # Si la requête retourne les bons paramètres
            if($form->isSubmitted() && $form->isValid())
            {
                # Le commentaire est modifié
                $this->getDoctrine()->getManager()->flush();

                # Et on renvoie une vue du formulaire (mode lecture)
                $view = $this->renderView('trick/trick/comment.html.twig', ['comment' => $comment]);

                # Au format JSON !
                return new JsonResponse(['view' => $view, 'status' => 'OK_2']);
            }

            $view = $this->renderView('trick/trick/comment_type.html.twig', ['form' => $form->createView(), 'comment' => $comment]);
            return new JsonResponse(['view' => $view, 'status' => 'OK_1']);
        }

        # Interdiction d'accéder à cette page autrement que via Ajax
        throw new NotFoundHttpException();
    }

    /**
     * @Route("/commentaires/{id}/supprimer", name="remove_comment", requirements={"id":"\d+"})
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN') or user == comment.getUser()")
     */
    public function removeCommentAction(Comment $comment, Request $request)
    {
        # La suppression se fait via Ajax
        if($request->isXmlHttpRequest())
        {
            # On ajoute la requête de suppression et on flush
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();

            # On retourne une réponse
            return new JsonResponse(['status' => 'OK', 'msg' => 'Commentaire supprimé']);
        }

        # SI pas Ajax : out !
        throw new NotFoundHttpException();
    }

    /**
     * @Route("{id}/commentaires/{page}", name="trick_comments", requirements={"id":"\d+", "page":"\d+"})
     * @Method("GET")
     * @param \AppBundle\Entity\Trick\Trick $trick
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $page
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getTrickCommentsAction(Trick $trick, Request $request, $page)
    {
        # La récupération des requêtes se fait par requête Ajax
        if($request->isXmlHttpRequest())
        {
            # Get comments
            $repository = $this->getDoctrine()->getRepository( Comment::class );
            $comments = $repository->getPaginatedComments($page, 10, $trick);

            # Return jsonResponse
            $view = $this->renderView('trick/trick/comments.html.twig', ['comments' => $comments]);
            $jsonResponse = new JsonResponse(['totalComments' => count($comments), 'view' => $view]);
            return $jsonResponse;
        }

        # Impossible d'accéder à cette page autrement que par Ajax
        throw new NotFoundHttpException();
    }

    /**
     * Creates a new trick entity.
     *
     * @Route("/nouvelle-figure/{family}", name="figures_new", requirements={"family":"\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \AppBundle\Entity\Trick\Family|null $family
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, Family $family = null, Slugify $slugifier)
    {
        # Nouvelle figure, action réservée aux membres inscrits
        # Nouvelle instance + on l'associe d'ores et déjà à une famille
        # pour peu qu'on la connaisse
        $trick = new Trick();
        $trick->setFamily($family);

        # Nouveau formulaire pour hydater l'entité
        $form = $this->createForm('AppBundle\Form\Trick\TrickType', $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            # Je pourrais slugifier dans un service mais
            # pour la facilité, je le fais ici.
            $trick->setSlug( $slugifier->exec( $trick->getName() ) );

            # Sauvegarder la nouvelle figure
            # La sauvegarde des images / vidéos se fait dans un Subscriber
            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();

            # Direction => la figure nouvellement créée !
            $this->addFlash(
                'success_new_trick',
                '<i class="icon-ok"></i> Figure sauvegardée ! <a href="'. $this->generateUrl('figures_show', ['id' => $trick->getId(), 'slug' => $trick->getSlug()]) .'" class="text-bold">Consultez-la ici.</a>');

            return $this->redirectToRoute('figures_familles_show', array('id' => $trick->getFamily()->getId(), 'slug' => $trick->getFamily()->getSlug()));
        }

        # Si formulaire non soumis, on renvoie le formulaire.
        return $this->render('trick/trick/new.html.twig', array(
            'trick' => $trick,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a trick entity.
     *
     * @Route("/{id}/{slug}.html", name="figures_show")
     * @Method({"GET", "POST"} )
     * @ParamConverter("trick", options={"mapping" : {"id" : "id", "slug" : "slug"}})
     */
    public function showAction(Trick $trick, Request $request)
    {
        # Formulaire de suppression
        $deleteForm = $this->createDeleteForm($trick);

        # Formulaire d'ajout de commentaires
        # Seul un membre connecté peut publier un commentaire
        if($this->isGranted('ROLE_USER'))
        {
            # Un commentaire est associé à une figure
            # à un utilisateur et a une date de création
            $comment = (new Comment())->setTrick($trick)->setUser($this->getUser())->setCreatedAt(new \DateTime());
            $commentForm = $this->createForm(CommentType::class, $comment);
            $commentForm->handleRequest($request);

            # Si formulaire soumis
            if($commentForm->isSubmitted() && $commentForm->isValid())
            {
                # Je sauve le commentaire
                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();

                # Et on redirige vers la même figure
                return $this->redirectToRoute('figures_show', ['id' => $trick->getId(), 'slug' => $trick->getSlug()]);
            }

            $commentFormView = $commentForm->createView();
        }

        # Comportement par défaut : retourner une réponse
        return $this->render('trick/trick/show.html.twig', array(
            'trick' => $trick,
            'delete_form' => $deleteForm->createView(),
            'comment_form' => $commentFormView ?? null
        ));
    }

    /**
     * Displays a form to edit an existing trick entity.
     *
     * @Route("{id}/modifier", name="figures_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \AppBundle\Entity\Trick\Trick $trick
     * @param \AppBundle\Service\Common\Slugify $slugifier
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Trick $trick, Slugify $slugifier)
    {
        # Deux formulaires
        # Formulaire de suppression + édition
        $deleteForm = $this->createDeleteForm($trick);
        $editForm = $this->createForm('AppBundle\Form\Trick\TrickType', $trick);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            # Slugifier à l'édition
            $trick->setSlug( $slugifier->exec( $trick->getName() ) );

            # L'édition se gère en partie dans un subscriber
            # On sauve
            $this->getDoctrine()->getManager()->flush();

            # On notifie et on redirige
            $this->addFlash('success', 'La figure a été mise à jour ! <a href="'. $this->generateUrl('figures_show', ['id' => $trick->getId(), 'slug' => $trick->getSlug()] ) .'" class="text-bold">Voir la figure</a>');
            return $this->redirectToRoute('figures_edit', array('id' => $trick->getId()));
        }

        # Si formulaire non soumis, on l'affiche
        return $this->render('trick/trick/edit.html.twig', array(
            'trick' => $trick,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/supprimer/{id}", name="remove_trick")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function trickRemoveAction(Trick $trick, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($trick);
        $em->flush();

        $referer = $request->headers->get('referer') ?? $this->generateUrl('figures_familles_show', [
            'id' => $trick->getFamily()->getId()
        ]);

        return $this->redirect($referer);
    }

    /**
     * Deletes a trick entity.
     *
     * @Route("/{id}", name="figures_delete")
     * @Method()
     * @Security("has_role('ROLE_USER')")
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

        return $this->redirectToRoute('homepage');
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
