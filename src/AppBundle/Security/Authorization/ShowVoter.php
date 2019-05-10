<?php
/**
 * Created by PhpStorm.
 * User: remidupuy
 * Date: 20/02/18
 * Time: 10:26
 */

namespace AppBundle\Security\Authorization;


use AppBundle\Entity\Show;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ShowVoter extends Voter
{

    protected function supports($attribute, $subject)
    {
        // only vote on Post objects inside this voter
        if (!$subject instanceof Show) {
            return false;
        }

        return true;

    }


    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Post object, thanks to supports
        /** @var Show $show */
        $show = $subject;

        if($show->getAuthor() == $user)
            return true;

        return false;
    }
}