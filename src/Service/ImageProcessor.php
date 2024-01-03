<?php

namespace App\Service;

use App\Entity\Image;
use App\Entity\PrivateConversation;
use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImageProcessor
{
    private ImageRepository $imageRepository;
    private UploaderHelper $uploaderHelper;
    private CacheManager $cacheManager;

    public function __construct(ImageRepository $imageRepository, UploaderHelper $uploaderHelper, CacheManager $cacheManager)
    {
        $this->imageRepository = $imageRepository;
        $this->uploaderHelper = $uploaderHelper;
        $this->cacheManager = $cacheManager;
    }

    public function getImagesFromImagesIds(array $imagesIds): array
    {
        $images = [];
        foreach ($imagesIds as $imageId){
            $image = $this->imageRepository->find($imageId);
            if ($image){
                $images[] = $image;
            }
        }
        return $images;
    }

    public function setImagesUrlsOfMessagesFromPrivateConversation(PrivateConversation $privateConversation)
    {
        $messages = $privateConversation->getPrivateMessages();
        foreach ($messages as $message){
            $images = $message->getImages();
            $imagesUrls = new ArrayCollection();
            foreach ($images as $image){
                $imageUrl = [];
                $imageUrl["id"] = $image->getId();
                $imageUrl["url"] = $this->cacheManager->generateUrl($this->uploaderHelper->asset($image), 'vignette');
                $imagesUrls->add($imageUrl);
            }
            $message->setImagesUrls($imagesUrls);
        }
        return $messages;
    }
}