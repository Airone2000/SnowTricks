<?php

namespace AppBundle\Controller\Administration;

use AppBundle\Entity\Authentication\User;
use AppBundle\Form\Authentication\EditProfilType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 *
 * @package AppBundle\Controller\Administration
 *
 * @Security("has_role('ROLE_ADMIN')")
 * @Route("/administration")
 */
class AdminController extends Controller
{
    /**
     * @Route("/utilisateurs", name="admin_list_users")
     */
    public function listUsersAction()
    {
        # utilisateurs
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        # return views
        return $this->render('administration/users.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/utilisateurs/{id}/supprimer", name="admin_delete_user")
     */
    public function removeUser(User $user)
    {
        # Delete (not remove)
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        # Notify
        $this->addFlash('admin_success', 'L\'utilisateur a été supprimé');

        # Redirect
        return $this->redirectToRoute('admin_list_users');
    }

    /**
     * @Route("/utilisateurs/{id}/modifier", name="admin_edit_user")
     */
    public function editUser(User $user, Request $request)
    {
        $form = $this->createForm(EditProfilType::class, $user);
        $form->add('roles', ChoiceType::class, [
            'choices' => [
                "Simple utilisateur" => "ROLE_USER",
                "Modérateur" => "ROLE_ADMIN",
                "Administrateur" => "ROLE_SUPER_ADMIN"
            ]
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('admin_success', "L'utilisateur {$user->getNickname()} a été modifié");
            return $this->redirectToRoute('admin_list_users');
        }


        return $this->render('authentication/edit_profil.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);

    }
}