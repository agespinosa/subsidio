<?php

namespace App\Controller;

use App\Entity\Cabecera;
use App\Form\CabeceraType;
use App\Repository\CabeceraRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cabecera")
 */
class CabeceraController extends AbstractController
{
    /**
     * @Route("/", name="cabecera_index", methods={"GET"})
     */
    public function index(CabeceraRepository $cabeceraRepository): Response
    {
        return $this->render('cabecera/list.html.twig', [
            'cabeceras' => $cabeceraRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="cabecera_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cabecera = new Cabecera();
        $cabecera->setRegistroId('FH');
        $cabecera->setFechaCreacionArchivo(new \DateTime());
        $cabecera->setHoraCreacionArchivo(new \DateTime());
        $cabecera->setNumeroCliente(1234567);
        $form = $this->createForm(CabeceraType::class, $cabecera);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cabecera);
            $entityManager->flush();

            return $this->redirectToRoute('cabecera_index');
        }

        return $this->render('cabecera/new.html.twig', [
            'cabecera' => $cabecera,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cabecera_show", methods={"GET"})
     */
    public function show(Cabecera $cabecera): Response
    {
        return $this->render('cabecera/show.html.twig', [
            'cabecera' => $cabecera,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cabecera_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cabecera $cabecera): Response
    {
        $form = $this->createForm(CabeceraType::class, $cabecera);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cabecera_index');
        }

        return $this->render('cabecera/edit.html.twig', [
            'cabecera' => $cabecera,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cabecera_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Cabecera $cabecera): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cabecera->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cabecera);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cabecera_index');
    }
}
