<?php

namespace Application\MainBundle\DataFixtures\ORM;

use Application\MainBundle\DataFixtures\BasicFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserData extends BasicFixture {

    /**
     * @var numeric
     */
    protected $order = 2;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {

        $data = [
            [
                'enabled' => true,
                'username' => 'system',
                'firstname' => null,
                'lastname' => null,
                'dob' => null,
                'gender' => \Sonata\UserBundle\Model\UserInterface::GENDER_UNKNOWN,
                'password' => null,
                'email' => 'siciarek@hotmail.com',
                'groups' => [
                    'Superadmins',
                ]
            ],
            [
                'enabled' => true,
                'username' => 'jsiciarek',
                'firstname' => 'Jacek',
                'lastname' => 'Siciarek',
                'dob' => '1966-10-21',
                'gender' => \Sonata\UserBundle\Model\UserInterface::GENDER_MALE,
                'password' => 'pass',
                'email' => 'siciarek@gmail.com',
                'groups' => [
                    'Superadmins',
                ]
            ],
            [
                'enabled' => true,
                'username' => 'colak',
                'firstname' => 'Czesław',
                'lastname' => 'Olak',
                'dob' => '1985-04-11',
                'gender' => \Sonata\UserBundle\Model\UserInterface::GENDER_MALE,
                'password' => 'pass',
                'email' => 'colak@gmail.com',
                'groups' => [
                    'Admins',
                ]
            ],
            [
                'enabled' => true,
                'username' => 'molak',
                'firstname' => 'Marianna',
                'lastname' => 'Olak',
                'dob' => '1989-11-05',
                'gender' => \Sonata\UserBundle\Model\UserInterface::GENDER_FEMALE,
                'password' => 'pass',
                'email' => 'molak@gmail.com',
                'groups' => [
                    'Users',
                ]
            ],
        ];

        /**
         * @var Sonata\UserBundle\Entity\GroupManager $mngr
         */
        $mngr = $this->getContainer()->get('fos_user.user_manager');

        foreach ($data as $o) {
            $user = $mngr->createUser();
            $user->setEnabled($o['enabled']);
            $user->setUsername($o['username']);
            $user->setFirstname($o['firstname']);
            $user->setLastname($o['lastname']);
            $user->setGender($o['gender']);
            $user->setEmail($o['email']);
            $user->setDateOfBirth(new \DateTime($o['dob']));
            $user->setPlainPassword($o['password']);

            foreach ($o['groups'] as $group) {
                $user->addGroup($this->getReference('group' . $group));
            }
            $mngr->updateUser($user);
            $this->setReference('user' . $user->getUsername(), $user);
        }
    }

}
