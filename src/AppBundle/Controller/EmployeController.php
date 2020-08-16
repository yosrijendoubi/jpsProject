<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Employe;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Employe controller.
 *
 * @Route("employe")
 */
class EmployeController extends Controller
{
    /**
     * Lists all employe entities.
     *
     * @Route("/", name="employe_index")
     * @Method("GET")
     */
    public function indexAction(PaginatorInterface $paginator, Request $request)
    {
        $employe = new Employe();
        $form = $this->createForm('AppBundle\Form\EmployeType', $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $employe->setStatus(1);
            $em->persist($employe);
            $em->flush();
            $this->addFlash('success', 'Employée ajoutée avec success!');

            return $this->redirectToRoute('employe_index');
        }
        $em = $this->getDoctrine()->getManager();

        $employes = $em->getRepository('AppBundle:Employe')->findAll();
        $dql   = "SELECT e FROM AppBundle:Employe e";
        $query = $em->createQuery($dql);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('employe/index.html.twig', array(
            'employes' => $employes,
            'pagination' => $pagination,
            'form' => $form->createView()

        ));
    }

    /**
     * Creates a new employe entity.
     *
     * @Route("/new", name="employe_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $employe = new Employe();
        $form = $this->createForm('AppBundle\Form\EmployeType', $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($employe);
            $em->flush();
            $this->addFlash('success', 'Employée ajoutée avec success!');

            return $this->redirectToRoute('employe_new', array('idEmp' => $employe->getIdemp()));
        }

        return $this->render('employe/new.html.twig', array(
            'employe' => $employe,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a employe entity.
     *
     * @Route("/{idEmp}", name="employe_show")
     * @Method("GET")
     */
    public function showAction(Employe $employe)
    {
        $deleteForm = $this->createDeleteForm($employe);

        return $this->render('employe/show.html.twig', array(
            'employe' => $employe,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing employe entity.
     *
     * @Route("/{idEmp}/edit", name="employe_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Employe $employe)
    {
        $deleteForm = $this->createDeleteForm($employe);
        $editForm = $this->createForm('AppBundle\Form\EmployeType', $employe);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('warning', 'Employe modifié avec success!');

            return $this->redirectToRoute('employe_edit', array('idEmp' => $employe->getIdemp()));
        }

        return $this->render('employe/edit.html.twig', array(
            'employe' => $employe,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a employe entity.
     *
     * @Route("/{idEmp}", name="employe_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Employe $employe)
    {
        $form = $this->createDeleteForm($employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($employe);
            $em->flush();
        }

        return $this->redirectToRoute('employe_index');
    }

    /**
     * Creates a form to delete a employe entity.
     *
     * @param Employe $employe The employe entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Employe $employe)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('employe_delete', array('idEmp' => $employe->getIdemp())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Deletes a employe entity.
     *
     * @Route("/supprimer/{idEmp}", name="employe_supprimer")
     * @Method("DELETE")
     */
    public function SupprimerEmployeAction($idEmp){
        $em = $this->getDoctrine()->getManager();

        $emp = $em->getRepository(Employe::class)->find($idEmp);

        $em->remove($emp);

        $em->flush();

        return new Response('Supprimer');

    }


    /**
     * set stats to an employe .
     *
     * @Route("/enableDisable/{idEmp}/{status}", name="set_status_employe")
     * @Method("GET")
     */
    public function setStatusAction($idEmp , $status){
        $em = $this->getDoctrine()->getManager();
        $emp = $em->getRepository(Employe::class)->find($idEmp);
        $emp->setStatus($status);
        $em->flush();
        return new Response('ok');
    }
}
