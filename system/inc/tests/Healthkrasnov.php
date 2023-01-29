<?php

namespace tests;

if (!defined('PRFK_SITE_ROOT_DIR')) {
  die();
}

use PDO, core\DBConnect as DBConnect;

/**
 * Подсчет теста "Здоровье и самочувствие" (модификация  опросника В.Н. Краснова)
 * @package tests
 */
class Healthkrasnov extends Test
{
    
    /**
     * Производит подсчет ответов пользователя пошкально для простых тестов
     * в случае, если система подсчета сложная, метод переопределен в потомке
     * @param int $answersID идентификатор ответов пользователя
     * @return array || bool
     */
    public function calculate($answersID, $data = false)
    {
        if ($data) {
            $point = $data['Да'] * 1 + $data['Пожалуй, да'] * 0.5;
        } else {
            //1)получить список ответов юзера
            $answers = $this->getUsersAnswers($answersID)['answers'];

            //2)подсчет баллов
            $point = 0;
            foreach ($answers as $i => $answer) {
                if ($i > 1) {
                    switch ($answer[0]) {
                        case 1:
                            $point++;
                            break;
                        case 2:
                            $point = $point + 0.5;
                            break;
                    }
                }
            }
        }
        return [
            'Группа риска' => $point >= 10 ? 'Да' : 'Нет',
            'Балл' => $point
        ];
    }
 
    /**
     * Вернуть данные для статистики по школе/классу
     * @param array $fields - ['activeTestsId' => 123, 'classNum' => 11, 'classChar => 'а']
     * @return array || boolean
     */
    public function getStat($fields)        
    {
        $pdo = DBConnect::getInstance();
        $schoolsClass = 'AND `respondents_pupils`.`class_num` = ? AND `respondents_pupils`.`class_char` = ?';
        $data = [$fields['activeTestsId'], $fields['classNum'], $fields['classChar']];
        if (!$fields['classNum']) {
            $schoolsClass = '';
            $data = [$fields['activeTestsId']];
        }
        $sql = <<<sql
SELECT 
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."healthkrasnov"."Группа риска"' = 'Да' THEN `mrr`.`respondent_id` END)) `"Группа риска" по состоянию психического здоровья в т.ч. суицидальному поведению`,
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."healthkrasnov"."Группа риска"' = 'Нет' THEN `mrr`.`respondent_id` END)) `Состояние психического здоровья в норме`
FROM `methodics_respondents_results` `mrr`
INNER JOIN `respondents_pupils` ON `respondents_pupils`.`id` = `mrr`.`respondent_id`
WHERE `mrr`.`results` IS NOT NULL
AND `mrr`.`results`->'$."methodic"' = 'suicide'
AND `respondents_pupils`.`active_tests_id` = ?
$schoolsClass            
sql;
        $query = $pdo->prepare($sql);
        return $query->execute($data) ? $query->fetch($pdo::FETCH_ASSOC) : false;
    }
    
}
