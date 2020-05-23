<?php

namespace App\Controller;

use App\Repository\SessionRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(SessionRepository $session)
    {
        $sessions = $session->findAll();
        foreach ($sessions as  $session) {
            foreach ($session->getStagiaires() as  $stagiaire) {
                // dump($stagiaire);
            }
        }
        $session = $this->get("session");
        $session->set('sessions',$sessions);
        // dump($this->get("session"));die;
        return $this->render('home/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }
}
