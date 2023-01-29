<?php

namespace tests;

if (!defined('PRFK_SITE_ROOT_DIR')) {
  die();
}

use core\DBConnect as DBConnect;

/**
 * Подсчет теста "Личность и профессия" Дж. Холланда
 * (тест простой, поэтому используется стандартный родительский calculate()
 * @package tests
 */
class Holland extends Test
{

    /**
     * Вернуть данные для статистики по школе/классу
     * @param array $fields - ['count' => 20, 'activeTestsId' => 123, 'classNum' => 11, 'classChar => 'а']
     * @return array || boolean
     */
    public function getStat($fields)        
    {
        $pdo = DBConnect::getInstance();
        $schoolsClass = 'AND `respondents_pupils`.`class_num` = :classNum AND `respondents_pupils`.`class_char` = :classChar';
        $data = [':count' => $fields['count'],
            ':activeTestsId' => $fields['activeTestsId'],
            ':classNum' => $fields['classNum'],
            ':classChar' => $fields['classChar']];
        if (!$fields['classNum']) {
            $schoolsClass = '';
            $data = [':count' => $fields['count'], ':activeTestsId' => $fields['activeTestsId']];
        }
        $sql = <<<sql
SELECT 
CEIL((COUNT(DISTINCT (CASE WHEN JSON_EXTRACT(`mrr`.`results`, '$."tests"."holland"."Артистический"') >= 6 THEN `mrr`.`respondent_id` END))*100)/:count) `Артистический`,
CEIL((COUNT(DISTINCT (CASE WHEN JSON_EXTRACT(`mrr`.`results`, '$."tests"."holland"."Интеллектуальный"') >= 6 THEN `mrr`.`respondent_id` END))*100)/:count) `Интеллектуальный`,
CEIL((COUNT(DISTINCT (CASE WHEN JSON_EXTRACT(`mrr`.`results`, '$."tests"."holland"."Офисный"') >= 6 THEN `mrr`.`respondent_id` END))*100)/:count) `Офисный`,
CEIL((COUNT(DISTINCT (CASE WHEN JSON_EXTRACT(`mrr`.`results`, '$."tests"."holland"."Предпринимательский"') >= 6 THEN `mrr`.`respondent_id` END))*100)/:count) `Предпринимательский`,
CEIL((COUNT(DISTINCT (CASE WHEN JSON_EXTRACT(`mrr`.`results`, '$."tests"."holland"."Реалистичный"') >= 6 THEN `mrr`.`respondent_id` END))*100)/:count) `Реалистичный`,
CEIL((COUNT(DISTINCT (CASE WHEN JSON_EXTRACT(`mrr`.`results`, '$."tests"."holland"."Социальный"') >= 6 THEN `mrr`.`respondent_id` END))*100)/:count) `Социальный`
FROM `methodics_respondents_results` `mrr`
INNER JOIN `respondents_pupils` ON `respondents_pupils`.`id` = `mrr`.`respondent_id`
WHERE `mrr`.`results` IS NOT NULL
AND `mrr`.`results`->'$."methodic"' = 'proforientation'
AND `respondents_pupils`.`active_tests_id` = :activeTestsId
$schoolsClass
sql;
        $query = $pdo->prepare($sql);
        return $query->execute($data) ? $query->fetch($pdo::FETCH_ASSOC) : false;
    }
    
}
