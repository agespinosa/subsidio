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
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

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
    /**
     * @var ParameterBagInterface
     */
    private $params;

    public function __construct(LoggerInterface $logger, ExcelIngresoRepository $excelIngresoRepository,
                                SubsidioPagoProveedoresRepository $subsidioPagoProveedoresRepository,
                                CabeceraRepository $cabeceraRepository, TotalesRepository $totalesRepository,
                                AtributoConfiguracionRepository $atributoConfiguracionRepository,
                                ParameterBagInterface $params)
    {
        $this->logger = $logger;
        $this->excelIngresoRepository = $excelIngresoRepository;
        $this->subsidioPagoProveedoresRepository = $subsidioPagoProveedoresRepository;
        $this->cabeceraRepository = $cabeceraRepository;
        $this->totalesRepository = $totalesRepository;
        $this->atributoConfiguracionRepository = $atributoConfiguracionRepository;
        $this->params = $params;
    }
    
    public function generarSubsidioPagoProveedores(Requisito $requisito): array {

        $subsidiosPagoProveedores = array();
        $this->logger->debug("Formateando filas excel ingreso");

        $excelIngresos =
            $this->excelIngresoRepository->findByRequisito($requisito);

        $this->logger->debug("Cantidad de filas excel ingreso". count($excelIngresos) .", para requisito id ".$requisito->getId());

        /** @var AtributoConfiguracion $lastNumeroArchivoConfig */
        $lastNumeroArchivoConfig =
            $this->atributoConfiguracionRepository->findAtributoConfiguracionByClave('lastNumeroArchivo_Pago_Proveedores');
    
        /** @var AtributoConfiguracion $numeroProveedorConfig */
        $numeroProveedorConfig =
            $this->atributoConfiguracionRepository->findAtributoConfiguracionByClave('numeroProveedor_Pago_Proveedores');

        $moneda = 'ARS';
        // Cabecera y Totales
        $cabecera =
            $this->generarCabeceraPagoProveedores($excelIngresos);
    
        $totales =
            $this->generarTotalesPagoProveedores($excelIngresos);
    
        $this->cabeceraRepository->persist($cabecera);
        $this->totalesRepository->persist($totales);
        
        $index = 0;
        foreach ($excelIngresos as $excelIngreso) {
            $subsidioPagoProveedores = new SubsidioPagoProveedores();
            $index++;

            $subsidioPagoProveedores->setCabecera($cabecera);
            $subsidioPagoProveedores->setTotales($totales);
            $subsidioPagoProveedores->setRequsito($requisito);
    
            $subsidioPagoProveedores->setCuentaDebito($requisito->getCuantaOrigenFodos());
            $subsidioPagoProveedores->setMotivoPago($requisito->getMotivoPago());
            
            $subsidioPagoProveedores->setRegistroId('PR');
            $subsidioPagoProveedores->setTipoPago('004');
            $subsidioPagoProveedores->setConRecurso('N');
            $subsidioPagoProveedores->setRequiereReciboImpreso('N');
            
            $subsidioPagoProveedores->setReferenciaCliente($index);
            $subsidioPagoProveedores->setImporteAPagar($excelIngreso->getMonto());
            $subsidioPagoProveedores->setMonedaPago($moneda);
            $subsidioPagoProveedores->setFechaEjecucionPago($requisito->getFechaDesde());
            $subsidioPagoProveedores->setNumeroProveedor($excelIngreso->getCuit());
            $subsidioPagoProveedores->setNombreBeneficiario($excelIngreso->getFullName());
            $subsidioPagoProveedores->setCuit($excelIngreso->getCuit());
            $subsidioPagoProveedores->setDomicilio('');
            $subsidioPagoProveedores->setLocalidad('');
            $subsidioPagoProveedores->setCodigoPostal('');
            $subsidioPagoProveedores->setMedioComunicacion($excelIngreso->getEmail());
            $subsidioPagoProveedores->setTipoCuenta($this->getTipoCuenta($excelIngreso));
            $subsidioPagoProveedores->setMonedaCuenta($moneda);
            $subsidioPagoProveedores->setNumeroCuenta($excelIngreso->getNumeroCuentaBancaria());
            $subsidioPagoProveedores->setMonedaCuentaDebito($moneda);
            $subsidioPagoProveedores->setMotivoPago('PagoRef#'.$requisito->getId());
            $subsidioPagoProveedores->setCodigoNovedadOrden(1);
    
            $banco = str_pad(substr($excelIngreso->getCbu(),0,3),5, "0", STR_PAD_LEFT);
            $sucursal = str_pad(substr($excelIngreso->getCbu(),3,4),35, "0", STR_PAD_LEFT);
            $subsidioPagoProveedores->setBanco($banco);
            $subsidioPagoProveedores->setSucursal($sucursal);
    
            
            $subsidiosPagoProveedores[] = $subsidioPagoProveedores;
            $this->subsidioPagoProveedoresRepository->persist($subsidioPagoProveedores);

        }

        return $subsidiosPagoProveedores;
    }

    private function getTipoCuenta(ExcelIngreso $excelIngreso){
        $tipoCuenta = 'CA';
        if(strpos($excelIngreso->getTipoCuenta(), 'Cte') === TRUE ||
            strpos($excelIngreso->getTipoCuenta(), 'Cta') === TRUE ||
            strpos($excelIngreso->getTipoCuenta(), 'Corriente') === TRUE){
            $tipoCuenta = 'CC';
        }

        return $tipoCuenta;
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

    public function generarArchivoTxtSubsidio(array $subsidiosPagoProveedores){
        $directorioToSaveFile = $this->params->get('subsidio_directory');
        $subsidioDirectoryRelativePath = $this->params->get('subsidio_directory_relative_path');
        $subsidioFileName =  'requisito.'. $subsidiosPagoProveedores[0]->getRequsito()->getId() .'-file-'.uniqid().'.txt';
        $fullPath = $directorioToSaveFile.'/'.$subsidioFileName;
        $relativePath = $subsidioDirectoryRelativePath.'/'.$subsidioFileName;

        $this->logger->debug("Creando TXT File ".$subsidioFileName);
        $handle = null;
        try {
            $handle = fopen($fullPath, 'w');
        } catch (FileException | \RuntimeException $e) {
            $message = "Error Creando TXT File ".$e->getMessage();
            $this->logger->error($message);
            $this->addFlash('errorMessage', $message);
        }

        if(!$handle || is_null($handle))
        {
            throw new SimpleMessageException("No se pudo generar el archivo txt de salida. ".$subsidioFileName);
        }
        try{
            $newLine = "\n";

            /** @var SubsidioPagoProveedores $subsidioPagoProveedores */
            foreach ($subsidiosPagoProveedores as $subsidioPagoProveedores) {
                fwrite($handle, $this->getStringLine($subsidioPagoProveedores));
                fwrite($handle,$newLine);
            }
        }catch (\Exception | \RuntimeException $exception){
            fclose($handle);
            $message = "Error procesando TXT File ".$e->getMessage();
            $this->logger->error($message);
            $this->addFlash('errorMessage', $message);
        }

        fclose($handle);
        return $relativePath;
    }

    public function getStringLine(SubsidioPagoProveedores $subsidioPagoProveedores){
        $domicilioBeneficiario="";
        $cuentaBancariaDelBeneficiario="";
        $formaDeEntregaDeCheque="";
        $sucursalDelNBSFoDelPrestadorPostalALaCualEnviarSobre="";
        $indicacionesAdicionalesalCustomerServicedeBSF = "";
        $nrodelinstrumentodepago = "";
        
        return
            $subsidioPagoProveedores->getRegistroId().
            $subsidioPagoProveedores->getTipoPago().
            $subsidioPagoProveedores->getReferenciaCliente().
            $subsidioPagoProveedores->getImporteAPagarString().
            $subsidioPagoProveedores->getMonedaPago().
            $subsidioPagoProveedores->getFechaEjecucionPagoStr().
            $subsidioPagoProveedores->getNumeroProveedor().
            $subsidioPagoProveedores->getNombreBeneficiario().
            $subsidioPagoProveedores->getCuitStr().
            str_pad($domicilioBeneficiario, 150, " ", STR_PAD_LEFT).
            $subsidioPagoProveedores->getMedioComunicacionStr().
            $subsidioPagoProveedores->getBanco().
            $subsidioPagoProveedores->getSucursal().
            $subsidioPagoProveedores->getTipoCuenta().
            $subsidioPagoProveedores->getMonedaCuenta().
            str_pad($formaDeEntregaDeCheque, 3, " ", STR_PAD_LEFT).
            str_pad($sucursalDelNBSFoDelPrestadorPostalALaCualEnviarSobre, 8, " ", STR_PAD_LEFT).
            $subsidioPagoProveedores->getMonedaCuentaDebito().
            $subsidioPagoProveedores->getCuentaDebito().
            str_pad($indicacionesAdicionalesalCustomerServicedeBSF, 80, " ", STR_PAD_LEFT).
            $subsidioPagoProveedores->getConRecurso().
            $subsidioPagoProveedores->getRequiereReciboImpreso().
            str_pad($nrodelinstrumentodepago, 15, " ", STR_PAD_LEFT).
            $subsidioPagoProveedores->getCodigoNovedadOrden();
            
            


    }
    
   
    
}