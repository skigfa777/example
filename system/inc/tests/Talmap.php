<?php

namespace tests;

if (!defined('PRFK_SITE_ROOT_DIR')) {
  die();
}

use core\DBConnect as DBConnect;

/**
 * Подсчет теста "Карта одаренности"
 */
class Talmap extends Test {
    
    /**
     * Производит подсчет ответов пользователя пошкально для простых тестов
     * в случае, если система подсчета сложная, метод переопределен в потомке
     * @param int || string $answers идентификатор ответов пользователя или JSON ответов
     * @param bool $debug в случае true, $answers принимает JSON ответов пользователя
     * @return array || bool
     */
    public function calculate($answers, $debug = false) 
    {
        //1)получить список ответов      
        $answersArr = $debug ? $answers : $this->getUsersAnswers($answers)['answers'];
  
        //2)получить список шкал
        $scales = $this->getScales();        

        //3)подсчет баллов
        $result = [];

        foreach ($scales as $scale => $keys) {
            $result[$scale] = 0;
            $keys = $keys['answers'];
            foreach ($answersArr as $q => $a) {  
                if (isset($keys[$q])) {
                    foreach ($keys[$q] as $k) {
                        if ((int) $a[0] === $k['answer_num']) {
                            $result[$scale] += $k['points'];
                        }
                    }
                }
            }
        }
//        arsort($result);
        return empty($result) ? false : array_map(function ($v) {
            return strval(round($v/5, 2));
        }, $result);
    }
    
