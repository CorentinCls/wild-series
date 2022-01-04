<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Episode;
use App\Service\Slugify;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public const EPISODES = [ 
        [ 
            "title"=>"Unaired Pilot", 
            "number"=>1, 
            "synopsis"=>"The first Pilot of what will become The Big Bang Theory. 
            Leonard and Sheldon are two awkward scientists who share an apartment. 
            They meet a drunk girl called Katie and invite her to stay at their place, 
            because she has nowhere to stay. The two guys have a female friend, also a scientist, called Gilda.",
            "seasonReference" => 'season_1'
        ],
        [ 
            "title"=>"Pilot", 
            "number"=>2, 
            "synopsis"=>"A pair of socially awkward theoretical physicists meet their new neighbor Penny, 
            who is their polar opposite.",
            "seasonReference" => 'season_1'
        ],
        [ 
            "title"=>"The Big Bran Hypothesis", 
            "number"=>3, 
            "synopsis"=>"Penny is furious with Leonard and Sheldon when they sneak into her apartment and 
            clean it while she is sleeping.",
            "seasonReference" => 'season_1'
        ],
        [ 
            "title"=>"Unaired Pilot", 
            "number"=>4, 
            "synopsis"=>"Leonard gets upset when he discovers that Penny is seeing a new guy, so he tries 
            to trick her into going on a date with him.",
            "seasonReference" => 'season_1'
        ],
        [ 
            "title"=>"Unaired Pilot", 
            "number"=>5, 
            "synopsis"=>"Sheldon's mother is called to intervene when he delves into numerous obsessions 
            after being fired for being disrespectful to his new boss.",
            "seasonReference" => 'season_1'
        ],
    ];

    private Slugify $slugify;

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager): void
    {
        foreach( self::EPISODES as $episodeData ) { 
 
            $episode=new Episode;  
            
            $episode->setTitle($episodeData['title']);  
            $episode->setnumber($episodeData['number']);  
            $episode->setSynopsis($episodeData['synopsis']);  

            $slug = $this->slugify->generate($episode->getTitle());
            $episode->setSlug($slug);

            $episode->setSeason($this->getReference($episodeData['seasonReference'])); 

            $manager->persist($episode); 

        } 
        $manager->flush(); 
    }

    public function getDependencies()
    {
        return [
          SeasonFixtures::class,
        ];
    }
}