<?php

namespace App\Controller;

use App\Entity\AtributoConfiguracion;
use App\Form\AtributoConfiguracionType;
use App\Repository\AtributoConfiguracionRepository;
use App\Repository\MarcaRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/atributo/configuracion")
 */
class AtributoConfiguracionController extends AbstractController
{
    
    /**
     * @var AtributoConfiguracionRepository
     */
    private $atributoConfiguracionRepository;
    /**
     * @var PaginatorInterface
     */
    private $paginator;
    
    public function __construct(AtributoConfiguracionRepository $atributoConfiguracionRepository, PaginatorInterface $paginator)
    {
    
        $this->atributoConfiguracionRepository = $atributoConfiguracionRepository;
        $this->paginator = $paginator;
    }
    /**
     * @Route("/", name="atributo_configuracion_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $query = $request->query->get('q');
        $size = $request->query->get('size');
        $page = $request->query->getInt('page', 1);
        $reset = $request->query->get('reset');
        if(!is_null($reset) && $reset === 'reset'
            && (is_null($size)
                && $query)){
            $query = null;
            $size = 10;
            $page = 1;
        }
        $queryBuilder =
            $this->atributoConfiguracionRepository->getWithSearchQueryBuilder($query);
    
        $pagination =$this->paginator->paginate(
            $queryBuilder,
            $page,
            $size ? $size : 10
        );


        return $this->render('atributo_configuracion/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="atributo_configuracion_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $atributoConfiguracion = new AtributoConfiguracion();
        $form = $this->createForm(AtributoConfiguracionType::class, $atributoConfiguracion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($atributoConfiguracion);
            $entityManager->flush();

            return $this->redirectToRoute('atributo_configuracion_index');
        }

        return $this->render('atributo_configuracion/new.html.twig', [
            'atributo_configuracion' => $atributoConfiguracion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="atributo_configuracion_show", methods={"GET"})
     */
    public function show(AtributoConfiguracion $atributoConfiguracion): Response
    {
        return $this->render('atributo_configuracion/show.html.twig', [
            'atributo_configuracion' => $atributoConfiguracion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="atributo_configuracion_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, AtributoConfiguracion $atributoConfiguracion): Response
    {
        $form = $this->createForm(AtributoConfiguracionType::class, $atributoConfiguracion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('atributo_configuracion_index');
        }

        return $this->render('atributo_configuracion/edit.html.twig', [
            'atributo_configuracion' => $atributoConfiguracion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="atributo_configuracion_delete", methods={"DELETE"})
     */
    public function delete(Request $request, AtributoConfiguracion $atributoConfiguracion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$atributoConfiguracion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($atributoConfiguracion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('atributo_configuracion_index');
    }
}
