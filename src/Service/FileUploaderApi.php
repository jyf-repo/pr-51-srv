<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploaderApi
{
    private $params;
    private $slugger;

    public function __construct(ParameterBagInterface $params, SluggerInterface $slugger)
    {
        $this->params = $params;
        $this->slugger = $slugger;
    }
    public function uploadFile(File $file): string
    {
        //return new JsonResponse($this->slugger->slug($file->getFilename()));
        $destination = $this->params->get('prescriptions_directory');
        //$originalFilename =$file->getFilename();
        //return new JsonResponse($originalFilename);
        //$newFilename = $originalFilename.'.'.uniqid().'.'.$file->guessExtension();
        $newFilename = uniqid().'.'.$file->guessExtension();
        try{
            $file->move(
              $destination,
              $newFilename
            );
        }catch(FileException $error){
            return new JsonResponse($error);
        }
        return $newFilename;
    }
}
