<?php

namespace App\DataFixtures;

use App\Entity\Consommable;
use App\Entity\ZoneAdministrative;
use App\Entity\Methode;
use App\Entity\Taille;
use App\Entity\Type;
use App\Entity\State;
use App\Entity\User;
use App\Entity\Flux;
use App\Entity\Like;
use App\Entity\Commentaire;
use App\Entity\Photo;
use App\Entity\Plant;
use App\Entity\Recolte;
use App\Entity\Panier;
use App\Entity\Post;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface $encoder;

    /** @var Consommable[] */
    private array $consommables = [];

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {

        // Liste de la surface de l'utilisateur - Entity Taille
        $tailles['pot'] = new Taille('Pot', '5m2', 5);
        $tailles['interieur'] = new Taille('Intérieur', '10m2', 10);
        $tailles['balcon'] = new Taille('Balcon', '10m2', 10);
        $tailles['terrasse'] = new Taille('Terrasse', '30m2', 30);
        $tailles['petitjardin'] = new Taille('Petit Jardin', '50m2', 50);
        $tailles['moyenjardin'] = new Taille('Moyen Jardin', '100m2', 100);
        $tailles['grandjardin'] = new Taille('Grand Jardin', '200m2', 200);

        foreach ($tailles as $taille) {
            $manager->persist($taille);
        }

        $zones['FR-01'] = new ZoneAdministrative('Zone Continentale : Est et Nord-Est', 'Ardennes');
        $zones['FR-02'] = new ZoneAdministrative('Zone Continentale : Est et Nord-Est', 'Alsace');
        $zones['FR-03'] = new ZoneAdministrative('Zone Continentale : Est et Nord-Est', 'Lorraine');
        $zones['FR-04'] = new ZoneAdministrative('Zone Continentale : Est et Nord-Est', 'Haute Saône');
        $zones['FR-10'] = new ZoneAdministrative('Zone Méditerranéenne : Sud-Est', 'Corse');
        $zones['FR-11'] = new ZoneAdministrative('Zone Méditerranéenne : Sud-Est', 'Provence');
        $zones['FR-12'] = new ZoneAdministrative('Zone Méditerranéenne : Sud-Est', 'Rousillon');
        $zones['FR-13'] = new ZoneAdministrative('Zone Méditerranéenne : Sud-Est', 'Côtes méditerranéennes');
        $zones['FR-20'] = new ZoneAdministrative('Zone Océanique', 'Ouest bordant la Manche et Océan Atlantique');
        $zones['FR-30'] = new ZoneAdministrative('Zone Semi-océanique', 'Centre de la France');
        $zones['FR-30'] = new ZoneAdministrative('Zone Montagnarde', 'Alpes');
        $zones['FR-31'] = new ZoneAdministrative('Zone Montagnarde', 'Massif Central');
        $zones['FR-32'] = new ZoneAdministrative('Zone Montagnarde', 'Pyrénées');

        foreach ($zones as $zone) {
            $manager->persist($zone);
        }

        // Liste de l'état de la plantation - Entity State
        $states['semis'] = new State('Semis');
        $states['plant'] = new State('Plant');

        foreach ($states as $state) {
            $manager->persist($state);
        }

        // Liste des consommables - Entity Consommable
        $this->loadConsommables($manager);

        // Liste des méthode de culture pour les récoltes - Entity Méthode
        $methodes['butte'] = new Methode('Butte', 'method_butte.png');
        $methodes['lasagnes'] = new Methode('Lasagnes', 'method_pleine_terre.png');
        $methodes['jardiniere'] = new Methode('Jardinière / Pot', 'method_jardiniere_pot.png');
        $methodes['cueillette'] = new Methode('Cueillette sauvage', 'method_cueillette.png');
        $methodes['serre'] = new Methode('Serre', 'method_serre.png');
        $methodes['hydro'] = new Methode('Hydro/Aquaponie', 'method_aquaponie.png');
        $methodes['autre'] = new Methode('Autres', 'method_serre.png');

        foreach ($methodes as $methode) {
            $manager->persist($methode);
        }

        // Liste des types de dépenses - Entity Type
        $types['construction'] = new Type('Construction');
        $types['arrosage'] = new Type('Système d\'arrosage');
        $types['paillage'] = new Type('Paillage');
        $types['sol'] = new Type('Enrichissement sol');
        $types['graines'] = new Type('Graines');
        $types['plantons'] = new Type('Plantons');
        $types['eau'] = new Type('Eau');
        $types['traitement'] = new Type('Traitement');
        $types['energie'] = new Type('Energie');

        foreach ($types as $type) {
            $manager->persist($type);
        }

        // USER 1

        $user1 = new User();
        $user1->setEmail('camille@example.com');
        $user1->setNickname('Camille');
        $user1->setRoles(['ROLE_ADMIN']);
        $user1->setAge(31);
        $user1->setTelephone('+33 6 72 54 41 29');
        $user1->setPassword($this->encoder->hashPassword($user1, 'admin'));
        $photouser1 = new Photo();
        $photouser1->setImageName("th.jpg");
        $photouser1->setImageSize(236);
        $user1->setAvatar($photouser1);

        //Garden1
        $garden1 = new Garden();
        $garden1->setRegion($zones['FR-12']);
        $garden1->setSurface($tailles['jardin']);
       // $garden1->setUser($user1);
        
        $manager->persist($user1);
        $manager->persist($garden1);
        $manager->persist($photouser1);


        // Flux et panier du User1
        $fluxuser1 = new Flux();
        $fluxuser1->setCreatedat(new DateTime());
        $fluxuser1->setUpdatedat(new DateTime());
        $fluxuser1->setUser($user1);
        $fluxuser1->setShared(True);
        $panieruser1 = new Panier();
        $panieruser1->setDescription('Mes plantations et mes récoltes');
        $panieruser1->setCreatedat(new DateTime());
        $panieruser1->setUpdatedat(new DateTime());
        $panieruser1->setTitre('Mon panier');
        $panieruser1->setShared(false);
        $panieruser1->setFlux($fluxuser1);
        $photo30 = new Photo();
        $photo30->setPanier($panieruser1);
        $photo30->setImageSize(236);
        $photo30->setImageName("panieruser1.JPG");
        
        $manager->persist($fluxuser1);
        $manager->persist($panieruser1);
        $manager->persist($photo30);


        // USER 2
        $user2 = new User();
        $user2->setEmail('John@example.com');
        $user2->setNickname('John');
        $user2->setRoles(['ROLE_ADMIN']);
        $user2->setAge(25);
        $user2->setTelephone('0400000000');
        $user2->setPassword($this->encoder->hashPassword($user2, 'admin'));

        $photouser2 = new Photo();
        $photouser2->setImageName("th2.jpg");
        $photouser2->setImageSize(236);
        $user2->setAvatar($photouser2);
        
        //Garden2
        $garden2 = new Garden();
        $garden2->setRegion($zones['FR-20']);
        $garden2->setSurface($tailles['balcon']);
       // $garden2->setUser($user2);
        
        $manager->persist($user2);
        $manager->persist($garden2);
        $manager->persist($photouser2);

        // Flux et panier du User2
        $fluxuser2 = new Flux();
        $fluxuser2->setCreatedat(new DateTime());
        $fluxuser2->setUpdatedat(new DateTime());
        $fluxuser2->setUser($user2);
        $fluxuser2->setShared(True);
        $panieruser2 = new Panier();
        $panieruser2->setDescription('Mes plantations et mes récoltes');
        $panieruser2->setCreatedat(new DateTime());
        $panieruser2->setUpdatedat(new DateTime());
        $panieruser2->setTitre('Mon panier');
        $panieruser2->setShared(false);
        $panieruser2->setFlux($fluxuser2);
        $photo350 = new Photo();
        $photo350->setPanier($panieruser2);
        $photo350->setImageSize(236);
        $photo350->setImageName("panieruser2.JPG");

        $manager->persist($fluxuser2);
        $manager->persist($panieruser2);
        $manager->persist($photo350);


        // USER 3
        $user3 = new User();
        $user3->setEmail('doe@example.com');
        $user3->setNickname('Doe');
        $user3->setRoles(['ROLE_ADMIN']);
        $user3->setAge(32);
        $user3->setTelephone('0400000000');
        $user3->setPassword($this->encoder->hashPassword($user3, 'admin'));

        $photouser3 = new Photo();
        $photouser3->setImageName("th3.jpg");
        $photouser3->setImageSize(236);
        $user3->setAvatar($photouser3);

        //Garden3
        $garden2 = new Garden();
        $garden2->setRegion($zones['FR-30']);
        $garden2->setSurface($tailles['grandjardin']);
        //$garden2->setUser($user2);
        
        $manager->persist($user3);
        $manager->persist($garden3);
        $manager->persist($photouser3);

        // Flux et Panier Doe
        $fluxuser3 = new Flux();
        $fluxuser3->setCreatedat(new DateTime());
        $fluxuser3->setUpdatedat(new DateTime());
        $fluxuser3->setUser($user3);
        $fluxuser3->setShared(True);
        $panieruser3 = new Panier();
        $panieruser3->setDescription('Mes plantations et mes récoltes');
        $panieruser3->setCreatedat(new DateTime());
        $panieruser3->setUpdatedat(new DateTime());
        $panieruser3->setTitre('Mon panier');
        $panieruser3->setShared(False);
        $panieruser3->setflux($fluxuser3);
        $photo32 = new Photo();
        $photo32->setPanier($panieruser3);
        $photo32->setImageSize(236);
        $photo32->setImageName("panieruser3.JPG");

        $manager->persist($fluxuser3);
        $manager->persist($panieruser3);
        $manager->persist($photo32);



// ELEMENTS 

 
 
         // Camille, note2 avec photo
         $fluxuser13 = new Flux();
         $fluxuser13->setCreatedat(new DateTime());
         $fluxuser13->setUpdatedat(new DateTime());
         $fluxuser13->setUser($user1);
         $fluxuser13->setShared(True);
         $postuser12 = new post();
         $postuser12->setDescription('Récolte du jour');
         $postuser12->setCreatedat(new DateTime());
         $postuser12->setShared(True);
         $postuser12->setFlux($fluxuser13);
         $photo230 = new Photo();
         $photo230->setPost($postuser12);
         $photo230->setImageSize(236);
         $photo230->setImageName("noteuser12.JPG");
 
         $manager->persist($fluxuser13);
         $manager->persist($postuser12);
         $manager->persist($photo230);




        // John, plantation
        $plantuser2 = new Plant();
        $plantuser2->setUser($user2);
        $plantuser2->setQuantite(3);
        $plantuser2->setDescription("Semis de tomates");
        $plantuser2->setState($states['semis']);
        $plantuser2->setConsommable($this->consommables['Tomate cerise']);
        $plantuser2->setCreatedat(new DateTime());
        $plantuser2->setPanier($panieruser2);

        $manager->persist($plantuser2);


        //Doe, récolte haricot
        $recolteuser3 = new recolte();
        $recolteuser3->setUser($user3);
        $recolteuser3->setQuantity(3);
        $recolteuser3->setMethode($methodes['butte']);
        $recolteuser3->setConsommable($this->consommables['Haricot']);
        $recolteuser3->setCreatedat(new DateTime());
        $recolteuser3->setPanier($panieruser3);

        $manager->persist($recolteuser3);

         // Camille, récolte tomate
         $recolte1 = new recolte();
         $recolte1->setUser($user1);
         $recolte1->setQuantity(5);
         $recolte1->setMethode($methodes['serre']);
         $recolte1->setConsommable($this->consommables['Tomate']);
         $recolte1->setCreatedat(new DateTime());
         $recolte1->setPanier($panieruser1);
         $comment2 = new Commentaire();
         $comment2->setcontenu('Super!');
         $comment2->setdateHeureCreation(new DateTime());
         $comment2->setuser($user1);
         $comment2->setflux($fluxuser1);
 
         $manager->persist($recolte1);
         $manager->persist($comment2);   
         
         // Camille, plantation salade
         $plantuser1 = new plant();
         $plantuser1->setUser($user1);
         $plantuser1->setQuantite(2);
         $plantuser1->setConsommable($this->consommables['Salade']);
         $plantuser1->setDescription("plant de salades");
         $plantuser1->setState($states['plant']);
         $plantuser1->setCreatedat(new DateTime());
         $plantuser1->setPanier($panieruser1);
 
         $manager->persist($plantuser1);

        // Doe, note avec photo
        $fluxuser31 = new Flux();
        $fluxuser31->setCreatedat(new DateTime());
        $fluxuser31->setUpdatedat(new DateTime());
        $fluxuser31->setUser($user3);
        $fluxuser31->setShared(True);
        $postuser3 = new post();
        $postuser3->setDescription('tuteurs pour haricots à rames');
        $postuser3->setCreatedat(new DateTime());
        $postuser3->setShared(true);
        $postuser3->setFlux($fluxuser31);
        $photo40 = new Photo();
        $photo40->setPost($postuser3);
        $photo40->setImageSize(236);
        $photo40->setImageName("noteuser3.jpg");

        $manager->persist($fluxuser31);
        $manager->persist($postuser3);
        $manager->persist($photo40);


         // Camille, note avec photo
         $fluxuser12 = new Flux();
         $fluxuser12->setCreatedat(new DateTime());
         $fluxuser12->setUpdatedat(new DateTime());
         $fluxuser12->setUser($user1);
         $fluxuser12->setShared(True);
         $postuser1 = new post();
         $postuser1->setDescription('Culture hors-sol');
         $postuser1->setCreatedat(new DateTime());
         $postuser1->setShared(True);
         $postuser1->setFlux($fluxuser12);
         $photo200 = new Photo();
         $photo200->setPost($postuser1);
         $photo200->setImageSize(236);
         $photo200->setImageName("noteuser1.jpg");
 
         $manager->persist($fluxuser12);
         $manager->persist($postuser1);
         $manager->persist($photo200);  
         
         
        // John, note avec photo
        $fluxuser22 = new Flux();
        $fluxuser22->setCreatedat(new DateTime());
        $fluxuser22->setUpdatedat(new DateTime());
        $fluxuser22->setUser($user2);
        $fluxuser22->setShared(True);
        $postuser2 = new post();
        $postuser2->setDescription('Merci merci les insectes');
        $postuser2->setCreatedat(new DateTime());
        $postuser2->setShared(True);
        $postuser2->setFlux($fluxuser22);
        $photo10 = new Photo();
        $photo10->setPost($postuser2);
        $photo10->setImageSize(236);
        $photo10->setImageName("noteuser2.JPG");
        $comment1 = new Commentaire();
        $comment1->setcontenu('Cool!');
        $comment1->setdateHeureCreation(new DateTime());
        $comment1->setuser($user1);
        $comment1->setflux($fluxuser22);
        $like1 = new Like();
        $like1->setFlux($fluxuser22);
        $like1->setUser($user1);
        $like2 = new Like();
        $like2->setFlux($fluxuser22);
        $like2->setUser($user2);

        $manager->persist($fluxuser22);
        $manager->persist($postuser2);
        $manager->persist($photo10);
        $manager->persist($comment1);
        $manager->persist($like1);
        $manager->persist($like2);

        $manager->flush();
    
    }


    // Fonction pour ajouter la Liste des consommbales
    private function loadConsommables(ObjectManager $manager): void
    {
        // Level 0 - Root
        $this->consommables['ESPECES'] = $root = $this->createConsommable('ESPECES');

        // Level 1
        $this->consommables['Fruits'] = $fruits = $this->createConsommable('Fruits', $root);
        $this->consommables['Légumes'] = $legumes = $this->createConsommable('Légumes', $root);
        $this->consommables['Aromates'] = $aromates = $this->createConsommable('Aromates', $root);


        // Level 2 Fruits

        $this->consommables['Abricot'] = $abricot = $this->createConsommable('Abricot', $fruits, 'Abricots', 'ab.png', "ab_1.png","ab_2.png","ab_3.png",3.4, 430);
        $this->consommables['Agrume'] = $agrume = $this->createConsommable('Agrume', $fruits, 'Agrumes', 'citron.png', "citron_1.png", "citron_2.png", "citron_3.png", 1.56, 350);
        $this->consommables['Asimine'] = $asimine = $this->createConsommable('Asimine', $fruits, 'Asimines', 'as.png', "as_1.png", "as_2.png", "as_3.png", 1.75 , 800);
        $this->consommables['Baie'] = $baie = $this->createConsommable('Baie', $fruits, 'Baies', 'autre.png', 'autre_1.png', "autre_2.png", "autre_3.png", 54, 350);
        $this->consommables['Cassis'] = $cassis = $this->createConsommable('Cassis', $fruits, 'Cassis', 'cassis.png', "cassise_1.png", "cassise_2.png", "cassise_3.png", 28, 350);
        $this->consommables['Chataigne'] = $chataigne = $this->createConsommable('Chataigne', $fruits, 'Chataignes', 'chat.png', "chat_1.png", "chat_2.png", "chat_3.png", 7.21, 1330);
        $this->consommables['Cerise'] = $cerise = $this->createConsommable('Cerise', $fruits, 'Cerises', 'cerise.png', "cerise_1.png", "cerise_2.png", "cerise_3.png", 6, 500);
        $this->consommables['Figue'] = $figue = $this->createConsommable('Figue', $fruits, 'Figues', 'fi.png', "fi_1.png", "fi_2.png", "fi_3.png", 8.26, 1070);
        $this->consommables['Fraise'] = $fraise = $this->createConsommable('Fraise', $fruits, 'Fraises', 'fraise.png', "fraise_1.png", "fraise_2.png", "fraise_3.png", 7.6, 320);
        $this->consommables['Framboise'] = $framboise = $this->createConsommable('Framboise', $fruits, 'Framboises', 'framboise.png', "framboise_1.png", "framboise_2.png", "framboise_3.png", 48, 360);
        $this->consommables['Groseille'] = $groseille = $this->createConsommable('Groseille', $fruits, 'Groseilles', 'groseilles.png', "","","",30, 560);
        $this->consommables['Kakie'] = $kaki = $this->createConsommable('Kakie', $fruits, 'Kakies', 'ka.png', "","","",3.71, 680);
        $this->consommables['Kiwi'] = $kiwi = $this->createConsommable('Kiwi', $fruits, 'Kiwis', 'ki.png', "","","",2.4, 610);
        $this->consommables['Mangue'] = $mangue = $this->createConsommable('Mangue', $fruits, 'Mangue', "default.png", "","","", 18.1, 660);
        $this->consommables['Melon'] = $melon = $this->createConsommable('Melon', $fruits, 'Melons', 'mel.png', "","","",1.6, 420);
        $this->consommables['Mirabelle'] = $mirabelle = $this->createConsommable('Mirabelle', $fruits, 'Mirabelle', 'mi.png', "","","",4.7,470);
        $this->consommables['Mure'] = $mure = $this->createConsommable('Mure', $fruits, 'Mure', 'mu.png', "","","",27.11,430);
        $this->consommables['Myrtille'] = $myrtille = $this->createConsommable('Myrtille', $fruits, 'Myrtilles', 'my.png', "","","",16.15,350);
        $this->consommables['Noisette'] = $noisette = $this->createConsommable('Noisette', $fruits, 'Noisette', 'noi.png', "","","",4.96,6280);
        $this->consommables['Noix'] = $noix = $this->createConsommable('Noix', $fruits, 'Noix', 'noix.png', "","","",4.45,6540);
        $this->consommables['Pastèque'] = $pasteque = $this->createConsommable('Pastèque', $fruits, 'Pastèques', 'pas.png', "","","",1.45,300);
        $this->consommables['Pèche'] = $peche = $this->createConsommable('Pèche', $fruits, 'Pèches', 'pe.png', "","","",3.67,410);
        $this->consommables['Poire'] = $poire = $this->createConsommable('Poire', $fruits, 'Poires', 'poire.png', "","","",2.23,550);
        $this->consommables['Pomme'] = $pomme = $this->createConsommable('Pomme', $fruits, 'Pommes', 'pomm.png', "","","",1.05,520);
        $this->consommables['Prune'] = $prune = $this->createConsommable('Prune', $fruits, 'Prunes', 'pru.png', "","","",4.2,470);
        $this->consommables['Quetshe'] = $quetshe = $this->createConsommable('Quetshe', $fruits, 'Quetshes', 'que.png', "","","",1.68,470);
        $this->consommables['Raisin'] = $raison = $this->createConsommable('Raisin', $fruits, 'Raisins', 'rai.png', "","","",3.1,700);
        $this->consommables['Rhubarbe'] = $rhubarbe = $this->createConsommable('Rhubarbe', $fruits, 'Rhubarbes', 'rhu.png', "","","",3.9,210);

        // Level 3 fruits
        $this->consommables['Citron'] = $citron = $this->createConsommable('Citron', $agrume, 'Citrons', 'citron.png', "","","",1.56,350);
        $this->consommables['Kunquat'] = $kunquat = $this->createConsommable('Kunquat', $agrume, 'Kunquats', 'citron.png', "","","",1.42,710);
        $this->consommables['Orange'] = $orange = $this->createConsommable('Orange', $agrume, 'Oranges', 'ora.png', "","","",1.4,550);
        $this->consommables['Mandarine'] = $mandarine = $this->createConsommable('Mandarine', $agrume, 'Mandarines', 'citron.png', "","","",1.94,500);
        $this->consommables['Pamplemousse'] = $pamplemousse = $this->createConsommable('Pamplemousse', $agrume, 'Pamplemousses', 'citron.png', "","","",3.35,500);

        $this->consommables['Amarelle'] = $amarelle = $this->createConsommable('Amarelle', $cerise, 'Amarelles', 'cerise.png', "","","",6,500);
        $this->consommables['Bigarreaux'] = $bigarreaux = $this->createConsommable('Bigarreaux', $cerise, 'Bigarreaux', 'cerise.png', "","","",9,500);
        $this->consommables['Guigne'] = $guigne = $this->createConsommable('Guigne', $cerise, 'Guignes', 'cerise.png', "","","",5,500);
        $this->consommables['Griotte'] = $griotte = $this->createConsommable('Griotte', $cerise, 'Griottes', 'cerise.png', "","","",5.1,500);

        $this->consommables['Charlotte'] = $charlotte = $this->createConsommable('Charlotte', $fraise, 'Charlottes', 'fraise.png', "","","",7.6, 320);
        $this->consommables['Gariguette'] = $guariguette = $this->createConsommable('Guariguette', $fraise, 'Guariguettes', 'fraise.png', "","","",14,320);
        $this->consommables['Mara'] = $mara = $this->createConsommable('Mara des bois', $fraise, 'Mara des bois', 'fraise.png', "","","",14,320);

        $this->consommables['Remonante'] = $remonante = $this->createConsommable('Framboise remontante', $framboise, 'Framboises remontantes', 'framboise.png', "","","",48,360);
        $this->consommables['Nremaontante'] = $nremaontante = $this->createConsommable('Framboise non remontante', $framboise, 'Framboises non remontantes', 'framboise.png', "","","",48,360);

        $this->consommables['Blanche'] = $blanche = $this->createConsommable('Groseille blanche', $groseille, 'Groseilles blanches', 'groseilles.png', "","","",30,560);
        $this->consommables['Maquereaux'] = $maquereaux = $this->createConsommable('Groseille maquereaux', $groseille, 'Groseilles maquereaux', 'groseilles.png', "","","",13.35,560);
        $this->consommables['Rouges'] = $rouge = $this->createConsommable('Groseille rouge', $groseille, 'Groseilles blanches', 'groseilles.png', "","","",28,560);

        $this->consommables['JauneCanari'] = $jaune = $this->createConsommable('Melon jaune/canari', $melon, 'Melons jaunes/canaris', 'mel.png', "","","",1.6,420);
        $this->consommables['Vert'] = $vert = $this->createConsommable('Melon vert', $melon, 'Melons verts', 'mel.png', "","","",2.3,420);

        // Level 2 Legumes
        $this->consommables['Ail'] = $ail = $this->createConsommable('Ail', $legumes, 'Ails', 'ail.png', "","","",8.7,1110);
        $this->consommables['Artichaut'] = $artichaud = $this->createConsommable('Artichaut', $legumes, 'Artichauts', 'arti.png', "arti_1.png", "arti_2.png", "arti_3.png",3.08,470);
        $this->consommables['Aubergine'] = $aubergine = $this->createConsommable('Aubergine', $legumes, 'Aubergines', 'aub.png', "aub_1.png", "aub_2.png", "aub_3.png",3.73,240);
        $this->consommables['Betterave'] = $betterave = $this->createConsommable('Betterave', $legumes, 'Betteraves', 'bett.png', "bett_1.png", "bett_2.png", "bett_3.png",2.08,430);
        $this->consommables['Blette'] = $blette = $this->createConsommable('Blette', $legumes, 'Blettes', 'blette.png', "","","",2.79,190);
        $this->consommables['Brocoli'] = $brocoli = $this->createConsommable('Brocoli', $legumes, 'Brocolis', 'bro.png', "bro_1.png", "bro_2.png", "bro_3.png",3.15,350);
        $this->consommables['Canneberge'] = $canneberge = $this->createConsommable('Canneberge', $legumes, 'Canneberge', 'cel.png', "","","",6.9,460);
        $this->consommables['Carotte'] = $carotte = $this->createConsommable('Carotte', $legumes, 'Carottes', 'caro.png', "car_1.png", "car_2.png", "car_3.png", 1.45,360);
        $this->consommables['Céleri'] = $Celeri = $this->createConsommable('Céleri', $legumes, 'Céleris', 'cel.png', "cel_1.png", "cel_2.png", "cel_3.png",2.26,176);
        $this->consommables['Champignons'] = $champignon = $this->createConsommable('Champignon', $legumes, 'Champignons', 'champi.png', "","","",0,220);
        $this->consommables['Choux'] = $Choux = $this->createConsommable('Chou', $legumes, 'Choux', 'chouf.png', "chouf_1.png", "chouf_2.png", "chouf_3.png",6.83,430);
        $this->consommables['Concombre'] = $concombre = $this->createConsommable('Concombre', $legumes, 'Concombres', 'concon.png', "conco_1.png", "conco_2.png", "conco_3.png",0.85,150);
        $this->consommables['Cornichon'] = $cornichon = $this->createConsommable('Cornichon', $legumes, 'Cornichon', 'cor.png', "","","",6.07,0);
        $this->consommables['Courge'] = $courge = $this->createConsommable('Courge', $legumes, 'Courges', 'courge.png', "courge_1.png", "courge_2.png", "courge_3.png", 1.4,190);
        $this->consommables['Courgette'] = $courgette = $this->createConsommable('Courgette', $legumes, 'Courgettes', 'courgette.png', "courgette_1.png", "courgette_2.png", "courgette_3.png",1.68,200);
        $this->consommables['Echalotte'] = $echalotte = $this->createConsommable('Echalotte', $legumes, 'Echalottes', 'ech.png', "","","",5.51,720);
        $this->consommables['Endives'] = $endive = $this->createConsommable('Endive', $legumes, 'Endives', 'end.png', "","","",5.06,170);
        $this->consommables['Epinard'] = $epinard = $this->createConsommable('Epinard', $legumes, 'Epinards', 'ep.png', "","","",3.18,230);
        $this->consommables['Fenouil'] = $fenouil = $this->createConsommable('Fenouil', $legumes, 'Fenouils', 'fe.png', "","","",2.86,310);
        $this->consommables['Fève'] = $feve = $this->createConsommable('Fève', $legumes, 'Fèves', 'bro.png', "","","",3.89,829);
        $this->consommables['Haricot'] = $haricotv = $this->createConsommable('Haricot vert/jaune', $legumes, 'Haricots verts/jaunes', 'har.png', "","","",9.79,250);
        $this->consommables['Haricot sec'] = $haricot_sec = $this->createConsommable('Haricot sec', $legumes, 'Haricots secs', 'hari_sec.png', "har_1.png", "har_2.png", "har_3.png", 3.46,2000);
        $this->consommables['Lentille'] = $lentille = $this->createConsommable('Lentille', $legumes, 'Lentilles', 'len.png', "","","",0,1160);
        $this->consommables['Mais'] = $mais = $this->createConsommable('Mais', $legumes, 'Mais', 'mais.png', "mais_1.png", "mais_2.png", "mais_3.png",0,1080);
        $this->consommables['Navet'] = $navet = $this->createConsommable('Navet', $legumes, 'Navets', 'nav.png', "","","",1.94,280);
        $this->consommables['Oignon'] = $oignon = $this->createConsommable('Oignon', $legumes, 'Oignons', 'oig.png', "","","",2,400);
        $this->consommables['Panais'] = $panais = $this->createConsommable('Panai', $legumes, 'Panais', 'pan.png', "","","", 2.08,400);
        $this->consommables['Patate douce'] = $patatedouce = $this->createConsommable('Patate douce', $legumes, 'Patates douces', 'patate_douce.png', "","","",2.467,760);
        $this->consommables['Petit pois'] = $petitpois = $this->createConsommable('Petit pois', $legumes, 'Petits pois', 'petit_pois.png',"","","",7.3,820);
        $this->consommables['Pignon'] = $pignon = $this->createConsommable('Pignon', $legumes, 'Pignons', 'bro.png', "","","",135.92,6730);
        $this->consommables['Piment'] = $piment = $this->createConsommable('Piment', $legumes, 'Piments', 'pim.png', "","","",5.35,400);
        $this->consommables['Plante médicinale'] = $plantemed = $this->createConsommable('Plante médicinale', $legumes, 'Plantes médicinales', 'cana.png', "cana_1.png", "cana_2.png", "cana_3.png", 2000,0);
        $this->consommables['Poireau'] = $poireau = $this->createConsommable('Poireau', $legumes, 'Poireaux', 'bro.png', 3.81,310);
        $this->consommables['Pois gourmand'] = $pois = $this->createConsommable('Pois gourmand', $legumes, 'Pois gourmands', 'bro.png', "","","",6,448);
        $this->consommables['Poivron'] = $poivron = $this->createConsommable('Poivron', $legumes, 'Poivrons', 'poivron.png', "poiv_1.png", "poiv_2.png", "poiv_3.png",4.71,210 );
        $this->consommables['Pomme de terre'] = $pommeterre = $this->createConsommable('Pomme de terre', $legumes, 'Pommes de terre', 'pommterre.png', "pommeterre_1.png", "pommeterre_2.png", "pommeterre_3.png",1,860);
        $this->consommables['Radis'] = $radis = $this->createConsommable('Radis', $legumes, 'Radis', 'rad.png', "radi_1.png", "radi_2.png", "radi_3.png", 1.08,160);
        $this->consommables['Rutabaga'] = $rutabaga = $this->createConsommable('Rutabaga', $legumes, 'Rutabagas', 'rut.png', "","","",2.17,380);
        $this->consommables['Salade'] = $salade = $this->createConsommable('Salade', $legumes, 'Salades', 'salad.png', "","","",1.21,140);
        $this->consommables['Tomate'] = $tomate = $this->createConsommable('Tomate', $legumes, 'Tomates', 'to.png', "to_1.png", "to_2.png", "to_3.png", 3.5, 200);
        $this->consommables['Tomate cerise'] = $tomate_cer = $this->createConsommable('Tomate cerise', $legumes, 'Tomates cerises', 'tomate_cer.png', "","","",5.73,200);
        $this->consommables['Topinambour'] = $topinambour = $this->createConsommable('Topinambour', $legumes, 'Topinambours', 'topi.png', "","","",1.87,730);

        // Level 3 légumes
        $this->consommables['branche'] = $branche = $this->createConsommable('Céleri branche', $Celeri, 'Céleris branches', 'cel.png', "","","",2.26,176);
        $this->consommables['rave'] = $rave = $this->createConsommable('Céleri rave', $Celeri, 'Céleris raves', 'cel.png', "","","",1.39,293);

        $this->consommables['bruxelle'] = $bruxelle = $this->createConsommable('Chou de bruxelle', $Choux, 'Choux de bruxelle', 'chouf.png', "","","",6.83,430);
        $this->consommables['fleur'] = $fleur = $this->createConsommable('Chou-fleur', $Choux, 'Choux-fleurs', 'chouf.png', "","","",2.4,250);
        $this->consommables['kale'] = $kale = $this->createConsommable('Chou kale', $Choux, 'Choux kale', 'chouf.png', "","","",4.23,490);
        $this->consommables['pômmé'] = $pomm = $this->createConsommable('Chou pômmé', $Choux, 'Choux pômmé', 'chouf.png', "","","",0,490);
        $this->consommables['romanesco'] = $romanesco = $this->createConsommable('Chou romanesco', $Choux, 'Choux romanesco','chouf.png', "","","",1.8,280);

        $this->consommables['butternut'] = $butternut = $this->createConsommable('Butternut', $courge, 'Butternuts', 'courge.png', "","","",1.4,190);
        $this->consommables['christophine'] = $christophine = $this->createConsommable('Christophine', $courge, 'Christophine', 'courge.png', "","","",1,190);
        $this->consommables['citrouille'] = $citrouille = $this->createConsommable('Citrouille', $courge, 'Citrouilles', 'courge.png', "","","",1,190);
        $this->consommables['patisson'] = $patisson = $this->createConsommable('Patisson', $courge, 'Patissons', 'courge.png', "","","",1.21,190);
        $this->consommables['potimarron'] = $potimarron = $this->createConsommable('Potimarron', $courge, 'Potimarrons', 'courge.png', "","","",1.66,190);
        $this->consommables['potiron'] = $potiron = $this->createConsommable('Potiron', $courge, 'Potirons', 'courge.png', "","","",0.95,190);
        $this->consommables['spaghetti'] = $spaghetti = $this->createConsommable('Courge spaghetti', $courge, 'Courges spaghettis', 'courge.png', "","","",1.28,190);

        $this->consommables['Allongée'] = $allonge = $this->createConsommable('Courgette allongée', $courgette, 'Courgettes allongées', 'courgette.png', "","","",1.68,200);
        $this->consommables['Popcorn'] = $popcorn = $this->createConsommable('Courgette Popcorn', $courgette, 'Courgettes Popcorn', 'courgette.png', "","","",1.68,200);
        $this->consommables['Ronde'] = $ronde = $this->createConsommable('Courgette ronde', $courgette, 'Courgettes ronde', 'courgette.png', "","","",2.22,200);

        $this->consommables['coco'] = $coco = $this->createConsommable('Haricot coco', $haricot_sec, 'Haricots coco', 'hari_sec.png', "","","",3.46,3350);
        $this->consommables['flageolet'] = $flageolet = $this->createConsommable('Flageolet', $haricot_sec, 'Flageolets', 'hari_sec.png', "","","",8.02,840);
        $this->consommables['noir'] = $noir = $this->createConsommable('Haricot noir', $haricot_sec, 'Haricots noir', 'hari_sec.png', "","","",4.25,1700);
        $this->consommables['pinto'] = $pinto = $this->createConsommable('Haricot pinto', $haricot_sec, 'Haricots pinto', 'hari_sec.png', "","","",4.25,2470);

        $this->consommables['belle de fontenay'] = $fontenay = $this->createConsommable('Belle de fontenay', $pommeterre, 'Belles de fontenay', 'pommterre.png', "","","",0,860);
        $this->consommables['bintche'] = $bintche = $this->createConsommable('Bintche', $pommeterre, 'Bintches', 'pommterre.png', "","","",0.55,860);
        $this->consommables['charlotte'] = $charlotte = $this->createConsommable('Charlotte', $pommeterre, 'Charlottes', 'pommterre.png', "","","",1.2,860);
        $this->consommables['chéri'] = $cheri = $this->createConsommable('Chérie', $pommeterre, 'Chéries', 'pommterre.png', "","","",1.25,860);
        $this->consommables['pompadour'] = $pompadour = $this->createConsommable('Pompadour', $pommeterre, 'Pompadours', 'pommterre.png', "","","",1,860);
        $this->consommables['ratte'] = $ratte = $this->createConsommable('Ratte', $pommeterre, 'Rattes', 'pommterre.png', "","","",2.8,860);
        $this->consommables['roseval'] = $roseval = $this->createConsommable('Roseval', $pommeterre, 'Rosevals', 'pommterre.png', "","","",1,860);
        $this->consommables['amandine'] = $amandine = $this->createConsommable('Amandine', $pommeterre, 'Amandines', 'pommterre.png', "","","",1.2,860);
        $this->consommables['agata'] = $agata = $this->createConsommable('Agata', $pommeterre, 'Agata', 'pommterre.png', "","","",1.2,860);
        $this->consommables['apollo'] = $apollo = $this->createConsommable('Apollo', $pommeterre, 'Apollo', 'pommterre.png', "","","",1.2,860);
        $this->consommables['bonnotte'] = $bonnotte = $this->createConsommable('Bonnotte', $pommeterre, 'Bonnottes', 'pommterre.png', "","","",1.2,860);
        $this->consommables['bf15'] = $bf15 = $this->createConsommable('BF 15', $pommeterre, 'BF 15', 'pommterre.png', "","","",1.2,860);
        $this->consommables['ladychristi'] = $ladychristi = $this->createConsommable('Lady Christi', $pommeterre, 'Ladies Christi', 'pommterre.png', "","","",1.2,860);
        $this->consommables['annabelle'] = $annabelle = $this->createConsommable('Annabelle', $pommeterre, 'Annabelles', 'pommterre.png', "","","",1.2,860);
        $this->consommables['victoria'] = $victoria = $this->createConsommable('Victoria', $pommeterre, 'Victoria', 'pommterre.png', "","","",1.2,860);
        $this->consommables['artemis'] = $artemis = $this->createConsommable('Artemis', $pommeterre, 'Artemis', 'pommterre.png', "","","",1.2,860);

        $this->consommables['greenmeat'] = $greenmeat = $this->createConsommable('Radis Green Meat', $radis, 'Radis Green Meat', 'rad.png', "","","",1.08,160);
        $this->consommables['hilds'] = $hilds = $this->createConsommable('Radis Hilds Blauer', $radis, 'Radis Hilds Blauer', 'rad.png', "","","",1.08,160);
        $this->consommables['radinoir'] = $radinoir = $this->createConsommable('Radis Noir', $radis, 'Radis Noir', 'rad.png', "","","",1.08,160);
        $this->consommables['redmeat'] = $redmeat = $this->createConsommable('Radis red Meat', $radis, 'Radis red Meat', 'rad.png', "","","",1.08,160);
        $this->consommables['radirose'] = $radirose = $this->createConsommable('Radis Rose', $radis, 'Radis Rose', 'rad.png', "","","",1.08,160);
        $this->consommables['violetgournay'] = $violetgournay = $this->createConsommable('Radis Violet de Gournay', $radis, 'Radis Violet de Gournay', 'rad.png', "","","",1.08,160);


        $this->consommables['chicorée'] = $chicoree = $this->createConsommable('Chicorée', $salade, 'Chicorées', 'salad.png', "","","",1.21,140);
        $this->consommables['cresson'] = $cresson = $this->createConsommable('Cresson', $salade, 'Cressons', 'salad.png', "","","",7.74,140);
        $this->consommables['laitue'] = $laitue = $this->createConsommable('Laitue', $salade, 'Laitues', 'salad.png', "","","",0.69,140);
        $this->consommables['mache'] = $mache = $this->createConsommable('Mache', $salade, 'Maches', 'salad.png', "","","",7,140);
        $this->consommables['pissenlit'] = $pissenlit = $this->createConsommable('Pissenlit', $salade, 'Pissenlits', 'salad.png', "","","",7,140);
        $this->consommables['pourpier'] = $pourpier = $this->createConsommable('Pourpier', $salade, 'Pourpiers', 'salad.png', "","","",3.2,140);

        $this->consommables['Coeur de boeuf'] = $coeurboeuf = $this->createConsommable('Coeur de boeuf', $tomate, 'Coeurs de beuf', 'to.png', "","","",3.5,200);
        $this->consommables['Colorée'] = $coloree = $this->createConsommable('Tomate colorée', $tomate, 'tomates colorées', 'to.png', "","","",2.6,200);
        $this->consommables['Plête_Cottelée'] = $plate = $this->createConsommable('Plate/Cottelée', $tomate, 'Plâtes/Cottelées', 'to.png', "","","",2.34,200);
        $this->consommables['roma_Chico'] = $roma = $this->createConsommable('Roma/Chico', $tomate, 'Roma/Chico', 'to.png', "","","",2.34,200);
        $this->consommables['Ronde'] = $ronde = $this->createConsommable('Tomate ronde', $tomate, 'Tomates rondes', 'to.png', "","","",3.34,200);
        $this->consommables['green'] = $green = $this->createConsommable('Tomate Green/Zebra', $tomate, 'Tomates Green Zebra', 'to.png', "","","",3.5,200);


        // Level 2 aromates
        $this->consommables['Basilic'] = $basilic = $this->createConsommable('Basilic', $aromates, 'Basilic', 'basilic.png');
        $this->consommables['Estragon'] = $estragon = $this->createConsommable('Estragon', $aromates, 'Estragon', 'default.png');
        $this->consommables['Laurier'] = $laurier = $this->createConsommable('Laurier', $aromates, 'Laurier', 'default.png');
        $this->consommables['Cerfeuil'] = $cerfeuil = $this->createConsommable('Cerfeuil', $aromates, 'Cerfeuil', 'cerf.png');
        $this->consommables['Ciboulette'] = $ciboulette = $this->createConsommable('Ciboulette', $aromates, 'Ciboulette', 'cib.png');
        $this->consommables['Coriandre'] = $coriandre = $this->createConsommable('Coriandre', $aromates, 'Coriandre', 'cori.png');
        $this->consommables['Mélisse'] = $melisse = $this->createConsommable('Mélisse', $aromates, 'Mélisses', 'meli.png');
        $this->consommables['Menthe'] = $menthe = $this->createConsommable('Menthe', $aromates, 'Menthe', 'men.png');
        $this->consommables['Origan'] = $origan = $this->createConsommable('Origan', $aromates, 'Origan', 'ori.png');
        $this->consommables['Ortie'] = $ortie = $this->createConsommable('Ortie', $aromates, 'Orties', 'ort.png');
        $this->consommables['Oseille'] = $oseille = $this->createConsommable('Oseille', $aromates, 'Oseille', 'oseil.png');
        $this->consommables['Persil'] = $persil = $this->createConsommable('Persil', $aromates, 'Persil', 'persil.png');
        $this->consommables['Réglisse'] = $reglisse = $this->createConsommable('Réglisse', $aromates, 'Réglisse', 'cel.png');
        $this->consommables['Romarin'] = $romarin = $this->createConsommable('Romarin', $aromates, 'Romarin', 'rom.png');
        $this->consommables['Sariette'] = $sariette = $this->createConsommable('Sariette', $aromates, 'Sariette', 'sar.png');
        $this->consommables['Sauge'] = $sauge = $this->createConsommable('Sauge', $aromates, 'Sauge', 'sau.png');
        $this->consommables['Thym'] = $thym = $this->createConsommable('Thym', $aromates, 'Thym', 'thy.png');
        $this->consommables['Valériane'] = $valeriane = $this->createConsommable('Valériane', $aromates, 'Valériane', 'val.png', "", "", "", 0,0);


        foreach ($this->consommables as $consommable) {
            $manager->persist($consommable);
        }

        $manager->flush();
    }

    private function createConsommable(string $name, ?Consommable $parent = null, ?string $description = null, ?string $iconImg = null, ?string $badge1 = null, ?string $badge2 = null, ?string $badge3 = null, ?float $prix = null, ?float $calorie = null): Consommable
    {

        $consommable = new Consommable($name, $parent);

        if ($iconImg){
            $consommable->setIconLien($iconImg);
        }
        else{
            $consommable->setIconLien('default.png');
        }
        if ($description) {
            $consommable->setDescription($description);
        }
        if ($badge1) {
            $consommable->setBadge1($badge1);
        }
        if ($badge2) {
            $consommable->setBadge2($badge2);
        }
        if ($badge3) {
            $consommable->setBadge3($badge3);
        }
        if ($prix) {
            $consommable->setPrix($prix);
        }
        if ($calorie) {
            $consommable->setCalorie($calorie);
        }

        return $consommable;
    }
}
