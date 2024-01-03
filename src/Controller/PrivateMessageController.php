<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\PrivateConversation;
use App\Entity\PrivateMessage;
use App\Repository\PrivateConversationRepository;
use App\Service\ImageProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/privconv/message')]
class PrivateMessageController extends AbstractController
{
    #[Route('/{id}/send', name: 'app_privatemessage_send', methods: 'POST')]
    public function send($id,PrivateConversationRepository $repo, SerializerInterface $serializer, Request $request,
                         EntityManagerInterface $manager, ImageProcessor $imageProcessor): Response
    {
        $privateConversation = $repo->find($id);
        $privateMessage = $serializer->deserialize($request->getContent(), PrivateMessage::class, 'json');

        $privateMessage->setConversation($privateConversation);
        $privateMessage->setAuthor($this->getUser()->getProfile());
        $privateMessage->setCreatedAt(new \DateTimeImmutable());

        $assiocatedImages = $privateMessage->getAssociatedImages();

        if ($assiocatedImages){
            foreach ($imageProcessor->getImagesFromImagesIds($assiocatedImages) as $image){
                $privateMessage->addImage($image);
            }
        }

        $manager->persist($privateMessage);
        $manager->flush();
        return $this->json("message send", 201);
    }


}
