<?php

namespace App\Controller;

use App\Entity\GroupConversation;
use App\Entity\Profile;
use App\Service\GroupConversationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/groupconv')]
class GroupConversationController extends AbstractController
{
    #[Route('/create', methods: 'POST')]
    public function create(EntityManagerInterface $manager)
    {
        $groupConv = new GroupConversation();
        $groupConv->addAdmin($this->getUser()->getProfile());
        $manager->persist($groupConv);
        $manager->flush();
        return $this->json("Group Conversation created", 201);
    }
    #[Route('/{groupId}/add/{profileId}')]
    public function addToGroupConv(
        #[MapEntity(mapping: ['groupId'=>'id'])] GroupConversation $groupConversation,
        #[MapEntity(mapping: ['profileId'=>'id'])] Profile $profile,
        GroupConversationService $service, EntityManagerInterface $manager)
    {
        if ($service->isInGroupConv($profile, $groupConversation))
        {
            return $this->json("User already in this conversation");
        }
        $groupConversation->addRecipient($profile);
        $manager->persist($groupConversation);
        $manager->flush();
        return $this->json("User added to conv", 200);
    }
}
