<?php namespace PHPDS\Heaps;

use Countable;
use SplFixedArray;
use RuntimeException;

class BinaryHeap implements \Countable {

    protected $arr;
    protected $compare;

    public function __construct($compare = null)
    {
        $this->arr = new SplFixedArray;
        if (!is_callable($compare)) {
            $this->compareClosure = function ($el1, $el2) {
                if ($el1 === $el2) return 0;
                return $el1 > $el2 ? 1 : -1;
            };
        } else {
            $this->compareClosure = $compare;
        }
    }

    public function setCompare($compare)
    {
        $this->compareClosure = $compare;
        return $this;
    }

    /**
     * Compare two elements in the container
     *
     * @param  mixed $el1
     * @param  mixed $el2
     * @return int
     */
    protected function compare($el1, $el2)
    {
        return call_user_func($this->compareClosure, $el1, $el2);
    }

    /**
     * [heapify description]
     *
     * @param  index $index
     * @return void
     */
    protected function heapify($index)
    {
        $maxIndex = $index;
        $leftIndex = 2 * $index + 1;
        $rightIndex = $leftIndex + 1;

        // Compare with left child
        $curVal = $this->arr[$maxIndex];
        if (
            $leftIndex < count($this->arr) &&
            $this->compare($this->arr[$leftIndex], $curVal) > 0
        ) {
            $maxIndex = $leftIndex;
        }

        // Compare with right child
        $curVal = $this->arr[$maxIndex];
        if (
            $rightIndex < count($this->arr) &&
            $this->compare($this->arr[$rightIndex], $curVal) > 0
        ) {
            $maxIndex = $rightIndex;
        }

        if ($maxIndex !== $index) {
            $tmpVal = $this->arr[$maxIndex];
            $this->arr[$maxIndex] = $this->arr[$index];
            $this->arr[$index] = $tmpVal;
            $this->heapify($maxIndex);
        }
    }

    /**
     * Rebuild the underlying container
     *
     * @return $this
     */
    public function rebuildHeap()
    {
        if (!$this->isEmpty()) {
            for ($i = (int) ($this->arr->count() / 2); $i >= 0 ; --$i) {
                $this->heapify($i);
            }
        }
        return $this;
    }

    public function count()
    {
        return count($this->arr);
    }

    public function isEmpty()
    {
        return !$this->count();
    }

    /**
     * Return the top element
     * Time complexity: O(1)
     *
     * @return mixed
     */
    public function top()
    {
        if ($this->isEmpty()) return null;
        return $this->arr[0];
    }

    /**
     * Return the top element with removing it
     * Time complexity: O(log n)
     *
     * @return mixed
     */
    public function pop()
    {
        if ($this->isEmpty()) {
            throw new RuntimeException('Heap is empty');
        }
        $result = $this->arr[0];
        $this->arr[0] = $this->arr[count($this->arr)-1];
        $this->arr->setSize($this->arr->count() - 1);
        if (!$this->isEmpty()) $this->heapify(0);
        return $result;
    }

    /**
     * Insert element
     * Time complexity: O(log n)
     *
     * @param  mixed $value
     * @return $this
     */
    public function push($value)
    {
        $curIndex = count($this->arr);
        $this->arr->setSize($curIndex + 1);
        $this->arr[$curIndex] = $value;
        do {
            $parentIndex = ceil($curIndex / 2) - 1;
            if ($parentIndex < 0) break;
            if ($this->compare($this->arr[$curIndex], $this->arr[$parentIndex]) < 0 ) break;
            $tmp = $this->arr[$curIndex];
            $this->arr[$curIndex] = $this->arr[$parentIndex];
            $this->arr[$parentIndex] = $tmp;
            $curIndex = $parentIndex;
        } while (true);
        return $this;
    }

    public function toArray()
    {
        return $this->arr->toArray();
    }

    public function fromArray(array $arr)
    {
        $this->arr = SplFixedArray::fromArray($arr);
        return $this->rebuildHeap();
    }
}
