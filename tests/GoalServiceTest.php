<?php


namespace App\Tests;


use App\Entity\Sale;
use App\Service\GoalVO;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GoalServiceTest extends KernelTestCase
{
    public function testShouldObtainGlobalSaleAmount()
    {
        $sales = new ArrayCollection();

        $sales->add((new Sale())->setGoal(10000)->setAmount(4000)->setAmountTaxes(5000));
        $sales->add((new Sale())->setGoal(10000)->setAmount(4000)->setAmountTaxes(5000));

        $goalService = new GoalVO($sales);
        $this->assertEquals(8000, $goalService->getAmount());
        $this->assertEquals(10000, $goalService->getAmountTaxes());
        $this->assertEquals(80, $goalService->getRateGoal());
    }

    public function testShouldHaveGoal5000()
    {
        $sales = new ArrayCollection();

        $sales->add((new Sale())->setGoal(4000));
        $sales->add((new Sale())->setGoal(5000));

        $goalService = new GoalVO($sales);
        $this->assertEquals(5000, $goalService->getMaxGoal());
    }
}