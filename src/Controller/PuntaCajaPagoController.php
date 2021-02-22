<?php

namespace App\Controller;

use App\Entity\ExcelIngreso;
use App\Entity\PuntaCajaPago;
use App\Exception\SimpleMessageException;
use App\Form\PuntaCajaPagoType;
use App\Repository\ExcelIngresoRepository;
use App\Repository\PuntaCajaPagoRepository;
use App\Repository\SubsidioPagoProveedoresRepository;
use App\Services\ExcelService;
use App\Services\SubsidioService;
use App\Services\ValidationService;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Error\FatalError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/punta/caja/pago")
 */
class PuntaCajaPagoController extends AbstractController
{
    /**
     * @var PuntaCajaPagoRepository
     */
    private $puntaCajaPagoRepository;
    /**
     * @var ExcelService
     */
    private $excelService;
    /**
     * @var ExcelIngresoRepository
     */
    private $excelIngresoRepository;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var SubsidioPagoProveedoresRepository
     */
    private $subsidioPagoProveedoresRepository;
    /**
     * @var ValidationService
     */
    private $validationService;
    /**
     * @var SubsidioService
     */
    private $subsidioService;
    /**
     * @var PaginatorInterface
     */
    private $paginator;
    
    /**
     * RequisitoController constructor.
     * @param ExcelService $excelService
     * @param ExcelIngresoRepository $excelIngresoRepository
     * @param LoggerInterface $logger
     * @param PuntaCajaPagoRepository $puntaCajaPagoRepository
     * @param SubsidioPagoProveedoresRepository $subsidioPagoProveedoresRepository
     * @param ValidationService $validationService
     * @param SubsidioService $subsidioService
     */
    public function __construct(ExcelService $excelService, ExcelIngresoRepository $excelIngresoRepository,
                                LoggerInterface $logger,
                                PuntaCajaPagoRepository $puntaCajaPagoRepository,
                                SubsidioPagoProveedoresRepository $subsidioPagoProveedoresRepository,
                                ValidationService $validationService,
                                SubsidioService $subsidioService,
                                PaginatorInterface $paginator
    )
    {
    
        $this->excelService = $excelService;
        $this->excelIngresoRepository = $excelIngresoRepository;
        $this->logger = $logger;
        $this->puntaCajaPagoRepository = $puntaCajaPagoRepository;
        $this->subsidioPagoProveedoresRepository = $subsidioPagoProveedoresRepository;
        $this->validationService = $validationService;
        $this->subsidioService = $subsidioService;
        $this->paginator = $paginator;
    }
    
    
    /**
     * @Route("/", name="punta_caja_pago_index", methods={"GET"})
     */
    public function index( Request $request, PaginatorInterface $paginator): Response
    {
        $publicUploadsPathBase = $this->getParameter('public_uploads_path_base');
        
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
        
        $queryBuilder =$this->puntaCajaPagoRepository->getWithSearchQueryBuilder($query);
        
        $pagination =$this->paginator->paginate(
            $queryBuilder,
            $page,
            $size ? $size : 10
        );
        
        
        return $this->render('punta_caja_pago/index.html.twig', [
            'pagination' => $pagination,
            'publicUploadsPathBase' => $publicUploadsPathBase,
        ]);
        
    }

