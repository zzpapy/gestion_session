<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Module;
use App\Entity\Session;
use App\Entity\Vacances;
use App\Form\ModuleType;
use App\Entity\Categorie;
use App\Entity\Programme;
use App\Entity\Stagiaire;
use App\Form\SessionType;
use App\Form\VacancesType;
use App\Form\CategorieType;
use App\Form\ProgrammeType;
use App\Form\AddStagiaireType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Mime\Email;
use App\Repository\ModuleRepository;
use App\Repository\SessionRepository;
use App\Repository\CategorieRepository;
use App\Repository\ProgrammeRepository;
use App\Repository\StagiaireRepository;
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
     * @Route("/admin/session/ModifSession/{id}", name="ModifSession")
     * @Route("/admin/session/createSession", name="createSession")
     */
    public function createSession(Request $request,Session $session = null)
    {
        
        if(!$session){
            $session = new session();
        }
        $form = $this->createForm(SessionType::class, $session);
        if(isset($request->request->get("session")["stagiaires"])){
            if(count($request->request->get("session")["stagiaires"])>$request->request->get("session")["nb_places"]){
                // dump($request->request->get("session"));die;
                $this->addFlash('error', 'vous avez séléctionné trop de stagiaires');
                $form->remove("stagiaires");
                $form->add("stagiaires");
                if($session->getId() != null){
                    return $this->redirectToRoute('ModifSession',["id" => $session->getId()]);
                }
                // else{
                //     return $this->redirectToRoute('home');
                // }
            }
        }
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $dateDeb = $request->request->get("session")["date_debut"];
            $dateFin = $request->request->get("session")["date_fin"];
            $dateDeb = new \Datetime(implode('-',$dateDeb));
            $dateFin = new \Datetime(implode('-',$dateFin));
            // dump($dateFin > $dateDeb);die;
            if($dateFin < $dateDeb || $dateFin == $dateDeb){
                    $this->addFlash('error', 'le date de fin de session ne peut inférieure à celle du début !!!');
                    return $this->redirectToRoute('createSession');
                }
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
     * @Route("admin/session/addStagiaireSess", name="/session/addStagiaireSess", methods={"GET"})
     * @Route("admin/session/{id<\d+>}", name="programme")
     */
    public function index(StagiaireRepository $stagiaireRep, Stagiaire $stagiaire = null,MailerInterface $mailer, Session $session = null, SessionRepository $sessRep,Request $request)
    {
        // dump($request->request->get("add"));die;
        $session = $sessRep->findOneBy(["id"=> $request->get("id")]);
        if($request->request->get("remove")){
            $stagiaire = $this->getDoctrine()
                            ->getRepository(Stagiaire::class)
                            ->find($request->request->get("add_stagiaire")['stagiaires'][0]);
                $session->removestagiaire($stagiaire);
                // dump($session->getStagiaires()->contains($stagiaire));die;
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($session);
                $em->flush();
                $this->addFlash('success', 'stagiaire(s) retiré(s) avec succés');
                return $this->redirectToRoute('programme',["id" => $session->getId()]);
                // return  new Response( "false" );
            }
            else if($request->request->get("add")){
                $nb_stagiaires = count($session->getStagiaires());
                $nb_places = $session->getNbPlaces();
                
                if($nb_stagiaires >= $nb_places){
                    $this->addFlash('error', 'le stagiare est déjà inscrit à une autre formation durant cette période !!!');
                    return $this->redirectToRoute('programme',["id" => $session->getId()]);
                }
                $stagiaire = $this->getDoctrine()
                ->getRepository(Stagiaire::class)
                ->find($request->request->get("add_stagiaire")['stagiaires'][0]);
                foreach ($stagiaire->getSessions() as  $stagsess) {
                    $dateDeb = $stagsess->getDateDebut();
                    $dateFin = $stagsess->getDateFin();
                    $deb = $session->getDateDebut();
                    $fin = $session->getDateFin();
                    
                    if($dateDeb > $deb && $dateFin < $fin || $dateDeb < $deb && $dateFin < $fin || $dateDeb < $deb && $dateFin > $fin){
                        $this->addFlash('error', 'le stagiaire est déjà en formation durant cette période');
                        return $this->redirectToRoute('programme',["id" => $session->getId()]);
                    }
                }
                $session->addStagiaire($stagiaire);
                $em = $this->getDoctrine()->getManager();
                $em->persist($session);
                $em->flush();
                $session->addStagiaire($stagiaire);
                $this->addFlash('success', 'stagiaire(s) ajouté(s) avec succés');
                return $this->redirectToRoute('programme',["id" => $session->getId()]); 
                // return  new Response( "true" );
            }
            
         
        
        $programmes = $session->getProgrammes();
        // $session = $sessRep->findOneBy(["id" => $request->get("id")]); 
        $tab=[];
        foreach ($programmes as $key => $programme) {
            // dump($programme->getModule()->getCategorie()->getNom());die;
            if(array_key_exists($programme->getModule()->getCategorie()->getNom(),$tab)){
                array_push($tab[$programme->getModule()->getCategorie()->getNom()],$programme);
            }
            else{
                $tab[$programme->getModule()->getCategorie()->getNom()] = [$programme];
            }
        }
        $tps_session = 0;
        foreach ($session->getProgrammes() as $value) {
            $tps = $value->getDuree();
            $tps_session = $tps_session + $tps;
        }
        $debut = $session->getDateDebut();
        $fin = $session->getDateFin();
        $fin->modify('+1 day');
        $longueur = $fin->diff($debut);
        $days = $longueur->days;
        $period = new \DatePeriod($debut, new \DateInterval('P1D'), $fin);
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
        // dump($days);die;
        foreach($period as $dt) {
            $curr = $dt->format('D');
            if ($curr == 'Sat' || $curr == 'Sun') {
                $days--;
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
            'form'    => $form->createView(),
            'longueur' => $days,
            'tps_session' => $tps_session,
            'vacances' => $vacances
        ]);
    }
    
    
     /**
     * @Route("/admin/delete_session", name="session_delete", methods={"GET"})
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
     * @Route("/vacances/{id<\d+>}", name="vacances")
     */
    public function addVacances(Request $request,Session $session,Vacances $vacances = null)
    {
        if(!$vacances){
            $vacances = new Vacances();
        }
        $formVacances = $this->createForm(VacancesType::class, $vacances);
        $formVacances->handleRequest($request);
        $tabVacances = $session->getVacances(); 
        if($formVacances->isSubmitted() && $formVacances->isValid()){
            
            $debVac =new \Datetime( implode('-',$request->request->get('vacances')["date_debut"]));
            $finVac =new \Datetime( implode('-',$request->request->get('vacances')["date_fin"]));
            $periodVac = new \DatePeriod($debVac, new \DateInterval('P1D'), $finVac);
            $debSess = $session->getDateDebut();
            $finSess = $session->getDateFin();
            if($finVac < $debVac){
                $this->addFlash('error', 'Ces dates ne sont pas valides');
                return $this->redirectToRoute('vacances',["id" => $session->getId()]);
            }
            for ($i=0; $i < count($tabVacances); $i++) { 
                $debSessVac = $tabVacances[$i]->getDateDebut();
                $finSessVac =$tabVacances[$i]->getDateFin();
                // dump($debSess < $debVac);die;
                if($debVac < $debSessVac && $finVac > $debSessVac || $debVac > $debSessVac && $finVac < $finSessVac || $debVac > $debSessVac && $finVac > $finSessVac &&  $debVac < $finSessVac || $debVac == $debSessVac){
                    $this->addFlash('error', 'Cette pèriode de vacances en chevauche une autre');
                    return $this->redirectToRoute('vacances',["id" => $session->getId()]);
                }
            //     $periodVacSess = new \DatePeriod($debSessVac, new \DateInterval('P1D'), $finSessVac);
            //     foreach($periodVac as $dt) {
            //         $curr = $dt->format('Y-m-d');
            //         foreach($periodVacSess as $date) {
            //             $currVac = $date->format('Y-m-d');
            //             dump($currVac,$curr);
            //             if ($currVac == $curr) {
            //                 $this->addFlash('error', 'des vacances on déjà été positionnées à ces dates');
            //                 return $this->redirectToRoute('vacances',["id" => $session->getId()]);
            //             }
            //         }
            //     }
            //     // dump($periodVac);die;
            }
            $diffDeb = $debSess < $debVac;
            if($diffDeb){
                if($finVac > $finSess){
                    // dump($diffDeb);die;
                    $this->addFlash('error', 'Cette pèriode de vacances est non valide tata');
                    return $this->redirectToRoute('vacances',["id" => $session->getId()]);
                }    
                
            }
        
            $periodSess = new \DatePeriod($debSess, new \DateInterval('P1D'), $finSess);
            $vacances->setSession($session);
            $em = $this->getDoctrine()->getManager();
            $em->persist($vacances);
            $em->flush();
            $this->addFlash('success', 'Vacances bien enregistrées');
            return $this->redirectToRoute('programme',["id" => $session->getId()]);

            
        }
       
        return $this->render('session/vacances.html.twig', [
            
            'formVacances' => $formVacances->createView(),
            'session' => $session,
            'tabVacances' => $tabVacances
        ]);
    }
    /**
     * @Route("/admin/delVacances/{id<\d+>", name="delVacances", methods={"GET"})
     */
    public function delVacances(Request $request,ModuleRepository $moduleRep,Vacances $vacances = null)
    {
        $id_vacances = $request->get("id");
        $vacances = $this->getDoctrine()
        ->getRepository(Vacances::class)
        ->find($id_vacances);
        // dump($vacances->getSession());die;
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($vacances);
        $entityManager->flush();        
       
        return $this->redirectToRoute('programme',["id" => $vacances->getSession()->getId()]);
    }
     /**
     * Export to PDF
     * 
     * @Route("/admin/session/pdf/{id<\d+>}/{id_stagiaire<\d+>}", name="acme_demo_pdf")
     */
    public function pdfAction(Request $request,Session $session = null,StagiaireRepository $stagiaireRep )
    {

        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->setIsRemoteEnabled(true);
        $stagiaire = $stagiaireRep->findOneBy(["id" =>$request->get("id_stagiaire")]);
        if($stagiaire->getPhoto() != null){
            $path = '../public/img/'.$stagiaire->getPhoto();
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        else{
            $base64 = "";
        }
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
    
        $dompdf = new Dompdf($options);
        $data = array(
        'title' => 'my headline'
        );
        $html = $this->renderView('session/pdf.html.twig', [
            'title' => "Attestation de formation",
            'session' => $session,
            'stagiaire' => $stagiaire,
            'tab' => $tab,
            'photo' => $base64
        ]);
        
    
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("attestation_".$stagiaire->getNom().".pdf", [
            "Attachment" => false
        ]);
    }
}
