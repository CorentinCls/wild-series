<?php

namespace App\DataFixtures;

use App\Entity\Season;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public const SEASONS = [ 
        [
            "number"=>1,
            "year"=>2007, 
            "description"=>"Season 1",
            "programReference" => 'program_The Big Bang Theory'
        ],
        [ 
            "number"=>2, 
            "year"=>2008, 
            "description"=>"Season 2",
            "programReference" => 'program_The Big Bang Theory'
        ],
        [
            "number"=>3, 
            "year"=>2009, 
            "description"=>"Season 3",
            "programReference" => 'program_The Big Bang Theory'
        ],
        [ 
            "number"=>4, 
            "year"=>2010, 
            "description"=>"Season 4",
            "programReference" => 'program_The Big Bang Theory'
        ],
        [ 
            "number"=>5, 
            "year"=>2011, 
            "description"=>"Season 5",
            "programReference" => 'program_The Big Bang Theory'
        ],
    ];

    private Slugify $slugify;

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager): void
    {
        foreach( self::SEASONS as $seasonData ) { 
 
            $season = new Season();  

            $season->setNumber($seasonData['number']);  
            $season->setYear($seasonData['year']);  
            $season->setDescription($seasonData['description']);  

            $slug = $this->slugify->generate($season->getNumber());
            $season->setSlug($slug);

            $season->setProgram($this->getReference($seasonData['programReference'])); 
            $this->addReference('season_' . $seasonData['number'], $season);
            $manager->persist($season); 
 
        } 
        $manager->flush(); 
    }

    public function getDependencies()
    {
        return [
          ProgramFixtures::class,
        ];
    }
}