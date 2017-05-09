<?php

namespace AppBundle\File;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class FileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $fs = new Filesystem();

        $date = date('Y-m-d');

        if (!$fs->exists($targetDir . $date)) {
            $fs->mkdir($targetDir . $date);
        }

        $this->targetDir = $targetDir . $date;
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->targetDir, $fileName);

        return $fileName;
    }
}