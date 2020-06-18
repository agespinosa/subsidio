<?php
namespace App\Controller;

use App\Entity\Propietario;
use App\Repository\PropietarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PropietarioController extends AbstractController
{
    /**
     * @Route("/propietario/list")
     */
    public function list(PropietarioRepository $repository){
        $propietarios= $repository->findAllPropietarioOrderedByRazonSocial();

        return $this->render('propietario/list.html.twig', [
            'propietarios'=> $propietarios
        ]);
    }
    /**
     * @Route("/propietario/new")
     */
    public function new( EntityManagerInterface $em){
        /*
        $propietario1= new Propietario();
        $propietario1->setRenspa("20.007.0.0.01311/02");
        $propietario1->setRazonSocial("DON ANTONIO S.H");
        $propietario1->setCuit("33714350079");
        $propietario1->setDomicilio("Zona Rural Los Laureles");
        $propietario1->setTelefono("3482555589");
        $propietario1->setCodigoPostal("3567");

        $em->persist($propietario1);
        $em->flush();

        return new Response(sprintf("Se creo el nuevo Propietario %s", $propietario1->getRazonSocial()));
        */
    }

    /**
     * @Route("/propietario/show/{slug}", name="propietario_show")
     */
    public function show(Propietario $propietario, EntityManagerInterface $em){
        if(!$propietario){
            throw $this->createNotFoundException(sprintf("No existe el propietario %s que quiere mostrar", $cuit));
        }
        return $this->render('propietario/show.html.twig', [
            'propietario' => $propietario
        ]);
    }

}