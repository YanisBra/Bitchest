<?php

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

    $entityManager->flush();

    return $this->render('crypto/index.html.twig', [
        'cryptoHistory' => $cryptoHistory,
    ]);
    }
}