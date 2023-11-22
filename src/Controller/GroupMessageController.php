<?php

namespace App\Controller;

use App\Entity\GroupConversation;
use App\Entity\GroupMessage;
use App\Service\GroupConversationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/groupconv/message')]
class GroupMessageController extends AbstractController
{
    #[Route('/create/{id}', methods: 'POST')]
    public function create(GroupConversation $groupConversation, GroupConversationService $service, Request $request,
                           EntityManagerInterface $manager, SerializerInterface $serializer)
    {
        if (!$service->isInGroupConv($this->getUser()->getProfile(), $groupConversation))
        {
            return $this->json("You are not member of this group conversation", 400);
        }
        $groupMessage = $serializer->deserialize($request->getContent(), GroupMessage::class, 'json');
        $groupMessage->setGroupConversation($groupConversation);
        $groupMessage->setAuthor($this->getUser()->getProfile());
        $manager->persist($groupMessage);
        $manager->flush();
        return $this->json($groupMessage, 201, [], ["groups"=>"groupmessage:read-one"]);
    }

    #[Route('/all/{id}', methods: 'GET')]
    public function index(GroupConversation $groupConversation, GroupConversationService $service)
    {
        if (!$service->isInGroupConv($this->getUser()->getProfile(), $groupConversation))
        {
            return $this->json("You are not member of this group conversation", 400);
        }
        $messages = $groupConversation->getGroupMessages();
        return $this->json($messages, 200, [], ["groups"=>"groupmessage:read-all"]);
    }
}
