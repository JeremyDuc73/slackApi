<?php

namespace App\Controller;

use App\Entity\Image;
use App\Service\ImageProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    #[Route('/api/image', methods: 'POST')]
    public function index(EntityManagerInterface $manager, Request $request, ImageProcessor $imageProcessor)
    {
        $file = $request->files->get('image');

        $image = new Image();
        $image->setImageFile($file);
        $manager->persist($image);
        $manager->flush();

        return $this->json( $image->getId(), 201);
    }
}
