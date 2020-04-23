<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CreateUniverseType;
use App\Entity\Universe;
use App\Entity\Community;
//use App\Entity\User;

class UniverseController extends AbstractController
{
    /**
     * @Route("/universe", name="universe")
     *
     *
     */
    public function index(Request $request): Response
    {
        $options = ['controller_name' => 'UniverseController',];
        $communityRepository = $this->getDoctrine()->getRepository(Community::class);

        // --- All Universes ----
         $communities = $communityRepository->findBy(["statut" => 0]);
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
                $community->setStatut(0);
                $entityManager->persist($community);
                $entityManager->flush();
                return $this->redirectToRoute('index');
            }
            $options += ['univForm' => $form->createView()];
        }
        return $this->render('universe/index.html.twig', $options);
    }
}
