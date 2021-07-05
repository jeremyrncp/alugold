<?php

namespace App\Entity;

use App\Repository\SaleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SaleRepository::class)
 */
class Sale
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $acceptedAt;

    /**
     * @ORM\OneToOne(targetEntity=Proposition::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Proposition;

    /**
     * @ORM\Column(type="float")
     */
    private $BonificationRate;

    /**
     * @ORM\Column(type="float")
     */
    private $CommissionRate;

    /**
     * @ORM\Column(type="integer")
     */
    private $Goal;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @ORM\Column(type="integer")
     */
    private $amountTaxes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAcceptedAt(): ?\DateTimeImmutable
    {
        return $this->acceptedAt;
    }

    public function setAcceptedAt(\DateTimeImmutable $acceptedAt): self
    {
        $this->acceptedAt = $acceptedAt;

        return $this;
    }

    public function getProposition(): ?Proposition
    {
        return $this->Proposition;
    }

    public function setProposition(Proposition $Proposition): self
    {
        $this->Proposition = $Proposition;

        return $this;
    }

    public function getBonificationRate(): ?float
    {
        return $this->BonificationRate;
    }

    public function setBonificationRate(float $BonificationRate): self
    {
        $this->BonificationRate = $BonificationRate;

        return $this;
    }

    public function getCommissionRate(): ?float
    {
        return $this->CommissionRate;
    }

    public function setCommissionRate(float $CommissionRate): self
    {
        $this->CommissionRate = $CommissionRate;

        return $this;
    }

    public function getGoal(): ?int
    {
        return $this->Goal;
    }

    public function setGoal(int $Goal): self
    {
        $this->Goal = $Goal;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAmountTaxes(): ?int
    {
        return $this->amountTaxes;
    }

    public function setAmountTaxes(int $amountTaxes): self
    {
        $this->amountTaxes = $amountTaxes;

        return $this;
    }

    public function getCommission()
    {
        return round($this->getAmount() * $this->getCommissionRate() / 100, 2);
    }

    public function getVendor(): ?Vendor
    {
       return $this->getProposition()->getVendor();
    }
}
