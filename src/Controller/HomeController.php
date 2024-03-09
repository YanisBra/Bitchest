<?php

namespace App\Controller;

use App\Service\CoinrankingApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur gérant la page d'accueil de l'application.
 */
class HomeController extends AbstractController
{
    /**
     * Récupère les données sur les cryptomonnaies en utilisant le service CoinrankingApiService
     * et affiche la page d'accueil avec ces données.
     *
     * @param CoinrankingApiService $coinrankingApiService Service pour récupérer les données des cryptomonnaies.
     * @return Response La réponse HTTP pour la page d'accueil.
     */
    #[Route('/', name: 'app_home')]
    public function fetchData(CoinrankingApiService $coinrankingApiService): Response
    {
        // Appelle le service pour obtenir les données sur les cryptomonnaies
        $cryptoData = $coinrankingApiService->getCryptoData();

        // Affiche la page d'accueil avec les données récupérées
        return $this->render('home/index.html.twig', ['cryptoData' => $cryptoData]);
    }
}
