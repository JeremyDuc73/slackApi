<?php

namespace App\Entity;

use App\Repository\PrivateMessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PrivateMessageRepository::class)]
class PrivateMessage
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['privconv:read', 'privmessage:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['privmessage:read', 'privconv: read'])]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'privateMessages')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['privmessage:read', 'privconv:read'])]
    private ?Profile $author = null;

    #[ORM\Column]
    #[Groups('privmessage:read')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'privateMessages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PrivateConversation $conversation = null;

    #[ORM\OneToMany(mappedBy: 'privateMessage', targetEntity: Image::class)]
    private Collection $images;

    #[Groups("privmessage:read")]
    private ArrayCollection $imagesUrls;

    private array $associatedImages;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
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

    public function getConversation(): ?PrivateConversation
    {
        return $this->conversation;
    }

    public function setConversation(?PrivateConversation $conversation): static
    {
        $this->conversation = $conversation;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setPrivateMessage($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getPrivateMessage() === $this) {
                $image->setPrivateMessage(null);
            }
        }

        return $this;
    }

    public function getAssociatedImages(): array
    {
        return $this->associatedImages;
    }

    public function setAssociatedImages(array $associatedImages): void
    {
        $this->associatedImages = $associatedImages;
    }

    public function getImagesUrls(): ArrayCollection
    {
        return $this->imagesUrls;
    }

    public function setImagesUrls(ArrayCollection $imagesUrls): void
    {
        $this->imagesUrls = $imagesUrls;
    }
}
