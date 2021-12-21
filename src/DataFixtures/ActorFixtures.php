<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Actor;

class ActorFixtures extends Fixture
{
    public const ACTORS = [ 
        'Andrew Lincoln',
        'Norman Reedus',
        'Lauren Cohan',
        'Danai Gurira',
        'Lesley-Ann Brandt'
    ];

    public function load(ObjectManager $manager): void
    {
        foreach( self::ACTORS as $key => $actorData ) { 
 
            $actor=new Actor; 
            $actor->setName($actorData);
            $manager->persist($actor);
            $this->addReference('actor_' . $key, $actor);
        }
        $manager->flush(); 
    }
}
