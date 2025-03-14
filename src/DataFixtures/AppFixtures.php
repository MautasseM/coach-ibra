<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Service;
use App\Entity\Schedule;
use App\Entity\Location;
use DateTimeImmutable;
use App\Entity\Booking;
use App\Entity\Subscription;
use App\Entity\SubscriptionPlan;
use App\Entity\BlogPost;
use App\Entity\Testimonial;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Création des utilisateurs
        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->unique()->email)
                ->setPassword($this->passwordHasher->hashPassword($user, 'password'))
                ->setRoles(['ROLE_USER'])
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setPhone($faker->phoneNumber)
                ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTimeThisYear))
                ->setIsVerified($faker->boolean(80));

            $manager->persist($user);
            $users[] = $user;
        }

        // Création des services
        $services = [];
        for ($i = 0; $i < 5; $i++) {
            $service = new Service();
            $service->setName($faker->sentence(3))
                ->setSlug($faker->slug)
                ->setDescription($faker->paragraph)
                ->setPrice($faker->randomFloat(2, 10, 100))
                ->setDuration($faker->numberBetween(30, 120))
                ->setCategory($faker->randomElement(['adult', 'kid']))
                ->setMaxParticipants($faker->numberBetween(5, 20))
                ->setIsActive($faker->boolean(90));

            $manager->persist($service);
            $services[] = $service;
        }

        // Création des lieux
        $locations = [];
        for ($i = 0; $i < 3; $i++) {
            $location = new Location();
            $location->setName($faker->company)
                ->setAddress($faker->address)
                ->setCity($faker->city)
                ->setPostalCode($faker->postcode)
                ->setLatitude($faker->latitude)
                ->setLongitude($faker->longitude);

            $manager->persist($location);
            $locations[] = $location;
        }

        // Création des plannings
        $schedules = [];
        foreach ($services as $service) {
            for ($i = 0; $i < 3; $i++) {
                $schedule = new Schedule();
                $schedule->setService($service)
                    ->setDayOfWeek($faker->numberBetween(1, 7))
                    ->setStartTime($faker->time('H:i:s'))
                    ->setEndTime($faker->time('H:i:s'))
                    ->setLocation($faker->randomElement($locations))
                    ->setIsActive($faker->boolean(90));

                $manager->persist($schedule);
                $schedules[] = $schedule;
            }
        }

        // Création des réservations
        foreach ($users as $user) {
            for ($i = 0; $i < 2; $i++) {
                $booking = new Booking();
                $booking->setUser($user)
                    ->setSchedule($faker->randomElement($schedules))
                    ->setDate($faker->dateTimeBetween('-1 months', '+1 months'))
                    ->setStatus($faker->randomElement(['pending', 'confirmed', 'cancelled']))
                    ->setCreatedAt($faker->dateTimeThisYear);

                $manager->persist($booking);
            }
        }

        // Création des plans d'abonnement
        $plans = [];
        for ($i = 0; $i < 3; $i++) {
            $plan = new SubscriptionPlan();
            $plan->setName($faker->word)
                ->setDescription($faker->sentence)
                ->setPrice($faker->randomFloat(2, 20, 200))
                ->setDuration($faker->numberBetween(7, 30))
                ->setSessionsPerWeek($faker->numberBetween(1, 5))
                ->setCategory($faker->randomElement(['adult', 'kid']));

            $manager->persist($plan);
            $plans[] = $plan;
        }

        // Création des abonnements
        foreach ($users as $user) {
            $subscription = new Subscription();
            $subscription->setUser($user)
                ->setPlan($faker->randomElement($plans))
                ->setStartDate($faker->dateTimeBetween('-6 months', 'now'))
                ->setEndDate($faker->dateTimeBetween('now', '+6 months'))
                ->setStatus($faker->randomElement(['active', 'expired', 'cancelled']))
                ->setPaymentStatus($faker->randomElement(['paid', 'pending', 'failed']));

            $manager->persist($subscription);
        }

        // Création des articles de blog
        for ($i = 0; $i < 5; $i++) {
            $blogPost = new BlogPost();
            $blogPost->setTitle($faker->sentence)
                ->setSlug($faker->slug)
                ->setContent($faker->paragraphs(3, true))
                ->setImage($faker->imageUrl())
                ->setAuthor($faker->randomElement($users))
                ->setCreatedAt($faker->dateTimeThisYear)
                ->setIsPublished($faker->boolean(80));

            $manager->persist($blogPost);
        }

        // Création des témoignages
        for ($i = 0; $i < 5; $i++) {
            $testimonial = new Testimonial();
            $testimonial->setName($faker->name)
                ->setContent($faker->paragraph)
                ->setRating($faker->numberBetween(1, 5))
                ->setIsApproved($faker->boolean(90))
                ->setCreatedAt($faker->dateTimeThisYear);

            $manager->persist($testimonial);
        }

        $manager->flush();
    }
}
