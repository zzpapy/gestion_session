<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use App\Form\AddSessionType;
use App\Form\AddStagiaireType;
use App\Repository\SessionRepository;
use Algolia\AlgoliaSearch\SearchClient;
use Algolia\SearchBundle\SearchService;
use App\Repository\StagiaireRepository;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class StagiaireController extends AbstractController
{
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }
    /**
     * @Route("/admin/stagiaire", name="stagiaire")
     */
    public function index(StagiaireRepository $stagiaireRep)
    {
        $stagiaires = $stagiaireRep->findBy([], ['nom' => 'ASC']);
        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires,
        ]);
    }
    /**
     * @Route("/admin/stagiaire/detailStagiaire/{id}", name="/stagiaire/detailStagiaire")
     */
    public function detailStagiaire(Request $request,Stagiaire $stagiaire,StagiaireRepository $stagiaireRep)
    {
        $sessionStagiaire = $stagiaire->getSessions();
        $sessions = $this->getDoctrine()
        ->getRepository(Session::class)
        ->findAll();
        $sessions_dispo = array_filter($sessions,function($session) use ($stagiaire){
            return $session->full() || $stagiaire->getSessions()->contains($session);
        });
        // dd($sessions,$sessions_dispo);
        $form = $this->createForm(AddSessionType::class,$stagiaire,[ 
            "sessions" => $sessions_dispo
        ]);
        $sessionTab = [];
        foreach ($stagiaire->getSessions() as  $sess) {
           array_push($sessionTab,$sess->getId());
        }
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // dd( count($sessionTab) , count($request->request->get("add_session")["sessions"]));
            if( isset($request->request->get("add_session")["sessions"]) && count($sessionTab) > count($request->request->get("add_session")["sessions"]) || count($sessionTab) == 0){
                $em = $this->getDoctrine()->getManager();
                $em->persist($stagiaire);
                $em->flush(); 
                $this->addFlash('success', 'la formation a été retirée');
                return $this->redirectToRoute('/stagiaire/detailStagiaire',["id" => $stagiaire->getId()]);   
            }
            else if(count($sessionTab) != 0 && !isset($request->request->get("add_session")["sessions"])){
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
                $clean1 = array_diff($sessionTab, $croise); 
                $clean2 = array_diff($tabReqSess, $croise); 
                $final_output = array_merge($clean1, $clean2);
                foreach ( $final_output as  $id) {
                    $session = $this->getDoctrine()
                    ->getRepository(Session::class)
                    ->find($id);
                    $nbInscrits = count($session->getStagiaires());
                    $nbPLace = $session->getNbPlaces();
                    foreach ($stagiaire->getSessions() as  $stagsess) {
                        // dump($stagsess);
                        $dateDeb = $stagsess->getDateDebut();
                        $dateFin = $stagsess->getDateFin();
                        $deb = $session->getDateDebut();
                        $fin = $session->getDateFin();
                        // dump($dateDeb,$dateFin,$deb,$fin,count($sessionTab));die;
                        // dump($dateDeb >= $deb && $dateFin <= $fin );die;
                        if($dateDeb >= $deb && $dateFin <= $fin || $dateDeb <= $deb && $dateFin >= $fin && !$stagiaire->getSessions()->contains($session) && $nbPLace <= $nbInscrits && count($sessionTab) != 0){
                            // dd($dateDeb >= $deb && $dateFin <= $fin || $dateDeb <= $deb && $dateFin >= $fin);
                            $this->addFlash('error', 'le stagiaire est déjà en formation durant cette période');
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

        }
       
        $stagiaires = $stagiaireRep->findAll();
        return $this->render('stagiaire/detailStagiaire.html.twig', [
            'stagiaire' => $stagiaire,
            'form' => $form->createView()
        ]);
    }
    /**
    * @Route("/admin/updateStagiaire/{id}", name="updateStagiaire")
    * @Route("/admin/addStagiaire", name="addStagiaire")
    */
    public function addStagiaire(Request $request,SessionRepository $sessionRep,Stagiaire $stagiaire = null,StagiaireType $form,StagiaireRepository $stagiaireRep,SluggerInterface $slugger)
    {
        if(!$stagiaire){
            $stagiaire = new Stagiaire(); 
        }
       
        
        // $form = $this->createForm(StagiaireType::class, $stagiaire,[
        //     "list_commune" => $content
        // ]);
        // dd($this->searchService);
        $form = $this->createForm(StagiaireType::class, $stagiaire);
        // dd($request->request);
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
     * @Route("/admin/addStagiaireSess/{id}", name="addStagiaireSess")
     */
    public function addStagiaireSess(Request $request,Stagiaire $stagiaire = null,Session $session,SessionRepository $sessionRep,StagiaireRepository $stagiaireRep)
    {
        $stagiaires = $stagiaireRep->findAll();
        $form = $this->createForm(AddStagiaireType::class,$session);
        $sessionId = $session->getId();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
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
     * @Route("/admin/stagiaire/trombi/{id}", name="trombi")
     */
    public function trombi(Session $session,StagiaireRepository $stagiaireRep)
    {
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
        $stagiaire = $stagiaireRep->findOneBy(["id" => $request->get("data") ]);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($stagiaire);
        $entityManager->flush();        
       
        return $this->redirectToRoute('stagiaire');
    }
}
