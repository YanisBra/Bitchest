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
        
        // Utilise le service pour récupérer les données des cryptos
        $CryptoData = $coinrankingApiService->getCryptoData();

        // Retourne une réponse, par exemple une redirection ou une vue
        return $this->render('home/index.html.twig', ['cryptoData' => $CryptoData
        
    ]);
    }

    
}
