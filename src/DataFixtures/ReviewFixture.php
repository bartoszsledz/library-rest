<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 09.12.18 19:33
 */

namespace App\DataFixtures;

use App\Entity\Review;
use App\Helpers\RandomGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ReviewFixture
 *
 * @package App\DataFixtures
 */
class ReviewFixture extends Fixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $history = new Review([
            'public_id' => RandomGenerator::generateUniqueInteger(\App\Enums\Review::LENGTH_UNIQUE),
            'comment' => 'Great book!',
            'stars' => 3
        ]);

        $manager->persist($history);

        $history->setUser($manager->merge($this->getReference('user1')));
        $history->setBook($manager->merge($this->getReference('book1')));

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 5;
    }
}