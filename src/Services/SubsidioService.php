<?php


namespace App\Services;


use App\Entity\Cabecera;
use App\Entity\ExcelIngreso;
use App\Entity\Requisito;
use App\Entity\SubsidioPagoProveedores;
use App\Entity\Totales;
use App\Repository\CabeceraRepository;
use App\Repository\ExcelIngresoRepository;
use App\Repository\SubsidioPagoProveedoresRepository;
use App\Repository\TotalesRepository;
use AppBundle\Entity\AtributoConfiguracion;
use AppBundle\Repository\AtributoConfiguracionRepository;
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
    
    public function formatearArchivoTxtSubsidio(Requisito $requisito): SubsidioPagoProveedores {
    
        $subsidioPagoProveedores = new SubsidioPagoProveedores();
        
        $excelIngresos =
            $this->excelIngresoRepository->findByRequisito($requisito);
    
        $cabecera =
            $this->generarCabecera();
    
        $totales =
            $this->generarTotales();
        
        foreach ($excelIngresos as $excelIngreso) {
        
            
        }
    
        $this->cabeceraRepository->persist($cabecera);
        $this->totalesRepository->persist($totales);
        $this->subsidioPagoProveedoresRepository->persist($subsidioPagoProveedores);
    }
    
    /**
     * @var ExcelIngreso[] $excelIngresos
     * @param ExcelIngreso[] $excelIngresos
     */
    private function generarCabecera($excelIngresos){
        $cabecera = new Cabecera();
        $horaCreacionArchivo = new \DateTime();
    
        /** @var AtributoConfiguracion $atributoConfiguracion */
        $atributoConfiguracion =
            $this->atributoConfiguracionRepository->findAtributoConfiguracionByClave('lastNumeroArchivo');
        
        foreach ($excelIngresos as $excelIngreso) {
            $cabecera->setNumeroCliente();
            $cabecera->setHoraCreacionArchivo($horaCreacionArchivo);
            $cabecera->setFechaCreacionArchivo(new \DateTime());
            $cabecera->setRegistroId('FH');
            $cabecera->setIdentificacionArchivo();
            $cabecera->setNumeroArchivo($atributoConfiguracion->getValor());
        }
        return $cabecera;
        
    }
    
    /**
     * @var ExcelIngreso[] $excelIngresos
     * @param ExcelIngreso[] $excelIngresos
     */
    private function generarTotales($excelIngresos){
        $totales = new Totales();
        
        return $totales;
        
    }
    
}