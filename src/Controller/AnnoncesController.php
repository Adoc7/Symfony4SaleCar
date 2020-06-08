<?php

namespace App\Controller;

use DateTime;
use App\Entity\Annonces;
use App\Entity\Comments;

use App\Form\AnnoncesType;
use App\Form\CommentsType;
use App\Repository\AnnoncesRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnnoncesController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(AnnoncesRepository $repo)
    {
        $annonces=$repo->findAll();
        return $this->render('annonces/home.html.twig', [
            'annonces' => $annonces,
        ]);
    }

    /**
     * @Route("/create", name="annonce_create")
     * @Route("/annonce/{id}/edit", name="annonce_edit")
     */
    public function form(Request $request,ObjectManager $manager,Annonces $annonces=null)
    {
        if(!$annonces){
            $annonces = new Annonces();
        }

        $form = $this->createForm(AnnoncesType::class,$annonces);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            if(!$annonces->getId())
            $annonces->setCreatedAt(new \DateTime());

        $manager->persist($annonces);
        $manager->flush();
        return $this->RedirectToRoute('annonce_detail', [
                'id'=>$annonces->getId()
         
            ]);
        }
        return $this->render('annonces/form.html.twig',[
            'formAnnonces'=>$form->createView(),
            'editmode'=>$annonces->getId()!== null
        ]);
    }
    /**
     * @Route("/detail/{id}", name="annonce_detail")
     */
    public function detail(Annonces $annonces, Request $request, ObjectManager $manager)
    {
        //ici le formulaire des commentaires
        $comments = new Comments();
        $form=$this->createForm(CommentsType::class, $comments);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){
             $comments->setCreatedAt(new \DateTime())
                      ->setAnnonce($annonces);

            $manager->persist($comments);
            $manager->flush();

        return $this->redirectToRoute('annonce_detail', [
            'id'=>$annonces->getId()
        ]);
        }

        return $this->render('annonces/detail.html.twig',[
            'annonce' => $annonces,
            'commentForm' => $form->createView() 
        ]);
    }
       
}
