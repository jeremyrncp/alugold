<?php

namespace App\Entity;

use App\Repository\DiscountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DiscountRepository::class)
 */
class Discount
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
    private $minimalamount;

    /**
     * @ORM\Column(type="integer")
     */
    private $maximumrate;

    /**
     * @ORM\OneToMany(targetEntity=Vendor::class, mappedBy="discounts")
     */
    private $vendors;

    /**
     * @ORM\ManyToOne(targetEntity=Vendor::class, inversedBy="iscounts")
     */
    private $vendor;

    public function __construct()
    {
        $this->vendors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMinimalamount(): ?int
    {
        return $this->minimalamount;
    }

    public function setMinimalamount(int $minimalamount): self
    {
        $this->minimalamount = $minimalamount;

        return $this;
    }

    public function getMaximumrate(): ?int
    {
        return $this->maximumrate;
    }

    public function setMaximumrate(int $maximumrate): self
    {
        $this->maximumrate = $maximumrate;

        return $this;
    }

    /**
     * @return Collection|Vendor[]
     */
    public function getVendors(): Collection
    {
        return $this->vendors;
    }

    public function addVendor(Vendor $vendor): self
    {
        if (!$this->vendors->contains($vendor)) {
            $this->vendors[] = $vendor;
            $vendor->setDiscounts($this);
        }

        return $this;
    }

    public function removeVendor(Vendor $vendor): self
    {
        if ($this->vendors->removeElement($vendor)) {
            // set the owning side to null (unless already changed)
            if ($vendor->getDiscounts() === $this) {
                $vendor->setDiscounts(null);
            }
        }

        return $this;
    }

    public function getVendor(): ?Vendor
    {
        return $this->vendor;
    }

    public function setVendor(?Vendor $vendor): self
    {
        $this->vendor = $vendor;

        return $this;
    }
}
