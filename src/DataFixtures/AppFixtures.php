<?php

namespace App\DataFixtures;

use App\Factory\BookFactory;
use App\Factory\LoanFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create();

        UserFactory::new()
            ->many(5)
            ->create(function () {
                return [
                    'roles' => ['ROLE_USER'],
                ];
            });

        UserFactory::new()
            ->many(5)
            ->create(function () {
                return [
                    'roles' => ['ROLE_LIBRARIAN'],
                ];
            });

        $user1 = UserFactory::new()->create([
            'email' => "21itusf129@ddu.ac.in",
            'name' => 'dhruv',
            'roles' => ['ROLE_USER']
        ]);

        $librarian = UserFactory::new()->create([
            'email' => "dhruvsolanki5604admin@gmail.com",
            'name' => 'admindhruv',
            'roles' => ['ROLE_LIBRARIAN']
        ]);

        $books = BookFactory::new()->many(25)->create();
        LoanFactory::new()->createMany(10);

        LoanFactory::new()->createMany(10, function () use ($faker) {
            return [
                "returnedAt" => $faker->dateTimeBetween('now', '+1 month'),
            ];
        });

        foreach (array_slice($books, 0, 2) as $book) {
            LoanFactory::new()->create([
                'user' => $user1,
                'book' => $book,
                'loanedAt' => $faker->dateTimeBetween('-35 days', '-30 days'),
                'dueAt' => $faker->dateTimeBetween('-28 days', '-26 days'),
                'returnedAt' => null,
            ]);
        }

        $user2 = UserFactory::new()->create([
            'email' => 'meetvisodiya5@gmail.com',
            'name' => 'Meet Visodiya',
            'roles' => ['ROLE_USER'],
        ]);

        LoanFactory::new()->create([
            'user' => $user1,
            'book' => $books[2],
            'loanedAt' => $faker->dateTimeBetween('-35 days', '-30 days'),
            'dueAt' => $faker->dateTimeBetween('-28 days', '-26 days'),
            'returnedAt' => null,
        ]);

        LoanFactory::new()->create([
            'user' => $user2,
            'book' => $books[3],
            'loanedAt' => $faker->dateTimeBetween('-15 days', '-10 days'),
            'dueAt' => $faker->dateTimeBetween('-5 days', '+5 days'),
            'returnedAt' => null,
        ]);

        $manager->flush();
    }
}
