<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Affectation_Agent_Rotation;
use AppBundle\Entity\Agent_de_rotation;
use AppBundle\Entity\Employe;
use AppBundle\Entity\Marche;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * affectation controller.
 *
 * @Route("affectation")
 */
class AffectationController extends Controller
{
    /**
     * Creates a new affectation entity.
     *
     * @Route("/new/{idMarche}/{idAgent}/{date}/{type}/{role}", name="affectation_new")
     */
    public function newAction($idMarche,$idAgent,$date,$type,$role){

        $dateAffectation = new \DateTime($date);
        $em = $this->getDoctrine()->getManager();
        $agent = $em->getRepository(Employe::class)->find($idAgent);
        $marche = $em->getRepository(Marche::class)->find($idMarche);
        $affectation = new Affectation_Agent_Rotation();
        $affectation->setIdAgent($agent);
        $affectation->setIdMarche($marche);
        $affectation->setRole($role);
        $affectation->setType($type);
        $affectation->setDate($dateAffectation);

        $em->persist($affectation);
        $em->flush();

        return new Response("ok");

    }
}