    /**
     * Вернуть данные для статистики по школе/классу
     * @param array $fields - ['activeTestsId' => 123, 'classNum' => 11, 'classChar => 'а']
     * @return array || boolean
     */
    public function getStat($fields) 
    {
        $pdo = DBConnect::getInstance();        
        if (!$fields['classNum']) {
            $data = [$fields['activeTestsId']];
            $sql = <<<sql
SELECT 
COUNT(CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Академическая"' AS DECIMAL(4,2)) >=2 THEN `mrr`.`respondent_id` END) `Академическая`,
COUNT(CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Артистическая"' AS DECIMAL(4,2)) >=2 THEN `mrr`.`respondent_id` END) `Артистическая`,
COUNT(CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Интеллектуальная"' AS DECIMAL(4,2)) >=2 THEN `mrr`.`respondent_id` END) `Интеллектуальная`,
COUNT(CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Лидерская"' AS DECIMAL(4,2)) >=2 THEN `mrr`.`respondent_id` END) `Лидерская`,
COUNT(CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Литературная"' AS DECIMAL(4,2)) >=2 THEN `mrr`.`respondent_id` END) `Литературная`,
COUNT(CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Музыкальная"' AS DECIMAL(4,2)) >=2 THEN `mrr`.`respondent_id` END) `Музыкальная`,
COUNT(CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Спортивная"' AS DECIMAL(4,2)) >=2 THEN `mrr`.`respondent_id` END) `Спортивная`,
COUNT(CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Творческая"' AS DECIMAL(4,2)) >=2 THEN `mrr`.`respondent_id` END) `Творческая`,
COUNT(CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Техническая"' AS DECIMAL(4,2)) >=2 THEN `mrr`.`respondent_id` END) `Техническая`,
COUNT(CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Художественно-изобразительная"' AS DECIMAL(4,2)) >=2 THEN `mrr`.`respondent_id` END) `Художественно-изобразительная`
FROM `methodics_respondents_results` `mrr`
INNER JOIN `respondents_pupils` ON `respondents_pupils`.`id` = `mrr`.`respondent_id`
WHERE `mrr`.`results` IS NOT NULL
AND `mrr`.`results`->'$."methodic"' = 'choiceprof'
AND `respondents_pupils`.`active_tests_id` = ?
sql;
            $q = $pdo->prepare($sql);
            return $q->execute($data) ? $q->fetch($pdo::FETCH_ASSOC) : false;
        } else {
            $data = [$fields['activeTestsId'], $fields['classNum'], $fields['classChar']];
            if (isset($fields['middle'])) {
                $sql = <<<sql
SELECT 

CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Академическая"' AS DECIMAL(4,2)) >=1.4 AND CAST(`mrr`.`results`->'$."tests"."talmap"."Академическая"' AS DECIMAL(4,2)) <=1.8 THEN
 CONCAT("{text: '", `respondents_pupils`.`lfm`, "'}") END `Академическая`, 
    
CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Артистическая"' AS DECIMAL(4,2)) >=1.4 AND CAST(`mrr`.`results`->'$."tests"."talmap"."Артистическая"' AS DECIMAL(4,2)) <=1.8 THEN
 CONCAT("{text: '", `respondents_pupils`.`lfm`, "'}") END `Артистическая`,         

CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Интеллектуальная"' AS DECIMAL(4,2)) >=1.4 AND CAST(`mrr`.`results`->'$."tests"."talmap"."Интеллектуальная"' AS DECIMAL(4,2)) <=1.8 THEN
 CONCAT("{text: '", `respondents_pupils`.`lfm`, "'}") END `Интеллектуальная`,

CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Лидерская"' AS DECIMAL(4,2)) >=1.4 AND CAST(`mrr`.`results`->'$."tests"."talmap"."Лидерская"' AS DECIMAL(4,2)) <=1.8 THEN
 CONCAT("{text: '", `respondents_pupils`.`lfm`, "'}") END `Лидерская`,
                    
CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Литературная"' AS DECIMAL(4,2)) >=1.4 AND CAST(`mrr`.`results`->'$."tests"."talmap"."Литературная"' AS DECIMAL(4,2)) <=1.8 THEN
 CONCAT("{text: '", `respondents_pupils`.`lfm`, "'}") END `Литературная`,   

CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Музыкальная"' AS DECIMAL(4,2)) >=1.4 AND CAST(`mrr`.`results`->'$."tests"."talmap"."Музыкальная"' AS DECIMAL(4,2)) <=1.8 THEN
 CONCAT("{text: '", `respondents_pupils`.`lfm`, "'}") END `Музыкальная`,   

CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Спортивная"' AS DECIMAL(4,2)) >=1.4 AND CAST(`mrr`.`results`->'$."tests"."talmap"."Спортивная"' AS DECIMAL(4,2)) <=1.8 THEN
 CONCAT("{text: '", `respondents_pupils`.`lfm`, "'}") END `Спортивная`,   

CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Творческая"' AS DECIMAL(4,2)) >=1.4 AND CAST(`mrr`.`results`->'$."tests"."talmap"."Творческая"' AS DECIMAL(4,2)) <=1.8 THEN
 CONCAT("{text: '", `respondents_pupils`.`lfm`, "'}") END `Творческая`,   

CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Техническая"' AS DECIMAL(4,2)) >=1.4 AND CAST(`mrr`.`results`->'$."tests"."talmap"."Техническая"' AS DECIMAL(4,2)) <=1.8 THEN
 CONCAT("{text: '", `respondents_pupils`.`lfm`, "'}") END `Техническая`,   

CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Художественно-изобразительная"' AS DECIMAL(4,2)) >=1.4 AND CAST(`mrr`.`results`->'$."tests"."talmap"."Художественно-изобразительная"' AS DECIMAL(4,2)) <=1.8 THEN
 CONCAT("{text: '", `respondents_pupils`.`lfm`, "'}") END `Художественно-изобразительная` 
                    
FROM `methodics_respondents_results` `mrr`
INNER JOIN `respondents_pupils` ON `respondents_pupils`.`id` = `mrr`.`respondent_id`
WHERE `mrr`.`results` IS NOT NULL
AND `mrr`.`results`->'$."methodic"' = 'choiceprof'
AND `respondents_pupils`.`active_tests_id` = ? 
AND `respondents_pupils`.`class_num` = ?
AND `respondents_pupils`.`class_char` = ?
GROUP BY `respondents_pupils`.`id`
sql;
            } else {
                $sql = <<<sql
SELECT 

CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Академическая"' AS DECIMAL(4,2)) >=2 THEN
CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Академическая"' AS DECIMAL(4,2)) >=2.6
THEN CONCAT("{text: '", `respondents_pupils`.`lfm`, "', bold: true}") ELSE CONCAT("{text: '", `respondents_pupils`.`lfm`, "'}") END END `Академическая`, 
    
CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Артистическая"' AS DECIMAL(4,2)) >=2 THEN
CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Артистическая"' AS DECIMAL(4,2)) >=2.6
THEN CONCAT("{text: '", `respondents_pupils`.`lfm`, "', bold: true}") ELSE CONCAT("{text: '", `respondents_pupils`.`lfm`, "'}") END END `Артистическая`,         

CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Интеллектуальная"' AS DECIMAL(4,2)) >=2 THEN
CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Интеллектуальная"' AS DECIMAL(4,2)) >=2.6
THEN CONCAT("{text: '", `respondents_pupils`.`lfm`, "', bold: true}") ELSE CONCAT("{text: '", `respondents_pupils`.`lfm`, "'}") END END `Интеллектуальная`,

CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Лидерская"' AS DECIMAL(4,2)) >=2 THEN
CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Лидерская"' AS DECIMAL(4,2)) >=2.6
THEN CONCAT("{text: '", `respondents_pupils`.`lfm`, "', bold: true}") ELSE CONCAT("{text: '", `respondents_pupils`.`lfm`, "'}") END END `Лидерская`,
                    
CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Литературная"' AS DECIMAL(4,2)) >=2 THEN
CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Литературная"' AS DECIMAL(4,2)) >=2.6
THEN CONCAT("{text: '", `respondents_pupils`.`lfm`, "', bold: true}") ELSE CONCAT("{text: '", `respondents_pupils`.`lfm`, "'}") END END `Литературная`,   

CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Музыкальная"' AS DECIMAL(4,2)) >=2 THEN
CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Музыкальная"' AS DECIMAL(4,2)) >=2.6
THEN CONCAT("{text: '", `respondents_pupils`.`lfm`, "', bold: true}") ELSE CONCAT("{text: '", `respondents_pupils`.`lfm`, "'}") END END `Музыкальная`,   

CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Спортивная"' AS DECIMAL(4,2)) >=2 THEN
CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Спортивная"' AS DECIMAL(4,2)) >=2.6
THEN CONCAT("{text: '", `respondents_pupils`.`lfm`, "', bold: true}") ELSE CONCAT("{text: '", `respondents_pupils`.`lfm`, "'}") END END `Спортивная`,   

CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Творческая"' AS DECIMAL(4,2)) >=2 THEN
CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Творческая"' AS DECIMAL(4,2)) >=2.6
THEN CONCAT("{text: '", `respondents_pupils`.`lfm`, "', bold: true}") ELSE CONCAT("{text: '", `respondents_pupils`.`lfm`, "'}") END END `Творческая`,   

CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Техническая"' AS DECIMAL(4,2)) >=2 THEN
CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Техническая"' AS DECIMAL(4,2)) >=2.6
THEN CONCAT("{text: '", `respondents_pupils`.`lfm`, "', bold: true}") ELSE CONCAT("{text: '", `respondents_pupils`.`lfm`, "'}") END END `Техническая`,   

CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Художественно-изобразительная"' AS DECIMAL(4,2)) >=2 THEN
CASE WHEN CAST(`mrr`.`results`->'$."tests"."talmap"."Художественно-изобразительная"' AS DECIMAL(4,2)) >=2.6
THEN CONCAT("{text: '", `respondents_pupils`.`lfm`, "', bold: true}") ELSE CONCAT("{text: '", `respondents_pupils`.`lfm`, "'}") END END `Художественно-изобразительная` 
                    
FROM `methodics_respondents_results` `mrr`
INNER JOIN `respondents_pupils` ON `respondents_pupils`.`id` = `mrr`.`respondent_id`
WHERE `mrr`.`results` IS NOT NULL
AND `mrr`.`results`->'$."methodic"' = 'choiceprof'
AND `respondents_pupils`.`active_tests_id` = ? 
AND `respondents_pupils`.`class_num` = ?
AND `respondents_pupils`.`class_char` = ?
GROUP BY `respondents_pupils`.`id`
sql;
            }
            $q = $pdo->prepare($sql);
            return $q->execute($data) ? $q->fetchAll($pdo::FETCH_ASSOC) : false;
        } 
    } 
}
