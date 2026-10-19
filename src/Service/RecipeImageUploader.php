<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class RecipeImageUploader
{
    public function __construct(
        #[Autowire('%kernel.project_dir%/public/uploads/recipes')]
        private string $targetDirectory,
        private SluggerInterface $slugger,
    ) {
    }

    public function upload(UploadedFile $file): string
    {
        
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = $this->slugger->slug($originalName)->lower();
        $fileName = sprintf('%s-%s.%s', $safeName, uniqid(), $file->guessExtension());

        $file->move($this->targetDirectory, $fileName);

        return $fileName;
    }
}
