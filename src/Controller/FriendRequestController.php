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
    #[Route('/api/friendrequests/sent', methods: ['GET'])]
    public function listSent(FriendRequestRepository $repo)
    {
        $friendRequests = $this->getUser()->getProfile()->getSentFriendRequests();
        return $this->json($friendRequests, 200, [], ['groups'=>'friendrequest:sent']);
    }

    #[Route('/api/friendrequests/received', methods: ['GET'])]
    public function listReceived(FriendRequestRepository $repo)
    {
        $friendRequests = $this->getUser()->getProfile()->getReceivedFriendRequests();
        return $this->json($friendRequests, 200, [], ['groups'=>'friendrequest:received']);
    }

    #[Route('/api/friendrequest/send/{id}', methods: ['POST'])]
    public function send($id, ProfileRepository $repo, EntityManagerInterface $manager) : Response
    {

        $sender = $this->getUser()->getProfile();
        $recipient = $repo->find($id);
        $request = new FriendRequest();
        $request->setFromUser($sender);
        $request->setToUser($recipient);
        $request->setStatus(0);

        $manager->persist($recipient);
        $manager->persist($sender);
        $manager->persist($request);
        $manager->flush();
        return $this->json("Request sent to ".$recipient->getOfUser()->getEmail(), 201);
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

        $manager->remove($request);
        $manager->persist($sender);
        $manager->persist($recipient);
        $manager->persist($relation);
        $manager->flush();
        return $this->json("Friend add",200);
    }
    #[Route('/api/friendrequest/deny/{id}', methods: ['POST'])]
    public function deny($id, FriendRequestRepository $repo, EntityManagerInterface $manager): Response
    {
        $request = $repo->find($id);
        if (!$request){
            return $this->json("this request does not exists (already accepted or not sent)");
        }
        $sender = $request->getFromUser();
        $recipient = $request->getToUser();
        $manager->remove($request);
        $manager->persist($sender);
        $manager->persist($recipient);
        $manager->flush();
        return $this->json("Request denied", 200);
    }


}
