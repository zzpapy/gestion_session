<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\Session;
use App\Form\ModuleType;
use App\Entity\Categorie;
use App\Entity\Programme;
use App\Form\SessionType;
use App\Form\CategorieType;
use App\Form\ProgrammeType;
use App\Form\AddStagiaireType;
use Doctrine\ORM\EntityManager;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    /**
     * @Route("/session/ModifSession/{id}", name="ModifSession")
     * @Route("/session/createSession", name="createSession")
     */
    public function createSession(Request $request,Session $session = null)
    {
        // dump($session);die;
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
    public function index(Session $session, SessionRepository $sessRep,Request $request)
    {
        $session = $sessRep->findOneBy(["id"=> $session->getId()]);
        
        // dump($session->getStagiaires());die;
        $programmes = $session->getProgrammes();
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
        // dump($programmes);die;
        return $this->render('session/index.html.twig', [
            'session' => $session,
            'form'    => $form->createView()
        ]);
    }
    /**
     * @Route("/session/CreaProgramme/{id}", name="CreaProgramme")
     */
    public function CreaProgramme(Request $request,Categorie $categorie = null,Module $module = null,Programme $programme = null,Session $session = null, SessionRepository $sessRep)
    {
        $session = $sessRep->findOneBy(["id"=> $session->getId()]);
        $programmes = $session->getProgrammes();
        if(!$programme){
            $programme = new programme();
        }
        $formProgramme = $this->createForm(ProgrammeType::class, $programme);
        
        $formProgramme->handleRequest($request);
        $programme->setSession($session);
        if($formProgramme->isSubmitted() && $formProgramme->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($programme);
            $em->flush();
            return $this->redirectToRoute('programme',["id" => $session->getId()]);
        }
        if(!$module){
            $module = new Module();
        }
        $formModule = $this->createForm(ModuleType::class, $module);
        
        $formModule->handleRequest($request);
        if($formModule->isSubmitted() && $formModule->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($module);
            $em->flush();
            return $this->redirectToRoute('CreaProgramme',["id" => $session->getId()]);
        }
        // if(!$categorie){
            $categorie = new categorie();
        // }
        $formCategorie = $this->createForm(CategorieType::class, $categorie);
        
        $formCategorie->handleRequest($request);
        if($formCategorie->isSubmitted() && $formCategorie->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('CreaProgramme',["id" => $session->getId()]);
        }
        
        return $this->render('session/CreaProgramme.html.twig', [
            'session' => $session,
            'formProgramme' => $formProgramme->createView(),
            'formModule' => $formModule->createView(),
            'formCategorie' => $formCategorie->createView()
        ]);
    }
     /**
     * @Route("/{id}<\d+>/{id_session}<\d+>", name="programme_delete", methods={"GET"})
     */
    public function delete(Request $request, Programme $programme, SessionRepository $sessionRep): Response
    {
       $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($programme);
        $entityManager->flush();
        
        $session = $sessionRep->findOneBy(["id" => $request->get("id_session")]);
        return $this->redirectToRoute('programme',["id" => $session->getId()]);
    }
    
     /**
     * @Route("/{id}<\d+>", name="session_delete", methods={"GET"})
     */
    public function deleteSession( Session $session)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($session);
        $entityManager->flush();        
       
        return $this->redirectToRoute('home');
    }
}
