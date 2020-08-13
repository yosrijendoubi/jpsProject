<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Agent_de_rotation;
use AppBundle\Entity\Avance;
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
     * @Route("/api/new/{idMarche}/{idEmp}/{etat}/{date}/{type}", name="presence_api_new")
     * @Route("/api/agent/new/{idMarche}/{idAgent}/{etat}/{date}/{type}/{role}", name="presence_agent_api_new")
     * @Method({"GET", "POST"})
     */
    public function newPresenceAction($idMarche , $idEmp=null , $etat , $date , $type ,$idAgent=null , $role = null){
        if ( $idAgent){

            $em = $this->getDoctrine()->getManager();
            $presence = new Presence();
            $presenceDate = new \DateTime($date);
            $emp = $em->getRepository(Employe::class)->find($idAgent);
            $marche = $em->getRepository(Marche::class)->find($idMarche);
            $presence->setIdMarche($marche);
            $presence->setType($type);
            $presence->setIdAgent($emp);
            $presence->setRole($role);
            if ( $etat != 'null'){
                $presence->setEtat($etat);
            }
            else {
                $presence->setEtat(3);
            }
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
            if ( $etat != 'null'){
                $presence->setEtat($etat);
            }
            else {
                $presence->setEtat(3);
            }

            $presence->setType($type);
            $presence->setDate($presenceDate);

            $em->persist($presence);
            $em->flush();
            dump($date);
            return new Response($presence->getIdPresence());

        }


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

    public function Repos($idMarche,$date,$idEmp){

        $entityManager = $this->getDoctrine()->getManager();


        $query = $entityManager->createQuery(
            'SELECT count(p) AS NBR , p.etat , e.nom , e.prenom , e.idEmp
    FROM AppBundle:Presence p
    inner join p.idEmp e
    WHERE p.idMarche = :idMarche and p.date LIKE :date and p.etat = 2 and e.idEmp = :idEmp
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

    public function totalAvance($idEmp , $date){
        $em = $this->getDoctrine()->getManager();
        $somme = 0 ;
        $query = $em->createQuery(
            'SELECT a 
    FROM AppBundle:Avance a
    WHERE a.date LIKE :date and a.idEmp = :idEmp 
    '
        )->setParameter('date', $date.'%')
            ->setParameter('idEmp', $idEmp);
        $list = $query->getResult();

        if ($list ){
            foreach ($list as $av ){
                $somme += $av->getMontant();
            }
        }

        return $somme;
    }

    /**
     * totalAbsencePresence.
     *
     * @Route("/api/totalAvance/{idEmp}/{date}", name="total_avance_par_mois")
     * @Method({"GET"})
     */
    public function totalAvance2Action($idEmp , $date){
        $em = $this->getDoctrine()->getManager();
        $somme = 0 ;
        $query = $em->createQuery(
            'SELECT a 
    FROM AppBundle:Avance a
    WHERE a.date LIKE :date and a.idEmp = :idEmp 
    '
        )->setParameter('date', $date.'%')
            ->setParameter('idEmp', $idEmp);
        $list = $query->getResult();

        if ($list ){
            foreach ($list as $av ){
                $somme += $av->getMontant();
            }
        }

        return new Response($somme);
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
        $marche  = $em->getRepository(Marche::class)->find($idMarche);
        $totalAbsence = $this->Absence($idMarche,$date,$idEmp);
        $totalPresence = $this->Presence($idMarche,$date,$idEmp);
        $totalRepos = $this->Repos($idMarche,$date,$idEmp);
        $totalPresenceAffectation = $this->PresenceAgentAction(1 ,$date , $idEmp );
        $totalavance = $this->totalAvance($idEmp,$date);
        $salaire = (($marche->getSpjfixe()*$totalPresence) + ($this->PresenceAgent2Action(1 , $date , $idEmp , 'rotation') + $this->PresenceAgent2Action(1 , $date , $idEmp , 'doublage')) + $marche->getNbrrepos() * $marche->getSpjfixe()) - $totalavance ;

        $totalPresence += $totalPresenceAffectation;
        $totalAbsenceAffectation = $this->PresenceAgentAction(0 ,$date , $idEmp );
        $totalAbsence+=$totalAbsenceAffectation;
        $totalReposAffectation = $this->PresenceAgentAction(2 ,$date , $idEmp );
        $totalRepos+=$totalReposAffectation;

       /* if ( $totalRepos == 0 and $totalPresence != 0 ){
            $salaire+= $marche->getNbrrepos() * $marche->getSpjfixe() ;
        }

        elseif($totalRepos != 0 and $totalPresence != 0) {
            $salaire+=$totalRepos * $marche->getSpjfixe() ;
        }*/

        $total = array(
            'nom'=>$emp->getNom(),
            'Prenom'=>$emp->getPrenom(),
            'TotalAbsence'=>$totalAbsence,
            'TotalPresence'=>$totalPresence,
            'TotalRepos'=>$totalRepos,
            'Periode'=>$date,
            'Salaire'=>$salaire,
            'TotalPresenceAffectation'=>$totalPresenceAffectation
        );

        return new JsonResponse($total);

    }

    /**
     * Modifier presence entity.
     *
     * @Route("/api/modifier/{idPresence}/{etat}/{type}", name="presence_api_modifier")
     * @Route("/api/agent/modifier/{idPresence}/{etat}", name="presence_agent_api_modifier")
     * @Method({"GET", "POST"})
     */
    public function modifierPresenceAction($idPresence , $etat , $type=null){
        if ( $type ){
            $em = $this->getDoctrine()->getManager();

            $presence = $em->getRepository(Presence::class)->find($idPresence);

            $presence->setEtat($etat);
            $presence->setType($type);

            $em->flush();
        }
        else {
            $em = $this->getDoctrine()->getManager();

            $presence = $em->getRepository(Presence::class)->find($idPresence);

            $presence->setEtat($etat);

            $em->flush();
        }


        return new Response('modifier');
    }



    public function PresenceAgentAction($etat,$date,$idEmp){

        $entityManager = $this->getDoctrine()->getManager();


        $query = $entityManager->createQuery(
            'SELECT count(p) AS NBR , p.etat , e.nom , e.prenom 
    FROM AppBundle:Presence p
    inner join p.idAgent e
    LEFT JOIN p.idMarche m
    WHERE p.date LIKE :date and p.idAgent = :idEmp and p.etat = :etat
    GROUP BY p.etat , p.idAgent
    '
        )
            ->setParameter('date', $date.'%')
        ->setParameter('idEmp', $idEmp)
        ->setParameter('etat', $etat);




        $list = $query->getResult();

        if ($list){
            return $list[0]['NBR'];
        }

        else {
            return 0 ;
        }


    }


    public function ReposAction($idMarche,$date,$idEmp){

        $entityManager = $this->getDoctrine()->getManager();


        $query = $entityManager->createQuery(
            'SELECT count(p) AS NBR , p.etat , e.nom , e.prenom , e.idEmp
    FROM AppBundle:Presence p
    inner join p.idEmp e
    WHERE p.idMarche = :idMarche and p.date LIKE :date and p.etat = 2 and e.idEmp = :idEmp
    GROUP BY p.idEmp , p.etat
    '
        )->setParameter('idMarche', $idMarche)
            ->setParameter('date', $date.'%')
            ->setParameter('idEmp', $idEmp);

        $tab = array();
        $list = $query->getResult();

        if ($list){
            return new Response($list[0]['NBR']);
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
            return new Response(0) ;
        }


    }



    public function PresenceAgent2Action($etat,$date,$idEmp , $type){

        $entityManager = $this->getDoctrine()->getManager();

        $RAW_QUERY = 'select  COUNT(*) as nbr , p.etat , m.libellle , m.spjfixe from presence p INNER join marche m on m.id_marche = p.id_marche where p.id_agent_rotation is not null and p.id_agent_rotation = :agent and p.role = :type and date like :date and p.etat = :etat group by p.etat , m.libellle ';

        $statement = $entityManager->getConnection()->prepare($RAW_QUERY);
        // Set parameters
        $statement->bindValue('etat', $etat);
        $statement->bindValue('agent', $idEmp);
        $statement->bindValue('date', $date .'%');
        $statement->bindValue('type', $type);
        $statement->execute();

        $result = $statement->fetchAll();
        $somme = 0 ;
        if ( $result ){
            foreach ( $result as $i ){
                $somme+= $i['nbr'] * $i['spjfixe'];
            }
        }

        return $somme;

    }

    /**
     * totalAbsencePresence.
     *
     * @Route("/api/detaile/{etat}/{date}/{idEmp}", name="details_par_mois")
     * @Method({"GET"})
     */
    public function totalPresenceAffectationAction($etat,$date,$idEmp){

        $entityManager = $this->getDoctrine()->getManager();

        $RAW_QUERY = 'select * from presence p inner join marche m on m.id_marche = p.id_marche where (p.id_agent_rotation = :agent or p.id_emp = :agent) and p.etat = :etat and date like :date';

        $statement = $entityManager->getConnection()->prepare($RAW_QUERY);
        // Set parameters
        $statement->bindValue('etat', $etat);
        $statement->bindValue('agent', $idEmp);
        $statement->bindValue('date', $date .'%');
        $statement->execute();

        $result = $statement->fetchAll();

        $tab = array() ;

        foreach ( $result as $i ){
            if ($i['role']){
                $tab[] = [
                    "marche"=>$i['libellle'] ,
                    "date"=>$i['date'],
                    "role"=>$i['role'],
                    "type"=>$i['type']
                ];
            }

            else {
                $tab[] = [
                    "marche"=>$i['libellle'] ,
                    "date"=>$i['date'],
                    "role"=>'Marche Actuelle',
                    "type"=>$i['type']
                ];
            }

        }

        return new JsonResponse($tab);

    }


    /**
     * totalAbsencePresence.
     *
     * @Route("/api/detail/{idEmp}/{date}", name="detail_salaire_par_mois")
     * @Method({"GET"})
     */
    public function DetailPresenceAction($idEmp , $date){
        $em = $this->getDoctrine()->getManager() ;
        $emp = $em->getRepository(Employe::class)->find($idEmp);
        if ($emp->getIdMarche()){
            $spjfixe = $emp->getIdMarche()->getSpjfixe();
            $salaireRotation = $this->PresenceAgent2Action(1 , $date , $idEmp , 'rotation');
            $salaireDoublage = $this->PresenceAgent2Action(1 , $date , $idEmp , 'doublage');
            $salaireParMarcheActuelle = $this->Presence($emp->getIdMarche()->getIdMarche() , $date , $idEmp) * $spjfixe;
            $totalRepos = $this->Repos($emp->getIdMarche()->getIdMarche(),$date,$idEmp);
            $totalAvance = $this->totalAvance($idEmp , $date);

            $diff = $emp->getIdMarche()->getNbrrepos() - $totalRepos ;

            if ( $diff != $emp->getIdMarche()->getNbrrepos() ){

                $salaireRepos=  $totalRepos  * $emp->getIdMarche()->getSpjfixe();
            }
            else{
                $salaireRepos=  $emp->getIdMarche()->getNbrrepos()  * $emp->getIdMarche()->getSpjfixe();
            }

            $tab = [
                'TotalRotation' => round($salaireRotation) ,
                'TotalDoublage' => round($salaireDoublage),
                'TotalMarche' => round($salaireParMarcheActuelle,3),
                'SalaireRepos'=>round($salaireRepos),
                'TotalAvance'=>round($totalAvance)
            ];

        }

        else {
            $salaireRotation = $this->PresenceAgent2Action(1 , $date , $idEmp , 'rotation');
            $totalAvance = $this->totalAvance($idEmp , $date);
            $tab = [
                'TotalRotation' => round($salaireRotation) ,
                'TotalDoublage' => 0,
                'TotalMarche' => 0,
                'SalaireRepos'=>0,
                'TotalAvance'=>round($totalAvance)
            ];
        }

        return new JsonResponse($tab);

    }

    /**
     * RapportComplet.
     *
     * @Route("/api/rapport/{date}", name="rapport_par_mois")
     * @Method({"GET"})
     */
    public function RapportCompletAction($date){
        $em = $this->getDoctrine()->getManager() ;
        $employes = $em->getRepository(Employe::class)->findBy(array('status'=>'1')) ;
        $tab = array();
        foreach ($employes as $emp ){
            $tab[] = $this->RapportEmployeParMois($emp->getIdMarche() , $date , $emp->getIdEmp());
        }

        return new JsonResponse($tab);
    }

    public function RapportEmployeParMois($idMarche,$date,$idEmp){

        $em = $this->getDoctrine()->getManager();
        if ( $idMarche){
            $emp = $em->getRepository(Employe::class)->find($idEmp);
            $marche  = $em->getRepository(Marche::class)->find($idMarche);
            $totalAbsence = $this->Absence($idMarche,$date,$idEmp);
            $totalPresence = $this->Presence($idMarche,$date,$idEmp);
            $totalRepos = $this->Repos($idMarche,$date,$idEmp);
            $totalPresenceAffectation = $this->PresenceAgentAction(1 ,$date , $idEmp );
            $totalAvance = $this->totalAvance($idEmp , $date);
            $salaire = (($marche->getSpjfixe()*$totalPresence) + ($this->PresenceAgent2Action(1 , $date , $idEmp , 'rotation') + $this->PresenceAgent2Action(1 , $date , $idEmp , 'doublage'))) - $totalAvance ;

            $totalPresence += $totalPresenceAffectation;
            $totalAbsenceAffectation = $this->PresenceAgentAction(0 ,$date , $idEmp );
            $totalAbsence+=$totalAbsenceAffectation;
            $totalReposAffectation = $this->PresenceAgentAction(2 ,$date , $idEmp );
            $totalRepos+=$totalReposAffectation;

            if ( $totalRepos == 0 and $totalPresence != 0 ){
                $salaire+= $marche->getNbrrepos() * $marche->getSpjfixe() ;
            }

            elseif($totalRepos != 0 and $totalPresence != 0) {
                $salaire+=$totalRepos * $marche->getSpjfixe() ;
            }
            $total = array(
                'id'=>$emp->getIdEmp(),
                'nom'=>$emp->getNom(),
                'Prenom'=>$emp->getPrenom(),
                'Marche'=>$marche->getLibellle(),
                'TotalAbsence'=>$totalAbsence,
                'TotalPresence'=>$totalPresence,
                'TotalRepos'=>$totalRepos,
                'Periode'=>$date,
                'Salaire'=>round($salaire , 4),
                'TotalPresenceAffectation'=>$totalPresenceAffectation
            );
        }

        else {
            $emp = $em->getRepository(Employe::class)->find($idEmp);
            $totalAbsence = 0;
            $totalPresence = 0;
            $totalRepos = 0;
            $totalPresenceAffectation = $this->PresenceAgentAction(1 ,$date , $idEmp );
            $totalAvance = $this->totalAvance($idEmp , $date);
            $salaire =  ($this->PresenceAgent2Action(1 , $date , $idEmp , 'rotation') ) - $totalAvance ;

            $totalPresence += $totalPresenceAffectation;
            $totalAbsenceAffectation = $this->PresenceAgentAction(0 ,$date , $idEmp );
            $totalAbsence+=$totalAbsenceAffectation;
            $totalReposAffectation = $this->PresenceAgentAction(2 ,$date , $idEmp );
            $totalRepos+=$totalReposAffectation;

            $total = array(
                'id'=>$emp->getIdEmp(),
                'nom'=>$emp->getNom(),
                'Marche'=>'Non Affecté',
                'Prenom'=>$emp->getPrenom(),
                'TotalAbsence'=>$totalAbsence,
                'TotalPresence'=>$totalPresence,
                'TotalRepos'=>$totalRepos,
                'Periode'=>$date,
                'Salaire'=>round($salaire ,4),
                'TotalPresenceAffectation'=>$totalPresenceAffectation
            );
        }




        return $total;

    }

    /**
     * SalaireComplet.
     *
     * @Route("/api/TotalSalaire/{date}", name="total_salaire")
     * * @Route("/api/TotalSalaire/{date}/{idMarche}", name="total_salaire")
     * @Method({"GET"})
     */
    public function TotalSalaireAction($date ,$idMarche = null){
        $em = $this->getDoctrine()->getManager() ;
        $TotalSalaire = 0 ;
        if ($idMarche == null) {
            $employes = $em->getRepository(Employe::class)->findby(array('status'=>'1')) ;
        }
        else {
            $marche = $em->getRepository(Marche::class)->find($idMarche);
            $employes = $em->getRepository(Employe::class)->findBy(array('idMarche'=>$marche , 'status'=>'1')) ;
        }
        $tab = array();
        foreach ($employes as $emp ){
            $tab[] = $this->RapportEmployeParMois($emp->getIdMarche() , $date , $emp->getIdEmp());
        }

        foreach ($tab as $t){
            $TotalSalaire+= $t['Salaire'];
        }
        return new Response($TotalSalaire);
    }


}
