<?php


namespace App\Services;


use App\Entity\Cabecera;
use App\Entity\ExcelIngreso;
use App\Entity\Requisito;
use App\Entity\SubsidioPagoProveedores;
use App\Entity\Totales;
use App\Exception\SimpleMessageException;
use App\Repository\CabeceraRepository;
use App\Repository\ExcelIngresoRepository;
use App\Repository\SubsidioPagoProveedoresRepository;
use App\Repository\TotalesRepository;
use App\Entity\AtributoConfiguracion;
use App\Repository\AtributoConfiguracionRepository;
use Psr\Log\LoggerInterface;

class SubsidioService
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var ExcelIngresoRepository
     */
    private $excelIngresoRepository;
    /**
     * @var SubsidioPagoProveedoresRepository
     */
    private $subsidioPagoProveedoresRepository;
    /**
     * @var CabeceraRepository
     */
    private $cabeceraRepository;
    /**
     * @var TotalesRepository
     */
    private $totalesRepository;
    /**
     * @var AtributoConfiguracionRepository
     */
    private $atributoConfiguracionRepository;
    
    public function __construct(LoggerInterface $logger, ExcelIngresoRepository $excelIngresoRepository,
                                SubsidioPagoProveedoresRepository $subsidioPagoProveedoresRepository,
                                CabeceraRepository $cabeceraRepository, TotalesRepository $totalesRepository,
                                AtributoConfiguracionRepository $atributoConfiguracionRepository)
    {
        $this->logger = $logger;
        $this->excelIngresoRepository = $excelIngresoRepository;
        $this->subsidioPagoProveedoresRepository = $subsidioPagoProveedoresRepository;
        $this->cabeceraRepository = $cabeceraRepository;
        $this->totalesRepository = $totalesRepository;
        $this->atributoConfiguracionRepository = $atributoConfiguracionRepository;
    }
    
    public function generarSubsidioPagoProveedores(Requisito $requisito): SubsidioPagoProveedores {

        $this->logger->debug("Formateando filas excel ingreso");
        $subsidioPagoProveedores = new SubsidioPagoProveedores();
        
        $excelIngresos =
            $this->excelIngresoRepository->findByRequisito($requisito);

        $this->logger->debug("Cantidad de filas excel ingreso". count($excelIngresos) .", para requisito id ".$requisito->getId());

        $cabecera =
            $this->generarCabeceraPagoProveedores($excelIngresos);
    
        $totales =
            $this->generarTotalesPagoProveedores($excelIngresos);

        $subsidioPagoProveedores->setCabecera($cabecera);
        $subsidioPagoProveedores->setTotales($totales);
        $subsidioPagoProveedores->setRequsito($requisito);

        foreach ($excelIngresos as $excelIngreso) {
        
            
        }
    
        $this->cabeceraRepository->persist($cabecera);
        $this->totalesRepository->persist($totales);
        $this->subsidioPagoProveedoresRepository->persist($subsidioPagoProveedores);

        return $subsidioPagoProveedores;
    }
    
    /**
     * @var ExcelIngreso[] $excelIngresos
     * @param ExcelIngreso[] $excelIngresos
     */
    private function generarCabeceraPagoProveedores($excelIngresos){
        $this->logger->debug("Generando Cabecera");
        $cabecera = new Cabecera();
        $horaCreacionArchivo = new \DateTime();
    
        /** @var AtributoConfiguracion $lastNumeroArchivoConfig */
        $lastNumeroArchivoConfig =
            $this->atributoConfiguracionRepository->findAtributoConfiguracionByClave('lastNumeroArchivo_Pago_Proveedores');

        /** @var AtributoConfiguracion $numeroClienteNBSFConfig */
        $numeroClienteNBSFConfig =
            $this->atributoConfiguracionRepository->findAtributoConfiguracionByClave('numeroClienteNBSF_Pago_Proveedores');

        /** @var AtributoConfiguracion $identificacionArchivoConfig */
        $identificacionArchivoConfig =
            $this->atributoConfiguracionRepository->findAtributoConfiguracionByClave('identificacionArchivo_Pago_Proveedores');


        if(is_null($lastNumeroArchivoConfig) || is_null($numeroClienteNBSFConfig) || is_null($identificacionArchivoConfig)){
            throw new SimpleMessageException("Faltan configuraciones lastNumeroArchivo_Pago_Proveedores | numeroClienteNBSF_Pago_Proveedores | identificacionArchivo_Pago_Proveedores");
        }
        /** @var ExcelIngreso $excelIngreso */
        foreach ($excelIngresos as $excelIngreso) {
            $cabecera->setNumeroCliente($numeroClienteNBSFConfig->getValor());
            $cabecera->setHoraCreacionArchivo($horaCreacionArchivo);
            $cabecera->setFechaCreacionArchivo(new \DateTime());
            $cabecera->setRegistroId('FH');
            $cabecera->setIdentificacionArchivo($identificacionArchivoConfig->getValor());
            $cabecera->setNumeroArchivo($lastNumeroArchivoConfig->getValor()+1);
            $cabecera->setFechaHabilProcesamiento($this->getFirstDiaHabil());
            break;
        }

        $this->logger->debug("Cabecera Generada");
        return $cabecera;
        
    }

    private function getFirstDiaHabil(){
        return new \DateTime();
    }
    /**
     * @var ExcelIngreso[] $excelIngresos
     * @param ExcelIngreso[] $excelIngresos
     */
    private function generarTotalesPagoProveedores($excelIngresos){
        $this->logger->debug("Generando Totales");
        $totales = new Totales();
        $totales->setRegistroId('FT');
        $totales->setTotalAPagar($this->getTotalAPagar($excelIngresos));
        $totales->setTotalRegistros(count($excelIngresos));
        
        return $totales;
        
    }

    private function getTotalAPagar($excelIngresos){
        $totalAPagar = 0;
        /** @var ExcelIngreso $excelIngreso */
        foreach ($excelIngresos as $excelIngreso) {
            $totalAPagar = $totalAPagar + doubleval($excelIngreso->getMonto());
        }

        return $totalAPagar;
    }

    public function generarArchivoTxtSubsidio(SubsidioPagoProveedores $subsidioPagoProveedores){

    }
    
}