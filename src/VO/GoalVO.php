<?php


namespace App\VO;


use App\Entity\Sale;
use Doctrine\Common\Collections\ArrayCollection;

class GoalVO
{
    const MAX_GOAL = 'maxGoal';
    const AMOUNT = 'amount';
    const AMOUNT_TAXES = 'amountTaxes';
    const RATE_GOAL = 'rateGoal';
    const COMMISSION = 'commission';
    /**
     * @var ArrayCollection $sales
     */
    private $sales;

    /**
     * @var int
     */
    private $maxGoal;

    /**
     * @var int
     */
    private $amount;

    /**
     * @var int
     */
    private $amountTaxes;

    /**
     * @var float
     */
    private $rateGoal;

    /**
     * @var int
     */
    private $commission;

    /**
     * GoalService constructor.
     * @param ArrayCollection $sales
     */
    public function __construct(ArrayCollection $sales)
    {
        $this->sales = $sales;
        $data = $this->getAllData();


        $this->maxGoal = $data['' . self::MAX_GOAL . ''];
        $this->amount = $data['' . self::AMOUNT . ''];
        $this->amountTaxes = $data['' . self::AMOUNT_TAXES . ''];
        $this->rateGoal = $data['' . self::RATE_GOAL . ''];
        $this->commission = $data['' . self::COMMISSION . ''];
    }

    public function getMaxGoal()
    {
        return $this->maxGoal;
    }

    /**
     * @return ArrayCollection
     */
    public function getSales(): ArrayCollection
    {
        return $this->sales;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function getAmountTaxes(): int
    {
        return $this->amountTaxes;
    }

    /**
     * @return float
     */
    public function getRateGoal(): float
    {
        return $this->rateGoal;
    }

    /**
     * @return int
     */
    public function getCommission(): int
    {
        return $this->commission;
    }

    private function getAllData()
    {
        $goalMax = null;
        $amountTaxes = 0;
        $amount = 0;
        $commission = 0;

        foreach ($this->sales as $sale)
        {
            /** @var Sale $sale */
            if ($sale->getGoal() > $goalMax) {
                $goalMax = $sale->getGoal();
            }

            $amountTaxes += $sale->getAmountTaxes();
            $amount += $sale->getAmount();
            $commission += $sale->getCommission();
        }

        return [
            self::MAX_GOAL => $goalMax,
            self::AMOUNT => $amount,
            self::AMOUNT_TAXES => $amountTaxes,
            self::RATE_GOAL => $amount * 100 / $goalMax,
            self::COMMISSION => $commission
        ];
    }
}