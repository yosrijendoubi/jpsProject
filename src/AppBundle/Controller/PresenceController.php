<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Agent_de_rotation;
use AppBundle\Entity\Employe;
use AppBundle\Entity\Marche;
use AppBundle\Entity\Presence;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Presence controller.
 *
 * @Route("presence")
 */
class PresenceController extends Controller
{
    /**
     * Lists all presence entities.
     *
     * @Route("/", name="presence_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $presences = $em->getRepository('AppBundle:Presence')->findAll();

        return $this->render('presence/index.html.twig', array(
            'presences' => $presences,
        ));
    }

    /**
     * Creates a new presence entity.
     *
     * @Route("/new", name="presence_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $presence = new Presence();
        $form = $this->createForm('AppBundle\Form\PresenceType', $presence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($presence);
            $em->flush();

            return $this->redirectToRoute('presence_show', array('idPresence' => $presence->getIdpresence()));
        }

        return $this->render('presence/new.html.twig', array(
            'presence' => $presence,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a presence entity.
     *
     * @Route("/{idPresence}", name="presence_show")
     * @Method("GET")
     */
    public function showAction(Presence $presence)
    {
        $deleteForm = $this->createDeleteForm($presence);

        return $this->render('presence/show.html.twig', array(
            'presence' => $presence,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing presence entity.
     *
     * @Route("/{idPresence}/edit", name="presence_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Presence $presence)
    {
        $deleteForm = $this->createDeleteForm($presence);
        $editForm = $this->createForm('AppBundle\Form\PresenceType', $presence);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('presence_edit', array('idPresence' => $presence->getIdpresence()));
        }

        return $this->render('presence/edit.html.twig', array(
            'presence' => $presence,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a presence entity.
     *
     * @Route("/{idPresence}", name="presence_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Presence $presence)
    {
        $form = $this->createDeleteForm($presence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($presence);
            $em->flush();
        }

        return $this->redirectToRoute('presence_index');
    }

    /**
     * Creates a form to delete a presence entity.
     *
     * @param Presence $presence The presence entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Presence $presence)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('presence_delete', array('idPresence' => $presence->getIdpresence())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Creates a new presence entity.
     *
     * @Route("/api/new/{idMarche}/{idEmp}/{etat}/{date}", name="presence_api_new")
     * @Route("/api/agent/new/{idMarche}/{idAgent}/{etat}/{date}", name="presence_agent_api_new")
     * @Method({"GET", "POST"})
     */
    public function newPresenceAction($idMarche , $idEmp=null , $etat , $date ,$idAgent=null){
        if ( $idAgent){

            $em = $this->getDoctrine()->getManager();
            $presence = new Presence();
            $presenceDate = new \DateTime($date);
            $emp = $em->getRepository(Agent_de_rotation::class)->find($idAgent);
            $marche = $em->getRepository(Marche::class)->find($idMarche);
            $presence->setIdMarche($marche);
            $presence->setIdAgent($emp);
            $presence->setEtat($etat);
            $presence->setDate($presenceDate);

            $em->persist($presence);
            $em->flush();
            dump($date);
            return new Response($presence->getIdPresence());

        }

        else {

            $em = $this->getDoctrine()->getManager();
            $presence = new Presence();
            $presenceDate = new \DateTime($date);
            $emp = $em->getRepository(Employe::class)->find($idEmp);
            $marche = $em->getRepository(Marche::class)->find($idMarche);
            $presence->setIdMarche($marche);
            $presence->setIdEmp($emp);
            $presence->setEtat($etat);
            $presence->setDate($presenceDate);

            $em->persist($presence);
            $em->flush();
            dump($date);
            return new Response($presence->getIdPresence());

        }


    }

    /**
     * Creates a new presence entity.
     *
     * @Route("/api/presence/{idMarche}/{idEmp}/{date}", name="total_presence_par_employe_par_mois")
     * @Method({"GET"})
     */
    public function PresenceParMoisAction($idMarche,$date,$idEmp){

        $entityManager = $this->getDoctrine()->getManager();


        $query = $entityManager->createQuery(
            'SELECT count(p) AS NBR , p.etat , e.nom , e.prenom , e.idEmp
    FROM AppBundle:Presence p
    inner join p.idEmp e
    WHERE p.idMarche = :idMarche and p.date LIKE :date and p.etat = 1 and e.idEmp = :idEmp
    GROUP BY p.idEmp , p.etat
    '
        )->setParameter('idMarche', $idMarche)
            ->setParameter('date', $date.'%')
        ->setParameter('idEmp', $idEmp);

        $tab = array();
        $list = $query->getResult();

        foreach ( $list as $p ){
            $tab[] = [
                'idEmp' => $p['idEmp'],
                'etat'=>$p['etat'],
                'total'=>$p['NBR']
            ];


        }


        return new JsonResponse($tab);
    }


    /**
     * Creates a new presence entity.
     *
     * @Route("/api/absence/{idMarche}/{idEmp}/{date}", name="total_absence_par_employe_par_mois")
     * @Method({"GET"})
     */
    public function AbsenceParMoisAction($idMarche,$date,$idEmp){

        $entityManager = $this->getDoctrine()->getManager();


        $query = $entityManager->createQuery(
            'SELECT count(p) AS NBR , p.etat , e.nom , e.prenom , e.idEmp
    FROM AppBundle:Presence p
    inner join p.idEmp e
    WHERE p.idMarche = :idMarche and p.date LIKE :date and p.etat = 0 and e.idEmp = :idEmp
    GROUP BY p.idEmp , p.etat
    '
        )->setParameter('idMarche', $idMarche)
            ->setParameter('date', $date.'%')
            ->setParameter('idEmp', $idEmp);

        $tab = array();
        $list = $query->getResult();

        foreach ( $list as $p ){
            $tab[] = [
                'idEmp' => $p['idEmp'],
                'nom'=>$p['nom'],
                'prenom'=>$p['prenom'],
                'etat'=>$p['etat'],
                'total'=>$p['NBR']
            ];


        }


        return new JsonResponse($tab);
    }


    public function Presence($idMarche,$date,$idEmp){

        $entityManager = $this->getDoctrine()->getManager();


        $query = $entityManager->createQuery(
            'SELECT count(p) AS NBR , p.etat , e.nom , e.prenom , e.idEmp
    FROM AppBundle:Presence p
    inner join p.idEmp e
    WHERE p.idMarche = :idMarche and p.date LIKE :date and p.etat = 1 and e.idEmp = :idEmp
    GROUP BY p.idEmp , p.etat
    '
        )->setParameter('idMarche', $idMarche)
            ->setParameter('date', $date.'%')
            ->setParameter('idEmp', $idEmp);

        $tab = array();
        $list = $query->getResult();

        if ($list){
            return $list[0]['NBR'];
        }

        /*foreach ( $list as $p ){
            $tab[] = [
                'idEmp' => $p['idEmp'],
                'etat'=>$p['etat'],
                'total'=>$p['NBR']
            ];


        }*/

        else {
            return 0 ;
        }


        return $tab;
    }

    public function Absence($idMarche,$date,$idEmp){

        $entityManager = $this->getDoctrine()->getManager();


        $query = $entityManager->createQuery(
            'SELECT count(p) AS NBR , p.etat , e.nom , e.prenom , e.idEmp
    FROM AppBundle:Presence p
    inner join p.idEmp e
    WHERE p.idMarche = :idMarche and p.date LIKE :date and p.etat = 0 and e.idEmp = :idEmp
    GROUP BY p.idEmp , p.etat
    '
        )->setParameter('idMarche', $idMarche)
            ->setParameter('date', $date.'%')
            ->setParameter('idEmp', $idEmp);

        $tab = array();
        $list = $query->getResult();

        if ($list){
            return $list[0]['NBR'];
        }

        /*foreach ( $list as $p ){
            $tab[] = [
                'idEmp' => $p['idEmp'],
                'nom'=>$p['nom'],
                'prenom'=>$p['prenom'],
                'etat'=>$p['etat'],
                'total'=>$p['NBR']
            ];


        }*/

        else {
            return 0 ;
        }


    }


    /**
     * totalAbsencePresence.
     *
     * @Route("/api/total/{idMarche}/{idEmp}/{date}", name="total_absence_presence_par_mois")
     * @Method({"GET"})
     */
    public function TotalPresenceAbsenceParMois($idMarche,$date,$idEmp){
        $em = $this->getDoctrine()->getManager();
        $emp = $em->getRepository(Employe::class)->find($idEmp);
        $totalAbsence = $this->Absence($idMarche,$date,$idEmp);
        $totalPresence = $this->Presence($idMarche,$date,$idEmp);

        $total = array(
            'nom'=>$emp->getNom(),
            'Prenom'=>$emp->getPrenom(),
            'TotalAbsence'=>$totalAbsence,
            'TotalPresence'=>$totalPresence,
            'Periode'=>$date
        );

        return new JsonResponse($total);

    }


}
