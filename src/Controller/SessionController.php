<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\Session;
use App\Form\ModuleType;
use App\Entity\Categorie;
use App\Entity\Programme;
use App\Entity\Stagiaire;
use App\Form\SessionType;
use App\Form\CategorieType;
use App\Form\ProgrammeType;
use App\Form\AddStagiaireType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Mime\Email;
use App\Repository\ModuleRepository;
use App\Repository\SessionRepository;
use App\Repository\CategorieRepository;
use App\Repository\ProgrammeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
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
        if(isset($request->request->get("session")["stagiaires"])){
            if(count($request->request->get("session")["stagiaires"])>$request->request->get("session")["nb_places"]){
                // dump($request->request->get("session"));die;
                $this->addFlash('error', 'vous avez séléctionné trop de stagiares');
                if($session->getId() != null){
                    return $this->redirectToRoute('ModifSession',["id" => $session->getId()]);
                }
                else{
                    return $this->redirectToRoute('home');
                }
            }
        }
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
    }
    /**
     * @Route("/session/{id}", name="programme")
     */
    public function index(Stagiaire $stagiaire = null,MailerInterface $mailer, Session $session, SessionRepository $sessRep,Request $request)
    {
        $session = $sessRep->findOneBy(["id"=> $session->getId()]);
        
        $programmes = $session->getProgrammes();
        $tab=[];
        foreach ($programmes as $key => $programme) {
            if(array_key_exists($programme->getModule()->getCategorie()->getNom(),$tab)){
                array_push($tab[$programme->getModule()->getCategorie()->getNom()],$programme);
            }
            else{
                $tab[$programme->getModule()->getCategorie()->getNom()] = [$programme];
            }
        }

        $form = $this->createForm(AddStagiaireType::class,$session);
        $sessionId = $session->getId();
        $nbStagiaires = count($session->getStagiaires());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // dump(isset($request->request->get("add_stagiaire")["stagiaires"]),$request->request->get("add_stagiaire"));die;
            if(isset($request->request->get("add_stagiaire")["stagiaires"])){
                if(count($request->request->get("add_stagiaire")["stagiaires"])>$session->getNbPlaces()){
                    $this->addFlash('error', 'vous avez séléctionné trop de stagiares');
                    return $this->redirectToRoute('programme',["id" => $session->getId()]);
                }
            }
            // $email = (new TemplatedEmail())
            //     ->from('zzpapy666@gmail.com')
            //     ->to('gregory.pace@hotmail.fr')
            //     ->subject('test mail')
            //     ->text('ceci est un test de mail symfony')
            //     ->htmlTemplate('home/mail.html.twig', 'r')
            //     ->context([
            //         'expiration_date' => new \DateTime('+7 days'),
            //         'username' => 'foo',
            //     ]);
            //     $email->SMTPOptions = array(
            //         'ssl' => array(
            //             'verify_peer' => false,
            //             'verify_peer_name' => false,
            //             'allow_self_signed' => true
            //         )
            //     );
            // $mailer->send($email);
            $em = $this->getDoctrine()->getManager();
            $em->persist($session);
            $em->flush();
            $nbStagiairesAfter = count($session->getStagiaires());
            if($nbStagiairesAfter < $nbStagiaires){
                $this->addFlash('success', 'stagiaire(s) retiré(s) avec succés');
            }
            else{
                $this->addFlash('success', 'stagiaire(s) ajouté(s) avec succés');
            }
            return $this->redirectToRoute('programme',["id" => $session->getId()]);
            
        }
        $toto= $session;
        return $this->render('session/index.html.twig', [
            'thisSession' => $session,
            'tab'     => $tab,  
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
        if(!$categorie){
            $categorie = new categorie();
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
        $formProgramme = $this->createForm(ProgrammeType::class, $programme);
        
        $formProgramme->handleRequest($request);
        $programme->setSession($session);
        if($formProgramme->isSubmitted() && $formProgramme->isValid()){
            $em = $this->getDoctrine()->getManager();
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
            return $this->redirectToRoute('CreaProgramme',["id" => $session->getId()]);
        }
       
        
        return $this->render('session/CreaProgramme.html.twig', [
            'thisSession' => $session,
            'formProgramme' => $formProgramme->createView(),
            'formModule' => $formModule->createView(),
            'formCategorie' => $formCategorie->createView()
        ]);
    }
     /**
     * @Route("programme_delete", name="programme_delete", methods={"GET"})
     */
    public function delete(Request $request, ProgrammeRepository $programmeRep, SessionRepository $sessionRep): Response
    {
        $id = $request->get("data");
        $sessionId = $request->get("session");
        $programme = $programmeRep->findOneBy(["id" => $id]);
       $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($programme);
        $entityManager->flush();
        
        $session = $sessionRep->findOneBy(["id" => $request->get("id_session")]);
        return $this->redirectToRoute('programme',["id" => $sessionId]);
    }
    
     /**
     * @Route("/delete_session", name="session_delete", methods={"GET"})
     */
    public function deleteSession(Request $request,SessionRepository $sessionRep)
    {
        $id = $request->get("data");
        $session = $sessionRep->findOneBy(["id" => $id]);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($session);
        $entityManager->flush();        
       
        return $this->redirectToRoute('home');
    }

     /**
     * @Route("/affich_module", name="affich_module", methods={"GET"})
     */
    public function affichModule(Request $request,ModuleRepository $moduleRep,CategorieRepository $catRep,Module $module = null)
    {
        $id = $request->get("data");
       
        $modules = $moduleRep->findAll();
        $categories = $catRep->findAll();
        // dump($categories);die;
        // $entityManager = $this->getDoctrine()->getManager();
        // $entityManager->remove($session);
        // $entityManager->flush();        
       
        return $this->render('session/affichModule.html.twig', [
            'modules' => $modules,
            "categories" => $categories
        ]);
    }
     /**
      * @Route("/session/ModifModule/{id}", name="ModifModule"))
     */
    public function mofifModule(Request $request,ModuleRepository $moduleRep,CategorieRepository $catRep,Module $module = null)
    {
        $id = $request->get("data");
        $nom = $module->getNom();
        
        if(!$module){
        $module = new Module();
        }
        // dump($cat);die;
        $formModule = $this->createForm(ModuleType::class, $module);
        $formModule->remove("categorie");
        $formModule->handleRequest($request);
        if($formModule->isSubmitted() && $formModule->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($module);
            $em->flush();
            return $this->redirectToRoute('affich_module');
        }
       
       
        return $this->render('session/modifModule.html.twig', [
            'formModule' => $formModule->createView()
        ]);
    }
    /**
      * @Route("/session/ModifCategorie/{id}", name="ModifCategorie"))
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
