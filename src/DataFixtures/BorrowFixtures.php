<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 03.12.18 20:53
 */

namespace App\DataFixtures;

use App\Entity\Borrow;
use App\Helpers\RandomGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class BorrowFixtures
 *
 * @package App\DataFixtures
 */
class BorrowFixtures extends Fixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        $borrow = new Borrow([
            'public_id' => RandomGenerator::generateUniqueInteger(\App\Enums\Borrow::LENGTH_UNIQUE)
        ]);

        $manager->persist($borrow);

        $borrow->setUser($manager->merge($this->getReference('user1')));
        $borrow->setBook($manager->merge($this->getReference('book1')));

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }
}