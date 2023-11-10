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
        $test = $this->getUser()->getProfile()->getFriendsList();
        return $this->json($test, 200, [], ['groups'=>'friends:read']);
    }
}
