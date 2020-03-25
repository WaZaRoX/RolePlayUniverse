<?php
// src/Controller/test.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response; //--> pour le return response
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; // Pour utiliser twig

class DefaultController extends AbstractController
{
    public function index()
    {
        return $this->render('index.html.twig', [
        ]);
    }
}