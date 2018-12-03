<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 03.12.18 20:53
 */

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class BookFixtures
 *
 * @package App\DataFixtures
 */
class BookFixtures extends Fixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        $book1 = new Book([
            'isbn' => 9780132350884,
            'title' => 'Clean Code: A Handbook of Agile Software Craftsmanship.',
            'author' => 'Uncle Bob',
            'description' => 'Clean Code is divided into three parts. The first describes the principles, patterns, and 
            practices of writing clean code. The second part consists of several case studies of increasing complexity.
            Each case study is an exercise in cleaning up code—of transforming a code base that has some problems into 
            one that is sound and efficient. The third part is the payoff: a single chapter containing a list of heuristics 
            and “smells” gathered while creating the case studies. The result is a knowledge base that describes the way 
            we think when we write, read, and clean code.',
            'available' => false,
        ]);

        $book2 = new Book([
            'isbn' => 9780135974445,
            'title' => 'Agile Software Development: Principles, Patterns, and Practices.',
            'author' => 'Uncle Bob',
            'description' => 'Written by a software developer for software developers, this book is a unique collection 
            of the latest software development methods. The author includes OOD, UML, Design Patterns, Agile and XP 
            methods with a detailed description of a complete software design for reusable programs in C++ and Java. 
            Using a practical, problem-solving approach, it shows how to develop an object-oriented application—from the 
            early stages of analysis, through the low-level design and into the implementation. Walks readers through the 
            designer\'s thoughts — showing the errors, blind alleys, and creative insights that occur throughout the 
            software design process. The book covers: Statics and Dynamics; Principles of Class Design; Complexity 
            Management; Principles of Package Design; Analysis and Design; Patterns and Paradigm Crossings.',
            'available' => true,
        ]);

        $manager->persist($book1);
        $manager->persist($book2);

        $this->addReference('book1', $book1);
        $this->addReference('book2', $book2);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}