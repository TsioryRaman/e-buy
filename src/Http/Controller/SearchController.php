<?php

namespace App\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController{

    /**
     * Undocumented function
     * @Route("/search", "search")
     */
    public function search(Request $request):Response
    {
        $q = $request->query->get('q');
        return $this->render('pages/search.html.twig',[
            'q' => $q
        ]);
    }

}