<?php

namespace AppBundle\Security;

use AppBundle\Entity\Module;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class ModuleVoter.
 *
 * Restricts users access to Module entities
 */
class ModuleVoter extends Voter
{
    const CREATE_COURSE = 'create_course';
    const EDIT_COURSE = 'edit_course';
    const DELETE_COURSE = 'delete_course';

    const EDIT_SEMINAR = 'edit_seminar';
    const DELETE_SEMINAR = 'delete_seminar';

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
                    self::CREATE_COURSE,
                    self::EDIT_COURSE,
                    self::DELETE_COURSE,
                    self::EDIT_SEMINAR,
                    self::DELETE_SEMINAR,
                ]
        )) {
            return false;
        }

        return $subject instanceof Module;
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
            case self::CREATE_COURSE:
                return $this->canCreateCourse($subject, $user);
            case self::EDIT_COURSE:
                return $this->canEditCourse($subject, $user);
            case self::DELETE_COURSE:
                return $this->canDeleteCourse($subject, $user);
            case self::EDIT_SEMINAR:
                return $this->canEditSeminar($subject, $user);
            case self::DELETE_SEMINAR:
                return $this->canDeleteSeminar($subject, $user);
        }

        return false;
    }

    /**
     * Create access restriction.
     *
     * @param Module  $module
     * @param User    $user
     *
     * @return bool
     */
    private function canCreateCourse(Module $module, User $user)
    {
        return in_array(User::ROLE_PROFESSOR, $user->getRoles());
    }

    /**
     * Edit access restriction.
     *
     * @param Module  $module
     * @param User    $user
     *
     * @return bool
     */
    private function canEditCourse(Module $module, User $user)
    {
        return $module->getAuthor() == $user;
    }

    /**
     * Delete access restriction.
     *
     * @param Module  $module
     * @param User    $user
     *
     * @return bool
     */
    private function canDeleteCourse(Module $module, User $user)
    {
        return $module->getAuthor() == $user;
    }



    /**
     * Edit access restriction.
     *
     * @param Module  $module
     * @param User    $user
     *
     * @return bool
     */
    private function canEditSeminar(Module $module, User $user)
    {
        return $module->getAuthor() == $user;
    }

    /**
     * Delete access restriction.
     *
     * @param Module  $module
     * @param User    $user
     *
     * @return bool
     */
    private function canDeleteSeminar(Module $module, User $user)
    {
        return $module->getAuthor() == $user;
    }
}
