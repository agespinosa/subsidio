<?php


namespace App\Services;


use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class FileManagerService
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    public function downloadFile (string $pathToResource, string $nameFile = 'download.txt', string $mimeType = 'text/plain'  ){
        $response = null;
        try{
            $response = new BinaryFileResponse($pathToResource);
            $response->headers->set('Content-Type',$mimeType);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $nameFile);
        }catch (\Exception $e){
           $this->logger->error("Error descargando archivo ".$pathToResource. " type ".$mimeType);
           throw $e;
        }
       
        return $response;
    }
}