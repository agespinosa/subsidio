<?php

namespace App\Controller;

use App\Entity\AtributoConfiguracion;
use App\Entity\ExcelIngreso;
use App\Entity\Requisito;
use App\Entity\SubsidioPagoProveedores;
use App\Exception\SimpleMessageException;
use App\Form\RequisitoType;
use App\Repository\AtributoConfiguracionRepository;
use App\Repository\ExcelIngresoRepository;
use App\Repository\RequisitoRepository;
use App\Repository\SubsidioPagoProveedoresRepository;
use App\Services\ExcelReaderService;
use App\Services\SubsidioService;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Error\FatalError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\TCPDFBundle\Controller\TCPDFController;

/**
 * @Route("/subsidio")
 * @IsGranted("ROLE_ADMIN")
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
    /**
     * @var AtributoConfiguracionRepository
     */
    private $atributoConfiguracionRepository;
    /**
     * @var SubsidioPagoProveedoresRepository
     */
    private $subsidioPagoProveedoresRepository;
    /**
     * @var TCPDFController
     */
    private $TCPDFController;
    
    public function __construct(RequisitoRepository $requisitoRepository,
                                ExcelIngresoRepository $excelIngresoRepository,
                                LoggerInterface $logger,
                                AtributoConfiguracionRepository $atributoConfiguracionRepository,
                                SubsidioService $subsidioService,
                                SubsidioPagoProveedoresRepository $subsidioPagoProveedoresRepository,
                                TCPDFController $TCPDFController)
    {
        $this->excelIngresoRepository = $excelIngresoRepository;
        $this->requisitoRepository = $requisitoRepository;
        $this->logger = $logger;
        $this->subsidioService = $subsidioService;
        $this->atributoConfiguracionRepository = $atributoConfiguracionRepository;
        $this->subsidioPagoProveedoresRepository = $subsidioPagoProveedoresRepository;
        $this->TCPDFController = $TCPDFController;
    }
    
    /**
     * @Route("/generarPagoProveedores/{id}", name="generar_archivo_subsidio_proveedores", methods={"GET","POST"})
     */
    public function generarPagoProveedores(Requisito $requisito): Response
    {
        $this->logger->info("Entro a generar archivo de Subsidio - Requisito: ".$requisito->getId());
        $this->logger->info("Requisito:".$requisito->getId(). " TipoFormaPago:". $requisito->getTipoFormaPago());
    
        /** @var AtributoConfiguracion $cuentaOrigenFondosConfig */
        $cuentaOrigenFondosConfig =
            $this->atributoConfiguracionRepository
                ->findAtributoConfiguracionByClave('cuentaOrigenFondos');
                
        $requisito->setCuantaOrigenFodos($cuentaOrigenFondosConfig->getValor());
        
        /** @var SubsidioPagoProveedores $subsidioPagoProveedores */
        try{
            $subsidioPagoProveedores =
                $this->subsidioService->generarSubsidioPagoProveedores($requisito);

            $archivoGenerado = null;
            if(!is_null($subsidioPagoProveedores) && count($subsidioPagoProveedores)>0){
                /** @var SubsidioPagoProveedores $subsidio */
                $subsidio = $subsidioPagoProveedores[0];
                
                $archivoGenerado = $this->subsidioService->generarArchivoTxtSubsidio($subsidioPagoProveedores,$requisito);
                $requisito->setFileSubsidioName($archivoGenerado);
                $requisito->setTotalBeneficiarios($subsidio->getTotales()->getTotalRegistros());
                $requisito->setTotalMontoPesos($subsidio->getTotales()->getTotalAPagar());
                $requisito->setEstado(Requisito::ESTADO_PROCESADO);
            }
            // Persiste
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $message = "Termino de generar archivo de Subsidio - Requisito: ".$requisito->getId();
            $this->logger->info($message);
            $this->addFlash('successMessage', $message);
        }catch (\Exception | FatalError | \RuntimeException $exception){
            $message = "Error formateando archivo ".$exception->getMessage();
            $this->logger->error($message);
            $this->addFlash('errorMessage', $message);
            $this->redirectToRoute('requisito_index');
        }catch (SimpleMessageException $sm){
            $message = "Error formateando archivo ".$sm->getMessage();
            $this->logger->error($message);
            $this->addFlash('errorMessage', $message);
            $this->redirectToRoute('requisito_index');
        }

        return $this->redirectToRoute('requisito_index');
        
    }
    
    /**
     * @Route("exportBeneficiariosList/{id}", name="exportBeneficiariosList",
     *     methods={"GET","POST"})
     */
    public function exportBeneficiariosList(Request $request, Requisito $requisito): Response
    {
        $beneficiarios =
            $this->subsidioPagoProveedoresRepository->findBy(
                array(
                    'requsito' => $requisito,
                )
            );
    
        $excelIngresos =
            $this->excelIngresoRepository->findBy(
                array(
                    'requisito' => $requisito,
                )
            );
        
        $requisito->setTotalMontoPesos($this->subsidioService->getTotalAPagar($excelIngresos));
        $pdfName = $requisito->getId()."-".$requisito->getMotivoPagoStr();
        
        $pdf = $this->TCPDFController->create('',
            PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetAuthor('Ministerio de la Producción, Ciencia y Tecnologia');
        $pdf->SetTitle($pdfName);
        $pdf->SetSubject($requisito->getMotivoPagoStr());
        $pdf->SetKeywords($requisito->getMotivoPagoStr());
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 11, '', true);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();
        
        // El HTML Tiene los datos de la licencia
        $html = $this->renderView('subsidioPagoProveedores/beneficiariosList.html.twig',
            array( 'requisito' => $requisito,
                'beneficiarios' => $beneficiarios));
        $pdf->writeHTML($html, true, false, true, false, 'J');
        
       
        $pdf->Output($pdfName.'.pdf', 'I');
        
    }
    
}
