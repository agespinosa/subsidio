<?php


namespace App\Services;


use App\Entity\AtributoConfiguracion;
use App\Entity\ExcelIngreso;
use App\Repository\AtributoConfiguracionRepository;
use App\Repository\ExcelIngresoRepository;
use Psr\Log\LoggerInterface;

class ValidationService
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
     * @var AtributoConfiguracionRepository
     */
    private $atributoConfiguracionRepository;
    
    public function __construct(LoggerInterface $logger, ExcelIngresoRepository $excelIngresoRepository,
                                AtributoConfiguracionRepository $atributoConfiguracionRepository)
    {
    
        $this->logger = $logger;
        $this->excelIngresoRepository = $excelIngresoRepository;
        $this->atributoConfiguracionRepository = $atributoConfiguracionRepository;
    }
    
    /**
     * @param ExcelIngreso[] $excelIngresos
     */
    public function getMessageValidation($excelIngresos){
        $messageValidation = array();
        /** @var ExcelIngreso $excelIngreso */
        $cuitExistentes = array();
        $cuitDuplicados = array();
        $cuitNoCumpleLength = array();
        $sinEmail = array();
        foreach ($excelIngresos as $excelIngreso) {
            
            //Cuits Duplicados
            if(in_array($excelIngreso->getCuit(), $cuitExistentes)){
                $cuitDuplicados[] = $excelIngreso->getCuit();
                if(!array_key_exists($excelIngreso->getCuit(),$messageValidation )){
                    $messageValidation[$excelIngreso->getCuit()] = "El cuit ".$excelIngreso->getCuit(). " existe mas de una vez en el archivo";
                }
            }
            $cuitExistentes[] = $excelIngreso->getCuit();
            
            // Length CUIT
            $len = strlen($excelIngreso->getCuit());
            if($len !== 11){
                $cuitNoCumpleLength[] = $excelIngreso->getCuit();
                if(!array_key_exists($excelIngreso->getCuit(),$messageValidation )){
                    $messageValidation[$excelIngreso->getCuit()] = "El cuit ".$excelIngreso->getCuit(). " no es valido";
                }
            }
    
            // Valida mail exista
            /** @var AtributoConfiguracion $emailDefaultPagoProveedoresConfig */
            $emailDefaultPagoProveedoresConfig =
                $this->atributoConfiguracionRepository
                    ->findAtributoConfiguracionByClave('emailDefault_Pago_Proveedores');
            
            $defaultEmail = null;
            if(!is_null($emailDefaultPagoProveedoresConfig) &&
                !is_null($emailDefaultPagoProveedoresConfig->getValor()) &&
                !empty($emailDefaultPagoProveedoresConfig->getValor())){
                $defaultEmail = $emailDefaultPagoProveedoresConfig->getValor();
            }
            if(is_null($excelIngreso->getEmail()) || empty($excelIngreso)
                || $defaultEmail == $excelIngreso->getEmail()){
                $sinEmail[] = $excelIngreso->getFullName(). " cuit:". $excelIngreso->getCuit();
                if(!array_key_exists($excelIngreso->getCuit(),$messageValidation )){
                    $messageValidation[$excelIngreso->getCuit()] = "Registro sin email: ".$excelIngreso->getFullName(). " cuit:". $excelIngreso->getCuit();
                }
            }
        }
        
        return $messageValidation;
    }
}