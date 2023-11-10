<?php

namespace App\Controller;

use App\Entity\PrivateConversation;
use App\Entity\PrivateMessage;
use App\Repository\PrivateConversationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PrivateMessageController extends AbstractController
{
    #[Route('/api/privatconv/{id}/send')]
    public function send($id,PrivateConversationRepository $repo, SerializerInterface $serializer, Request $request, EntityManagerInterface $manager): Response
    {
        $privateConversation = $repo->find($id);
        $privateMessage = $serializer->deserialize($request->getContent(), PrivateMessage::class, 'json');

        $privateMessage->setConversation($privateConversation);
        $privateMessage->setAuthor($this->getUser()->getProfile());
        $privateMessage->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($privateMessage);
        $manager->flush();
        return $this->json("message send", 201);
    }
}
