<?php

namespace App\Entity;

use App\Repository\GroupConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupConversationRepository::class)]
class GroupConversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Profile::class, inversedBy: 'AdminGroupConversations')]
    private Collection $admins;

    #[ORM\JoinTable(name: 'group_recipient_conv_profile')]
    #[ORM\ManyToMany(targetEntity: Profile::class, inversedBy: 'MemberGroupConversations')]
    private Collection $recipients;

    #[ORM\OneToMany(mappedBy: 'groupConversation', targetEntity: GroupMessage::class)]
    private Collection $groupMessages;

    public function __construct()
    {
        $this->admins = new ArrayCollection();
        $this->recipients = new ArrayCollection();
        $this->groupMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Profile>
     */
    public function getAdmins(): Collection
    {
        return $this->admins;
    }

    public function addAdmin(Profile $admin): static
    {
        if (!$this->admins->contains($admin)) {
            $this->admins->add($admin);
        }

        return $this;
    }

    public function removeAdmin(Profile $admin): static
    {
        $this->admins->removeElement($admin);

        return $this;
    }

    /**
     * @return Collection<int, Profile>
     */
    public function getRecipients(): Collection
    {
        return $this->recipients;
    }

    public function addRecipient(Profile $recipient): static
    {
        if (!$this->recipients->contains($recipient)) {
            $this->recipients->add($recipient);
        }

        return $this;
    }

    public function removeRecipient(Profile $recipient): static
    {
        $this->recipients->removeElement($recipient);

        return $this;
    }

    /**
     * @return Collection<int, GroupMessage>
     */
    public function getGroupMessages(): Collection
    {
        return $this->groupMessages;
    }

    public function addGroupMessage(GroupMessage $groupMessage): static
    {
        if (!$this->groupMessages->contains($groupMessage)) {
            $this->groupMessages->add($groupMessage);
            $groupMessage->setGroupConversation($this);
        }

        return $this;
    }

    public function removeGroupMessage(GroupMessage $groupMessage): static
    {
        if ($this->groupMessages->removeElement($groupMessage)) {
            // set the owning side to null (unless already changed)
            if ($groupMessage->getGroupConversation() === $this) {
                $groupMessage->setGroupConversation(null);
            }
        }

        return $this;
    }
}
