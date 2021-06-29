<?php

namespace App\Entity;

use App\Repository\PropositionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PropositionRepository::class)
 */
class Proposition
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @ORM\Column(type="integer")
     */
    private $ShippingFees;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ShippingFeesDiscount;

    /**
     * @ORM\Column(type="integer")
     */
    private $VendorCost;

    /**
     * @ORM\Column(type="integer")
     */
    private $DiscountRate;

    /**
     * @ORM\ManyToOne(targetEntity=Vendor::class, inversedBy="propositions")
     */
    private $Vendor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $RefCustomer;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getShippingFees(): ?int
    {
        return $this->ShippingFees;
    }

    public function setShippingFees(int $ShippingFees): self
    {
        $this->ShippingFees = $ShippingFees;

        return $this;
    }

    public function getShippingFeesDiscount(): ?int
    {
        return $this->ShippingFeesDiscount;
    }

    public function setShippingFeesDiscount(?int $ShippingFeesDiscount): self
    {
        $this->ShippingFeesDiscount = $ShippingFeesDiscount;

        return $this;
    }

    public function getVendorCost(): ?int
    {
        return $this->VendorCost;
    }

    public function setVendorCost(int $VendorCost): self
    {
        $this->VendorCost = $VendorCost;

        return $this;
    }

    public function getDiscountRate(): ?int
    {
        return $this->DiscountRate;
    }

    public function setDiscountRate(int $DiscountRate): self
    {
        $this->DiscountRate = $DiscountRate;

        return $this;
    }

    public function getVendor(): ?Vendor
    {
        return $this->Vendor;
    }

    public function setVendor(?Vendor $Vendor): self
    {
        $this->Vendor = $Vendor;

        return $this;
    }

    public function getRefCustomer(): ?string
    {
        return $this->RefCustomer;
    }

    public function setRefCustomer(string $RefCustomer): self
    {
        $this->RefCustomer = $RefCustomer;

        return $this;
    }
}
