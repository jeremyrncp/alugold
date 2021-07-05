<?php


namespace App\VO;

/**
 * Class MonthlyGoalVO
 * @package App\VO
 */
class MonthlyGoalVO
{
    /** @var GoalVO */
    private $goalVO;

    /** @var string */
    private $month;

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
     * @return string
     */
    public function getMonth(): string
    {
        return $this->month;
    }

    /**
     * @param string $month
     */
    public function setMonth(string $month): void
    {
        $this->month = $month;
    }
}
