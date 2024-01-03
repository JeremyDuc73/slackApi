<?php

namespace App\Entity;

use App\Repository\GroupMessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GroupMessageRepository::class)]
class GroupMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['groupmessage:read-one', 'groupmessage:read-all'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'groupMessages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GroupConversation $groupConversation = null;

    #[ORM\Column(length: 255)]
    #[Groups(['groupmessage:read-one', 'groupmessage:read-all'])]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'groupMessages')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['groupmessage:read-one', 'groupmessage:read-all'])]
    private ?Profile $author = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroupConversation(): ?GroupConversation
    {
        return $this->groupConversation;
    }

    public function setGroupConversation(?GroupConversation $groupConversation): static
    {
        $this->groupConversation = $groupConversation;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?Profile
    {
        return $this->author;
    }

    public function setAuthor(?Profile $author): static
    {
        $this->author = $author;

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
}
