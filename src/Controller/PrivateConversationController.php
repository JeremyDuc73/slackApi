<?php

namespace App\Controller;

use App\Entity\PrivateConversation;
use App\Repository\PrivateConversationRepository;
use App\Repository\ProfileRepository;
use App\Service\ImageProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/privconv')]
class PrivateConversationController extends AbstractController
{
    #[Route('s', methods: ['GET'])]
    public function getAll()
    {
        $tmp = $this->getUser()->getProfile()->getPrivConvs();
        return $this->json($tmp, 200, [], ['groups'=>'privconv:read']);
    }

    #[Route('/get/{id}', methods: 'GET')]
    public function get($id, PrivateConversationRepository $repository)
    {
        $privateConversation = $repository->find($id);
        return $this->json($privateConversation->getPrivateMessages(), 200, [], ['groups'=>"privmessage:read"]);
    }

    #[Route('/create/{id}', methods: 'POST')]
    public function create($id, ProfileRepository $repo, EntityManagerInterface $manager): Response
    {
        $creator = $this->getUser()->getProfile();
        $member = $repo->find($id);
        $privateConv = new PrivateConversation();
        $privateConv->setCreator($creator);
        $privateConv->setMember($member);
        $privateConv->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($privateConv);
        $manager->flush();
        return $this->json("Private conversation created", 201);
    }
}
