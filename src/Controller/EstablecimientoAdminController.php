<?php

namespace App\Controller;

use App\Entity\Establecimiento;
use App\Repository\EstablecimientoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EstablecimientoAdminController extends AbstractController
{
    /**
     * @Route("/admin/establecimiento", name="establecimiento_admin")
     * @IsGranted("ROLE_ADMIN_ESTABLECIMIENTO")
     */
    public function index()
    {
        return $this->render('establecimiento_admin/index.html.twig', [
            'controller_name' => 'EstablecimientoAdminController',
        ]);
    }
    /**
     * @Route("/admin/establecimiento/list", name="admin_list_establecimiento")
     */
    public function list(EstablecimientoRepository $repository, Request $request)
    {

        $q = $request->query->get('q');
        $establecimientos= $repository->findAllWithSearch($q);

        return $this->render('establecimiento_admin/list.html.twig', [
            'establecimientos'=> $establecimientos
        ]);
    }

    /**
     * @Route("/establecimiento/show/{slug}", name="establecimiento_show")
     */
    public function show(Establecimiento $establecimiento, EntityManagerInterface $em){
        if(!$establecimiento){
            throw $this->createNotFoundException(sprintf("No existe el establecimeinto %s que quiere mostrar"));
        }
        return $this->render('establecimiento_admin/show.html.twig', [
            'establecimiento' => $establecimiento
        ]);
    }
}
