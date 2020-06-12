<?php

namespace App\Controller;

use App\Entity\ListMat;
use App\Form\ListMatType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListMatController extends AbstractController
{
    /**
     * @Route("/list/mat", name="creaListMat")
     */
    public function index(Request $request,ListMat  $listMat = null)
    {
        if(!$listMat){
            $listMat = new ListMat();
        }
        $form = $this->createForm(ListMatType::class, $listMat);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($listMat);
            $em->flush();
        }
        return $this->render('list_mat/creaListMat.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
