<?php

namespace AppBundle\Security;

use AppBundle\Entity\Homework;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class HomeworkVoter.
 *
 * Restricts users access to Homework entities
 */
class HomeworkVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';

    /**
     * Define actions on entity.
     *
     * @param string $attribute
     * @param mixed  $subject
     *
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array(
            $attribute,
            [
                self::EDIT,
                self::DELETE,
            ]
        )) {
            return false;
        }

        return $subject instanceof Homework;
    }

    /**
     * Restrict access.
     *
     * @param string         $attribute
     * @param mixed          $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!($user instanceof User)) {
            return false;
        }

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($subject, $user);
            case self::DELETE:
                return $this->canDelete($subject, $user);
        }

        return false;
    }

    /**
     * Edit access restriction.
     *
     * @param Homework $homework
     * @param User     $user
     *
     * @return bool
     */
    private function canEdit(Homework $homework, User $user)
    {
        return $homework->getAuthor() == $user;
    }

    /**
     * Delete access restriction.
     *
     * @param Homework $homework
     * @param User     $user
     *
     * @return bool
     */
    private function canDelete(Homework $homework, User $user)
    {
        return $homework->getAuthor() == $user;
    }
}
