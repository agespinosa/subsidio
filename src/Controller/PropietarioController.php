<?php
namespace App\Controller;

use App\Entity\Propietario;
use App\Repository\PropietarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class PropietarioController extends AbstractController
{
    /**
     * @Route("/propietario/list")
     */
    public function list(PropietarioRepository $repository, Request $request, PaginatorInterface $paginator)
    {

        $q = $request->query->get('q');

        $queryBuilder = $repository->getWithSearchQueryBuilder($q);
        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('propietario/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    /**
     * @Route("/propietario/new")
     * @IsGranted("ROLE_ADMIN_PROPIETARIO")
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

    /**
     * @Route("/propietario/{id}/edit")
     * @IsGranted("MANAGE", subject="propietario")
     */
    public function edit( Propietario $propietario ){
        //$this->denyAccessUnlessGranted('MANAGE', $propietario);
        dd($propietario);
    }

}