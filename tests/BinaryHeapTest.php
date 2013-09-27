<?php

use PHPDS\Heaps\BinaryHeap;

class HeapTest extends PHPUnit_Framework_TestCase {

    protected function makeInstance()
    {
        return new BinaryHeap;
    }

    public function testPush()
    {
        $heap = $this->makeInstance();
        $this->assertNull($heap->top());
        $this->assertEquals(16, $heap->push(16)->top());
        $this->assertEquals(16, $heap->push(11)->push(10)->top());
        $this->assertEquals(22, $heap->push(7)->push(22)->top());
        return $heap;
    }

    /**
     * @depends testPush
     */
    public function testToArray(BinaryHeap $heap)
    {
        $this->assertEquals([22, 16, 10, 7, 11], $heap->toArray());
    }

    /**
     * @depends testPush
     */
    public function testPop(BinaryHeap $heap)
    {
        $a = [22, 16, 11, 10, 7];
        foreach ($a as $n) {
            $this->assertEquals($n, $heap->pop());
        }
    }

    public function testIsEmpty()
    {
        $heap = $this->makeInstance();
        $this->assertTrue($heap->isEmpty());
        $this->assertFalse($heap->push(1)->isEmpty());
        $heap->pop();
        $this->assertTrue($heap->isEmpty());
    }

    public function testFromArray()
    {
        $heap = $this->makeInstance()->fromArray([]);
        $this->assertEquals([], $heap->toArray());

        $a = range(0, 1000);
        shuffle($a);
        $heap = $this->makeInstance()->fromArray($a);
        $sortedArr = [];
        while (!$heap->isEmpty()) {
            $sortedArr[] = $heap->pop();
        }
        rsort($a);
        $this->assertEquals($a, $sortedArr);
    }

}
