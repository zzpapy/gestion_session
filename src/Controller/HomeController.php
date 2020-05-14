<?php

namespace App\Controller;

use App\Repository\SessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
                dump($stagiaire);
            }
        }
        // die;
        return $this->render('home/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }
}
