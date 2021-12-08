<?php

namespace App\DataFixtures;

use App\Entity\AboutMe;
use App\Entity\Illustration;
use App\Entity\Project;
use App\Entity\Techno;
use App\Entity\Timeline;
use App\Repository\TechnoRepository;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $slugger;

    public function __construct(Slugify $slugify){
        $this->slugger = $slugify;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        // AboutMe
        $aboutMe = new AboutMe();
        $aboutMe->setTitle('Olivier Maffre')
                ->setEmail('maffrolivier74@gmail.com')
                ->setGithublink('Olivier3231')
                ->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut imperdiet enim lacus, vel tincidunt leo malesuada a. Praesent et nibh elit. Vestibulum sit amet posuere mauris. Curabitur posuere tempor mauris, vitae sollicitudin urna. Donec consectetur consequat purus, vitae varius dolor commodo nec. Suspendisse eget erat luctus, rhoncus est a, tempus magna. Phasellus rhoncus commodo odio vel convallis.')
                ->setAvatar('https://picsum.photos/200');
                
        $manager->persist($aboutMe);        

        // Timeline
        $year = 2019;
        for ($i = 0; $i < 3; $i++) {
            $timeline = new Timeline();
            $timeline->setYear($year + $i)
                    ->setDescription($faker->paragraph(5));

            $manager->persist($timeline);

        }

        //Techno
        $technos = ['PHP', 'Javascript', 'Symfony', 'Bootstrap', 'WebPack Encore', 'Methode SCRUM'];
        $technosPersist = [];
        foreach ($technos as $techno) {
            $new = new Techno();
            $new->setName($techno);

            $manager->persist($new);
            $technosPersist[] = $new;
        }

        //Project
        for ($i =0; $i < 5; $i++) {
            $project = new Project();
            $project->setTitle($faker->sentence())
            ->setSlug($this->slugger->generate($project->getTitle()))
            ->setPitch($faker->paragraph(1))
            ->setDescription($faker->paragraph(3))
            ->addTechno($faker->randomElement($technosPersist))
            ->addTechno($faker->randomElement($technosPersist))
            ->addTechno($faker->randomElement($technosPersist))
            ->setGithublink($faker->domainName())
            ->setWebsiteLink($faker->domainName())
            ->setCreatedAt($faker->datetime())
            ->setIllustration('https://picsum.photos/500/300');

            for ($j = 0; $j < 5; $j++) {
                $illustration = new Illustration();
                $illustration->setImage('https://picsum.photos/500/300')
                ->setProject($project);
                $manager->persist($illustration);

                $project->addGallery($illustration);
            }

            $manager->persist($project);
        }


        $manager->flush();
    }
}
