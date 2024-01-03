<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[Vich\Uploadable]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("image:index")]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: 'appimages', fileNameProperty: 'imageName', size: 'imageSize')]
    #[Groups("privmessage:read")]
    private ?File $imageFile = null;


    #[ORM\Column(nullable: true)]
    #[Groups(["privmessage:read", 'image:index'])]
    private ?string $imageName = null;


    #[ORM\Column(nullable: true)]
    #[Groups("image:index")]
    private ?int $imageSize = null;


    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: true)]
    private ?PrivateMessage $privateMessage = null;

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }


    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }


    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }


    public function getImageName(): ?string
    {
        return $this->imageName;
    }


    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }


    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrivateMessage(): ?PrivateMessage
    {
        return $this->privateMessage;
    }

    public function setPrivateMessage(?PrivateMessage $privateMessage): static
    {
        $this->privateMessage = $privateMessage;

        return $this;
    }


}
