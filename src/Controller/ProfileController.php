<?php

namespace App\Controller;

use App\Repository\ProfileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/api/profiles', methods: ['GET'])]
    public function index(ProfileRepository $repo): Response
    {
        $profiles = [];
        $profilesTemps = $repo->findAll();
        $friends = $this->getUser()->getProfile()->getFriendsList();
        foreach ($profilesTemps as $profileTemp)
        {
            if ($profileTemp->getOfUser()->getEmail() !== $this->getUser()->getEmail() && !in_array($profileTemp, $friends))
            {
                $profiles[]=$profileTemp;
            }
        }
        return $this->json($profiles, 200, [], ['groups'=>'profile:read-all']);
    }

    #[Route('/api/myprofile', methods: ['GET'])]
    public function myProfile(): Response
    {
        return $this->json($this->getUser()->getProfile(), 200, [], ['groups'=>'profile:read-all']);
    }
}
