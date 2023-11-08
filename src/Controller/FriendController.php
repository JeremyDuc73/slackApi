<?php

namespace App\Controller;

use App\Repository\ProfileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FriendController extends AbstractController
{
    #[Route('/api/friends', methods: ['GET'])]
    public function list(): Response
    {
        return $this->json($this->getUser()->getProfile()->getRelationsAsSender(), 200, [], ['groups'=>'user:read']);
    }
}
