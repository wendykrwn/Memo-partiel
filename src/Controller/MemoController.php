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
            $memo->setCreatedAt(new \DateTime());

            $manager->persist($memo);
            $manager->flush();

            return $this->redirectToRoute('memo_show', [ "id" => $memo->getId()]);
        }

        return $this->render('memo/create.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("memo/{id}", name="memo_show")
     */
    public function show(Memo $memo){
        $heureExpiration = $memo->getCreatedAt();
        $delaiExpiration = $memo->getDelaiExpiration();
        $heureExpiration->modify("+$delaiExpiration minutes");

        if( new \DateTime() > $heureExpiration){
            $response = new Response();
            $response->setStatusCode(410);

            return $response;
        }

        return $this->render('memo/show.html.twig', [
            'memo' => $memo,
            'heureExpiration' => $heureExpiration
        ]);
    }
}
