<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Trick\Family;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class defaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        # A l'accueil, je veux lister les figures horizontalement par famille.
        # Récupérer les familles

        $families = $this->getDoctrine()->getManager()->getRepository(Family::class)->findAll();


        return $this->render('default/index.html.twig', ['families' => $families]);
    }

}