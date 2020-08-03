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
    
    public function __construct(RequisitoRepository $requisitoRepository,
                                ExcelIngresoRepository $excelIngresoRepository,
                                LoggerInterface $logger,
                                AtributoConfiguracionRepository $atributoConfiguracionRepository,
                                SubsidioService $subsidioService)
    {
        $this->excelIngresoRepository = $excelIngresoRepository;
        $this->requisitoRepository = $requisitoRepository;
        $this->logger = $logger;
        $this->subsidioService = $subsidioService;
        $this->atributoConfiguracionRepository = $atributoConfiguracionRepository;
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
    
}
