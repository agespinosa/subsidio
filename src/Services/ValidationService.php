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
            
            // CUIT valido
            if(!$this->cuitValido($excelIngreso->getCuit())){
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
    
    /**
     * @param $cuit
     * @return bool
     */
    public function cuitValido($cuit){
        $esCuit=false;
        $cuit_rearmado="";
        //separo cualquier caracter que no tenga que ver con numeros
        for ($i=0; $i < strlen($cuit); $i++) {
            if ((Ord(substr($cuit, $i, 1)) >= 48) && (Ord(substr($cuit, $i, 1)) <= 57))     {
                $cuit_rearmado = $cuit_rearmado . substr($cuit, $i, 1);
            }
        }
        $cuit=$cuit_rearmado;
        if ( strlen($cuit_rearmado) <> 11) {  // si no estan todos los digitos
            $esCuit=false;
        } else {
            $x=$i=$dv=0;
            // Multiplico los dígitos.
            $vec[0] = (substr($cuit, 0, 1)) * 5;
            $vec[1] = (substr($cuit, 1, 1)) * 4;
            $vec[2] = (substr($cuit, 2, 1)) * 3;
            $vec[3] = (substr($cuit, 3, 1)) * 2;
            $vec[4] = (substr($cuit, 4, 1)) * 7;
            $vec[5] = (substr($cuit, 5, 1)) * 6;
            $vec[6] = (substr($cuit, 6, 1)) * 5;
            $vec[7] = (substr($cuit, 7, 1)) * 4;
            $vec[8] = (substr($cuit, 8, 1)) * 3;
            $vec[9] = (substr($cuit, 9, 1)) * 2;
            
            // Suma cada uno de los resultado.
            for( $i = 0;$i<=9; $i++) {
                $x += $vec[$i];
            }
            $dv = (11 - ($x % 11)) % 11;
            if ($dv == (substr($cuit, 10, 1)) ) {
                $esCuit=true;
            }
        }
        return( $esCuit );
    }
}