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
        $this->bitcoin = $bitcoin;

        return $this;
    }

    public function getEthereum(): ?float
    {
        return $this->Ethereum;
    }

    public function setEthereum(float $Ethereum): static
    {
        $this->Ethereum = $Ethereum;

        return $this;
    }

    public function getXRP(): ?float
    {
        return $this->XRP;
    }

    public function setXRP(float $XRP): static
    {
        $this->XRP = $XRP;

        return $this;
    }

    public function getCardano(): ?float
    {
        return $this->cardano;
    }

    public function setCardano(float $cardano): static
    {
        $this->cardano = $cardano;

        return $this;
    }

    public function getLitecoin(): ?float
    {
        return $this->litecoin;
    }

    public function setLitecoin(float $litecoin): static
    {
        $this->litecoin = $litecoin;

        return $this;
    }

    public function getBitcoinCash(): ?float
    {
        return $this->bitcoin_cash;
    }

    public function setBitcoinCash(float $bitcoin_cash): static
    {
        $this->bitcoin_cash = $bitcoin_cash;

        return $this;
    }

    public function getStellar(): ?float
    {
        return $this->stellar;
    }

    public function setStellar(float $stellar): static
    {
        $this->stellar = $stellar;

        return $this;
    }

    public function getIota(): ?float
    {
        return $this->iota;
    }

    public function setIota(float $iota): static
    {
        $this->iota = $iota;

        return $this;
    }

    public function getDash(): ?float
    {
        return $this->dash;
    }

    public function setDash(float $dash): static
    {
        $this->dash = $dash;

        return $this;
    }

    public function getNem(): ?float
    {
        return $this->nem;
    }

    public function setNem(float $nem): static
    {
        $this->nem = $nem;

        return $this;
    }
}
