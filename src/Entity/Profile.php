<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['friends:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['friends:read'])]
    private ?string $username = null;

    #[ORM\Column]
    #[Groups(['friends:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToOne(inversedBy: 'profile', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['friends:read'])]
    private ?User $ofUser = null;

    #[ORM\OneToMany(mappedBy: 'toUser', targetEntity: FriendRequest::class)]
    private Collection $sentFriendRequests;

    #[ORM\OneToMany(mappedBy: 'fromUser', targetEntity: FriendRequest::class)]
    private Collection $receivedFriendRequests;

    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: Relation::class)]
    private Collection $relationsAsSender;

    #[ORM\OneToMany(mappedBy: 'recipient', targetEntity: Relation::class)]
    private Collection $relationsAsRecipient;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: PrivateConversation::class)]
    private Collection $privateCreatedConversations;

    #[ORM\OneToMany(mappedBy: 'member', targetEntity: PrivateConversation::class)]
    private Collection $privateMemberConversations;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: PrivateMessage::class)]
    private Collection $privateMessages;


    public function __construct()
    {
        $this->sentFriendRequests = new ArrayCollection();
        $this->receivedFriendRequests = new ArrayCollection();
        $this->relationsAsSender = new ArrayCollection();
        $this->relationsAsRecipient = new ArrayCollection();
        $this->privateCreatedConversations = new ArrayCollection();
        $this->privateMemberConversations = new ArrayCollection();
        $this->privateMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

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

    public function getOfUser(): ?User
    {
        return $this->ofUser;
    }

    public function setOfUser(User $ofUser): static
    {
        $this->ofUser = $ofUser;

        return $this;
    }


    /**
     * @return Collection<int, FriendRequest>
     */
    public function getSentFriendRequests(): Collection
    {
        return $this->sentFriendRequests;
    }

    public function addSentFriendRequest(FriendRequest $sentFriendRequest): static
    {
        if (!$this->sentFriendRequests->contains($sentFriendRequest)) {
            $this->sentFriendRequests->add($sentFriendRequest);
            $sentFriendRequest->setToUser($this);
        }

        return $this;
    }

    public function removeSentFriendRequest(FriendRequest $sentFriendRequest): static
    {
        if ($this->sentFriendRequests->removeElement($sentFriendRequest)) {
            // set the owning side to null (unless already changed)
            if ($sentFriendRequest->getToUser() === $this) {
                $sentFriendRequest->setToUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FriendRequest>
     */
    public function getReceivedFriendRequests(): Collection
    {
        return $this->receivedFriendRequests;
    }

    public function addReceivedFriendRequest(FriendRequest $receivedFriendRequest): static
    {
        if (!$this->receivedFriendRequests->contains($receivedFriendRequest)) {
            $this->receivedFriendRequests->add($receivedFriendRequest);
            $receivedFriendRequest->setFromUser($this);
        }

        return $this;
    }

    public function removeReceivedFriendRequest(FriendRequest $receivedFriendRequest): static
    {
        if ($this->receivedFriendRequests->removeElement($receivedFriendRequest)) {
            // set the owning side to null (unless already changed)
            if ($receivedFriendRequest->getFromUser() === $this) {
                $receivedFriendRequest->setFromUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Relation>
     */
    public function getRelationsAsSender(): Collection
    {
        return $this->relationsAsSender;
    }

    public function addRelationAsSender(Relation $relationAsSender): static
    {
        if (!$this->relationsAsSender->contains($relationAsSender)) {
            $this->relationsAsSender->add($relationAsSender);
            $relationAsSender->setSender($this);
        }

        return $this;
    }

    public function removeRelationAsSender(Relation $relationAsSender): static
    {
        if ($this->relationsAsSender->removeElement($relationAsSender)) {
            // set the owning side to null (unless already changed)
            if ($relationAsSender->getSender() === $this) {
                $relationAsSender->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Relation>
     */
    public function getRelationsAsRecipient(): Collection
    {
        return $this->relationsAsRecipient;
    }

    public function addRelationAsRecipient(Relation $relationAsRecipient): static
    {
        if (!$this->relationsAsRecipient->contains($relationAsRecipient)) {
            $this->relationsAsRecipient->add($relationAsRecipient);
            $relationAsRecipient->setRecipient($this);
        }

        return $this;
    }

    public function removeRelationAsRecipient(Relation $relationAsRecipient): static
    {
        if ($this->relationsAsRecipient->removeElement($relationAsRecipient)) {
            // set the owning side to null (unless already changed)
            if ($relationAsRecipient->getRecipient() === $this) {
                $relationAsRecipient->setRecipient(null);
            }
        }

        return $this;
    }


    public function getFriendsList()
    {
        $list = [];
        foreach ($this->relationsAsSender as $relation){

            if($relation->getSender() != $this)
            {
                $otherProfile = $relation->getSender();

            }else{
                $otherProfile = $relation->getRecipient();
            }
            $list[]=$otherProfile;
        }
        foreach ($this->relationsAsRecipient as $relation){

            if($relation->getSender() != $this)
            {
                $otherProfile = $relation->getSender();

            }else{
                $otherProfile = $relation->getRecipient();
            }
            $list[]=$otherProfile;
        }

        return $list;
    }

    /**
     * @return Collection<int, PrivateConversation>
     */
    public function getPrivateCreatedConversations(): Collection
    {
        return $this->privateCreatedConversations;
    }

    public function addPrivateCreatedConversation(PrivateConversation $privateCreatedConversation): static
    {
        if (!$this->privateCreatedConversations->contains($privateCreatedConversation)) {
            $this->privateCreatedConversations->add($privateCreatedConversation);
            $privateCreatedConversation->setCreator($this);
        }

        return $this;
    }

    public function removePrivateCreatedConversation(PrivateConversation $privateCreatedConversation): static
    {
        if ($this->privateCreatedConversations->removeElement($privateCreatedConversation)) {
            // set the owning side to null (unless already changed)
            if ($privateCreatedConversation->getCreator() === $this) {
                $privateCreatedConversation->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PrivateConversation>
     */
    public function getPrivateMemberConversations(): Collection
    {
        return $this->privateMemberConversations;
    }

    public function addPrivateMemberConversation(PrivateConversation $privateMemberConversation): static
    {
        if (!$this->privateMemberConversations->contains($privateMemberConversation)) {
            $this->privateMemberConversations->add($privateMemberConversation);
            $privateMemberConversation->setMember($this);
        }

        return $this;
    }

    public function removePrivateMemberConversation(PrivateConversation $privateMemberConversation): static
    {
        if ($this->privateMemberConversations->removeElement($privateMemberConversation)) {
            // set the owning side to null (unless already changed)
            if ($privateMemberConversation->getMember() === $this) {
                $privateMemberConversation->setMember(null);
            }
        }

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
            $privateMessage->setAuthor($this);
        }

        return $this;
    }

    public function removePrivateMessage(PrivateMessage $privateMessage): static
    {
        if ($this->privateMessages->removeElement($privateMessage)) {
            // set the owning side to null (unless already changed)
            if ($privateMessage->getAuthor() === $this) {
                $privateMessage->setAuthor(null);
            }
        }

        return $this;
    }

}



