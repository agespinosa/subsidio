<?php


namespace App\Services;


use App\Entity\AtributoConfiguracion;
use App\Entity\ExcelIngreso;
use App\Exception\SimpleMessageException;
use App\Repository\AtributoConfiguracionRepository;
use App\Repository\ExcelIngresoRepository;
use PhpOffice\PhpSpreadsheet\Worksheet\RowCellIterator;
use Psr\Log\LoggerInterface;
use function GuzzleHttp\Psr7\str;

class ExcelService
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
    
    public function readFile($filePath){
        $this->logger->info('Leyendo excel '.$filePath);
    
        $this->logger->info('Comienzo lectura');
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(TRUE);
        $spreadsheet = $reader->load($filePath);
        $activeSheet = $spreadsheet->getActiveSheet();
        $excelIngreso = null;
        $filasProcesadas = 0;
        foreach ($activeSheet->getRowIterator(2) as $row) {
            $this->logger->info('Leyendo fila '.$row->getRowIndex());
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE);
    
            $this->logger->info('Genero Fila ExcelIngreso para fila '.$row->getRowIndex());
            if($this->isValidFile($cellIterator)){
                $excelIngresos[] = $this->createExcelIngreso($cellIterator);
            }

            $filasProcesadas++;
        }
    
        $this->logger->info('Cantidad de Filas Procesadas '.$filasProcesadas);
        $this->logger->info('Cantidad de ExcelIngreso Creados '.count($excelIngresos));
        
        return $excelIngresos;
    }

    private function isValidFile(RowCellIterator $cellIterator){
        foreach ($cellIterator as $cell) {
            $value = $cell->getValue();
            $columnName = $cell->getColumn();
            $rowNumber = $cell->getRow();
            $message = 'Columna ' . $columnName . " fila ".$rowNumber ." valor " . $value;

            if(!in_array($columnName, $this->columnsAcceptNull()) && ($value === null || empty($value))) {
                $this->logger->info($message);
                throw new SimpleMessageException($message . 'Value is null or empty');
            }
        }

        return true;
    }
    
    public function columnsAcceptNull():array {
        return array('A','B','C','E','F','I','H');
    }
    
    public function createExcelIngreso(RowCellIterator $cellIterator){
        $excelIngreso = new ExcelIngreso();
        foreach ($cellIterator as $cell) {
            $value = trim($cell->getValue());
            $columnName = $cell->getColumn();
            $this->logger->info('ExcelIngreso para columna '.$columnName. " valor ".$value);
            $filaNumber = $cell->getRow();
    
            //if($filaNumber === 144 || $filaNumber === 143){
            //    $this->logger->debug('ExcelIngreso para columna '.$columnName. " valor ".$value);
            // }
            
            switch ($columnName) {
                case 'A':
                    $excelIngreso->setApellido($value);
                    break;
                case 'B':
                    $excelIngreso->setNombre($value);
                    break;
                case 'C':
                    $dni = str_replace("-","", $value);
                    $dni = str_replace("/","", $dni);
                    $dni = str_replace(".","", $dni);
                    $excelIngreso->setDni(preg_replace("/[^0-9]/", "",$dni));
                    break;
                case 'D':
                    $cuit = str_replace("-","", $value);
                    $cuit = str_replace("/","", $cuit);
                    $cuit = str_replace(".","", $cuit);
                    $excelIngreso->setCuit(preg_replace("/[^0-9]/", "",$cuit));
                    break;
                case 'E':
                    $excelIngreso->setRegimenIva($value);
                    break;
                case 'F':
                    $excelIngreso->setCategoriaIva($value);
                    break;
                case 'G':
                    $excelIngreso->setMonto($value);
                    break;
                case 'H':
                    $email = $value;
                    if(is_null($email) || empty($email)){
                        /** @var AtributoConfiguracion $emailDefaultPagoProveedoresConfig */
                        $emailDefaultPagoProveedoresConfig =
                            $this->atributoConfiguracionRepository
                                ->findAtributoConfiguracionByClave('emailDefault_Pago_Proveedores');
                        if(!is_null($emailDefaultPagoProveedoresConfig)){
                            $email = $emailDefaultPagoProveedoresConfig->getValor();
                        }
                    }
                    $excelIngreso->setEmail($email);
                    break;
                    
                case 'I':
                    $excelIngreso->setRubro($value);
                    break;
                case 'J':
                    $excelIngreso->setTipoCuenta($value);
                    break;
                case 'K':
                    $cbu = str_replace("-","", $value);
                    $cbu = str_replace("/","", $cbu);
                    $cbu = str_replace(".","", $cbu);
                    $excelIngreso->setCbu($cbu);
                    $excelIngreso->setNumeroCuentaBancaria($cbu);
                    break;
                case 'L':
                    if(!is_null($value) && !empty($value)){
                        $cuentaBancaria = str_replace("-","", $value);
                        $cuentaBancaria = str_replace("/","", $cuentaBancaria);
                        $cuentaBancaria = str_replace(".","", $cuentaBancaria);
                        $excelIngreso->setCuit(preg_replace("/[^0-9]/", "",$cuentaBancaria));
                        $excelIngreso->setNumeroCuentaBancaria($cuentaBancaria);
                    }
                    break;
            }
        }
        
        return $excelIngreso;
    }
}