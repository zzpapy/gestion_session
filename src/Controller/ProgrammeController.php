<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\Session;
use App\Form\ModuleType;
use App\Entity\Categorie;
use App\Entity\Programme;
use App\Form\CategorieType;
use App\Form\ProgrammeType;
use App\Form\Programme1Type;
use App\Repository\SessionRepository;
use App\Repository\ProgrammeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProgrammeController extends AbstractController
{
    
    /**
     * @Route("/admin/session/CreaProgramme/{id}", name="CreaProgramme")
     */
    public function CreaProgramme(Request $request,Categorie $categorie = null,Module $module = null,Programme $programme = null,Session $session = null, SessionRepository $sessRep)
    {
        $session = $sessRep->findOneBy(["id"=> $session->getId()]);
        $programmes = $session->getProgrammes();
        if(!$categorie){
            $categorie = new Categorie();
        }
        $formCategorie = $this->createForm(CategorieType::class, $categorie);
        
        $formCategorie->handleRequest($request);
        if($formCategorie->isSubmitted() && $formCategorie->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('CreaProgramme',["id" => $session->getId()]);
        }
        if(!$programme){
            $programme = new programme();
        }
        $tps_session = 0;
        foreach ($session->getProgrammes() as $value) {
            $tps = $value->getDuree();
            $tps_session = $tps_session + $tps;
        }
        $debut = $session->getDateDebut();
        $fin = $session->getDateFin();
        $longueur = $fin->diff($debut);
        $days = $longueur->days;
        $period = new \DatePeriod($debut, new \DateInterval('P1D'), $fin);
        foreach($period as $dt) {
            $curr = $dt->format('D');
        
            // substract if Saturday or Sunday
            if ($curr == 'Sat' || $curr == 'Sun') {
                $days--;
            }
        
            // (optional) for the updated question
            
        }
        if(isset($request->request->get("programme")["duree"])){
            if(($tps_session + $request->request->get("programme")["duree"]) > $days){
                $this->addFlash('error', 'vous dépassez la durée de la formation');
                return $this->redirectToRoute('CreaProgramme',["id" => $session->getId()]);
            }
            
        }
        $vacances = $session->getVacances();
        if(count($vacances) != 0){
            // dump(count($vacances));die;
            foreach ($vacances as $vac) {
                $dateDeb = $vac->getDateDebut();
                $dateFin = $vac->getDateFin();
                $debutHol =  $dateDeb;
                $finHol =$dateFin;
                $daysHol = $finHol->diff($debutHol);
                if($daysHol){
                    
                    $days = $days - $daysHol->days;
                }
            }
        }
        $formProgramme = $this->createForm(ProgrammeType::class, $programme);
        $formProgramme->handleRequest($request);
        $programme->setSession($session);
        if($formProgramme->isSubmitted() && $formProgramme->isValid()){
            if(($days-$tps_session+1 < $request->request->get("programme")["duree"]) ){
                $this->addFlash('error', 'vous dépassez la durée de la formation');
                return $this->redirectToRoute('CreaProgramme',["id" => $session->getId()]);
            }
            $em = $this->getDoctrine()->getManager();
            $test = $programme->getDuree();
            $test = intval($test);
            $programme->setDuree($test);
            $em->persist($programme);
            $em->flush();
            return $this->redirectToRoute('programme',["id" => $session->getId()]);
        }
        // if(!$module){
            $module = new Module();
        // }
        // dump($module);die;
        $formModule = $this->createForm(ModuleType::class, $module);
        
        $formModule->handleRequest($request);
        if($formModule->isSubmitted() && $formModule->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($module);
            $em->flush();
            $this->addFlash('success', 'module ajouté avec succés');
            return $this->redirectToRoute('CreaProgramme',["id" => $session->getId()]);
        }
       
        
        return $this->render('session/CreaProgramme.html.twig', [
            'thisSession' => $session,
            'formProgramme' => $formProgramme->createView(),
            'formModule' => $formModule->createView(),
            'formCategorie' => $formCategorie->createView(),
            'longueur' => $days
        ]);
    }
     /**
     * @Route("/admin/ModifDuree/{id<\d+>}/{id_session<\d+>}", name="ModifDuree")
     */
    public function modif(Request $request, Programme $programme = null,SessionRepository $sessionRep): Response
    {
        if(!$programme){
            $programme = new programme();
        }
        // dump($request->get("id_session"));die;
        $session = $sessionRep->findOneBy(["id" => $request->get("id_session")]);
        $formProgramme = $this->createForm(ProgrammeType::class, $programme);
        $formProgramme->remove('module');
        $formProgramme->handleRequest($request);
        if($formProgramme->isSubmitted() && $formProgramme->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($programme);
            $em->flush();
            return $this->redirectToRoute('programme',["id" => $session->getId()]);
        }
        return $this->render('session/ModifDuree.html.twig', [
            
            'formProgramme' => $formProgramme->createView(),
        ]);
    }
    /**
     * @Route("/admin/programme_delete/{id}", name="programme_delete", methods={"GET"})
     */
    public function delete(Request $request,Programme $programme = null, ProgrammeRepository $programmeRep, SessionRepository $sessionRep): Response
    {
        $sessionId = $programme->getSession()->getId();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($programme);
        $entityManager->flush();
        $this->addFlash('succes', 'module retiré');
        return $this->redirectToRoute('programme',["id" => $sessionId]);
    }
    
}
