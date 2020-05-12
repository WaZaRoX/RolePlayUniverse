<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\UniverseData;
use App\Entity\Personnage;
use App\Repository\PersonnageRepository;
use App\Form\CreatePersoFormType;

class PersonnageController extends AbstractController
{
    /**
     * @Route("/personnage", name="personnage")
     */
    public function index(Request $request, UniverseData $universeData)
    {
        $options = ['controller_name' => 'PersonnageController'];
        if($this->getUser() != null) {
            $idUniverse = $universeData->getIdUniverseInSession();
            // --- User perso ---
            $userPersos = $this->getDoctrine()->getRepository(Personnage::class)->findBy(['universe' => $idUniverse, 'user' => $this->getUser()->getId()]);
            $options += ['userPeros' => $userPersos];

            // --- If the user is an admin he haves access to every perso of the universe
            if($universeData->isPermissionGranted($this->getUser()->getId(), $idUniverse,'PersonnageController')){
                $allPersos = $this->getDoctrine()->getRepository(Personnage::class)->findBy(['universe'=>$idUniverse]);
                $options += ['allPersos' => $allPersos, 'admin' => true];
            }else{
                $options += ['admin' => false];
            }
        }
        return $this->render('personnage/persoList.html.twig', $options);
    }

    /**
     * @Route("create_perso", name="create_perso")
     * @param UniverseData $universeData
     * @return Response
     */
    public function createPersonnage(Request $request, UniverseData $universeData)
    {
        $options = ['controller_name' => 'PersonnageController',];
        if ($this->getUser() != null && !empty($this->getUser())) {
            $perso = new Personnage();
            $form = $this->createForm(CreatePersoFormType::class, $perso);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($perso);
                $entityManager->flush();
                return $this->redirectToRoute('personnage');
            }
            $options += ['persoForm' => $form->createView()];
        }
        dump($options);
        return $this->render('personnage/createPerso.html.twig', $options);
    }
}