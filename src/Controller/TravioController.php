<?php

namespace App\Controller;

use App\Service\TravioService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/travio', name: 'api_travio_')]
class TravioController extends AbstractController
{

    public function __construct(private TravioService $travioService){
    }

    #[Route('/search/hotels', name: 'search_hotels', methods: ['POST'])]
    public function searchHotels(Request $request): Response
    {
        $content = $request->toArray();
        $fromDate = $content['from'];
        $toDate = $content['to'];

        $response = $this->travioService->searchHotels($fromDate, $toDate);

        return new JsonResponse($response->toArray(false), $response->getStatusCode(), ["Content-Type" => 'application/json']);
    }

}
