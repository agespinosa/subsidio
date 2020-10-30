<?php


namespace App\Controller;


use App\Services\FileManagerService;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/fileManager")
 */
class FileManagerController
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var FileManagerService
     */
    private $fileManagerService;
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;
    
    public function __construct(LoggerInterface $logger, FileManagerService $fileManagerService,
                                ParameterBagInterface $parameterBag )
    {
        $this->logger = $logger;
        $this->fileManagerService = $fileManagerService;
        $this->parameterBag = $parameterBag;
    }
    
    /**
     * @param string $pathFolder
     * @param string $nameFile
     * @Route("/download/{pathFolder}/{nameFile}/{mimeType}",
     *      name="download_file", methods={"GET"},
     *      defaults={"mimeType": "text/plain", "nameFile":"download.txt"})
     * )
     */
    public function downloadFile(string $nameFile, string $pathFolder ){
    
        $extension = pathinfo($nameFile, PATHINFO_EXTENSION);
        if( is_null($extension) || empty($extension) ){
            $nameFile .= '.txt';
        }
        $fullPath = $this->parameterBag->get('public_uploads_path_base').'/'.$pathFolder.'/'.$nameFile;
        return $this->fileManagerService->downloadFile($fullPath, $nameFile);
    }
    
    /**
     *
     * @Route("/excelModelo",
     *      name="download_excel_modelo", methods={"GET"},
     *      )
     * )
     */
    public function downloadExcelModelo(){
        
        $fullPath = $this->parameterBag->get('excel_modelo_directory_relative_path');
        return $this->fileManagerService->downloadFile($fullPath, 'modeloExcel.xls');
    }
}