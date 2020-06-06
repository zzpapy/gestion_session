<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Form\Categorie1Type;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CategorieController extends AbstractController
{
     /**
      * @Route("/admin/session/ModifCategorie/{id}", name="ModifCategorie"))
     */
    public function mofifCategorie(Request $request,Categorie $categorie = null)
    {
       
        
        if(!$categorie){
        $categorie = new categorie();
        }
        // dump($cat);die;
        $formCategorie = $this->createForm(CategorieType::class, $categorie);
        $formCategorie->remove("categorie");
        $formCategorie->handleRequest($request);
        if($formCategorie->isSubmitted() && $formCategorie->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('affich_module');
        }
       
       
        return $this->render('session/modifCategorie.html.twig', [
            'formCategorie' => $formCategorie->createView()
        ]);
    }
}
