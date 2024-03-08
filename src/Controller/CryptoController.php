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
        // Cryptos autorisées
        $allowedCryptoSymbols = ['BTC', 'ETH', 'XRP', 'BCH', 'ADA', 'LTC', 'XEM', 'XLM', 'MIOTA', 'DASH'];

        // Utilise le service pour récupérer les données des cryptos
        $cryptoData = $coinrankingApiService->getCoinData();

        // Filtrer les données pour ne conserver que les cryptos autorisées
        $filteredCryptoData = array_filter($cryptoData, function ($crypto) use ($allowedCryptoSymbols) {
            return in_array($crypto['symbol'], $allowedCryptoSymbols);
        });

        foreach ($filteredCryptoData as $crypto) {
            // Vérifie si la crypto existe déjà en base de données
            $existingCrypto = $entityManager->getRepository(Crypto::class)->findOneBy(['Symbol' => $crypto['symbol']]);

            // Si la crypto existe déjà, met à jour ses propriétés
            if ($existingCrypto) {
                $existingCrypto->setPrice($crypto['price']);
                // Met à jour d'autres propriétés si nécessaire
            } else {
                // Si la crypto n'existe pas, crée une nouvelle instance de Crypto
                $newCrypto = new Crypto();
                $newCrypto->setName($crypto['name']);
                $newCrypto->setSymbol($crypto['symbol']);
                $newCrypto->setPrice($crypto['price']);
                // Initialise d'autres propriétés si nécessaire

                // Persiste la nouvelle crypto
                $entityManager->persist($newCrypto);
            }
        }

         $cryptoUUIDs = [
    'Bitcoin' => 'Qwsogvtv82FCd', // BTC
    'Ethereum' => 'razxDUgYGNAdQ', // ETH
    'Ripple' => '-l8Mn2pVlRs-p', // XRP
    'Cardano' => 'qzawljRxB5bYu', // ADA
    'Bitcoin Cash' => 'ZlZpzOJo43mIo', // BCH
    'Litecoin' => 'D7B1x_ks7WhV5', // LTC
    'Stellar' => 'f3iaFeCKEmkaZ', // XLM
    'IOTA' => 'LtWwuVANwRzV_', // MIOTA
    'Dash' => 'C9DwH-T7MEGmo', // DASH
    'NEM' => 'DZtb-6X8yCx0h', // XEM
];



    
    $cryptoHistory = $coinrankingApiService->getCoinHistory($cryptoUUIDs);


    // Exécute les requêtes SQL pour sauvegarder les données (déplacé après le rendu)
    $entityManager->flush();

    // Passer les données d'historique au template Twig
    return $this->render('crypto/index.html.twig', [
        'cryptoData' => $filteredCryptoData,
        'cryptoHistory' => $cryptoHistory,
    ]);
    }
}