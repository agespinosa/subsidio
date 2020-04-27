<?php


namespace App\Controller;


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

}