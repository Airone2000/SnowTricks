<?php

namespace AppBundle\Controller\Authentication;

use AppBundle\Form\Authentication\EditProfilType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @Route("/modifier-profil", name="edit_profil")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editUserAction(Request $request)
    {
        $form = $this->createForm(EditProfilType::class, ($user = $this->getUser()));

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Profil modifié !');
            return $this->redirect( $this->generateUrl('edit_profil') );
        }

        return $this->render('authentication/edit_profil.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}