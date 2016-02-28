<?php

spl_autoload_register('spl_autoload');

if (!defined('PHP_VERSION_ID')) {
    $version = explode('.', PHP_VERSION);

    define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}

/**
 * @coversDefaultClass serpri
 */
class serpriTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function test_htmlandtextout()
    {
        $o = (object) array('var' => 'val');
        $s = array(5.5, false, 100, null, 'roger' => 500, 'test', $o);
        $p = new serpri($s, 1);
        $this->assertInstanceOf('serpri', $p);
        ob_start();
        $p->process(1);
        $ret = ob_get_clean();
        $this->assertContains('<a name="a7">s<sub>4</sub></a><span class="c_s">"test"</span>', $ret);
        $this->assertNotContains('<style type="text/css">', $ret);
        ob_start();
        $p->process(0);
        $ret = ob_get_clean();
        $must = <<<'EOLL'
(&1)(a:7)array{
  [0] => (&2)(d)5.5
  [1] => (&3)(b)false
  [2] => (&4)(i)100
  [3] => (&5)NULL
  [roger] => (&6)(i)500
  [4] => (&7)(s:4)"test"
  [5] => (&8)(O:1)"stdClass"{
    [var] => (&9)(s:3)"val"
  }
}

EOLL;

        $this->assertEquals($must, $ret);
    }

    /**
     * @test
     */
    public function test_fileread()
    {
        $temp_file = tempnam(sys_get_temp_dir(), 'serpritest');
        $o = (object) array('var' => 'val');
        $s = array(5.5, false, 100, null, 'roger' => 500, 'test', $o);
        file_put_contents($temp_file, serialize($s));
        $p = new serpri($temp_file, 2);

        ob_start();
        $p->process();
        $ret = ob_get_clean();

        $this->assertContains('<a name="a7">s<sub>4</sub></a><span class="c_s">"test"</span>', $ret);

        $p->infile($temp_file);
        ob_start();
        $p->process();
        $ret = ob_get_clean();

        $this->assertContains('<style type="text/css">', $ret);
        unlink($temp_file);
    }
    /**
     * @test
     */
    public function test_referals()
    {
        $a = (object) array('var' => 'val');
        $b = (object) array('var' => 'val');
        $a->l = $b;
        $a->test->re = &$a;
        $b->l = $a;
        $p = new serpri(serialize($a), 0);

        ob_start();
        $p->process();
        $ret = ob_get_clean();
        $this->assertContains('[re] => *Recursion(&1)', $ret);

        ob_start();
        $p->html_title = 'My Test';
        $p->process(2);
        $ret = ob_get_clean();
        $this->assertContains('<title>My Test</title>', $ret);
        $this->assertContains('<a href="#a1">*Reference:1</a>', $ret);
    }
    /**
     * @test
     */
    public function test_protected()
    {
        $class_str = json_decode('"O:5:\"Test1\":3:{s:3:\"foo\";s:1:\"A\";s:10:\"\u0000Test1\u0000bar\";s:1:\"B\";s:6:\"\u0000*\u0000baz\";s:1:\"C\";}"');

        $p = new serpri();
        $p->instring($class_str);
        ob_start();
        $p->process();
        $ret = ob_get_clean();

        $this->assertContains('  [@Test1@bar] => (&3)(s:1)@ "B"', $ret);
        $this->assertContains('  [@*@baz] => (&4)(s:1)@ "C"', $ret);
    }
    /**
     * @test
     */
    public function test_nonserializable()
    {
        $a = (object) array('test' => 123);

        if (PHP_VERSION_ID > 55000) {
            function printer()
            {
                while (true) {
                    $string = yield;
                    echo $string;
                }
            }
            $p = printer();
            $a->bc = $p;
        }

        $a->pp = function ($ff) { return 5 + $ff; };

        $p = new serpri($a);
        ob_start();
        $p->process();
        $ret = ob_get_clean();

        if (PHP_VERSION_ID > 55000) {
            $this->assertContains('(G)Generator::__set_state(array(', $ret);
        }

        $this->assertContains('(C)Closure::__set_state(array(', $ret);
    }
    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function test_error()
    {
        $p = new serpri('blablabla');
        ob_start();
        $p->process();
        $ret = ob_get_clean();
        echo $ret;
        $this->assertContains('  [@*@baz] => (&4)(s:1)@ "C"', $ret);
    }
}
