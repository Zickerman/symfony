<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Cocur\Slugify\Slugify;

class AppFixtures extends Fixture
{
	private $faker;
	private $slug;

	public function __construct(Slugify $slugify)
	{
		$this->faker = Factory::create();
		$this->slug = $slugify;
	}

	public function load(ObjectManager $manager): void
	{
		$this->loadPosts($manager);
	}

	public function loadPosts(ObjectManager $manager)
	{
		for ($i = 1; $i <= 20; $i++) {
			$post = new Post();

			$post->setTitle($this->faker->sentence(6));
			$post->setSlug($this->slug->slugify($post->getTitle()));
			$post->setBody($this->faker->paragraph(5));
			$post->setCreatedAt($this->faker->dateTimeThisYear);

			$manager->persist($post);
		}

		$manager->flush();
	}
}
