<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Helpers\RandomGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixtures
 *
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture implements OrderedFixtureInterface
{

    /**
     * @var UserPasswordEncoderInterface $encoder
     */
    private $encoder;

    /**
     * UserFixtures constructor.
     *
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user1 = new User([
            'roles' => ['ROLE_USER'],
            'email' => 'user1@wp.pl',
            'public_id' => RandomGenerator::generateUniqueInteger(\App\Enums\User::LENGTH_UNIQUE)
        ]);
        $user1->setPassword($this->encoder->encodePassword($user1, 'haslo'));

        $manager->persist($user1);

        $this->addReference('user1', $user1);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}
