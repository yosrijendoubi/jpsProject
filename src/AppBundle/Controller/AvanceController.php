<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Avance;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

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

        return $this->render('avance/index.html.twig', array(
            'avances' => $avances,
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
}
