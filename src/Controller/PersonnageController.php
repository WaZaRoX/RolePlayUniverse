<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\UniverseData;
use App\Entity\Personnage;
use App\Entity\User;
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
            $options += ['userPersos' => $userPersos];

            // --- If the user is an admin he haves access to every perso of the universe
            if($universeData->getCommunityCurrentUserUniverse() != null && $universeData->getCommunityCurrentUserUniverse()->getStatut()->getId() < 4){
                $allPersos = $this->getDoctrine()->getRepository(Personnage::class)->findBy(['universe'=>$idUniverse]);
                $options += ['allPersos' => $allPersos];
                $options += ['admin' => true];
            }else{
                $options += ['admin' => false];
            }
        }
        return $this->render('personnage/persoList.html.twig', $options);
    }

    /**
     * @Route("create_perso", name="create_perso")
     * @param Request $request
     * @param UniverseData $universeData
     * @return Response
     */
    public function createPersonnage(Request $request, UniverseData $universeData)
    {
        $options = ['controller_name' => 'PersonnageController',];
        if ($this->getUser() != null && !empty($this->getUser())) {
            $perso = new Personnage();
            //pOUR AJOUTER DES PARENTS
            $allPersos = $this->getDoctrine()->getRepository(Personnage::class)->findBy(['universe'=>$universeData->getIdUniverseInSession()]);
            $options += ['allPersos' => $allPersos];
            // Preset Parents
            foreach($allPersos as $persoParent){
                $perso->addParent($persoParent);
            }

            $form = $this->createForm(CreatePersoFormType::class, $perso);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $perso->setUser($this->getUser());
                $perso->setUniverse($universeData->getUniverseInSession());
                foreach($form['parents'] as $parent){
                    if(!$parent['isChecked']->getData()){
                        $perso->removeParent($parent->getData());
                    }
                }
                //valide si admin le créé sinon il faudra attendre la validation d'un admin
                $perso->setValide($universeData->getCommunityCurrentUserUniverse() != null && $universeData->getCommunityCurrentUserUniverse()->getStatut()->getId() < 4);
                $entityManager->persist($perso);
                $entityManager->flush();
                return $this->redirectToRoute('personnage');
            }
            $options += ['persoForm' => $form->createView()];
        }
        return $this->render('personnage/createPerso.html.twig', $options);
    }


    /**
     * @Route("edit_perso/{id}", name="edit_perso")
     * @param $id
     * @param Request $request
     * @param UniverseData $universeData
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editPersonnage($id, Request $request, UniverseData $universeData){
        $options = ['controller_name' => 'PersonnageController',];
        if ($this->getUser() != null && !empty($this->getUser())) {
            $personnageRepository =  $this->getDoctrine()->getRepository(Personnage::class);
            $perso = $personnageRepository->find($id);
            $idUniverse = $universeData->getIdUniverseInSession();
            //pOUR AJOUTER DES PARENTS
            $allPersos = $this->getDoctrine()->getRepository(Personnage::class)->findBy(['universe'=>$idUniverse]);
            $options += ['allPersos' => $allPersos];

            // l'éditeur doit être soit le propriétaire du perso soit un admin, et le perso doit être de l'univers courrant
            if($perso != null && ($perso->getUser()->getId() == $this->getUser()->getId()
                    || ($universeData->getCommunityCurrentUserUniverse() != null && $universeData->getCommunityCurrentUserUniverse()->getStatut()->getId() < 4))
                && $perso->getUniverse()->getId() == $idUniverse) {

                // Preset Parents
                foreach($allPersos as $persoParent){
                    $perso->addParent($persoParent);
                }

                //

                $form = $this->createForm(CreatePersoFormType::class, $perso);
                // Pour l'ajout de parent au personnage liste perso s'ajoute
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager = $this->getDoctrine()->getManager();

                    foreach($form['parents'] as $parent){
                        if(!$parent['isChecked']->getData()){
                            $perso->removeParent($parent->getData());
                        }
                    }

                    //valide si admin le créé sinon il faudra attendre la validation d'un admin
                    $perso->setValide($universeData->getCommunityCurrentUserUniverse() != null && $universeData->getCommunityCurrentUserUniverse()->getStatut()->getId() < 4);
                    $entityManager->persist($perso);
                    $entityManager->flush();
                    return $this->redirectToRoute('personnage');
                }
                $options += ['persoForm' => $form->createView()];
            }
        }
        return $this->render('personnage/editPerso.html.twig', $options);
    }

    /**
     * @Route("remove_perso/{id}", name="remove_perso")
     * @param $id
     * @param Request $request
     * @param UniverseData $universeData
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function removePersonnage($id, Request $request, UniverseData $universeData)
    {
        $options = ['controller_name' => 'PersonnageController',];
        if ($this->getUser() != null && !empty($this->getUser())) {
            $personnageRepository = $this->getDoctrine()->getRepository(Personnage::class);
            $perso = $personnageRepository->find($id);
            // l'éditeur doit être soit le propriétaire du perso soit un admin, et le perso doit être de l'univers courrant
            if($perso != null && ($perso->getUser()->getId() == $this->getUser()->getId()
                    || ($universeData->getCommunityCurrentUserUniverse() != null && $universeData->getCommunityCurrentUserUniverse()->getStatut()->getId() < 4))
                && $perso->getUniverse()->getId() == $universeData->getIdUniverseInSession())
            {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($perso);
                    $entityManager->flush();
            }
        }
        return $this->redirectToRoute('personnage');
    }

    /**
     * @Route ("familytree/{idPerso}", name="familytree")
     * @param UniverseData $universeData
     * @param $idPerso
     * @return Response
     */
    public function makeFamilyTree($idPerso, UniverseData $universeData){
        $options = ['controller_name' => 'PersonnageController',];
        $personnageRepository =  $this->getDoctrine()->getRepository(Personnage::class);
        $perso = $personnageRepository->find($idPerso);
        $jsonTab = [];
        foreach($perso->getParents() as $parent) {
            $jsonPerso = ["nom" => $parent->getNom(), "prenom" => $parent->getPrenom()];
            array_push($jsonTab, $jsonPerso);
        }

        dump(json_encode($jsonTab));
        $options += ["perso" => $perso,"jsonTab" => json_encode($jsonTab)];



        return $this->render('personnage/familyTree.html.twig', $options);
    }

}