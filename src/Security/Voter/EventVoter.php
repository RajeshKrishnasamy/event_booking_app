<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Gives access to users against event control
 */

class EventVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['EDIT', 'CANCEL', 'DELETE'])
            && $subject instanceof \App\Entity\Event;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }


        switch ($attribute) {
            case 'EDIT':
            case 'CANCEL':
            case 'DELETE':
                return $user === $subject->getAdmin();
                break;
        }

        return false;
    }
}
