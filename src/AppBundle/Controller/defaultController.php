<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Trick\Family;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class defaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        # L'accueil liste les figures, par famille.
        # La page est accessible à tous.

        $families = $this->getDoctrine()->getManager()->getRepository(Family::class)->findAll();
        return $this->render('default/index.html.twig', ['families' => $families]);
    }

}