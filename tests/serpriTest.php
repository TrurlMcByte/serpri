<?php

include_once 'serpri.php';

/**
 * @coversDefaultClass serpri
 */
class serpriTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     */
    public function test_serpri()
    {
        $p = new serpri(array(1, 3, 4));
        $this->assertInstanceOf(serpri::class, $p);

        return $p;
    }
    /**
     * @covers ::process
     * @covers ::ga
     * @covers ::gc
     * @covers ::out_i
     * @covers ::eof
     * @covers ::lid
     */
    public function test_textout()
    {
        $s = array(3.14, false, 100, null, 'roger' => 500, 'test');
        $p = new serpri($s);
        ob_start();
        $p->process(1);
        $ret = ob_get_clean();

        ob_start();
        $p->process(0);
        $ret = ob_get_clean();
        $must = <<<'EOLL'
(&1)(a:6)array{
  [0] => (&2)(d)3.1400000000000001
  [1] => (&3)(b)false
  [2] => (&4)(i)100
  [3] => (&5)NULL
  [roger] => (&6)(i)500
  [4] => (&7)(s:4)"test"
}

EOLL;

        $this->assertEquals($must, $ret);
    }
}
