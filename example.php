#!/usr/bin/env php
<?php

include 'serpri.php';

$s = array(5.5, false, 100, null, 'roger' => 500, 'test', 'func' => function () {echo 'Ok';});
$s['rec'] = &$s;
$a = new stdClass();
$s['b'] = new stdClass();
$s['c'] = new stdClass();
$a->sub = new stdClass();
$a->b = $s['b'];
$s['b'] = $a;$s['c'] = $a;

$p = new serpri($s);
$p->process(0);
