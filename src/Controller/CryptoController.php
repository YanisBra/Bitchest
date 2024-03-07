<?php

// namespace App\Controller;

// use App\Service\CoinrankingApiService;
// use App\Entity\Crypto;
// use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// class CryptoController extends AbstractController
// {
//     #[Route('/crypto', name: 'app_crypto')]
//     public function fetchData(CoinrankingApiService $coinrankingApiService)
//     {
//         // Cryptos autorisées
//         $allowedCryptoSymbols = ['BTC', 'ETH', 'XRP', 'BCH', 'ADA', 'LTC', 'XEM', 'XLM', 'MIOTA', 'DASH'];

//         // Utiliser le service pour récupérer les données des cryptos
//         $cryptoData = $coinrankingApiService->getCoinData();

//         // Filtrer les données pour ne conserver que les cryptos autorisées
//         $filteredCryptoData = array_filter($cryptoData, function ($crypto) use ($allowedCryptoSymbols) {
//             return in_array($crypto['symbol'], $allowedCryptoSymbols);
//         });

//         // // Récupérer les données spécifiques du Bitcoin
//         // $bitcoinData = null;
//         // foreach ($filteredCryptoData as $crypto) {
//         //     if ($crypto['symbol'] === 'BTC') {
//         //         $bitcoinData = $crypto;
//         //         break;
//         //     }
//         // }

//         // Passer les données filtrées et spécifiques du Bitcoin au template Twig
//         return $this->render('crypto/index.html.twig', [
//             'cryptoData' => $filteredCryptoData,
//             // 'bitcoinData' => $bitcoinData,
//         ]);
//     }
// }


namespace App\Controller;

use App\Service\CoinrankingApiService;
use App\Entity\Crypto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CryptoController extends AbstractController
{
    #[Route('/crypto', name: 'app_crypto')]
    public function fetchData(CoinrankingApiService $coinrankingApiService, EntityManagerInterface $entityManager)
    {


         $cryptoUUIDs = [
    'Qwsogvtv82FCd', // BTC
    'razxDUgYGNAdQ', // ETH
    '-l8Mn2pVlRs-p', // XRP
    'qzawljRxB5bYu', // ADA
    'ZlZpzOJo43mIo', // BCH
    'D7B1x_ks7WhV5', // LTC
    'f3iaFeCKEmkaZ', // XLM
    'LtWwuVANwRzV_', // MIOTA
    'C9DwH-T7MEGmo', // DASH
    'DZtb-6X8yCx0h', // XEM
];

    $cryptoHistory = $coinrankingApiService->getCoinHistory($cryptoUUIDs);

    // Exécute les requêtes SQL pour sauvegarder les données (déplacé après le rendu)
    $entityManager->flush();

    // Passer les données d'historique au template Twig
    return $this->render('crypto/index.html.twig', [
        'cryptoHistory' => $cryptoHistory,
    ]);
    }
}