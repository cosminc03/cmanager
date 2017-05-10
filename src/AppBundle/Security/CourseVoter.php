<?php

namespace AppBundle\Security;

use AppBundle\Entity\Course;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class CourseVoter.
 *
 * Restricts users access to Course entities
 */
class CourseVoter extends Voter
{
    const CREATE = 'create';
    const EDIT = 'edit';
    const DELETE = 'delete';

    const CREATE_SEMINAR = 'create_seminar';

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
                    self::CREATE,
                    self::EDIT,
                    self::DELETE,
                    self::CREATE_SEMINAR,
                ]
        )) {
            return false;
        }

        return $subject instanceof Course;
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
            case self::CREATE:
                return $this->canCreate($subject, $user);
            case self::EDIT:
                return $this->canEdit($subject, $user);
            case self::DELETE:
                return $this->canDelete($subject, $user);
            case self::CREATE_SEMINAR:
                return $this->canCreateSeminar($subject, $user);
        }

        return false;
    }

    /**
     * Create access restriction.
     *
     * @param Course  $course
     * @param User    $user
     *
     * @return bool
     */
    private function canCreate(Course $course, User $user)
    {
        return in_array(User::ROLE_PROFESSOR, $user->getRoles());
    }

    /**
     * Edit access restriction.
     *
     * @param Course  $course
     * @param User    $user
     *
     * @return bool
     */
    private function canEdit(Course $course, User $user)
    {
        return $course->getAuthor() == $user;
    }

    /**
     * Delete access restriction.
     *
     * @param Course  $course
     * @param User    $user
     *
     * @return bool
     */
    private function canDelete(Course $course, User $user)
    {
        return $course->getAuthor() == $user;
    }

    /**
     * Create access restriction.
     *
     * @param Course  $course
     * @param User    $user
     *
     * @return bool
     */
    private function canCreateSeminar(Course $course, User $user)
    {
        $associates = $course->getAssistants();

        if (!$associates->contains($user)) {
            return false;
        }

        return in_array(User::ROLE_PROFESSOR, $user->getRoles()) || in_array(User::ROLE_ASSOCIATE, $user->getRoles());
    }
}
