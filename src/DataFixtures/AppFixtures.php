<?php

namespace App\DataFixtures;

use App\Factory\BookFactory;
use App\Factory\LoanFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Config\Security\ProviderConfig\Memory\UserConfig;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        UserFactory::new()
            ->many(5)
            ->create(function () {
                return [
                    'roles' => ['ROLE_USER'],
                ];
            });

        // Create 5 ROLE_LIBRARIAN
        UserFactory::new()
            ->many(5)
            ->create(function () {
                return [
                    'roles' => ['ROLE_LIBRARIAN'],
                ];
            });

        UserFactory::new()->create([
            'email' => "dhruvsolanki5604@gmail.com",
            'name' => 'dhruv',
            'roles' => ['ROLE_USER']
        ]);

        UserFactory::new()->create([
            'email' => "dhruvsolanki5604admin@gmail.com",
            'name' => 'admindhruv',
            'roles' => ['ROLE_LIBRARIAN']
        ]);

        BookFactory::new()->createMany(25);

        LoanFactory::new()->createMany(10);

        LoanFactory::new()->createMany(10, function () {
            return [
                "returnedAt" => \Faker\Factory::create()->dateTimeBetween('now', '+1 month'),
            ];
        });

        $manager->flush();
    }
}