    /**
     * @Route("/new", name="punta_caja_pago_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $puntaCajaPago = new PuntaCajaPago();
        $form = $this->createForm(PuntaCajaPagoType::class, $puntaCajaPago);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
    
            /** @var UploadedFile $file */
            $file = $form->get('filename')->getData();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
                $this->logger->debug("Subiendo Excel , nuevo pago punta Caja ".$newFilename);
                try {
                    $file->move(
                        $this->getParameter('files_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $message = "Error Subiendo Excel , nuevo pago punta Caja ".$e->getMessage();
                    $this->logger->error($message);
                    $this->addFlash('errorMessage', $message);
                }
        
                $excelIngresosPath = $this->getParameter('excel_puntacaja_directory_relative_path');
                $relativePath = $excelIngresosPath.'/'.$newFilename;
                $puntaCajaPago->setFileExcelOriginalPath($relativePath);
                $puntaCajaPago->setFileExcelOriginalName($newFilename);
        
                $filePath =  $this->getParameter('files_puntacaja_directory').'/'.$newFilename;
        
                $excelIngresos = array();
                try{
                    // Leo el excel y genero el/los ExcelIngreso, uno por cada fila del Excel
                    $excelIngresos = $this->excelService->readFilePuntaCaja($filePath);
            
                }catch (\Exception | FatalError | \RuntimeException $exception){
                    $message = "Error Leyendo Excel Punta Caja".$exception->getMessage();
                    $this->logger->error($message);
                    $this->addFlash('errorMessage', $message);
                    return $this->render('requisito/new.html.twig', [
                        'punta_caja_pago' => $puntaCajaPago,
                        'form' => $form->createView()
                    ]);
                }catch (SimpleMessageException $sm){
                    $message = "Error Leyendo Excel Punta Caja ".$sm->getMessage();
                    $this->logger->error($message);
                    $this->addFlash('errorMessage', $message);
                    return $this->render('requisito/new.html.twig', [
                        'punta_caja_pago' => $puntaCajaPago,
                        'form' => $form->createView()
                    ]);
                }
        
                /** @var ExcelIngreso $excelIngreso */
                foreach ($excelIngresos as $excelIngreso) {
                    $excelIngreso->setPuntaCajaPago($puntaCajaPago);
                    $this->excelIngresoRepository->persist($excelIngreso);
                }
            }
 
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($puntaCajaPago);
            $entityManager->flush();
    
            $this->logger->debug('Archivo subido y procesado correctamente '.$newFilename);
            $this->addFlash('successMessage','Archivo subido y procesado correctamente. Confirme la generacion');
    
    
            return $this->redirectToRoute('requisito_confirmExcelIngreso_puntaCajaPago',
                array('id'=> $puntaCajaPago->getId()));
            
        }

        return $this->render('punta_caja_pago/new.html.twig', [
            'punta_caja_pago' => $puntaCajaPago,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("confirmExcelIngresoPuntaCajaPago/{id}",
     *     name="requisito_confirmExcelIngreso_puntaCajaPago", methods={"GET","POST"})
     */
    public function confirmExcelIngresoPuntaCajaPago(Request $request, PuntaCajaPago $puntaCajaPago): Response
    {
        $excelIngresos =
            $this->excelIngresoRepository->findBy(
                array(
                    'puntaCajaPago' => $puntaCajaPago,
                )
            );
        
       // $validationConstraint =
        //     $this->validationService->getMessageValidation($excelIngresos);
    
        //if(!is_null($validationConstraint) && count($validationConstraint)>0) {
        //    $this->logger->warning(json_encode($validationConstraint));
        // }
    
        $puntaCajaPago->setMontoTotal($this->subsidioService->getTotalAPagar($excelIngresos));
        
        return $this->render('punta_caja_pago/confirm.html.twig', [
            'puntaCajaPago' => $puntaCajaPago,
           // 'validationConstraint' => $validationConstraint,
            'excelIngresos' => $excelIngresos
        ]);
        
    }
    
    /**
     * @Route("/{id}", name="punta_caja_pago_show", methods={"GET"})
     */
    public function show(PuntaCajaPago $puntaCajaPago): Response
    {
        return $this->render('punta_caja_pago/show.html.twig', [
            'punta_caja_pago' => $puntaCajaPago,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="punta_caja_pago_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PuntaCajaPago $puntaCajaPago): Response
    {
        $form = $this->createForm(PuntaCajaPagoType::class, $puntaCajaPago);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('punta_caja_pago_index');
        }

        return $this->render('punta_caja_pago/edit.html.twig', [
            'punta_caja_pago' => $puntaCajaPago,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="punta_caja_pago_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PuntaCajaPago $puntaCajaPago): Response
    {
        if ($this->isCsrfTokenValid('delete'.$puntaCajaPago->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($puntaCajaPago);
            $entityManager->flush();
        }

        return $this->redirectToRoute('punta_caja_pago_index');
    }
}
