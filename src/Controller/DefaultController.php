<?php
// src/Controller/test.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    public function index(Request $request) : Response
    {
        //page d'accueil affiche les informations de bases sur l'univers auquel on est connecé ou les informations de bases sur le site
        // l'index va donc faire appel à UniverseController
        $response = $this->forward('App\Controller\UniverseController::indexUniverse', [
            'request'  => $request,
        ]);

        return $response;
        //return $this->render('index.html.twig', [
        //]);
    }
}