<?php

namespace App\Controller;

use App\Service\CoinrankingApiService;
use App\Entity\Crypto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
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

        // Exécute les requêtes SQL pour sauvegarder les données
        $entityManager->flush();

        // Retourne une réponse, par exemple une redirection ou une vue
        return $this->render('home/index.html.twig', ['cryptoData' => $filteredCryptoData]);
    }
    
    // {
    //     return $this->render('home/index.html.twig', [
    //         'controller_name' => 'HomeController',
    //     ]);
    // }
}
