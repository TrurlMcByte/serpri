#!/usr/bin/env php
<?php

include 'serpri.php';

$s = array(3.14, false, 100, null, 'roger' => 500, 'test');

(new serpri($s))->process(0);
