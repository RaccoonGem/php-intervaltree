<?php

use IntervalTree\IntervalTree;
use IntervalTree\DateRangeInclusive;
use IntervalTree\NumericRangeInclusive;
use IntervalTree\DateRangeExclusive;
use IntervalTree\NumericRangeExclusive;
use PHPUnit\Framework\TestCase;

class IntervalTreeNumericTest extends TestCase {

	public function testNumericRangeInclusiveIteration() {
		$expected = array(10, 12, 14, 16, 18, 20);
		$range = new NumericRangeInclusive(10, 20, 2);
		foreach ($range->iterable() as $value) {
			$this->assertEquals(array_shift($expected), $value);
		}
		$this->assertEquals(0, count($expected));
	}

	public function testNumericRangeExclusiveIteration() {
		$expected = array(10, 12, 14, 16, 18);
		$range = new NumericRangeExclusive(10, 20, 2);
		foreach ($range->iterable() as $value) {
			$this->assertEquals(array_shift($expected), $value);
		}
		$this->assertEquals(0, count($expected));
	}

	public function testNumericInclusiveSearch() {
		$intervals = $this->getNumericInclusiveIntervals();
		$tree = new IntervalTree($intervals);
		$results = $tree->search(4);
		$this->assertCount(3, $results);
		$this->assertSame($intervals[6], $results[0]);
		$this->assertSame($intervals[0], $results[1]);
		$this->assertSame($intervals[1], $results[2]);
		$results = $tree->search(-6);
		$this->assertCount(1, $results);
		$this->assertSame($intervals[7], $results[0]);
	}

	public function testNumericInclusiveRangeSearch() {
		$intervals = $this->getNumericInclusiveIntervals();
		$tree = new IntervalTree($intervals);
		$results = $tree->search(new NumericRangeInclusive(5, 7));
		$this->assertCount(3, $results);
		$this->assertSame($intervals[0], $results[0]);
		$this->assertSame($intervals[1], $results[1]);
		$this->assertSame($intervals[2], $results[2]);
		$results = $tree->search(new NumericRangeInclusive(-7, -2));
		$this->assertCount(2, $results);
		$this->assertSame($intervals[7], $results[0]);
		$this->assertSame($intervals[6], $results[1]);
		$results = $tree->search(new NumericRangeInclusive(-8, 16));
		$this->assertCount(8, $results);
		$this->assertSame($intervals[7], $results[0]);
		$this->assertSame($intervals[6], $results[1]);
		$this->assertSame($intervals[0], $results[2]);
		$this->assertSame($intervals[1], $results[3]);
		$this->assertSame($intervals[2], $results[4]);
		$this->assertSame($intervals[3], $results[5]);
		$this->assertSame($intervals[5], $results[6]);
		$this->assertSame($intervals[4], $results[7]);
	}

	public function testNumericExclusiveSearch() {
		$intervals = $this->getNumericExclusiveIntervals();
		$tree = new IntervalTree($intervals);
		$results = $tree->search(4);
		$this->assertCount(2, $results);
		$this->assertSame($intervals[0], $results[0]);
		$this->assertSame($intervals[1], $results[1]);
	}

	public function testNumericInclusiveSingleItemSearch() {
		$intervals = array(
			new NumericRangeInclusive(1, 10),
		);
		$tree = new IntervalTree($intervals);
		$results = $tree->search(4);
		$this->assertCount(1, $results);
		$this->assertSame($intervals[0], $results[0]);
		$results = $tree->search(12);
		$this->assertCount(0, $results);
		$results = $tree->search(-1);
		$this->assertCount(0, $results);
	}

	private function getNumericInclusiveIntervals() {
		return $this->intervals('IntervalTree\NumericRangeInclusive');
	}

	private function getNumericExclusiveIntervals() {
		return $this->intervals('IntervalTree\NumericRangeExclusive');
	}

	private function intervals($class) {
		return array(
			new $class(1, 5),
			new $class(4, 7),
			new $class(6, 7),
			new $class(10, 15),
			new $class(13, 16),
			new $class(11, 12),
			new $class(-3, 4),
			new $class(-8, -5),
		);
	}

}

