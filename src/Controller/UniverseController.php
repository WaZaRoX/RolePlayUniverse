<?php

namespace App\Controller;

use App\Entity\Statut;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CreateUniverseType;
use App\Entity\Universe;
use App\Entity\Community;
use App\Service\UniverseData;

//use App\Entity\User;

class UniverseController extends AbstractController
{
    /**
     * @Route("/universe", name="universe")
     *
     */
    public function universeTab(Request $request): Response
    {
        $options = ['controller_name' => 'UniverseController',];
        $communityRepository = $this->getDoctrine()->getRepository(Community::class);


        // --- All Universes ----
        $communities = $communityRepository->findBy(["statut" => 1]);
        $options += ['allCommunities' => $communities];

        if ($this->getUser() != null && !empty($this->getUser())) {
            // User universes
            $options += ['userCommunities' => $this->getUser()->getCommunities()];

            // --- Creation universe ---
            // Formulaire de création
            $universe = new Universe();
            $form = $this->createForm(CreateUniverseType::class, $universe);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($universe);
                $entityManager->flush();

                $community = new Community();
                $community->setUniverse($universe);
                $community->setUser($this->getUser());
                $statutRepository = $this->getDoctrine()->getRepository(Statut::class);
                $statut = $statutRepository->find("1");
                $community->setStatut($statut);
                $entityManager->persist($community);
                $entityManager->flush();
                return $this->redirectToRoute('setUniverse', array('id'=> $universe->getId()));
            }
            $options += ['univForm' => $form->createView()];
        }
        return $this->render('universe/universeTab.html.twig', $options);
    }


    /**
     * @Route("/universeIndex", name="universeIndex")
     *
     */
    public function indexUniverse(Request $request,UniverseData $universeData)
    {
        $universe = $universeData->getUniverseInSession();
        $options = ['universeName' => $universe->getLabel(),
            'universeResume' => $universe->getResumeUniverse(),
            'universeRules' => $universe->getRules(),
        ];
        $com = $universe->getCommunityByUser($this->getUser());
        if($com !== null && $com->getStatut()->getId() == 1){
            $options += ['admin' => true];
            $form = $this->createForm(CreateUniverseType::class, $universe);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($universe);
                $entityManager->flush();
                return $this->redirectToRoute('index');
            }
            $options += ['univForm' => $form->createView()];
        }else{
            $options += ['admin' => false];
        }
        return $this->render('universe/universeHome.html.twig',$options);
    }

    /**
     * @Route("/setUniverse/{id}", name="setUniverse")
     *
     */
    public function setUniverseSession($id, UniverseData $universeData){
        $universeData->setUniverseSession($id);
        return $this->redirectToRoute('index');
    }

}
