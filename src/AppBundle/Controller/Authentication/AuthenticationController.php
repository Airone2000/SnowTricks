<?php

namespace AppBundle\Controller\Authentication;

use AppBundle\Entity\Authentication\PasswordRecovery;
use AppBundle\Entity\Authentication\User;
use AppBundle\Form\Authentication\LoginType;
use AppBundle\Form\Authentication\PasswordRecoveryType;
use AppBundle\Form\Authentication\RegisterType;
use AppBundle\Form\Authentication\ResetPasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthenticationController extends Controller
{
    /**
     * Je pourrais externaliser certaines opérations de ce contrôleur au sein de services
     * mais je souhaite montrer que je suis capable, également, de faire de telles opérations
     * à plusieurs endroits.
     *
     * Cette page doit vraisemblablement rester publique.
     *
     * @Route("/nouveau-mdp/{token}", name="reset_password", requirements={"token":"[a-f0-9]{32}"})
     * @param \AppBundle\Entity\Authentication\PasswordRecovery $passwordRecovery
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function ResetPasswordAction(PasswordRecovery $passwordRecovery, UserPasswordEncoderInterface $passwordEncoder, Request $request)
    {
        if(($timeLife = $passwordRecovery->getExpiresAt()->getTimestamp()) >= time()) {

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->findOneBy(['email' => $passwordRecovery->getEmail()]);
            $form = $this->createForm(ResetPasswordType::class, $user);

            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $password = $passwordEncoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);

                # Sauvegarder le nouveau mot de passe
                $em->persist($user);
                $em->flush();

                # Supprimer la demande de changement de mot de passe
                # stockée en BDD
                $em->remove($passwordRecovery);
                $em->flush();

                # Reconnecter automatiquement l'utilisateur
                $securityToken = new UsernamePasswordToken($user, null, 'p_1', $user->getRoles());
                $this->get('security.token_storage')->setToken($securityToken);

                # Le mot de passe a été modifié, redirection
                return $this->redirectToRoute('homepage');
            }

            return $this->render('authentication/reset_password.html.twig', ['form' => $form->createView()]);
        }

        return $this->redirectToRoute('password_recovery');
    }

    /**
     * @Route("/mdp-perdu", name="password_recovery")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @internal param \Swift_Mailer $mailer
     *
     * La gestion du formulaire se fait par AppBundle\Listener\Authentication\PasswordRecoveryPostPersist(à
     * Un membre qui a oublié son mot de passe n'est pas connecté
     *
     * @Security("not is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function PasswordRecoveryAction(Request $request)
    {
        $passwordRecovery = new PasswordRecovery();
        $form = $this->createForm(PasswordRecoveryType::class, $passwordRecovery);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($passwordRecovery);
            $em->flush();

            return $this->redirectToRoute('password_recovery');
        }

        return $this->render('authentication/password_recovery.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/connexion", name="login")
     *
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationUtils $authUtils
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Seul un membre anonyme peut s'identifier (avec une redondance mais peu importe, je teste)
     * @Security("not has_role('ROLE_USER') and not is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function loginAction(AuthenticationUtils $authUtils)
    {
        $loginError = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        $form = $this->createForm(LoginType::class);

        return $this->render('authentication/login.html.twig', ['form' => $form->createView(), 'error' => $loginError, 'lastUsername' => $lastUsername]);
    }

    /**
     * @Route("/inscription", name="register")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * Qui peut s'inscrire ? Un membre qui n'a pas le rôle_user, qui est anonyme sans être remembered ni fully.
     * @Security("not has_role('ROLE_USER') and not is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToroute('homepage');
        }

        return $this->render('authentication/register.html.twig', ['form' => $form->createView()]);
    }
}