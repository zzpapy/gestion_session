<?php

namespace App\Controller;

use App\Repository\SessionRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class HomeController extends AbstractController
{
    /**
     * @Route("/admin/home", name="home")
     */
    public function index(SessionRepository $session)
    {
        $sessions = $session->findAll();
        
        return $this->render('home/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     * @ ("/home/admin", name="home")
     */
    public function home(SessionRepository $session)
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
    
    
        $sessions = $session->findAll();
        // foreach ($sessions as  $session) {
        //     foreach ($session->getStagiaires() as  $stagiaire) {
        //         // dump($stagiaire);
        //     }
        // }
        
        // $session = $this->get("session");
        // $session->set('sessions',$sessions);
        // dump($this->get("session"));die;
        return $this->render('home/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }
}
