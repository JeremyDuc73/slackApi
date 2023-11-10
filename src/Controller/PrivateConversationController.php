<?php

namespace App\Controller;

use App\Entity\PrivateConversation;
use App\Repository\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrivateConversationController extends AbstractController
{
    #[Route('/api/privateconv/create/{id}')]
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
