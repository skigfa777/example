<?php

require 'system/init.php';

use \tests\Innovative as Innovative;
use \tests\Perception as Perception;



$innovative = new Innovative();
$answers = '{"1": ["2"], "2": ["3"], "3": ["3"], "4": ["3"], "5": ["3"], "6": ["3"], "7": ["3"], "8": ["3"], "9": ["4"], "10": ["3"], "11": ["3"], "12": ["2"]}';
$answers = json_decode($answers, true); 
$innovativeResult = $innovative->calculate($answers, $debug = true);
echo '<pre>' , var_export($innovativeResult, true) , '</pre>';



$perception = new Perception();
$answers = '{"1": ["2"], "2": ["21"], "3": ["87"], "4": ["example"], "5": ["СВ (северо-восток)", "ЮВ (юго-восток)", "СВ (северо-восток)", "В (восток)", "СВ (северо-восток)"], "6": ["example"], "7": ["01:05", "05:25", "06:24", "05:16", "04:05"]}';
$answers = json_decode($answers, true); 
$perceptionResult = $perception->calculate($answers, $debug = true);
echo '<pre>' , var_export($perceptionResult, true) , '</pre>';
