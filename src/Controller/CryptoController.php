<?php

namespace App\Controller;

use App\Service\CoinrankingApiService;
use App\Entity\Crypto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Contrôleur gérant les fonctionnalités liées aux cryptomonnaies.
 */
class CryptoController extends AbstractController
{
    /**
     * Récupère les données historiques des cryptomonnaies spécifiées
     * et affiche la page des cryptomonnaies avec ces données.
     *
     * @param CoinrankingApiService $coinrankingApiService Service pour récupérer les données des cryptomonnaies.
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités pour effectuer des opérations sur la base de données.
     * @return Response La réponse HTTP pour la page des cryptomonnaies.
     */
    #[Route('/crypto', name: 'app_crypto')]
    public function fetchData(CoinrankingApiService $coinrankingApiService, EntityManagerInterface $entityManager)
    {
        // UUIDs des cryptomonnaies spécifiées avec leurs noms correspondants
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

        // Appelle le service pour obtenir l'historique des cryptomonnaies spécifiées
        $cryptoHistory = $coinrankingApiService->getCoinHistory($cryptoUUIDs);

        // Effectue tout changement en attente dans la base de données
        $entityManager->flush();

        // Affiche la page des cryptomonnaies avec les données historiques
        return $this->render('crypto/index.html.twig', [
            'cryptoHistory' => $cryptoHistory,
        ]);
    }
}
