<?php

namespace App\Service;

use App\Entity\GroupConversation;
use App\Entity\Profile;

class GroupConversationService
{
    public function isInGroupConv(Profile $profile, GroupConversation $groupConversation): bool
    {
        $groupAdmins = $groupConversation->getAdmins();
        $groupRecipients = $groupConversation->getRecipients();

        foreach ($groupRecipients as $recipient){
            if ($recipient === $profile){
                return true;
            }
        }
        foreach ($groupAdmins as $admin){
            if ($admin === $profile){
                return true;
            }
        }
        return false;
    }
}