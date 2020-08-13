<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
/**
 * rapport controller.
 *
 * @Route("rapport")
 */
class RapportController extends Controller
{
    /**
     * @Route("/all", name="rapport_index")
     */
    public function indexAction(Request $request)
    {

        return $this->render('rapport/rapport.html.twig');
    }
}
