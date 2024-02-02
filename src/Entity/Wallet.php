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
}
