<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Actor;
use App\Service\Slugify;

class ActorFixtures extends Fixture
{
    public const ACTORS = [ 
        'Andrew Lincoln',
        'Norman Reedus',
        'Lauren Cohan',
        'Danai Gurira',
        'Lesley-Ann Brandt'
    ];

    private Slugify $slugify;

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager): void
    {
        foreach( self::ACTORS as $key => $actorData ) { 
 
            $actor=new Actor; 
            $actor->setName($actorData);
            $manager->persist($actor);

            $slug = $this->slugify->generate($actor->getName());
            $actor->setSlug($slug);

            $this->addReference('actor_' . $key, $actor);
        }
        $manager->flush(); 
    }
}
