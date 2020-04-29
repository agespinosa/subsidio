<?php


namespace App\Controller;


use App\Entity\Acta;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActaController extends AbstractController
{
    /**
     * @Route("/" , name="app_homepage")
     */
    public function homepage(LoggerInterface $logger)
    {
        $logger->info("ingresa al home de la aplicacion");
        return $this->render('acta/homepage.html.twig');
    }

    /**
     * @Route("/show/{slug}", name="acta_show")
     */
    public function show($slug)
    {
      return $this->render('acta/show.html.twig',
          [
              'acta'=>$slug
          ]);
    }

    /**
     * @Route("/admin/acta/new", name="acta_new")
     */
    public function new(EntityManagerInterface $em)
    {
        $acta= new Acta();
        $acta->setBovinosVacunadosContraFiebreAftosaVacas(10);
        $acta->setSistematica(true);
        $acta->setVacunaAntiAftosaMarca("Roche");
        $acta->setVacunaAntiAftosaVencimiento(new \DateTime());

        $em->persist($acta);
        $em->flush();
        return new Response(sprintf("Se genero un nuevo acta nùmero: %s", $acta->getId()));
    }

}