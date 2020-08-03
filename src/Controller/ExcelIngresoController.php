<?php

namespace App\Controller;

use App\Entity\ExcelIngreso;
use App\Form\ExcelIngresoType;
use App\Repository\ExcelIngresoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/excel/ingreso")
 */
class ExcelIngresoController extends AbstractController
{
    /**
     * @Route("/", name="excel_ingreso_index", methods={"GET"})
     */
    public function index(ExcelIngresoRepository $excelIngresoRepository): Response
    {
        return $this->render('excel_ingreso/index.html.twig', [
            'excel_ingresos' => $excelIngresoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="excel_ingreso_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $excelIngreso = new ExcelIngreso();
        $form = $this->createForm(ExcelIngresoType::class, $excelIngreso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($excelIngreso);
            $entityManager->flush();

            return $this->redirectToRoute('excel_ingreso_index');
        }

        return $this->render('excel_ingreso/new.html.twig', [
            'excel_ingreso' => $excelIngreso,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="excel_ingreso_show", methods={"GET"})
     */
    public function show(ExcelIngreso $excelIngreso): Response
    {
        return $this->render('excel_ingreso/show.html.twig', [
            'excel_ingreso' => $excelIngreso,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="excel_ingreso_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ExcelIngreso $excelIngreso): Response
    {
        $form = $this->createForm(ExcelIngresoType::class, $excelIngreso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('excel_ingreso_index');
        }

        return $this->render('excel_ingreso/edit.html.twig', [
            'excel_ingreso' => $excelIngreso,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="excel_ingreso_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ExcelIngreso $excelIngreso): Response
    {
        if ($this->isCsrfTokenValid('delete'.$excelIngreso->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($excelIngreso);
            $entityManager->flush();
        }

        return $this->redirectToRoute('excel_ingreso_index');
    }
}
