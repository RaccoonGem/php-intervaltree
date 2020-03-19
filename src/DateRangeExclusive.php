<?php namespace IntervalTree;

use DateTime;
use DateInterval;

/**
 * Date range which excludes intersecting dates.
 */
class DateRangeExclusive implements RangeInterface
{
    /**
     * @var \DateTime
     */
    protected $start;

    /**
     * @var \DateTime
     */
    protected $end;

    /**
     * @var \DateInterval
     */
    protected $step;

    /**
     * @var mixed   Boolean False if it does not overlap
     *              String "Complete" if it completely overlaps
     *              String "Partial" if it partially overlaps
     */
    protected $overlapType;

    /**
     * @param \DateTime     $start
     * @param \DateTime     $end
     * @param \DateInterval $step
     */
    public function __construct(DateTime $start, DateTime $end = null, DateInterval $step = null)
    {
        $this->start = clone $start;
        $this->end = clone $end;
        $this->step = $step ?: new DateInterval('P1D');
        $this->overlapType = false;
    }

    /**
     * {@inheritDoc}
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * {@inheritDoc}
     *
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * {@inheritDoc}
     *
     * @return \Generator
     */
    public function iterable()
    {
        $date = clone $this->getStart();
        $dateForComparison = clone $this->getStart();

        while ($date < $this->getEnd()) {
            if($dateForComparison->add($this->step) == $this->getEnd()) {
                yield ['startdatetime'=>$date, 'enddatetime'=>$this->getEnd()];
            }
            else {
                yield ['startdatetime' => $date, 'enddatetime' => null];
            }
            $date->add($this->step);
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->start->format('Y-m-d').' .. '.$this->end->format('Y-m-d');
    }
}
