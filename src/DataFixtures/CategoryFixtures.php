<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const CATEGORIES = [
        'Action',
        'Aventure',
        'Animation',
        'Fantastique',
        'Horreur',
        'Humour',
        'Guerre',
    ];

    private Slugify $slugify;

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORIES as $categoryData) {
            $category = new Category();
            $category->setName($categoryData);

            $slug = $this->slugify->generate($category->getName());
            $category->setSlug($slug);

            $this->addReference('category_' . $categoryData, $category);
            $manager->persist($category);
        }
        $manager->flush();
    }
}