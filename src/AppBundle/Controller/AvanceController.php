<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Avance;
use AppBundle\Entity\Employe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Avance controller.
 *
 * @Route("avance")
 */
class AvanceController extends Controller
{
    /**
     * Lists all avance entities.
     *
     * @Route("/", name="avance_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $avances = $em->getRepository('AppBundle:Avance')->findAll();
        $agent_de_rotations = $em->getRepository(Employe::class)->findBy(array('status'=>'1'));
        return $this->render('avance/index.html.twig', array(
            'avances' => $avances,
            'agent_de_rotations'=>$agent_de_rotations
        ));
    }

    /**
     * Creates a new avance entity.
     *
     * @Route("/new", name="avance_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $avance = new Avance();
        $form = $this->createForm('AppBundle\Form\AvanceType', $avance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($avance);
            $em->flush();

            return $this->redirectToRoute('avance_show', array('id' => $avance->getId()));
        }

        return $this->render('avance/new.html.twig', array(
            'avance' => $avance,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a avance entity.
     *
     * @Route("/{id}", name="avance_show")
     * @Method("GET")
     */
    public function showAction(Avance $avance)
    {
        $deleteForm = $this->createDeleteForm($avance);

        return $this->render('avance/show.html.twig', array(
            'avance' => $avance,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing avance entity.
     *
     * @Route("/{id}/edit", name="avance_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Avance $avance)
    {
        $deleteForm = $this->createDeleteForm($avance);
        $editForm = $this->createForm('AppBundle\Form\AvanceType', $avance);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('avance_edit', array('id' => $avance->getId()));
        }

        return $this->render('avance/edit.html.twig', array(
            'avance' => $avance,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a avance entity.
     *
     * @Route("/{id}", name="avance_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Avance $avance)
    {
        $form = $this->createDeleteForm($avance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($avance);
            $em->flush();
        }

        return $this->redirectToRoute('avance_index');
    }

    /**
     * Creates a form to delete a avance entity.
     *
     * @param Avance $avance The avance entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Avance $avance)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('avance_delete', array('id' => $avance->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Ajouter a avance entity.
     *
     * @Route("/api/ajouter/{idEmp}/{montant}", name="ajouter_avance")
     * @Method("GET")
     */
    public function AddAvanceAction($idEmp,$montant){
        $em = $this->getDoctrine()->getManager();
        $emp = $em->getRepository(Employe::class)->find($idEmp);
        $avance = new Avance();
        $avance->setIdEmp($emp) ;
        $avance->setMontant($montant);
        $avance->setDate(new \DateTime());

        $em->persist($avance);
        $em->flush();

        return new Response('ok');
    }

    /**
     * list avance
     *
     * @Route("/api/list/{idEmp}", name="get_avance")
     * @Method("GET")
     */
    public function ListAvanceAction($idEmp){
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository(Avance::class)->findBy(array('idEmp'=>$idEmp));
        $tab = array() ;
        if ( $list ){
            foreach ($list as $av){
                $tab[] = [
                    'idAvance'=>$av->getId(),
                    'date'=>$av->getDate()->format('Y-m-d'),
                    'montant'=>$av->getMontant()
                ];
            }
        }
        return new JsonResponse($tab);
    }


    /**
     * delete avance
     *
     * @Route("/api/delete/{idAvance}", name="supprimer_avance")
     * @Method("GET")
     */
    public function SupprimerAvanceAction($idAvance){
        $em = $this->getDoctrine()->getManager();
        $av = $em->getRepository(Avance::class)->find($idAvance);

        $em->remove($av);

        $em->flush();

        return new Response("success");

    }
}
