<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\User;

/**
 * Create a new user object (firstname, lastname and role are optionally).
 *
 * Command usage: app:user-create email@email.com password username firstname lastname ROLE_ADMIN
 */
class UserCreateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:user-create')
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the new user')
            ->addArgument('password', InputArgument::REQUIRED, 'The password of the new user')
            ->addArgument('username', InputArgument::REQUIRED, 'The username of the new user')
            ->addOption('first_name', null, InputArgument::OPTIONAL, 'The first name of the new user', 'John')
            ->addOption('last_name', null, InputArgument::OPTIONAL, 'The last name of the new user', 'Doe')
            ->addOption('phone', null, InputArgument::OPTIONAL, 'The phone of the new user', '0123456789')
            ->addOption('role', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'Roles to set to the user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $username = $input->getArgument('username');
        $firstName = $input->getOption('first_name');
        $lastName = $input->getOption('last_name');
        $roles = $input->getOption('role');
        $phone = $input->getOption('phone');

        $em = $this->getContainer()->get('doctrine')->getManager();

        if ($em->getRepository(User::class)->findOneBy(['email' => $email])) {
            $output->writeln(sprintf(
                '<error>Unable to create user with email "%s" as the email is already taken.</error>',
                $email
            ));

            return -1;
        }

        $user = new User();
        $user
            ->setFirstName($firstName)
            ->setLastName($lastName)
        ;
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->setEnabled(true);
        $user->setRoles($roles);
        $user->setPhone($phone);
        $user->setNationality('Romanian');
        $user->setCitizenship('Romanian');
        $user->setGender('gender.male');

        $em->persist($user);
        $em->flush();

        $output->writeln('<info>User created.</info>');
    }
}
