<?php
/**
* SERialized smart object pretty PRInt for php.
*
* May show raw serialized data without original clases.
* examples:
* (new serpri($SomeSmartObject))->process(2);
* $p=new serpri('file_with_serialized.dump'); $p->process(2);
*
* @author Trurl McByte.
*
* @version 0.7
*/

/**
 * Primary (and only) class.
 */
class serpri
{
    private $fp = null;
    private $lv = 0;
    private $lid = 0;
    private $eol = true;
    private $prot = false;
    private $type = '';
    /**
     * for self testing only.
     */
    protected $next = false;
    private $e = '';
    private $prefix = null;
    public $html = false;
    public $fullhtml = true;
    private $filemode = false;
    public $html_title = '';
    public $string = '';
    private $pt = 0;
    private $lpt = 0;

    /**
     * serpri object constructor.
     *
     * @param mixed $input filename (if file exists), serialized string, array or object
     * @param int   $html  1-format as html, 2-include page header/footer with css
     *
     * @return serpri $this self object
     */
    public function __construct($input = null, $html = 0)
    {
        if ($html > 0) {
            $this->html = true;
            if ($html > 1) {
                $this->fullhtml = true;
            } else {
                $this->fullhtml = false;
            }
        }
        if (empty($input)) {
            return $this;
        }
        if (!is_string($input)) {
            return $this->instring(serialize($input));
        }
        if (strlen($input) < 1025 && is_file($input) && is_readable($input)) {
            return $this->infile($input);
        }

        return $this->instring($input);
    }
    /**
     * Set serialized data file to process.
     *
     * @param string $input filename with serialized data
     *
     * @return serpri $this self object
     */
    public function infile($input)
    {
        if ($this->fp) {
            @fclose($this->fp);
        }
        $this->fp = fopen($input, 'r');
        $this->lid = 0;
        $this->filemode = true;

        return $this;
    }
    /**
     * Set serialized string to process.
     *
     * @param string $input serialized string
     *
     * @return serpri $this self object
     */
    public function instring($str)
    {
        assert(unserialize($str) !== false);
        $this->string = $str;
        $this->pt = 0;
        $this->lpt = strlen($this->string);
        $this->lid = 0;

        return $this;
    }

