<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public function __construct(private string $targetDirectory)
    {
        
    }

    public function upload(UploadedFile $file): string
    {
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();

        $file->move($this->getTargetDirectory(), $fileName);

        return $fileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    public function remove($file): void
    {
        $file = $this->getTargetDirectory() . '/' . $file;
        if (file_exists($file)) {
            unlink($file);
        }
    }
}