<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Module;
use App\Entity\Programme;
use App\Entity\Session;
use App\Entity\Stagiaire;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user1 = new Stagiaire();
        $nom = $user1->setNom("pace");
        $prenom = $user1->setPrenom("gregory");
        $sexe = $user1->setSexe(1);
        $birth = $user1->setBirth(new \DateTime("20-03-1976"));
        $ville = $user1->setVille("strasbourg");
        $email = $user1->setEmail("gregory.pace@hotmail.fr");
        $phone = $user1->setPhone("0667371303");

        $manager->persist($user1);

        $user2 = new Stagiaire();
        $nom = $user2->setNom("muller");
        $prenom = $user2->setPrenom("claire");
        $sexe = $user2->setSexe(0);
        $birth = $user2->setBirth(new \DateTime("01-01-1981"));
        $ville = $user2->setVille("strasbourg");
        $email = $user2->setEmail("gregory.pace@hotmail.fr");
        $phone = $user2->setPhone("0667371303");

        $manager->persist($user2);

        $cat1 = new Categorie();
        $catNom = $cat1->setNom("bureautique");

        $manager->persist($cat1);

        $mod1 = new Module();

        $nomMod1 = $mod1->setNom("word");
        $catMod1 = $mod1->setCategorie($cat1);
        $manager->persist($mod1);
        $mod2 = new Module();

        $nomMod2 = $mod2->setNom("excel");
        $catMod2 = $mod2->setCategorie($cat1);
        $manager->persist($mod2);

        $sess1 = new Session();

        $nomSess1 = $sess1->setNom("inititaion word et excel");
        $debSess1 = $sess1->setDateDebut(new \DateTime("01/01/2020"));
        $finSess1 = $sess1->setDateFin(new \DateTime("01/03/2020"));
        $places1 = $sess1->setNbPlaces(12);

        $user1->addSession($sess1);
        $user2->addSession($sess1);

        $manager->persist($sess1);

        $prog1 = new Programme();

        $progmod1 = $prog1->setModule($mod1);
        $progmod1 = $prog1->setDuree("2 jours");
        $progsess1 = $prog1->setSession($sess1);

        $manager->persist($prog1);

        $prog2 = new Programme();
        $progmod2 = $prog2->setModule($mod2);
        $progmod2 = $prog2->setDuree("1 jour");
        $progsess2 = $prog2->setSession($sess1);

        $manager->persist($prog2);

        $manager->flush();
    }
}
