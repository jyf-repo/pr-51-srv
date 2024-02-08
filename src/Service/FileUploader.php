<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    public function __construct(
        private string $targetDirectory,
        private SluggerInterface $slugger,
    ) {
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        //dd($originalFilename);
        $safeFilename = $this->slugger->slug($originalFilename);
        //dd($safeFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        //dd($fileName);

        try {
            $file->move($this->getTargetDirectory(), $fileName);
            //dd($fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
            return new JsonResponse($e);
        }

        return $fileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
