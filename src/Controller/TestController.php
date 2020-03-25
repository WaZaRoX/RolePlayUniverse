<?php
// src/Controller/test.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response; //--> pour le return response
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; // Pour utiliser twig

class TestController extends AbstractController
{

    /**
     * @Route("/test/{max}", name="app_test")
     */
    public function number($max) // lancer par le route ci-dessus
    {
        $number = random_int(0, $max);

        // Utilisation twig
        return $this->render('test/number.html.twig', [ // affichage dans templates/test/number.html.twig
            'number' => $number,
        ]);

//        return new Response(
//            '<html><body>Lucky number: '.$number.'</body></html>'
//        );
    }
}