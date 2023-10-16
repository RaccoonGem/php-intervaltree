<?php

use IntervalTree\IntervalTree;
use IntervalTree\DateRangeExclusive;
use PHPUnit\Framework\TestCase;

class IntervalTreeNegativeRangeTest extends TestCase
{
    /**
     * @expectedException \IntervalTree\NegativeRangeException
     */
    public function testNegativeRangeThrowsException()
    {
        $dateRange = new DateRangeExclusive(
            date_create('2014-09-01T03:00:00+00:00'), // Range start
            date_create('2014-01-01T03:15:00+00:00')  // Range end
        );

        $this->expectException(InvalidArgumentException::class);
        $tree = new IntervalTree(array($dateRange));
    }
}
