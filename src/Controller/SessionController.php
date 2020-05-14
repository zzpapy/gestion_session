<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\Session;
use App\Form\ModuleType;
use App\Entity\Programme;
use App\Form\SessionType;
use App\Form\ProgrammeType;
use App\Repository\SessionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    /**
     * @Route("/session/createSession", name="createSession")
     */
    public function createSession(Request $request,Session $session = null)
    {
        if(!$session){
            $session = new session();
        }
        $form = $this->createForm(SessionType::class, $session);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($session);
            $stagiare = $em->flush();
            $this->addFlash('success', 'session ajouté avec succés');
            return $this->redirectToRoute('home');
            
        }
        return $this->render('session/createSession.html.twig', [
            'form' => $form->createView(),
        ]);
        
        // return $this->render('session/createSession.html.twig');
    }
    /**
     * @Route("/session/{id}", name="programme")
     */
    public function index(Session $session, SessionRepository $sessRep)
    {
        $session = $sessRep->findOneBy(["id"=> $session->getId()]);
        $programmes = $session->getProgrammes();
        // dump($programmes);die;
        return $this->render('session/index.html.twig', [
            'session' => $session,
        ]);
    }
    /**
     * @Route("/session/CreaProgramme/{id}", name="CreaProgramme")
     */
    public function CreaProgramme(Request $request,Module $module = null,Programme $programme = null,Session $session, SessionRepository $sessRep)
    {
        $session = $sessRep->findOneBy(["id"=> $session->getId()]);
        $programmes = $session->getProgrammes();
        if(!$programme){
            $programme = new programme();
        }
        $formProgramme = $this->createForm(ProgrammeType::class, $programme);
        
        $formProgramme->handleRequest($request);
        if($formProgramme->isSubmitted() && $formProgramme->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($programme);
            $stagiare = $em->flush();
        }
        if(!$module){
            $module = new Module();
        }
        $formModule = $this->createForm(ModuleType::class, $module);
        
        $formModule->handleRequest($request);
        if($formModule->isSubmitted() && $formModule->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($module);
            $stagiare = $em->flush();
        }
        return $this->render('session/CreaProgramme.html.twig', [
            'session' => $session,
            'formProgramme' => $formProgramme->createView(),
            'formModule' => $formModule->createView()
        ]);
    }
}
