<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?float $quantity = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'transaction', targetEntity: Wallet::class)]
    private Collection $cryptos;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Crypto $cryptocurrency = null;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->cryptos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Wallet>
     */
    public function getCryptos(): Collection
    {
        return $this->cryptos;
    }

    public function addCrypto(Wallet $crypto): static
    {
        if (!$this->cryptos->contains($crypto)) {
            $this->cryptos->add($crypto);
            $crypto->setTransaction($this);
        }

        return $this;
    }

    public function removeCrypto(Wallet $crypto): static
    {
        if ($this->cryptos->removeElement($crypto)) {
            // set the owning side to null (unless already changed)
            if ($crypto->getTransaction() === $this) {
                $crypto->setTransaction(null);
            }
        }

        return $this;
    }

    public function getCryptocurrency(): ?Crypto
    {
        return $this->cryptocurrency;
    }

    public function setCryptocurrency(?Crypto $cryptocurrency): static
    {
        $this->cryptocurrency = $cryptocurrency;

        return $this;
    }
}
