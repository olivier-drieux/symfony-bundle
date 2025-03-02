<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\ProjectMockup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProjectFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 100; $i++) {
            $project = new Project();
            $project->setName('Project '.$i);

            $manager->persist($project);

            for ($j = 0; $j < 10; $j++) {
                $projectMockup = new ProjectMockup();
                
                $projectMockup->setDomainName("domain$i$j.fr");
                $projectMockup->setTheme("Theme $i$j");
                $projectMockup->setLogin("Login $i$j");
                $projectMockup->setPassword("Password $i$j");
                $projectMockup->setImageZipPath("path/to/image$i$j.zip");
                $projectMockup->setData(['key' => 'value']);

                $projectMockup->setProject($project);

                $manager->persist($projectMockup);
            }
        }

        $manager->flush();
    }
}
