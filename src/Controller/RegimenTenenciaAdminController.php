<?php
namespace App\Controller;

use App\Entity\RegimenTenencia;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegimenTenenciaAdminController extends AbstractController
{
    /**
     * @Route("/admin/regimentenencia/new")
     */
    public function new( EntityManagerInterface $em){
        $regimenTenencia= new RegimenTenencia();
        $regimenTenencia->setTipo("Propietario");

        $em->persist($regimenTenencia);
        $em->flush();

        return new Response(sprintf("nuevo regimen de tenencia %s", $regimenTenencia->getId()));
    }

    /**
     * @Route("/admin/regimentenencia/show/{tipo}")
     */
    public function show($tipo, EntityManagerInterface $em){
        $repository= $em->getRepository(RegimenTenencia::class);
        $regimenTenencia= $repository->findOneBy(['tipo'=>$tipo]);
        if(!$regimenTenencia){
            throw $this->createNotFoundException(sprintf("No existe el tipo %s que quiere mostrar", $tipo));
        }
        //dump($regimenTenencia); die();

        return $this->render('regimenTenencia/show.html.twig', [
            'regimenTenencia' => $regimenTenencia
        ]);
    }

}