<?php

namespace Application\MainBundle\DataFixtures\ORM;

use Application\MainBundle\DataFixtures\BasicFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class LoadUserData extends BasicFixture {

    /**
     * @var numeric
     */
    protected $order = 2;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {

        $path = __DIR__ . '/../data/User.yml';
        $path = realpath($path);
        $data = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($path));
        $data = array_pop($data);
        
        $this->getContainer()
            ->get('security.token_storage')
            ->setToken(new UsernamePasswordToken('system', null, 'main'));

        /**
         * @var Sonata\UserBundle\Entity\UserManager $mngr
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
