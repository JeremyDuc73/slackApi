<?php

namespace App\Entity;

use App\Repository\FriendRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FriendRequestRepository::class)]
class FriendRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['friendrequest:received', 'friendrequest:sent'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'receivedFriendRequests')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['friendrequest:sent'])]
    private ?Profile $toUser = null;

    #[ORM\ManyToOne(inversedBy: 'sentFriendRequests')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['friendrequest:received'])]
    private ?Profile $fromUser = null;

    #[ORM\Column]
    #[Groups(['friendrequest:sent', 'friendrequest:received'])]
    private ?int $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToUser(): ?Profile
    {
        return $this->toUser;
    }

    public function setToUser(?Profile $toUser): static
    {
        $this->toUser = $toUser;

        return $this;
    }

    public function getFromUser(): ?Profile
    {
        return $this->fromUser;
    }

    public function setFromUser(?Profile $fromUser): static
    {
        $this->fromUser = $fromUser;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }
}
