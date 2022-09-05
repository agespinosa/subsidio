<?php


namespace App\Services;


use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileService
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var ParameterBagInterface
     */
    private $params;

    public function __construct(LoggerInterface $logger,
                                ParameterBagInterface $params){

        $this->logger = $logger;
        $this->params = $params;
    }

    public function uploadFile($file){
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
        $this->logger->debug("Subiendo Excel , nuevo pagos " . $newFilename);
        try {
            $file->move(
                $this->params->get('files_pagos_directory'),
                $newFilename
            );
            $this->logger->debug("Subo OK Excel , nuevo pagos " . $newFilename);
        } catch (FileException $e) {
            $message = "Error Subiendo Excel , nuevo pagos " . $e->getMessage();
            $this->logger->error($message);
            $this->addFlash('errorMessage', $message);
            throw $e;
        }
        return $newFilename;
    }

    /**
     * A simple function that uses mtime to delete files older than a given age (in seconds)
     * Very handy to rotate backup or log files, for example...
     *
     * $dir String whhere the files are
     * $max_age Int in seconds
     * return String[] the list of deleted files
     *
     * EJ: Delete backups older than 7 days
        $deleted = deleteOlderThan($dir, 3600*24*7);
     */

    public function deleteOlderThan($dir, $max_age) {
        $list = array();

        $limit = time() - $max_age;

        $dir = realpath($dir);

        if (!is_dir($dir)) {
            return;
        }

        $dh = opendir($dir);
        if ($dh === false) {
            return;
        }

        while (($file = readdir($dh)) !== false) {
            $file = $dir . '/' . $file;
            if (!is_file($file)) {
                continue;
            }

            if (filemtime($file) < $limit) {
                $list[] = $file;
                unlink($file);
            }

        }
        closedir($dh);
        return $list;

    }
}