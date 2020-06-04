<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Stagiaire;
use App\Form\AddSessionType;
use App\Form\StagiaireType;
use App\Form\AddStagiaireType;
use App\Repository\SessionRepository;
use App\Repository\StagiaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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
     * @Route("/stagiaire/detailStagiaire/{id}", name="/stagiaire/detailStagiaire")
     */
    public function detailStagiaire(Request $request,Stagiaire $stagiaire,StagiaireRepository $stagiaireRep)
    {
        $form = $this->createForm(AddSessionType::class,$stagiaire);
        $sessionTab = [];
        foreach ($stagiaire->getSessions() as  $sess) {
           array_push($sessionTab,$sess->getId());
        }
        // dump(count($sessionTab),count($request->request->get("add_session")["sessions"]));die;
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if( isset($request->request->get("add_session")["sessions"]) && count($sessionTab) > count($request->request->get("add_session")["sessions"])){
                $em = $this->getDoctrine()->getManager();
                $em->persist($stagiaire);
                $em->flush(); 
                $this->addFlash('success', 'la formation a été retirée');
                return $this->redirectToRoute('/stagiaire/detailStagiaire',["id" => $stagiaire->getId()]);   
            }
            else{
                $tabReqSess = [];
                if(isset($request->request->get("add_session")["sessions"])){
                    foreach ($request->request->get("add_session")["sessions"] as  $value) {              
                        array_push($tabReqSess,$value);
                    }
                }
                $croise = array_intersect($sessionTab,$tabReqSess);
                $clean1 = array_diff($sessionTab, $tabReqSess); 
                $clean2 = array_diff($tabReqSess, $sessionTab); 
                $final_output = array_merge($clean1, $clean2);
                // dump($final_output);die;
                foreach ( $final_output as  $id) {
                    $session = $this->getDoctrine()
                    ->getRepository(Session::class)
                    ->find($id);
                    $nbInscrits = count($session->getStagiaires());
                    $nbPLace = $session->getNbPlaces();
                    $nom = $session->getNom();
                    
                    if($nbPLace <= $nbInscrits){
                        $this->addFlash('error', 'la formation est complète');
                        return $this->redirectToRoute('/stagiaire/detailStagiaire',["id" => $stagiaire->getId()]);
                    }
                    else{
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($stagiaire);
                        $em->flush();
                        $this->addFlash('success', 'formation ajoutée');
                        return $this->redirectToRoute('/stagiaire/detailStagiaire',["id" => $stagiaire->getId()]);
                    }
                }
            }

        }
        // if(isset($request->request->get("add_session")["sessions"])){
        //     $tabReqSess = [];
        //     foreach ($request->request->get("add_session")["sessions"] as  $value) {
                
               
        //         array_push($tabReqSess,$value);
        //         foreach ($sessionTab as $id) {
        //             // if($id == $value){
        //                 //     $this->addFlash('error', 'stagiaire déjà inscrit à cette formation');
        //             //     return $this->redirectToRoute('/stagiaire/detailStagiaire',["id" => $stagiaire->getId()]); 
        //             // }
        //         }
        //     }
        //     $croise = array_intersect($sessionTab,$tabReqSess);
        //     $clean1 = array_diff($sessionTab, $tabReqSess); 
        //     $clean2 = array_diff($tabReqSess, $sessionTab); 
        //     $final_output = array_merge($clean1, $clean2);
        //     dump($final_output);
        //     foreach ( $final_output as $key => $id) {
        //         $session = $this->getDoctrine()
        //         ->getRepository(Session::class)
        //         ->find($id);
        //         $nbInscrits = count($session->getStagiaires());
        //         $nbPLace = $session->getNbPlaces();
        //         $nom = $session->getNom();
        //         dump($nbPLace <= $nbInscrits,$nom);
        //             if($nbPLace <= $nbInscrits){
        //                 $this->addFlash('error', 'la formation est complète');
        //                 return $this->redirectToRoute('/stagiaire/detailStagiaire',["id" => $stagiaire->getId()]);
        //             }
        //             else{
        //             }
        //         }
        //     }
        // 
       
        $stagiaires = $stagiaireRep->findAll();
        return $this->render('stagiaire/detailStagiaire.html.twig', [
            'stagiaire' => $stagiaire,
            'form' => $form->createView()
        ]);
    }
    /**
    * @Route("/updateStagiaire/{id}", name="updateStagiaire")
    * @Route("/addStagiaire", name="addStagiaire")
    */
    public function addStagiaire(Request $request,SessionRepository $sessionRep,Stagiaire $stagiaire = null,StagiaireType $form,StagiaireRepository $stagiaireRep,SluggerInterface $slugger)
    {
        if(!$stagiaire){
            $stagiaire = new Stagiaire(); 
        }
        $form = $this->createForm(StagiaireType::class, $stagiaire);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            $photo = $form->get('photo')->getData();
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('img'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'photoname' property to store the PDF file name
                // instead of its contents
                $stagiaire->setPhoto($newFilename);
            }
            $date = new \DateTime();
            $stagiaire->setcreateDate($date);
            $em = $this->getDoctrine()->getManager();
            $em->persist($stagiaire);
            $em->flush();
            $this->addFlash('success', 'stagiaire ajouté avec succés');
            return $this->redirectToRoute('/stagiaire/detailStagiaire',array("id" => $stagiaire->getId()));
            
        }
        return $this->render('stagiaire/addStagiaire.html.twig', [
            'form' => $form->createView(),
            'stagiaire' => $stagiaire
        ]);
    }
    /**
     * @Route("/addStagiaireSess/{id}", name="addStagiaireSess")
     */
    public function addStagiaireSess(Request $request,Stagiaire $stagiaire = null,Session $session,SessionRepository $sessionRep,StagiaireRepository $stagiaireRep)
    {
        $stagiaires = $stagiaireRep->findAll();
        // if(!$stagiaire){
        //     $stagiaire = new Stagiaire();
        // }
        $form = $this->createForm(AddStagiaireType::class,$session);
        $sessionId = $session->getId();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            // $session->addStagiaire($stagiaire);
            $em = $this->getDoctrine()->getManager();
            $em->persist($session);
            $em->flush();
            

            return $this->redirectToRoute('programme',["id" => $session->getId()]);
            
        }
        return $this->render('session/addStagiaireSess.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/stagiaire/trombi/{id}", name="trombi")
     */
    public function trombi(Session $session,StagiaireRepository $stagiaireRep)
    {
        // dump($session->getStagiaires());die;
        $stagiaires = $session->getStagiaires();
        return $this->render('stagiaire/trombi.html.twig', [
            'stagiaires' => $stagiaires,
        ]);
    }
     /**
     * @Route("/stagiaire_delete", name="stagiaire_delete", methods={"GET"})
     */
    public function deleteSession(Request $request,StagiaireRepository $stagiaireRep)
    {
        // dump($request->get("data"));die;
        $stagiaire = $stagiaireRep->findOneBy(["id" => $request->get("data") ]);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($stagiaire);
        $entityManager->flush();        
       
        return $this->redirectToRoute('stagiaire');
    }
}
