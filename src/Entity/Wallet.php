<?php

namespace App\Entity;

use App\Repository\WalletRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WalletRepository::class)]
class Wallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $totalBalance = 0.0;

    #[ORM\Column]
    private ?float $cryptoBalance = 0.0;

    #[ORM\Column]
    private ?float $usableBalance = 500.0;

    #[ORM\Column]
    private ?float $bitcoin = 0.0;

    #[ORM\Column]
    private ?float $Ethereum = 0.0;

    #[ORM\Column]
    private ?float $XRP = 0.0;

    #[ORM\Column]
    private ?float $cardano = 0.0;

    #[ORM\Column]
    private ?float $litecoin = 0.0;

    #[ORM\Column]
    private ?float $bitcoin_cash = 0.0;

    #[ORM\Column]
    private ?float $stellar = 0.0;

    #[ORM\Column]
    private ?float $iota = 0.0;

    #[ORM\Column]
    private ?float $dash = 0.0;

    #[ORM\Column]
    private ?float $nem = 0.0;

    #[ORM\ManyToOne(inversedBy: 'cryptos')]
    private ?Transaction $transaction = null;

    public function __construct()
    {
        // Initialiser les propriétés dans le constructeur si nécessaire
        $this->totalBalance = $this->calculateTotalBalance();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalBalance(): ?float
    {
        return $this->totalBalance;
    }

    public function setTotalBalance(float $totalBalance): static
    {
        $this->totalBalance = $totalBalance;

        return $this;
    }

    public function getCryptoBalance(): ?float
    {
        return $this->cryptoBalance;
    }

    public function setCryptoBalance(float $cryptoBalance): static
    {
        $this->cryptoBalance = $cryptoBalance;
        $this->totalBalance = $this->calculateTotalBalance(); // Mettez à jour totalBalance

        return $this;
    }

    public function getUsableBalance(): ?float
    {
        return $this->usableBalance;
    }

    public function setUsableBalance(float $usableBalance): static
    {
        $this->usableBalance = $usableBalance;
        $this->totalBalance = $this->calculateTotalBalance(); // Mettez à jour totalBalance

        return $this;
    }

    private function calculateTotalBalance(): float
    {
        return $this->cryptoBalance + $this->usableBalance;
    }

    public function getBitcoin(): ?float
    {
        return $this->bitcoin;
    }

    public function setBitcoin(float $bitcoin): static
    {
        // Arrondir le prix à trois chiffres après la virgule
        $this->bitcoin = round($bitcoin, 5);

        return $this;
    }

    public function getEthereum(): ?float
    {
        return $this->Ethereum;
    }

    public function setEthereum(float $Ethereum): static
    {
        // Arrondir le prix à trois chiffres après la virgule
        $this->Ethereum = round($Ethereum, 5);

        return $this;
    }

    public function getXRP(): ?float
    {
        return $this->XRP;
    }

    public function setXRP(float $XRP): static
    {
        // Arrondir le prix à trois chiffres après la virgule
        $this->XRP = round($XRP, 5);

        return $this;
    }

    public function getCardano(): ?float
    {
        return $this->cardano;
    }

    public function setCardano(float $cardano): static
    {
        // Arrondir le prix à trois chiffres après la virgule
        $this->cardano = round($cardano, 5);

        return $this;
    }

    public function getLitecoin(): ?float
    {
        return $this->litecoin;
    }

    public function setLitecoin(float $litecoin): static
    {
        // Arrondir le prix à trois chiffres après la virgule
        $this->litecoin = round($litecoin, 5);

        return $this;
    }

    public function getBitcoinCash(): ?float
    {
        return $this->bitcoin_cash;
    }

    public function setBitcoinCash(float $bitcoin_cash): static
    {
        // Arrondir le prix à trois chiffres après la virgule
        $this->bitcoin_cash = round($bitcoin_cash, 5);

        return $this;
    }

    public function getStellar(): ?float
    {
        return $this->stellar;
    }

    public function setStellar(float $stellar): static
    {
        // Arrondir le prix à trois chiffres après la virgule
        $this->stellar = round($stellar, 5);

        return $this;
    }

    public function getIota(): ?float
    {
        return $this->iota;
    }

    public function setIota(float $iota): static
    {
        // Arrondir le prix à trois chiffres après la virgule
        $this->iota = round($iota, 5);

        return $this;
    }

    public function getDash(): ?float
    {
        return $this->dash;
    }

    public function setDash(float $dash): static
    {
        // Arrondir le prix à trois chiffres après la virgule
        $this->dash = round($dash, 5);

        return $this;
    }

    public function getNem(): ?float
    {
        return $this->nem;
    }

    public function setNem(float $nem): static
    {
        // Arrondir le prix à trois chiffres après la virgule
        $this->nem = round($nem, 5);

        return $this;
    }

    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }

    public function setTransaction(?Transaction $transaction): static
    {
        $this->transaction = $transaction;

        return $this;
    }
}
