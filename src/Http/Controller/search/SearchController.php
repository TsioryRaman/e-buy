<?php

namespace App\Http\Controller\search;

use App\Http\Controller\BaseController;
use App\Infrastructure\Search\SearchInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends BaseController
{

    /**
     * @Route("/search", "search")
     */
    public function search(Request $request, SearchInterface $search): Response
    {
        $q = $request->query->get('q');
        // http://typesense:8108/collections/content/documents/search?q=ts&query_by=name
        return $this->render('pages/search.html.twig', [
            'q' => $q,
            'results' => $search->search($q)['hits']
        ]);
    }

    /**
     * @Route("/api/search", "search.api")
     */
    public function searchApi(Request $request, SearchInterface $search): Response
    {
        $q = $request->query->get('q');
        // http://typesense:8108/collections/content/documents/search?q=ts&query_by=name
        return $this->json($search->search($q)['hits']);
    }
}
