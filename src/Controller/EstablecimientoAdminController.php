<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EstablecimientoAdminController extends AbstractController
{
    /**
     * @Route("/mono/establecimiento", name="establecimiento_admin")
     */
    public function index()
    {
        return $this->render('establecimiento_admin/index.html.twig', [
            'controller_name' => 'EstablecimientoAdminController',
        ]);
    }
}
