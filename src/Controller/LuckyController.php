<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LuckyController extends AbstractController
{
        /**
     * @Route("/number")
     */

public function number(): Response
    {
        $number = random_int(0, 100);

        return $this->render('lucky/number.html.twig', ['number' => $number]);
        
                // return $this->render('base.html.twig', ['number' => $number]);

        
        // return new Response(
        //     '<html><body>Lucky number: '.$number.'</body></html>'
        // );
    }
}