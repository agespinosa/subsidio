<?php

namespace App\Controller;

use App\Entity\Requisito;
use App\Form\RequisitoType;
use App\Repository\RequisitoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/requisito")
 */
class RequisitoController extends AbstractController
{
    /**
     * @Route("/", name="requisito_index", methods={"GET"})
     */
    public function index(RequisitoRepository $requisitoRepository): Response
    {
        return $this->render('requisito/index.html.twig', [
            'requisitos' => $requisitoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="requisito_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $requisito = new Requisito();
        $form = $this->createForm(RequisitoType::class, $requisito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($requisito);
            $entityManager->flush();

            return $this->redirectToRoute('requisito_index');
        }

        return $this->render('requisito/new.html.twig', [
            'requisito' => $requisito,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="requisito_show", methods={"GET"})
     */
    public function show(Requisito $requisito): Response
    {
        return $this->render('requisito/show.html.twig', [
            'requisito' => $requisito,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="requisito_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Requisito $requisito): Response
    {
        $form = $this->createForm(RequisitoType::class, $requisito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('requisito_index');
        }

        return $this->render('requisito/edit.html.twig', [
            'requisito' => $requisito,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="requisito_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Requisito $requisito): Response
    {
        if ($this->isCsrfTokenValid('delete'.$requisito->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($requisito);
            $entityManager->flush();
        }

        return $this->redirectToRoute('requisito_index');
    }
}
