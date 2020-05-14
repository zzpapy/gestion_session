<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use App\Repository\SessionRepository;
use App\Repository\StagiaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{
    /**
     * @Route("/stagiaire", name="stagiaire")
     */
    public function index(StagiaireRepository $stagiaireRep)
    {
        $stagiaires = $stagiaireRep->findAll();
        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires,
        ]);
    }
    /**
     * @Route("/detailStagiaire/{id}", name="detailStagiaire")
     */
    public function detailStagiaire(Stagiaire $stagiaire,StagiaireRepository $stagiaireRep)
    {
        $stagiaires = $stagiaireRep->findAll();
        return $this->render('stagiaire/detailStagiaire.html.twig', [
            'stagiaire' => $stagiaire,
        ]);
    }
     /**
     * @Route("/addStagiaire", name="addStagiaire")
     */
    public function addStagiaire(Request $request,SessionRepository $sessionRep,Stagiaire $stagiaire = null,StagiaireType $form,StagiaireRepository $stagiaireRep)
    {
        if(!$stagiaire){
            $stagiaire = new Stagiaire();
        }
        $form = $this->createForm(StagiaireType::class, $stagiaire);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($stagiaire);
            $stagiare = $em->flush();
            $this->addFlash('success', 'stagiaire ajouté avec succés');
            return $this->redirectToRoute('detailStagiaire',array("id" => $stagiaire->getId()));
            
        }
        return $this->render('stagiaire/addStagiaire.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
