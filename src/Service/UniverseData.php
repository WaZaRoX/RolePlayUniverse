<?php

namespace App\Service;

use App\Entity\Universe;
use App\Repository\UniverseRepository;
use App\Repository\CommunityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
     * universeData constructor.
     * @param SessionInterface $session
     * @param CommunityRepository $communityRepository
     */
    public function __construct(SessionInterface $session, CommunityRepository $communityRepository, UniverseRepository $universeRepository)
    {
        $this->session = $session;
        $this->communityRepository = $communityRepository;
        $this->universeRepository = $universeRepository;
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

    /**
     * @Route("/setUniverse/{id}", name="setUniverse")
     *
     */
    public function setUniverseSession($id){
        $this->session->set('universeId', $id);
        $universe = $this->universeRepository->find($id);
        $this->session->set('universeName', $universe->getLabel());
    }

    /**
     * @param int $idUser
     * @param int $idUniverse
     * @param String $controllerName
     * @return bool
     */
    public function isPermissionGranted(int $idUser,int $idUniverse,String $controllerName){
        $res = false;
        if(count($communities = $this->communityRepository->findBy(["user"=>$idUser, "universe"=>$idUniverse])) > 0){
            $idStatut = $communities[0]->getStatut()->getId();
            switch ($controllerName){
                case "PersonnageController":
                    $res = $idStatut <= 3;
                    break;
            }
        }else{
            $res = false;
        }
        return $res;
    }
}