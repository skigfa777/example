<?php

namespace tests;

if (!defined('PRFK_SITE_ROOT_DIR')) {
  die();
}

use core\DBConnect as DBConnect;

class Perception extends Test
{

    public function calculate($answers, $debug = false)
    {
        //1)получить список ответов юзера
        $answersArr = $debug ? $answers : $this->getUsersAnswers($answers)['answers'];

        //2)подсчет баллов
        $result = 0;

        foreach ($answersArr as $q => $a) {
            switch ($q) {
            	case 1:
            		if ($a[0] == 3) {
            			$result++;
            		}
            		break;
            	case 2:
            		if ($a[0] == 16) {
            			$result++;
            		}
            		break;
            	case 3:
            		if ($a[0] == 87) {
            			$result++;
            		}
            		break;
            	case 5:
            		foreach ($a as $k5 => $v5) {
            			switch ($k5) {
            				case 0:
            					if ($v5 == 'СВ (северо-восток)') {
            						$result++;
            					}
            					break;
            				case 1:
            					if ($v5 == 'Ю (юг)') {
            						$result++;
            					}
            					break;
            				case 2:
            					if ($v5 == 'СВ (северо-восток)') {
            						$result++;
            					}
            					break;
            				case 3:
            					if ($v5 == 'СЗ (северо-запад)') {
            						$result++;
            					}
            					break;
            				case 4:
            					if ($v5 == 'СВ (северо-восток)') {
            						$result++;
            					}
            					break;
            			}
            		}
            		break;
            	case 7:
            		foreach ($a as $k5 => $v5) {
            			switch ($k5) {
            				case 0:
            					if ($v5 == '05:20') {
            						$result++;
            					}
            					break;
            				case 1:
            					if ($v5 == '09:40') {
            						$result++;
            					}
            					break;
            				case 2:
            					if ($v5 == '11:05') {
            						$result++;
            					}
            					break;
            				case 3:
            					if ($v5 == '06:35') {
            						$result++;
            					}
            					break;
            				case 4:
            					if ($v5 == '01:30') {
            						$result++;
            					}
            					break;
            			}
            		}
            		break;	
            }
        }
        return $result;
    }
    
}
