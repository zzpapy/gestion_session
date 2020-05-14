<?php

namespace App\Controller;

use App\Entity\Session;
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
        // dump($form->handleRequest($request));die;
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
    public function index(Programme $programme = null,Request $request,Session $session, SessionRepository $sessRep)
    {
        $session = $sessRep->findOneBy(["id"=> $session->getId()]);
        $programmes = $session->getProgrammes();
        if(!$programme){
            $programme = new programme();
        }
        $form = $this->createForm(ProgrammeType::class, $programme);
        // dump($form->handleRequest($request));die;
        $form->handleRequest($request);
        $programme->setSession($session);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($programme);
            $stagiare = $em->flush();
            $this->addFlash('success', 'programme ajouté avec succés');
            return $this->redirectToRoute('home');            
        }
        // dump($programmes);die;
        return $this->render('session/index.html.twig', [
            'session' => $session,
            'form' => $form->createView()
        ]);
    }
}
