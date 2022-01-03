<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Service\Slugify;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public const PROGRAMS = [
        [
            "title"=>"The Big Bang Theory",
            "summary"=>"Leonard Hofstadter et Sheldon Cooper vivent en colocation à Pasadena, ville de l'agglomération de Los Angeles. Ce sont tous deux des physiciens surdoués, « geeks » de surcroît. C'est d'ailleurs autour de cela qu'est axée la majeure partie comique de la série. Ils partagent quasiment tout leur temps libre avec leurs deux amis Howard Wolowitz et Rajesh Koothrappali pour jouer à des jeux vidéo comme Halo, organiser un marathon de la saga Star Wars, jouer à des jeux de société comme le Boggle klingon ou de rôles tel que Donjons et Dragons, voire discuter de théories scientifiques très complexes.Leur univers routinier est perturbé lorsqu'une jeune femme, Penny, s'installe dans l'appartement d'en face. Leonard a immédiatement des vues sur elle et va tout faire pour la séduire ainsi que l'intégrer au groupe et à son univers, auquel elle ne connaît rien.",
            "poster"=>"https://m.media-amazon.com/images/I/81ksNXITStL._AC_SX466_.jpg",
            "categoryReference" => 'category_Humour'
        ],
        [
            "title"=>"Spartacus",
            "summary"=>"Spartacus est une série péplum américaine en 39 épisodes créée par Steven S. DeKnight sur la vie du gladiateur Spartacus et diffusée du 22 janvier 2010 au 12 avril 2013 sur Starz1. Robert Tapert et Sam Raimi en sont les producteurs exécutifs. ",
            "poster"=>"http://jmj41.com/video/affiches/Filmotech_01937.jpg",
            "categoryReference" => 'category_Guerre'
        ],
        [
            "title"=>"Lucifer",
            "summary"=>"Lucifer est une série télévisée américaine créée par Tom Kapinos, adaptée du personnage de bandes dessinées",
            "poster"=>"https://fr.web.img4.acsta.net/pictures/15/11/10/13/35/055302.jpg",
            "categoryReference" => 'category_Fantastique'
        ],
        [
            "title"=>"Breaking Bad",
            "summary"=>"Breaking Bad, ou Breaking Bad : Le Chimiste1 au Québec, est une série télévisée américaine en 62 épisodes de 47 minutes, créée par Vince Gilligan, diffusée simultanément du 20 janvier 2008 au 29 septembre 2013 sur AMC aux États-Unis et au Canada, et ensuite sur Netflix. ",
            "poster"=>"http://www.asud.org/wp-content/uploads/2014/01/Breaking-bad.jpg",
            "categoryReference" => 'category_Fantastique'
        ],
        [
            "title"=>"The Walking Dead",
            "summary"=>"The Walking DeadNote 1 est une série télévisée d'horreur et dramatique américaine, adaptée par Frank Darabont et Robert Kirkman, créateur de la bande dessinée du même nom, aux États-Unis diffusée depuis le 31 octobre 2010 sur AMC1. ",
            "poster"=>"https://photos.tf1.fr/700/933/the-walking-dead-vignette_portrait-09f433-0@1x.webp",
            "categoryReference" => 'category_Horreur'
        ],

    ];

    private Slugify $slugify;

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::PROGRAMS as $programData) {
            $program=new Program();

            $program->setTitle($programData['title']);
            $program->setSummary($programData['summary']);
            $program->setPoster($programData['poster']);

            $slug = $this->slugify->generate($program->getTitle());
            $program->setSlug($slug);

            //Crée une référence avec le titre du programe
            $this->addReference('program_' . $programData['title'], $program);

            //utilise la référence des catégories de l'entité category
            $program->setCategory($this->getReference($programData['categoryReference']));

            //utilise la référence de l'entité actor et l'ajoute uniquement sur le program the walking dead
            if (preg_match("/the walking dead/i", $programData['title'])) {
                foreach (ActorFixtures::ACTORS as $i => $actorData) {
                    $program->addActor($this->getReference('actor_' . $i));
                }
            }
            $manager->persist($program);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ActorFixtures::class,
            CategoryFixtures::class,
        ];
    }
}