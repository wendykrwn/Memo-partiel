<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ObjectManager;
use \App\Entity\Memo;
use App\Form\MemoType;

class MemoController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        return $this->render('memo/index.html.twig', [
            'controller_name' => 'MemoController',
        ]);
    }


    /**
     * @Route("/memo.test", name="memo_create")
     */
    public function create(Request $request,ObjectManager $manager){
        $memo = new Memo();
        $form = $this->createForm(MemoType::class, $memo);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //TODO : Rajouter une date de création

            $manager->persist($memo);
            $manager->flush();

            //TODO : Rediriger vers la page show
        }

        return $this->render('memo/create.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
