<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Annonces;
use App\Entity\Category;
use App\Entity\Comments;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AnnoncesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    { 
        $faker = \Faker\Factory::create('fr-FR');

                    for ($i=1; $i<=3; $i++){
                        $category = new Category();
                        $category->setTitre($faker->sentence())        
                                ->setDescription($faker->paragraph());
                    $manager->persist($category);
                    


                    for ($j=1; $j<= mt_rand(1,6); $j++){
                        $annonces = new Annonces();
                        $contenu='<p>'.join($faker->paragraphs(5),'</p><p>').'</p>';
                        $annonces-> setTitre($faker->sentence())
                                -> setPhoto($faker->imageUrl)
                                -> setPrix($faker->randomNumber(2))
                                -> setCreatedAt($faker->dateTimeBetween('-6 months'))
                                -> setPseudo($faker->name)
                                -> setContenu($contenu)
                                -> setCategory($category);

                    $manager->persist($annonces);
                
                    for ($k=1; $k<= mt_rand(4,10); $k++){
                        $comments = new Comments();
                        $contenu='<p>'.join($faker->paragraphs(2),'</p><p>').'</p>';
                        $days=(new \DateTime())->diff($annonces->getCreatedAt())->days;


                        $comments-> setAuteur($faker->name)
                                 -> setContenu($contenu)
                                 -> setCreatedAt($faker->dateTimeBetween('-'.$days.'days'))
                                 -> setAnnonce($annonces);


                    $manager->persist($comments);
                }
            }
        }
        
        $manager->flush();
    }
}
