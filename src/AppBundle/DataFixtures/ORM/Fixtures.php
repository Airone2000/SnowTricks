<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Authentication\User;
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
        # Generate database
        file_put_contents($this->container->getParameter('database_path'), null);

        # Create one user
        $user1 = new User();
        $user2 = new User();
        $user1->setEmail('admin@snowtricks.com')->setPassword('password')->setNickname('SnowTricks')->setRoles(['ROLE_SUPER_ADMIN']);
        $user2->setEmail('maels1991@gmail.com')->setPassword('a')->setNickname('Erwan')->setRoles(['ROLE_SUPER_ADMIN']);
        $manager->persist($user1);
        $manager->persist($user2);
        $manager->flush();

        # Create families / tricks
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
            #$manager->flush();

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
                #$manager->flush();

                # Sauver la video
                $videoEntity = new Video();
                $videoEntity->setTrick($trickEntity);
                $videoEntity->setUrlOrIframe($trickVideo);
                $manager->persist($videoEntity);
                #$manager->flush();

                # Sauver l'image
                $imageEntity = new Image();
                $trickImage = file_get_contents($trickImage);
                $imageName = "images/trick/" . sha1(uniqid()) . ".jpg";
                $dirWeb = $this->container->getParameter('kernel.project_dir') . '/web/';
                file_put_contents($dirWeb . $imageName, $trickImage);

                $imageEntity->setTrick($trickEntity);
                $imageEntity->setPathname($imageName);
                $manager->persist($imageEntity);
            }

        }

        # Total flush !
        $manager->flush();
    }
}