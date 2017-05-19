<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $roles = [User::ROLE_STUDENT];
        $student = new User();
        $student
            ->setFirstName('Cosmin')
            ->setLastName('Chirica')
            ->setPhone('0756744760')
            ->setNationality('Romanian')
            ->setCitizenship('Romanian')
            ->setGender('gender.male')
            ->setUsername('cosmin.chirica')
            ->setEmail('chirica3.cosmin@gmail.com')
            ->setPlainPassword('Student1')
            ->setEnabled(true)
            ->setRoles($roles)
        ;
        $manager->persist($student);

        $roles = [User::ROLE_PROFESSOR];
        $professor = new User();
        $professor
            ->setFirstName('John')
            ->setLastName('Smith')
            ->setPhone('0123456789')
            ->setNationality('Romanian')
            ->setCitizenship('Romanian')
            ->setGender('gender.male')
            ->setUsername('john.smith')
            ->setEmail('chirica3.cosmin+professor001@gmail.com')
            ->setPlainPassword('Professor1')
            ->setEnabled(true)
            ->setRoles($roles)
        ;
        $manager->persist($professor);

        $roles = [User::ROLE_PROFESSOR];
        $professor = new User();
        $professor
            ->setFirstName('Arthur')
            ->setLastName('Baxter')
            ->setPhone('0123456789')
            ->setNationality('Romanian')
            ->setCitizenship('Romanian')
            ->setGender('gender.male')
            ->setUsername('arthur.baxter')
            ->setEmail('chirica3.cosmin+professor002@gmail.com')
            ->setPlainPassword('Professor2')
            ->setEnabled(true)
            ->setRoles($roles)
        ;
        $manager->persist($professor);

        $roles = [User::ROLE_ASSOCIATE];
        $associate = new User();
        $associate
            ->setFirstName('Ana')
            ->setLastName('David')
            ->setPhone('0123456789')
            ->setNationality('Romanian')
            ->setCitizenship('Romanian')
            ->setGender('gender.female')
            ->setUsername('ana.david')
            ->setEmail('chirica3.cosmin+associate001@gmail.com')
            ->setPlainPassword('Associate1')
            ->setEnabled(true)
            ->setRoles($roles)
        ;
        $manager->persist($associate);

        $roles = [User::ROLE_SUPER_ADMIN];
        $superadmin = new User();
        $superadmin
            ->setFirstName('Super')
            ->setLastName('Admin')
            ->setPhone('0123456789')
            ->setNationality('Romanian')
            ->setCitizenship('Romanian')
            ->setGender('gender.male')
            ->setUsername('super.admin')
            ->setEmail('chirica3.cosmin+superadmin001@gmail.com')
            ->setPlainPassword('Superadmin1')
            ->setEnabled(true)
            ->setRoles($roles)
        ;
        $manager->persist($superadmin);

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}
