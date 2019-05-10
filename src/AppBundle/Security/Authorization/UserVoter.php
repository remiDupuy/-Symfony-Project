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

class UserVoter extends Voter
{

    const ADMIN = 'ROLE_ADMIN';

    protected function supports($attribute, $subject)
    {
        if(!in_array($attribute, [self::ADMIN]))
            return false;


        return true;
    }


    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User OR $user == null) {
            // the user must be logged in; if not, deny access
            return false;
        }

        if(in_array(self::ADMIN, $user->getRoles()))
            return true;

        return false;
    }
}