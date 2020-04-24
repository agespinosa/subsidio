<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActaController
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
        return new Response(sprintf('show acta numero: %s',
        $slug
        ));
    }

}