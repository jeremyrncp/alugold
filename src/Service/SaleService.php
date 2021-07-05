<?php


namespace App\Service;


use App\Entity\Sale;
use App\VO\GoalVO;
use App\VO\MonthlyGoalVO;
use App\VO\VendorSalesVO;
use Doctrine\Common\Collections\ArrayCollection;

class SaleService
{
    /**
     * @param array $sales
     * @return ArrayCollection
     */
    public function groupByVendor(array $sales): ArrayCollection
    {
        $salesGrouped = new ArrayCollection();
        $vendors = [];

        /** @var Sale $sale */
        foreach ($sales as $sale) {
            if (!array_key_exists($sale->getProposition()->getVendor()->getId(), $vendors)) {
                $vendorSaleVO = new VendorSalesVO();
                $vendorSaleVO->setVendor($sale->getProposition()->getVendor());
                $vendors[$sale->getProposition()->getVendor()->getId()] = $vendorSaleVO;
            }

            $vendors[$sale->getProposition()->getVendor()->getId()]->addSale($sale);
        }

        array_map(function ($elm) use ($salesGrouped) {
            /** @var VendorSalesVO $elm */
            $elm->setGoalVO(new GoalVO($elm->getSales()));
            $salesGrouped->add($elm);
        }, $vendors);

        return $salesGrouped;
    }

    /**
     * @param array $sales
     * @return ArrayCollection
     */
    public function groupByMonth(array $sales)
    {
        $salesGrouped = new ArrayCollection();
        $salesMonth = [];

        /** @var Sale $sale */
        foreach ($sales as $sale) {
            if (!array_key_exists($sale->getAcceptedAt()->format('m-Y'), $salesMonth)) {
                $salesMonth[$sale->getAcceptedAt()->format('m-Y')] = new ArrayCollection();
            }

            $salesMonth[$sale->getAcceptedAt()->format('m-Y')]->add($sale);
        }

        foreach ($salesMonth as $month => $elm) {
            $monthlyGoalVO = new MonthlyGoalVO();
            $monthlyGoalVO->setGoalVO(new GoalVO($elm));
            $monthlyGoalVO->setMonth($month);
            $salesGrouped->add($monthlyGoalVO);
        }

        return $salesGrouped;
    }
}
