<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Trick\Family;
use AppBundle\Entity\Trick\Image;
use AppBundle\Entity\Trick\Trick;
use AppBundle\Entity\Trick\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Fixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $familiesTricks = json_decode(file_get_contents($this->container->getParameter('data_fixtures')), true);
        foreach ($familiesTricks as $familyName => $family)
        {
            $familyIntro = $family['introduction'];
            $familyTricks = $family['tricks'];
            $familySlug = $family['slug'];

            # Création de la famille
            $familyEntity = new Family();
            $familyEntity->setName($familyName)->setIntroduction($familyIntro)->setSlug($familySlug);
            $manager->persist($familyEntity);
            $manager->flush();

            # Sauver les tricks
            foreach ($familyTricks as $trickName => $trick)
            {
                $trickIntro = $trick['introduction'];
                $trickSlug = $trick['slug'];
                $trickImage = $trick['image'];
                $trickVideo = $trick['video'];

                $trickEntity = new Trick();
                $trickEntity->setName($trickName)->setIntroduction($trickIntro)->setSlug($trickSlug)->setFamily($familyEntity);
                $manager->persist($trickEntity);
                $manager->flush();

                # Sauver la video
                $videoEntity = new Video();
                $videoEntity->setTrick($trickEntity);
                $videoEntity->setUrlOrIframe($trickVideo);
                $manager->persist($videoEntity);
                $manager->flush();

                # Sauver l'image
                $imageEntity = new Image();
                $trickImage = file_get_contents($trickImage);
                $imageName = "images/trick/" . sha1(uniqid()) . ".jpg";
                $dirWeb = $this->container->getParameter('kernel.project_dir') . '/web/';

                file_put_contents($dirWeb . $imageName, $trickImage);


                $imageEntity->setTrick($trickEntity);
                $imageEntity->setPathname($imageName);
                $manager->persist($imageEntity);
                $manager->flush();



            }

        }

    }
}