<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 05.12.18 17:34
 */

namespace App\DataFixtures;

use App\Entity\History;
use App\Helpers\RandomGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class BorrowHistoryFixtures
 *
 * @package App\DataFixtures
 */
class BorrowHistoryFixtures extends Fixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $history = new History([
            'public_id' => RandomGenerator::generateUniqueInteger(\App\Enums\History::LENGTH_UNIQUE),
            'date_borrow' => new \DateTime()
        ]);

        $manager->persist($history);

        $history->setUser($manager->merge($this->getReference('user1')));
        $history->setBook($manager->merge($this->getReference('book2')));

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 4;
    }
}