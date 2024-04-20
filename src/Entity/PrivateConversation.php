<?php

namespace App\Entity;

use App\Repository\PrivateConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PrivateConversationRepository::class)]
class PrivateConversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['privconv:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'privateCreatedConversations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['privconv:read'])]
    private ?Profile $creator = null;

    #[ORM\ManyToOne(inversedBy: 'privateMemberConversations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['privconv:read'])]
    private ?Profile $member = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'conversation', targetEntity: PrivateMessage::class)]
    #[Groups(['privconv:read'])]
    private Collection $privateMessages;

    public function __construct()
    {
        $this->privateMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getCreator(): ?Profile
    {
        return $this->creator;
    }

    public function setCreator(?Profile $creator): static
    {
        $this->creator = $creator;

        return $this;
    }

    public function getMember(): ?Profile
    {
        return $this->member;
    }

    public function setMember(?Profile $member): static
    {
        $this->member = $member;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, PrivateMessage>
     */
    public function getPrivateMessages(): Collection
    {
        return $this->privateMessages;
    }

    public function addPrivateMessage(PrivateMessage $privateMessage): static
    {
        if (!$this->privateMessages->contains($privateMessage)) {
            $this->privateMessages->add($privateMessage);
            $privateMessage->setConversation($this);
        }

        return $this;
    }

    public function removePrivateMessage(PrivateMessage $privateMessage): static
    {
        if ($this->privateMessages->removeElement($privateMessage)) {
            // set the owning side to null (unless already changed)
            if ($privateMessage->getConversation() === $this) {
                $privateMessage->setConversation(null);
            }
        }

        return $this;
    }
}
