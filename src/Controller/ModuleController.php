<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ModuleType;
use App\Entity\Categorie;
use App\Form\Module1Type;
use App\Form\CategorieType;
use App\Repository\ModuleRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ModuleController extends AbstractController
{
    /**
     * @Route("/admin/affich_module", name="affich_module")
     */
    public function affichModule(Request $request,ModuleRepository $moduleRep,CategorieRepository $catRep,Module $module = null)
    {
        $id = $request->get("data");
       
        $modules = $moduleRep->findAll();
        $categories = $catRep->findAll();
        
       
            $module = new Module();
            $formModule = $this->createForm(ModuleType::class, $module);
            $formModule->handleRequest($request);
            if($formModule->isSubmitted() && $formModule->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($module);
                $em->flush();
                return $this->redirectToRoute('affich_module');
            }

      
            $categorie = new Categorie();
            $formCategorie = $this->createForm(CategorieType::class, $categorie);
            $formCategorie->handleRequest($request);
            if($formCategorie->isSubmitted() && $formCategorie->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($categorie);
                $em->flush();
                return $this->redirectToRoute('affich_module');
            }
           
       
        return $this->render('session/affichModule.html.twig', [
            'modules' => $modules,
            "categories" => $categories,
            "formCategorie" => $formCategorie->createView(),
            "formModule" => $formModule->createView()
        ]);
    }
     /**
      * @Route("/admin/session/ModifModule/{id}", name="ModifModule"))
     */
    public function mofifModule(Request $request,ModuleRepository $moduleRep,CategorieRepository $catRep,Module $module = null)
    {
        $id = $request->get("data");
        $nom = $module->getNom();
        
        if(!$module){
            $module = new Module();
        }
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
     * @Route("/admin/delete_module", name="module_delete", methods={"GET"})
     */
    public function deleteModule(Request $request,ModuleRepository $moduleRep)
    {
        $id = $request->get("data");
        $module = $moduleRep->findOneBy(["id" => $id]);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($module);
        $entityManager->flush();        
       
        return $this->redirectToRoute('home');
    }
}
