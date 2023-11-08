<?php

namespace App\Controller;

use App\Entity\FriendRequest;
use App\Entity\Relation;
use App\Repository\FriendRequestRepository;
use App\Repository\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FriendRequestController extends AbstractController
{
    #[Route('/api/friendrequest/send/{id}', methods: ['POST'])]
    public function send($id, ProfileRepository $repo, EntityManagerInterface $manager) : Response
    {
        $sender = $this->getUser()->getProfile();
        $recipient = $repo->find($id);
        $request = new FriendRequest();
        $request->setFromUser($sender);
        $request->setToUser($recipient);
        $request->setStatus(0);
        $manager->persist($request);
        $manager->flush();
        return $this->json("Request sent", 201);
    }
    #[Route('/api/friendrequest/accept/{id}', methods: ['POST'])]
    public function accept($id, FriendRequestRepository $repo, EntityManagerInterface $manager) : Response
    {
        $request = $repo->find($id);
        $sender = $request->getFromUser();
        $recipient = $request->getToUser();
        $relation = new Relation();
        $relation->setSender($sender);
        $relation->setRecipient($recipient);
        $request->setStatus(1);
        $manager->persist($request);
        $manager->persist($relation);
        $manager->flush();
        return $this->json("Friend add",200);
    }
}