    /**
     * Output formatted object.
     *
     * use ob_start|ob_get_clean to put in var
     *
     * @param int $html 1-format as html, 2-include page header/footer with css
     */
    public function process($html = null)
    {
        if ($html !== null) {
            if ($html > 0) {
                $this->html = true;
                if ($html > 1) {
                    $this->fullhtml = true;
                } else {
                    $this->fullhtml = false;
                }
            } else {
                $this->html = false;
            }
        }

        if ($this->html && $this->fullhtml) {
            echo '<!DOCTYPE HTML><html><head>';
            if ($this->html_title) {
                echo "<title>{$this->html_title}</title>";
            }
            echo'<meta charset="utf-8" />
<style type="text/css">
body { border: 5px; }
div { border: 0; max-width: 99%; }
div.sl_0 {background-color: #f0fff0;}
div.sl_1 {background-color: #f8f8f8;}
div.sl_2 {background-color: #f8ffff;}
div.sl_3 {background-color: #fff8ff;}
div.sl_4 {background-color: #fffff8;}
div.sl_5 {background-color: #f8fff8;}
div.sl_6 {background-color: #fff8f8;}
div.sl_7 {background-color: #f8f8ff;}
div.sl_8 {background-color: #f8f8f8;}
div.sub { padding-left:24pt; /*float: left; padding-right: 100%;*/ }
a[name] { text-shadow: 0 0 1px #cdd; }
a[href] { text-decoration-style: dotted; }
span.c_i:before, span.c_s:before, span.c_b:before, span.c_d:before, span.c_a:before, span.c__o:before { content: "ː"; }
span.sp_s {color: #696; }
span.sp_i { color: #556; }
span.sp_s:after, span.sp_i:after {content: "]"; color: #556; }
span.sp_s:before, span.sp_i:before {content: "["; color: #556; }
span.c_s { color: #171; }
span.c__o { color: #f80; }
span.c_a { color: #c44; }
span.c__n { color: #f0f; }
span.prot { text-decoration: overline; text-decoration-style: dashed; text-decoration-color: red; }
a:target { background-color: #afa;}
a:hover { background-color: #ffa; }
input[type=checkbox] { transform: rotate(-45deg) scale(0.8); transform-origin: 4px 13px 0; }
input[type=checkbox]:checked + div {
   transform-origin: 0 0 0;
   transform: scale(0.25,0.25);
   height: 100px;
   overflow-x: auto;
   overflow-y: scroll;
   border: dotted 1px black;
   margin-bottom: -80px;
   transition-delay: 0.1s;
   transition-duration: 0.2s;
}
input[type=checkbox]:checked + div:hover {
   transform: none;
   margin-bottom: 0;
   transition-delay: 0.8s;
}
span.c__n + a { color: #909; font-style: oblique; }
sub { font-size: xx-small; }
</style>
</head><body>';
            if ($this->html_title) {
                echo "<h2>{$this->html_title}</h2>";
            }
            echo '<div class="sl_">';
        }

        if ($this->filemode) {
            fseek($this->fp, 0);
        } else {
            $this->pt = 0;
            $this->lpt = strlen($this->string);
        }
        $this->lid = 0;

        while (!$this->eof()) {
            $this->selector();
        }
        if ($this->filemode && $this->fp) {
            @fclose($this->fp);
        }

        if ($this->html && $this->fullhtml) {
            echo '</div></body></html>';
        }
    }

    private function selector()
    {
        $c = $this->ga(2);
        if ($this->eof()) {
            return;
        }
        if ($c[1] = ':') {
            $this->type = $c[0];
            if (strtolower($this->type) !== $this->type) {
                $this->type = '_'.strtolower($this->type);
            }
            $m = 'out_'.$this->type;
            if (method_exists($this, $m)) {
                $this->$m();
            } else {
                die("no method $m  ");
            }
        } else {
            die("crazy c=$c  ");
        }
    }

    private function lid($str, $size = 0, $type = null)
    {
        if (!$type) {
            $type = $this->type;
        }
        $stype = trim($type, '_');
        if ($type !== $stype) {
            $stype = strtoupper($stype);
        }

        if ($stype == 'N') {
            $stype = '';
        } //else $stype = '('.$stype.')';

        if ($stype && $size) {
            if ($this->html) {
                $stype .= ('<sub>'.$size.'</sub>');
            } else {
                $stype .= (':'.$size);
            }
        }

        if ($this->prefix === true) {
            if ($this->html) {
                return '<span class="sp_'.$type.'">'.$str.'</span>';
            }

            return '['.$str.']';
        }
        if ($this->html) {
            $str = '<span class="c_'.$type.($this->prot ? ' prot' : '').'">'.$str.'</span>';
        } else {
            $str = ($stype ? '('.$stype.')' : '').($this->prot ? '@ ' : '').$str;
        }
        $this->prot = false;
        ++$this->lid;

        if ($this->html) {
            return '<a name="a'.($this->lid).'">'.($stype ? $stype.'</a>' : '').$str.($stype ? '' : '</a>');
        }

        return '(&'.($this->lid).')'.$str;
    }

    private function lnk($str, $lid)
    {
        if ($this->html) {
            return '<a href="#a'.$lid.'">'.$str.$lid.'</a>';
        }

        return "{$str}(&{$lid})";
    }

    private function out__n()
    {
        $this->pp($this->lid('NULL'));
    }

    private function out__r()
    {
        $l = $this->col(';');

        $this->pp($this->lnk('*Recursion', $l));
    }

    private function out_r()
    {
        $l = $this->col(';');

        $this->pp($this->lnk('*Reference:', $l));
    }

    private function out__o()
    {
        $lcn = 0 + $this->col(':');
        $cn = $this->col(':');
        $l = 0 + $this->col(':');
        $this->prefix = null;
        $this->pp($this->lid($cn, $l).$this->block_s());
        assert($this->gc() == '{');
        ++$this->lv;
        for ($i = 0;$i < $l;++$i) {
            $this->prefix = true;
            $this->selector();
            echo ' => ';
            $this->prefix = false;
            $this->selector();
        }
        $this->prefix = null;
        assert($this->gc() == '}');
        --$this->lv;
        echo $this->block_e();
    }
    private function out_a()
    {
        $l = 0 + $this->col();
        $this->prefix = null;
        $this->pp($this->lid('array', $l).$this->block_s());
        assert($this->gc() == '{');
        ++$this->lv;
        for ($i = 0;$i < $l;++$i) {
            $this->prefix = true;
            $this->selector();
            echo ' => ';
            $this->prefix = false;
            $this->selector();
            $this->prot = false;
        }
        $this->prefix = null;
        assert($this->gc() == '}');
        --$this->lv;
        echo $this->block_e();
    }

    private function out_s()
    {
        $l = 0 + $this->col(':');
        $s = $this->ga($l + 2);
        assert(strlen($s) == ($l + 2));
        $s = substr($s, 1, $l);
        $b = $this->gc();
        assert($b == ';');
        if ($this->prefix) {
            if (strpos($s, "\x00") !== false) {
                $this->prot = true;
                $s = strtr($s, "\x00", '@');
            }
        } else {
            $jopt = 0;
            if (defined('JSON_UNESCAPED_UNICODE')) {
                $jopt = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
            }
            if ($this->html) {
                $jopt |= JSON_HEX_TAG;
            }
            $s = json_encode($s, $jopt);
        }
        $this->pp($this->lid($s, $l));
    }
    private function out_i()
    {
        $l = $this->col(';');
        $this->pp($this->lid($l));
    }
    private function out_d()
    {
        $l = $this->col(';');
        $this->pp($this->lid($l));
    }
    private function out_b()
    {
        $l = $this->col(';') === '0' ? 'false' : 'true';
        $this->pp($this->lid($l));
    }

    private function block_s()
    {
        if ($this->html) {
            return '{<input type="checkbox" /><div class="sub sl_'.$this->lv.'">'.PHP_EOL;
        } else {
            return '{'.PHP_EOL;
        }
    }

    private function block_e()
    {
        if ($this->html) {
            return '</div>}'.$this->br();
        } else {
            return $this->pad().'}'.$this->br();
        }
    }

    private function eof()
    {
        if (!$this->filemode) {
            return $this->pt > $this->lpt;
        }

        return feof($this->fp);
    }
    private function gc()
    {
        if (!$this->filemode) {
            return $this->string[$this->pt++];
        }

        $r = fgetc($this->fp);

        return $r;
    }

    private function col($stp = ':')
    {
        $r = '';
        while (!$this->eof()) {
            $c = $this->gc();
            if (strpos($stp, $c) !== false) {
                break;
            }
            $r .= $c;
        }

        return $r;
    }

    private function ga($l)
    {
        if (!$this->filemode) {
            $opt = $this->pt;
            $this->pt += $l;

            return substr($this->string, $opt, $l);
        }

        $r = fread($this->fp, $l);

        return $r;
    }

    private function pad()
    {
        if ($this->html) {
            return str_repeat('  ', $this->lv).'<div>';
        } //  return '<div style="padding-left:'.($this->lv*22).'pt;">';
        return str_replace('        ', "\t", str_repeat('  ', $this->lv));
    }
    private function pp($str)
    {
        if ($this->lv < 0) {
            die("bad level {$this->lv}  ");
        }
        if ($this->prefix) {
            echo $this->pad();
        }
        echo $str;
        echo $this->prefix === false  ? $this->br() : '';
    }
    private function br()
    {
        if ($this->html) {
            return '</div>'.PHP_EOL;
        }

        return PHP_EOL;
    }
}
