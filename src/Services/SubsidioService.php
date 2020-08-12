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
use App\Repository\RequisitoRepository;
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
    /**
     * @var RequisitoRepository
     */
    private $requisitoRepository;
    
    public function __construct(LoggerInterface $logger, ExcelIngresoRepository $excelIngresoRepository,
                                SubsidioPagoProveedoresRepository $subsidioPagoProveedoresRepository,
                                CabeceraRepository $cabeceraRepository, TotalesRepository $totalesRepository,
                                AtributoConfiguracionRepository $atributoConfiguracionRepository,
                                RequisitoRepository $requisitoRepository,
                                ParameterBagInterface $params)
    {
        $this->logger = $logger;
        $this->excelIngresoRepository = $excelIngresoRepository;
        $this->subsidioPagoProveedoresRepository = $subsidioPagoProveedoresRepository;
        $this->cabeceraRepository = $cabeceraRepository;
        $this->totalesRepository = $totalesRepository;
        $this->atributoConfiguracionRepository = $atributoConfiguracionRepository;
        $this->params = $params;
        $this->requisitoRepository = $requisitoRepository;
    }
    
    public function generarSubsidioPagoProveedores(Requisito $requisito): array {

        $subsidiosPagoProveedores = array();
        $this->logger->debug("Formateando filas excel ingreso");

        $excelIngresos =
            $this->excelIngresoRepository->findByRequisito($requisito);

        $this->logger->debug("Cantidad de filas excel ingreso". count($excelIngresos) .", para requisito id ".$requisito->getId());
    
        /** @var AtributoConfiguracion $numeroProveedorConfig */
        $numeroProveedorConfig =
            $this->atributoConfiguracionRepository->findAtributoConfiguracionByClave('numeroProveedor_Pago_Proveedores');

        $moneda = 'ARS';
        // Cabecera y Totales
        $cabecera =
            $this->generarCabeceraPagoProveedores($excelIngresos, $requisito);
    
        $totales =
            $this->generarTotalesPagoProveedores($excelIngresos);
    
        $this->cabeceraRepository->persist($cabecera);
        $this->totalesRepository->persist($totales);
    
        $numeroReferenciaClienteFila = $requisito->getNumeroReferenciaClienteFila();
        if(is_null($numeroReferenciaClienteFila) || $numeroReferenciaClienteFila == 0) {
            $maxReferenciaClienteFila = $this->requisitoRepository->findMaxNumeroReferenciaCliente();
            $numeroReferenciaClienteFila = abs($maxReferenciaClienteFila[0]['maxNumeroReferenciaCliente']);
        }
        foreach ($excelIngresos as $excelIngreso) {
            $subsidioPagoProveedores = new SubsidioPagoProveedores();

            $subsidioPagoProveedores->setCabecera($cabecera);
            $subsidioPagoProveedores->setTotales($totales);
            $subsidioPagoProveedores->setRequsito($requisito);
    
            $subsidioPagoProveedores->setCuentaDebito($requisito->getCuantaOrigenFodos());
            $subsidioPagoProveedores->setMotivoPago($requisito->getMotivoPago());
            
            $subsidioPagoProveedores->setRegistroId('PR');
            $subsidioPagoProveedores->setTipoPago('004');
            $subsidioPagoProveedores->setConRecurso('N');
            $subsidioPagoProveedores->setRequiereReciboImpreso('N');
            
            $subsidioPagoProveedores->setReferenciaCliente($numeroReferenciaClienteFila);
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
            $subsidioPagoProveedores->setCodigoNovedadOrden(0);
    
            $banco = str_pad(substr($excelIngreso->getCbu(),0,3),5, "0", STR_PAD_LEFT);
            $sucursal = str_pad(substr($excelIngreso->getCbu(),3,4),35, "0", STR_PAD_LEFT);
            
            $subsidioPagoProveedores->setBanco($banco);
            $subsidioPagoProveedores->setSucursal($sucursal);
            
            $subsidiosPagoProveedores[] = $subsidioPagoProveedores;
            $this->subsidioPagoProveedoresRepository->persist($subsidioPagoProveedores);
            $numeroReferenciaClienteFila++;
        }
    
        $requisito->setNumeroReferenciaClienteFila($numeroReferenciaClienteFila);
        return $subsidiosPagoProveedores;
    }

    private function getTipoCuenta(ExcelIngreso $excelIngreso){
        $tipoCuenta = 'CA';
        if(strpos($excelIngreso->   getTipoCuenta(), 'Cte') === TRUE ||
            strpos($excelIngreso->getTipoCuenta(), 'Cta') === TRUE ||
            strpos($excelIngreso->getTipoCuenta(), 'Corriente') === TRUE){
            $tipoCuenta = 'CC';
        }

        return $tipoCuenta;
    }


    /**
     * @var ExcelIngreso[] $excelIngresos
     * @var Requisito $requisito
     * @param ExcelIngreso[] $excelIngresos
     */
    private function generarCabeceraPagoProveedores($excelIngresos, $requisito){
        $this->logger->debug("Generando Cabecera");
        $cabecera = new Cabecera();
        $horaCreacionArchivo = new \DateTime();

        /** @var AtributoConfiguracion $numeroClienteNBSFConfig */
        $numeroClienteNBSFConfig =
            $this->atributoConfiguracionRepository->findAtributoConfiguracionByClave('numeroClienteNBSF_Pago_Proveedores');

        /** @var AtributoConfiguracion $identificacionArchivoConfig */
        $identificacionArchivoConfig =
            $this->atributoConfiguracionRepository->findAtributoConfiguracionByClave('identificacionArchivo_Pago_Proveedores');


        if(is_null($numeroClienteNBSFConfig) || is_null($identificacionArchivoConfig)){
            throw new SimpleMessageException("Faltan configuraciones numeroClienteNBSF_Pago_Proveedores | identificacionArchivo_Pago_Proveedores");
        }
        /** @var ExcelIngreso $excelIngreso */
        foreach ($excelIngresos as $excelIngreso) {
            $cabecera->setNumeroCliente($numeroClienteNBSFConfig->getValor());
            $cabecera->setHoraCreacionArchivo($horaCreacionArchivo);
            $cabecera->setFechaCreacionArchivo(new \DateTime());
            $cabecera->setRegistroId('FH');
            $cabecera->setIdentificacionArchivo($identificacionArchivoConfig->getValor());
            $cabecera->setNumeroArchivo($requisito->getNumeroArchivoPago());
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

    public function getTotalAPagar($excelIngresos){
        $totalAPagar = 0;
        /** @var ExcelIngreso $excelIngreso */
        foreach ($excelIngresos as $excelIngreso) {
            $totalAPagar = $totalAPagar + doubleval($excelIngreso->getMonto());
        }

        return $totalAPagar;
    }

    public function generarArchivoTxtSubsidio(array $subsidiosPagoProveedores, Requisito $requisito){
        $directorioToSaveFile = $this->params->get('subsidio_directory');
        $subsidioDirectoryRelativePath = $this->params->get('subsidio_directory_relative_path');
        
        /** @var AtributoConfiguracion $numeroClienteNBSFPagoProveedoresConfig */
        $numeroClienteNBSFPagoProveedoresConfig =
            $this->atributoConfiguracionRepository
                ->findAtributoConfiguracionByClave('numeroClienteNBSF_Pago_Proveedores');
        
        $today = new \DateTime();
        $mesDia = $today->format('md');
        $subsidioFileName = 'PC'.$numeroClienteNBSFPagoProveedoresConfig->getValor().'F'.$mesDia.$requisito->getNumeroArchivoPago();
        
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
    
            fwrite($handle,
                $this->getCabeceraStringLine($subsidiosPagoProveedores[0]->getCabecera(), $requisito));
            fwrite($handle,$newLine);
            
            /** @var SubsidioPagoProveedores $subsidioPagoProveedores */
            foreach ($subsidiosPagoProveedores as $subsidioPagoProveedores) {
                fwrite($handle, $this->getStringLine($subsidioPagoProveedores));
                fwrite($handle,$newLine);
            }
    
            fwrite($handle,
                $this->getTotalesStringLine($subsidioPagoProveedores->getTotales(), $requisito));
            fwrite($handle,$newLine);
            
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
            str_pad($subsidioPagoProveedores->getReferenciaCliente(), 16, "0", STR_PAD_LEFT).
            $subsidioPagoProveedores->getImporteAPagarString().
            $subsidioPagoProveedores->getMonedaPago().
            $subsidioPagoProveedores->getFechaEjecucionPagoStr().
            $subsidioPagoProveedores->getNumeroProveedor().
            $subsidioPagoProveedores->getNombreBeneficiario().
            $subsidioPagoProveedores->getCuitStr().
            str_pad($domicilioBeneficiario, 170, " ", STR_PAD_LEFT).
            $subsidioPagoProveedores->getMedioComunicacionStr().
            $subsidioPagoProveedores->getBanco().
            $subsidioPagoProveedores->getSucursal().
            $subsidioPagoProveedores->getTipoCuenta().
            $subsidioPagoProveedores->getMonedaCuenta().
            str_pad($subsidioPagoProveedores->getNumeroCuenta(), 22, " ", STR_PAD_RIGHT).
            str_pad($cuentaBancariaDelBeneficiario, 35, " ", STR_PAD_RIGHT).
            str_pad($formaDeEntregaDeCheque, 3, " ", STR_PAD_LEFT).
            str_pad($sucursalDelNBSFoDelPrestadorPostalALaCualEnviarSobre, 8, " ", STR_PAD_LEFT).
            $subsidioPagoProveedores->getMonedaCuentaDebito().
            $subsidioPagoProveedores->getCuentaDebito().
            str_pad($subsidioPagoProveedores->getMotivoPago(),105, " ", STR_PAD_RIGHT).
            str_pad($indicacionesAdicionalesalCustomerServicedeBSF, 80, " ", STR_PAD_LEFT).
            $subsidioPagoProveedores->getConRecurso().
            $subsidioPagoProveedores->getRequiereReciboImpreso().
            str_pad($nrodelinstrumentodepago, 15, " ", STR_PAD_LEFT).
            $subsidioPagoProveedores->getCodigoNovedadOrden();

    }
    public function getTotalesStringLine(Totales $totales, Requisito $requisito){
        // total de registros suma 2 por que cuenta la fila de cabecera y totales
        $totalAPagar = number_format($totales->getTotalAPagar(),2,'','');
        $filasCabecera = 1;
        $filasTotales = 1;
        return
            str_pad(
                $totales->getRegistroId().
                      str_pad($totalAPagar, 25, "0", STR_PAD_LEFT).
                      str_pad($totales->getTotalRegistros()+($filasCabecera+$filasTotales), 10, "0", STR_PAD_LEFT),
            794, " ", STR_PAD_RIGHT);
            
    }
    
    public function getCabeceraStringLine(Cabecera $cabecera, Requisito $requisito){
        
        $today = new \DateTime();
        
        /** @var AtributoConfiguracion $numeroClienteOrdenanteAnteBSFConfig */
        $numeroClienteOrdenanteAnteBSFConfig =
            $this->atributoConfiguracionRepository->findAtributoConfiguracionByClave('numeroClienteOrdenante_Pago_Proveedores');
    
        $numeroClienteOrdenanteAnteBSFValue = '0061170';
        if(!is_null($numeroClienteOrdenanteAnteBSFConfig)){
            $numeroClienteOrdenanteAnteBSFValue = $numeroClienteOrdenanteAnteBSFConfig->getValor();
        }
        
        return
            str_pad($cabecera->getRegistroId().
                $today->format('yymd').
                $today->format('His').
                str_pad($requisito->getNumeroArchivoPago(),3, "0", STR_PAD_LEFT).
                str_pad($numeroClienteOrdenanteAnteBSFValue,7, "0", STR_PAD_LEFT).
                $cabecera->getIdentificacionArchivo().
                $requisito->getFechaDesde()->format('yymd')
                , 794, " ", STR_PAD_RIGHT);
            
            
        
    }
    
   
    
}
