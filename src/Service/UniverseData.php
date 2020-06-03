<?php

namespace App\Service;

use App\Entity\Community;
use App\Entity\Statut;
use App\Entity\Universe;
use App\Repository\StatutRepository;
use App\Repository\UniverseRepository;
use App\Repository\CommunityRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;

class UniverseData{

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var CommunityRepository
     */
    private $communityRepository;

    /**
     * @var UniverseRepository
     */
    private $universeRepository;

    /**
     * @var StatutRepository
     */
    private $statutRepository;

    /**
     * @var Security
     */
    private $security;

    /**
     * @var
     */
    private $em;


    /**
     * universeData constructor.
     * @param SessionInterface $session
     * @param CommunityRepository $communityRepository
     * @param StatutRepository $statutRepository
     */
    public function __construct(SessionInterface $session, CommunityRepository $communityRepository, UniverseRepository $universeRepository, StatutRepository $statutRepository, Security $security,EntityManagerInterface $em)
    {
        $this->session = $session;
        $this->communityRepository = $communityRepository;
        $this->universeRepository = $universeRepository;
        $this->statutRepository = $statutRepository;
        $this->security = $security;
        $this->em = $em;
    }

    /**
     * @return int|mixed
     */
    public function getIdUniverseInSession(){
        if(($this->session->get('universeId')) != null) {
            $id = $this->session->get('universeId');
        }else{
            $id = 1;
            $this->setUniverseSession($id);
        }
        return $id;
    }

    public function getSession(){
        return $this->session;
    }

    /**
     * @return Universe|null
     */
    public function getUniverseInSession(){
        return $this->universeRepository->find($this->getIdUniverseInSession());
    }

    /**
     * @param $id
     */
    public function setUniverseSession($id){
        if(($universe = $this->universeRepository->find($id)) != null) {
            $this->session->set('universeId', $id);
            $this->session->set('universeName', $universe->getLabel());
            $user = $this->security->getUser();
            //--- L'utilisateur doit s'inscrire à l'univers en tant que non membre ---
            if ($user != null && $this->communityRepository->findBy(["user" => $user->getId(), "universe" => $id]) == null) {
                $community = new Community();
                $community->setUser($user);
                $community->setUniverse($universe);
                $community->setStatut($this->statutRepository->find("5"));
                $this->em->persist($community);
                $this->em->flush();
            }
        }
    }

    /**
     * @return Community|null
     */
    public function getCommunityCurrentUserUniverse(){
        $universeId = $this->getIdUniverseInSession();
        $userId = $this->security->getUser()->getId();
        return $this->communityRepository->findOneBy(["user" => $userId, "universe" => $universeId]);
    }
}