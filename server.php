<?php

//public keys
$n = 738721;
$g = 424811;

$a = 9839;//private key
$A = ($g^$a) % $n;

$_SESSION['A'] = $A;

 ?>
