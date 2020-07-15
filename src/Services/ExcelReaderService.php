<?php


namespace App\Services;


use App\Entity\ExcelIngreso;
use App\Repository\ExcelIngresoRepository;
use PhpOffice\PhpSpreadsheet\Worksheet\RowCellIterator;
use Psr\Log\LoggerInterface;

class ExcelReaderService
{
    
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var ExcelIngresoRepository
     */
    private $excelIngresoRepository;
    
    public function __construct(LoggerInterface $logger, ExcelIngresoRepository $excelIngresoRepository)
    {
        $this->logger = $logger;
        $this->excelIngresoRepository = $excelIngresoRepository;
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
            $excelIngreso = $this->createExcelIngreso($cellIterator);
            $filasProcesadas++;
        }
    
        if($filasProcesadas>0){
          //  $this->excelIngresoRepository->flush($excelIngreso);
        }
    }
    
    public function createExcelIngreso(RowCellIterator $cellIterator){
        $excelIngreso = new ExcelIngreso();
        foreach ($cellIterator as $cell) {
            $value =  $cell->getValue() ;
            $columnName = $cell->getColumn();
            $this->logger->info('ExcelIngreso para columna '.$columnName. " valor ".$value);
    
            switch ($columnName) {
                case 'A':
                    $excelIngreso->setApellido($value);
                    break;
                case 'B':
                    $excelIngreso->setNombre($value);
                    break;
                case 'C':
                    $excelIngreso->setDni($value);
                    break;
                case 'D':
                    $excelIngreso->setCuit($value);
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
                    $excelIngreso->setEmail($value);
                    break;
                    
                case 'I':
                    $excelIngreso->setRubro($value);
                    break;
                case 'J':
                    $excelIngreso->setTipoCuenta($value);
                    break;
                case 'K':
                    $excelIngreso->setCbu($value);
                    break;
            }
        }
    
        $this->excelIngresoRepository->persist($excelIngreso);
        return $excelIngreso;
    }
}