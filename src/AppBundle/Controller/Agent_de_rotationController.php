<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Agent_de_rotation;
use AppBundle\Entity\Marche;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Agent_de_rotation controller.
 *
 * @Route("rotation")
 */
class Agent_de_rotationController extends Controller
{
    /**
     * Lists all agent_de_rotation entities.
     *
     * @Route("/", name="agent_de_rotation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $agent_de_rotations = $em->getRepository('AppBundle:Agent_de_rotation')->findAll();
        $marches = $em->getRepository(Marche::class)->findAll();

        return $this->render('agent_de_rotation/index.html.twig', array(
            'agent_de_rotations' => $agent_de_rotations,
            'marches'=>$marches
        ));
    }

    /**
     * Creates a new agent_de_rotation entity.
     *
     * @Route("/new", name="agent_de_rotation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $agent_de_rotation = new Agent_de_rotation();
        $form = $this->createForm('AppBundle\Form\Agent_de_rotationType', $agent_de_rotation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($agent_de_rotation);
            $em->flush();

            return $this->redirectToRoute('agent_de_rotation_show', array('id' => $agent_de_rotation->getId()));
        }

        return $this->render('agent_de_rotation/new.html.twig', array(
            'agent_de_rotation' => $agent_de_rotation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a agent_de_rotation entity.
     *
     * @Route("/{id}", name="agent_de_rotation_show")
     * @Method("GET")
     */
    public function showAction(Agent_de_rotation $agent_de_rotation)
    {
        $deleteForm = $this->createDeleteForm($agent_de_rotation);

        return $this->render('agent_de_rotation/show.html.twig', array(
            'agent_de_rotation' => $agent_de_rotation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing agent_de_rotation entity.
     *
     * @Route("/{id}/edit", name="agent_de_rotation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Agent_de_rotation $agent_de_rotation)
    {
        $deleteForm = $this->createDeleteForm($agent_de_rotation);
        $editForm = $this->createForm('AppBundle\Form\Agent_de_rotationType', $agent_de_rotation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('agent_de_rotation_edit', array('id' => $agent_de_rotation->getId()));
        }

        return $this->render('agent_de_rotation/edit.html.twig', array(
            'agent_de_rotation' => $agent_de_rotation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a agent_de_rotation entity.
     *
     * @Route("/{id}", name="agent_de_rotation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Agent_de_rotation $agent_de_rotation)
    {
        $form = $this->createDeleteForm($agent_de_rotation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($agent_de_rotation);
            $em->flush();
        }

        return $this->redirectToRoute('agent_de_rotation_index');
    }

    /**
     * Creates a form to delete a agent_de_rotation entity.
     *
     * @param Agent_de_rotation $agent_de_rotation The agent_de_rotation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Agent_de_rotation $agent_de_rotation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('agent_de_rotation_delete', array('id' => $agent_de_rotation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
