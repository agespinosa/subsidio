<?php

namespace App\Controller;

use App\Entity\ExcelIngreso;
use App\Entity\Requisito;
use App\Form\RequisitoType;
use App\Repository\ExcelIngresoRepository;
use App\Repository\RequisitoRepository;
use App\Services\ExcelReaderService;
use App\Services\SubsidioService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/subsidio")
 */
class SubsidioController extends AbstractController
{
    
    /**
     * @var ExcelIngresoRepository
     */
    private $excelIngresoRepository;
    /**
     * @var RequisitoRepository
     */
    private $requisitoRepository;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var SubsidioService
     */
    private $subsidioService;
    
    public function __construct(RequisitoRepository $requisitoRepository,
                                ExcelIngresoRepository $excelIngresoRepository,
                                LoggerInterface $logger,
                                SubsidioService $subsidioService)
    {
        $this->excelIngresoRepository = $excelIngresoRepository;
        $this->requisitoRepository = $requisitoRepository;
        $this->logger = $logger;
        $this->subsidioService = $subsidioService;
    }
    
    /**
     * @Route("/generar/{id}", name="generar_archivo_subsidio", methods={"GET","POST"})
     */
    public function generar(Requisito $requisito): Response
    {
        $this->logger->info("Entro a generar archivo de Subsidio - Requisito: ".$requisito->getId());
        $this->logger->info("Requisito:".$requisito->getId(). " TipoFormaPago:". $requisito->getTipoFormaPago());
    
        $subsidioPagoProveedores =
            $this->subsidioService->formatearArchivoTxtSubsidio($requisito);
    
        // Persiste
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
    
        
        $this->logger->info("Termino de generar archivo de Subsidio - Requisito: ".$requisito->getId());
        return $this->redirectToRoute('requisito_index');
        
    }
    
}
