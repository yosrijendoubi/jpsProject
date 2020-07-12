<?php

namespace AppBundle\Controller;

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
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Marche controller.
 *
 * @Route("marche")
 */
class MarcheController extends Controller
{
    /**
     * Lists all marche entities.
     *
     * @Route("/", name="marche_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $marches = $em->getRepository('AppBundle:Marche')->findAll();

        return $this->render('marche/index.html.twig', array(
            'marches' => $marches,
        ));
    }

    /**
     * Creates a new marche entity.
     *
     * @Route("/new", name="marche_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $marche = new Marche();
        $form = $this->createForm('AppBundle\Form\MarcheType', $marche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($marche);
            $em->flush();

            return $this->redirectToRoute('marche_show', array('idMarche' => $marche->getIdmarche()));
        }

        return $this->render('marche/new.html.twig', array(
            'marche' => $marche,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a marche entity.
     *
     * @Route("/{idMarche}", name="marche_show")
     * @Method("GET")
     */
    public function showAction(Marche $marche)
    {
        $deleteForm = $this->createDeleteForm($marche);

        return $this->render('marche/show.html.twig', array(
            'marche' => $marche,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing marche entity.
     *
     * @Route("/{idMarche}/edit", name="marche_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Marche $marche)
    {
        $deleteForm = $this->createDeleteForm($marche);
        $editForm = $this->createForm('AppBundle\Form\MarcheType', $marche);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('marche_edit', array('idMarche' => $marche->getIdmarche()));
        }

        return $this->render('marche/edit.html.twig', array(
            'marche' => $marche,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a marche entity.
     *
     * @Route("/{idMarche}", name="marche_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Marche $marche)
    {
        $form = $this->createDeleteForm($marche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($marche);
            $em->flush();
        }

        return $this->redirectToRoute('marche_index');
    }

    /**
     * Creates a form to delete a marche entity.
     *
     * @param Marche $marche The marche entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Marche $marche)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('marche_delete', array('idMarche' => $marche->getIdmarche())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * consulter le marcher.
     *
     * @Route("/consulter/{idMarche}", name="consulter_marcher")
     * @Route("/consulter/{idMarche}/{date}", name="consulter_marcher")
     * @Method("GET")
     */
    public function ConsulterMarcherAction($idMarche , $date = null,Request $request){
        $em =  $this->getDoctrine()->getManager();

        $marche = $em->getRepository(Marche::class)->find($idMarche);
        $listEmp  = $marche->getIdEmp();
        if ( $date == null ){
            $todayPresence = $this->getDoctrine()
                ->getManager()
                ->createQuery('SELECT p FROM AppBundle:Presence p WHERE p.date LIKE CURRENT_DATE() and p.idMarche = :idMarche')
                ->setParameter('idMarche',$idMarche)
                ->getResult();

        }

        else {
            $todayPresence = $this->getDoctrine()
                ->getManager()
                ->createQuery('SELECT p FROM AppBundle:Presence p WHERE p.date LIKE :date and p.idMarche = :idMarche')
                ->setParameter('date',$date)
                ->setParameter('idMarche',$idMarche)
                ->getResult();
        }
        $avance = new Avance();
        $form = $this->createForm('AppBundle\Form\AvanceType', $avance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($avance);
            $em->flush();

        }



        return $this->render('marche/consulter.html.twig', array(

            'datee'=> $date,
            'form' => $form->createView(),
            'idMarche'=>$idMarche,
            'listEmp' => $listEmp,
            'todayPresence'=>$todayPresence
        ));

    }

    /**
     * presence par date.
     *
     * @Route("/presence/{date}/{idMarche}", name="presence_par_date")
     * @Method("GET")
     */
    public function getPresenceParDate($date , $idMarche){


        $Presences = $this->getDoctrine()
            ->getManager()
            ->createQuery('SELECT p FROM AppBundle:Presence p WHERE p.date LIKE :date and p.idMarche = :idMarche')
            ->setParameter('date',$date)
            ->setParameter('idMarche',$idMarche)
            ->getResult();

        $sz = new Serializer([new ObjectNormalizer()]);
        $formatted = $sz->normalize($Presences);


        return new JsonResponse($formatted);
    }

}
