<?php

namespace AppBundle\Controller\Authentication;

use AppBundle\Entity\Authentication\PasswordRecovery;
use AppBundle\Entity\Authentication\User;
use AppBundle\Form\Authentication\LoginType;
use AppBundle\Form\Authentication\PasswordRecoveryType;
use AppBundle\Form\Authentication\RegisterType;
use AppBundle\Form\Authentication\ResetPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     *
     * @Route("/nouveau-mdp/{token}", name="reset_password", requirements={"token":"[a-f0-9]{32}"})
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

                $em->persist($user);
                $em->flush();

                $em->remove($passwordRecovery);
                $em->flush();

                $securityToken = new UsernamePasswordToken($user, null, 'p_1', $user->getRoles());
                $this->get('security.token_storage')->setToken($securityToken);

                $this->addFlash('success', 'Mot de passe modifié');
                return $this->redirectToRoute('list_tricks');
            }

            return $this->render('authentication/reset_password.html.twig', ['form' => $form->createView()]);
        }

        return $this->redirectToRoute('password_recovery');
    }

    /**
     * @Route("/mdp-perdu", name="password_recovery")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Swift_Mailer $mailer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function PasswordRecoveryAction(Request $request, \Swift_Mailer $mailer)
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

            return $this->redirectToroute('figures_index');
        }

        return $this->render('authentication/register.html.twig', ['form' => $form->createView()]);
    }
}