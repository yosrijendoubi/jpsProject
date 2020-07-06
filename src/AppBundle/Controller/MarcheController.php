<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Employe;
use AppBundle\Entity\Marche;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * List employe par marche.
     *
     * @Route("/list_employe/{idMarche}", name="list_employe_par_marche")
     * @Method("GET")
     */
    public function ListEmployeParMarcheAction($idMarche){
        $em =  $this->getDoctrine()->getManager();

        $marche = $em->getRepository(Marche::class)->find($idMarche);

        $listEmp  = $marche->getIdEmp();
        return $this->render('presence/index.html.twig', array(
            'listEmp' => $listEmp
        ));

    }

}
