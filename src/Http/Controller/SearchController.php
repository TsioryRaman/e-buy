<?php

namespace App\Http\Controller;

use App\Infrastructure\Search\SearchInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController{

    /**
     * @Route("/search", "search")
     */
    public function search(Request $request, SearchInterface $search):Response
    {
        $q = $request->query->get('q');
        // http://typesense:8108/collections/content/documents/search?q=ts&query_by=name
        return $this->render('pages/search.html.twig',[
            'q' => $q,
            'results' => $search->search($q)['hits']
        ]);
    }

}