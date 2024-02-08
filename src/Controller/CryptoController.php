<?php

// namespace App\Controller;

// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// class CryptoController extends AbstractController

// {
//     #[Route('/crypto', name: 'app_crypto')]
//         public function fetchData()
//     {
//         $allowedCryptoSymbols = ['BTC', 'ETH', 'XRP', 'BCH', 'ADA', 'LTC', 'XEM', 'XLM', 'MIOTA', 'DASH'];
//         $cryptoSymbolsQuery = implode(',', $allowedCryptoSymbols);

//         $curl = curl_init();

//         curl_setopt_array($curl, [
//             CURLOPT_URL => "https://coinranking1.p.rapidapi.com/coins?referenceCurrencyUuid=yhjMzLPhuIDl&timePeriod=24h&tiers%5B0%5D=1&orderBy=marketCap&orderDirection=desc&limit=10&offset=0&symbols={$cryptoSymbolsQuery}",
//             CURLOPT_RETURNTRANSFER => true,
//             CURLOPT_ENCODING => "",
//             CURLOPT_MAXREDIRS => 10,
//             CURLOPT_TIMEOUT => 30,
//             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//             CURLOPT_CUSTOMREQUEST => "GET",
//             CURLOPT_HTTPHEADER => [
//                 "X-RapidAPI-Host: coinranking1.p.rapidapi.com",
//                 "X-RapidAPI-Key: c8035be1a9mshbe9a3ef3c4faab5p1e7608jsnab2b2b8a301d"
//             ],
//         ]);

//         $response = curl_exec($curl);
//         $err = curl_error($curl);

//         curl_close($curl);

//         if ($err) {
//             return $this->render('error.html.twig', ['error' => "cURL Error #:" . $err]);
//         } else {
//             // Convert the JSON response to an associative array
//             $data = json_decode($response, true);

//             // Check if the status is 'success'
//             if ($data['status'] === 'success') {
//                 // Render the index.html.twig template with the data
//                 return $this->render('crypto/index.html.twig', ['data' => $data['data']]);
//             } else {
//                 // If not successful, render an error template or handle it accordingly
//                 return $this->render('error.html.twig', ['error' => 'API request failed.']);
//             }
//         }
//     }
// }

//TOUTES LES CRYPTO

// namespace App\Controller;

// use App\Service\CoinrankingApiService;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// class CryptoController extends AbstractController
// {
//     #[Route('/crypto', name: 'app_crypto')]
//     public function fetchData(CoinrankingApiService $coinrankingApiService)
//     {
//         // Utiliser le service pour récupérer les données des cryptos
//         $cryptoData = $coinrankingApiService->getCoinData();

//         // Passer les données récupérées au template Twig
//         return $this->render('crypto/index.html.twig', ['cryptoData' => $cryptoData]);
//     }
// }


//SUELEMET LES 10 CRYPTO

// namespace App\Controller;

// use App\Service\CoinrankingApiService;
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

//         // Passer les données filtrées au template Twig
//         return $this->render('crypto/index.html.twig', ['cryptoData' => $filteredCryptoData]);
//     }
// }

namespace App\Controller;

use App\Service\CoinrankingApiService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CryptoController extends AbstractController
{
    #[Route('/crypto', name: 'app_crypto')]
    public function fetchData(CoinrankingApiService $coinrankingApiService)
    {
        // Cryptos autorisées
        $allowedCryptoSymbols = ['BTC', 'ETH', 'XRP', 'BCH', 'ADA', 'LTC', 'XEM', 'XLM', 'MIOTA', 'DASH'];

        // Utiliser le service pour récupérer les données des cryptos
        $cryptoData = $coinrankingApiService->getCoinData();

        // Filtrer les données pour ne conserver que les cryptos autorisées
        $filteredCryptoData = array_filter($cryptoData, function ($crypto) use ($allowedCryptoSymbols) {
            return in_array($crypto['symbol'], $allowedCryptoSymbols);
        });

        // Récupérer les données spécifiques du Bitcoin
        $bitcoinData = null;
        foreach ($filteredCryptoData as $crypto) {
            if ($crypto['symbol'] === 'BTC') {
                $bitcoinData = $crypto;
                break;
            }
        }

        // Passer les données filtrées et spécifiques du Bitcoin au template Twig
        return $this->render('crypto/index.html.twig', [
            'cryptoData' => $filteredCryptoData,
            'bitcoinData' => $bitcoinData,
        ]);
    }
}
