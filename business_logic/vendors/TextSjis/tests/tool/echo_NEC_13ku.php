<?php

// NEC“ÁŽê‹L†‚ðo—Í

$gaiji = array(
        array(0x8740,0x877E), 
        array(0x8780,0x879E), 
    );

foreach ($gaiji as $range) {
    for ($i = $range[0]; $i <= $range[1]; ++$i) {
        $hex  = dechex($i);
        $char = eval('return "' . 
                     '\x'.substr($hex, 0, 2) . 
                     '\x'.substr($hex, 2, 2) . 
                     '";');
        echo $char, PHP_EOL;
    }
}
