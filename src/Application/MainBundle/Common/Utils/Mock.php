<?php

namespace Application\MainBundle\Common\Utils;

class Mock
{

    protected static $emails = [];
    protected static $usernames = [];

    public static function generateUsers($count)
    {

        $faker = \Faker\Factory::create('pl_PL');

        $gender = [
            \Sonata\UserBundle\Model\UserInterface::GENDER_MALE,
            \Sonata\UserBundle\Model\UserInterface::GENDER_FEMALE,
        ];

        $persons = [];

        foreach (range(1, $count) as $i) {

            $gndr = $gender[array_rand($gender)];

            $firstName = $faker->firstNameMale;
            $lastName = $faker->lastNameMale;

            if ($gndr === \Sonata\UserBundle\Model\UserInterface::GENDER_FEMALE) {
                $firstName = $faker->firstNameFemale;
                $lastName = $faker->lastNameFemale;
            }

            $fname = mb_convert_case($firstName, MB_CASE_LOWER, 'UTF-8');
            $lname = mb_convert_case($lastName, MB_CASE_LOWER, 'UTF-8');
            $fname = iconv('UTF-8', 'ASCII//TRANSLIT', $fname);
            $lname = iconv('UTF-8', 'ASCII//TRANSLIT', $lname);

            $username = $fname[0] . $lname;
            $password = '4561237';

            switch (rand(1, 3)) {
                case 1:
                    $email = sprintf('%s@%s', $username, $faker->safeEmailDomain);
                    break;
                case 2:
                    $email = sprintf('%s@%s', $lname, $faker->safeEmailDomain);
                    break;
                case 3:
                    $email = sprintf('%s.%s@%s', $fname, $lname, $faker->safeEmailDomain);
                    break;
            };

            if (array_key_exists($username, self::$usernames)) {
                continue;
            }

            if (array_key_exists($email, self::$emails)) {
                continue;
            }

            self::$emails[$email] = true;
            self::$usernames[$username] = true;

            $user = new \Application\Sonata\UserBundle\Entity\User();
            $user->setUsername($username);
            $user->setFirstname($firstName);
            $user->setLastname($lastName);
            $user->setGender($gndr);
            $user->setEmail($email);
            
            $persons[] = $user;
        }
        
        return $persons;
    }

}
