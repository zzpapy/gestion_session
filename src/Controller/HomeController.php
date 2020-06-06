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
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/home", name="home")
     */
    public function index(SessionRepository $session)
    {
        $sessions = $session->findBy([], ['date_debut' => 'DESC']);;
        
        return $this->render('home/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }
   
}
