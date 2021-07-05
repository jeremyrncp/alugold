<?php


namespace App\VO;

use App\Entity\Sale;
use App\Entity\Vendor;
use App\VO\GoalVO;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class VendorSalesVO
 * @package App\VO
 */
class VendorSalesVO
{
    /**
     * @var Vendor
     */
    private $vendor;

    /** @var ArrayCollection */
    private $sales;

    /**
     * @var GoalVO
     */
    private $goalVO;

    /**
     * VendorSalesVO constructor.
     */
    public function __construct()
    {
        $this->sales = new ArrayCollection();
    }

    /**
     * @return Vendor
     */
    public function getVendor(): Vendor
    {
        return $this->vendor;
    }

    /**
     * @param Vendor $vendor
     */
    public function setVendor(Vendor $vendor): void
    {
        $this->vendor = $vendor;
    }

    /**
     * @return ArrayCollection
     */
    public function getSales(): ArrayCollection
    {
        return $this->sales;
    }

    /**
     * @param ArrayCollection $sales
     */
    public function setSales(ArrayCollection $sales): void
    {
        $this->sales = $sales;
    }

    /**
     * @param Sale $sale
     */
    public function addSale(Sale $sale)
    {
        $this->sales->add($sale);
    }

    /**
     * @return GoalVO
     */
    public function getGoalVO(): GoalVO
    {
        return $this->goalVO;
    }

    /**
     * @param GoalVO $goalVO
     */
    public function setGoalVO(GoalVO $goalVO): void
    {
        $this->goalVO = $goalVO;
    }

    /**
     * @return array
     */
    public function getExport()
    {
        return [
            $this->vendor->getEmail(),
            $this->goalVO->getAmountTaxes(),
            $this->goalVO->getCommission(),
            $this->goalVO->getRateGoal(),
            $this->goalVO->getMaxGoal()
        ];
    }
}
