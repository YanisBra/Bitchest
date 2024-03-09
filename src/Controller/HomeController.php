<?php

namespace App\Controller;

use App\Service\CoinrankingApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function fetchData(CoinrankingApiService $coinrankingApiService): Response
    {
        
        $CryptoData = $coinrankingApiService->getCryptoData();

        return $this->render('home/index.html.twig', ['cryptoData' => $CryptoData
        
    ]);
    }

    
}
