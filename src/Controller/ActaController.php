<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActaController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function homepage()
    {
        return new Response('home acta');
    }

    /**
     * @Route("/show/{slug}")
     */
    public function show($slug)
    {
      return $this->render('acta/show.html.twig',
          [
              'acta'=>$slug
          ]);
    }

}