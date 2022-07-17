<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    public const BOOKS = [
        [
            'name' => 'First book',
            'year' => 1960,
        ],
        [
            'name' => 'Second book',
            'year' => 1982,
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::BOOKS as $book) {
            $name = $book['name'] ?? 'Default name';
            $year = $book['year'] ?? 1980;

            $book = $this->createBook($name, $year);

            $manager->persist($book);
        }

        $manager->flush();
    }

    protected function createBook(string $name, int $year): Book
    {
        return (new Book())
            ->setName($name)
            ->setYear($year);
    }
}
