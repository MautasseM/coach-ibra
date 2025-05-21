<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BlogPostFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $blogPost1 = new BlogPost();
        $blogPost1->setTitle('Test Blog Post 1');
        $blogPost1->setContent('This is the content of test blog post 1.');
        $manager->persist($blogPost1);

        $blogPost2 = new BlogPost();
        $blogPost2->setTitle('Test Blog Post 2');
        $blogPost2->setContent('This is the content of test blog post 2.');
        $manager->persist($blogPost2);

        $blogPost3 = new BlogPost();
        $blogPost3->setTitle('Test Blog Post 3');
        $blogPost3->setContent('This is the content of test blog post 3.');
        $manager->persist($blogPost3);

        $manager->flush();
    }
}
