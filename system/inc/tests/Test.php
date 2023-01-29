<?php

namespace tests;

if (!defined('PRFK_SITE_ROOT_DIR')) {
  die();
}

use PDO, DateTime, core\DBConnect as DBConnect;

/**
 * Тест
 * @package tests
 */
class Test
{
    /**
     * Производит подсчет ответов пользователя пошкально для простых тестов
     * в случае, если система подсчета сложная, метод переопределен в потомке
     * @param int || string $answers идентификатор ответов пользователя или JSON ответов
     * @param bool $debug в случае true, $answers принимает JSON ответов пользователя
     * @return array || bool
     */
    public function calculate($answers, $debug = false)
    {
        //1)получить список ответов юзера
        $answersArr = $debug ? $answers : $this->getUsersAnswers($answers)['answers'];

        //2)получить список шкал
        $scales = $this->getScales();

        //3)подсчет баллов
        $result = [];
        foreach ($scales as $scale => $keys) {
            $result[$scale] = $i = 0;
            $keys = $keys['answers'];
            foreach ($answersArr as $q => $a) {
                if (isset($keys[$q]) && $keys[$q] === (int) $a[0]) {
                    $result[$scale] = ++$i;
                }
            }
        }
        arsort($result);
        return empty($result) ? false : $result;
    }

    /**
     * Вернуть машинное имя на основе названия класса
     * NOTE: не юзать у объекта Test, только у потомков
     * @return String
     */
    public function getMachineName()
    {
        $machineName = strtolower((new \ReflectionClass($this))->getShortName());
        return $machineName;
    }

    /**
     * Вернуть всю информацию о тесте, включая список вопросов
     * @param string $machineName
     * @return array
     */
    protected function getTest($machineName)
    {
        $db = DBConnect::getInstance();
        $sql = <<<sql
        SELECT * FROM tests WHERE content->'$.meta.machine_name' = ?
sql;
        $q = $db->prepare($sql);
        $q->execute([$machineName]);
        $test = $q->fetch(PDO::FETCH_ASSOC);
        return json_decode($test['content'], true);
    }

    /**
     * Получить ID неоконченного результата теста
     * @param int $respondentId
     * @return array or boolean
     */
    protected function getUnfinished($respondentId) 
    {
        $db = DBConnect::getInstance();
        $sql = <<<sql
SELECT 
`result`.`id` AS `result_id`, 
`answers`.`id` AS `answers_id`, 
`answers`.`testname`,      
`answers`.`answers`
FROM `tests_accounts_answers` AS `answers`
INNER JOIN `methodics_respondents_results` AS `result` 
ON `result`.`id` = `answers`.`methodics_respondents_results_id`
AND `result`.`endtime` IS NULL AND `answers`.`finished` = 0
WHERE `result`.`respondent_id` = ?
ORDER BY `result`.`id` DESC LIMIT 1
sql;
        $query = $db->prepare($sql);
        $result = $query->execute([$respondentId]) ? $query->fetch(PDO::FETCH_ASSOC) : false;
        return $result ? $result : false;
    }
        
    /**
     * Сохранить ответы пользователя на вопросы теста
     * @param array $data
     * @param boolean $update
     * @return boolean || int
     */
    protected function saveUsersAnswers($data)
    {
        $pdo = DBConnect::getInstance();
        $JSONanswers = json_encode($data['answers']);
        $sql = <<<sql
INSERT INTO `tests_accounts_answers`
(`methodics_respondents_results_id`, `testname`, `answers`, `finished`) 
VALUES(:resultId, :testName, :answers, :finished)
sql;
        $fields = [
            ':resultId' => $data['resultId'],
            ':testName' => $data['answers']['test'],
            ':answers' => $JSONanswers,
            ':finished' => $data['finished']
        ];
        $q = $pdo->prepare($sql);
        return $q->execute($fields) && $q->rowCount() ? $pdo->lastInsertId() : false;
    }
    
    /**
     * Обновить ответы пользователя на вопросы теста
     * @param array $data
     * @param boolean $update
     * @return boolean
     */
    protected function updateUsersAnswers($data)
    {
        $pdo = DBConnect::getInstance();
        $JSONanswers = json_encode($data['answers']);
        $sql = <<<sql
UPDATE `tests_accounts_answers` SET 
`answers`=:answers, `finished`=:finished 
WHERE `id`=:id
sql;
        $fields = [
            ':id' => $data['id'],
            ':answers' => $JSONanswers,
            ':finished' => $data['finished']
        ];
        $q = $pdo->prepare($sql);
        return $q->execute($fields) && $q->rowCount() ? true : false;
    }

    /**
     * Вернуть массив ответов пользователя на вопросы теста
     * @param int $answersID
     * @return array
     */
    protected function getUsersAnswers($answersID)
    {
        $pdo = DBConnect::getInstance();
        $sql = <<<sql
SELECT `answers` FROM `tests_accounts_answers` WHERE `id` = ?
sql;
        $query = $pdo->prepare($sql);
        $query->execute([$answersID]);
        $test = $query->fetch(PDO::FETCH_ASSOC);
        return json_decode($test['answers'], true);
    }
    
    protected function getUsersAnswersUp($answersID)
    {
        $pdo = DBConnect::getInstance();
        $sql = <<<sql
SELECT `answers` FROM `tests_accounts_answers` WHERE `id` = ?
sql;
        $query = $pdo->prepare($sql);
        $query->execute([$answersID]);
        $test = $query->fetch(PDO::FETCH_ASSOC);
        $answers = json_decode($test['answers'], true);
        foreach ($answers['answers'] as $i => $answer) {
            if ($i == 7) {     
                foreach ($answer as $j => $a) {
                    if ($a > 1) {
                        $answers['answers'][$i][$j] = (string)($a + 1); 
                    }
                }
            }
        }
        
//$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        $sql2 = <<<sql
UPDATE `tests_accounts_answers` SET `answers` = ? WHERE `id` = ?
sql;
        $query2 = $pdo->prepare($sql2);
        $query2->execute([json_encode($answers), $answersID]);
        return $answers;
    }

    /**
     * Вернуть список шкал. Используется только, когда есть Шкалы
     * @return array
     */
    protected function getScales()
    {
        $db = DBConnect::getInstance();
        $sql = <<<sql
SELECT content->'$.counting.scales' scales
FROM tests WHERE content->'$.meta.machine_name' = ?
sql;
        $q = $db->prepare($sql);
        $machineName = $this->getMachineName();
        $q->execute([$machineName]);
        $test = $q->fetch(PDO::FETCH_ASSOC);
        return json_decode($test['scales'], true);
    }

    /**
     * Вывести список ответов Респондента на вопросы Анкеты
     * @param int $answersID - идентификатор ответов пользователя
     * @param bool $hidden - показывать в т.ч. и скрытые ответы
     * @param string $testname - машиннное имя теста-анкеты
     * @return array or bool
     */
    protected function calculateSheet($answersID, $hidden = false, $testname)
    {
        //1)получить список ответов юзера
        $answers = $this->getUsersAnswers($answersID)['answers'];

        //2)получить список вопросов
        $test = $this->getTest($testname);
        $questions = $test['questions'];

        //3)сопоставить ответы и вопросы
        $result = [];
        foreach ($answers as $key => $value) {
            if (isset($questions[$key]['result'])) {
                if ($questions[$key]['result'] || $hidden) {
                    $respondentAnswer = [];
                    if ($questions[$key]['type'] == 'custom') {
                        //проверить, что вопрос имеет несколько версий
                        $respondent = new \core\Respondent();
                        $userFields = $respondent->getFields($this->getUsersIdByAnswersId($answersID));
                        $questionCustomIndex = 1;
                        foreach ($questions[$key]['question'] as $i => $custom) {
                            if (isset($userFields[$custom['clause']['type']]) && isset($custom['clause']['value']) && $custom['clause']['value'] == $userFields[$custom['clause']['type']]) {
                                $questionCustomIndex = $i; //"особый" респондент
                            } else if ($custom['clause']['type'] == 'default') {
                                $questionCustomIndex = $i; //"обычный" респондент
                            }
                        }
                        $questionText = $questions[$key]['question'][$questionCustomIndex]['variant']['question'];
                    } else {
                        $questionText = $questions[$key]['result'];
                    }
                    foreach ($value as $i) {
                        if ($questions[$key]['type'] == 'custom') {
                            if (isset($questions[$key]['question'][$questionCustomIndex]['variant']['answers'][$i]['result'])) {
                                $answerValue = $questions[$key]['question'][$questionCustomIndex]['variant']['answers'][$i]['result'];
                            } else {
                                $answerValue = isset(
                                        $questions[$key]['question'][$questionCustomIndex]['variant']['answers'][$i]['answer']) ? 
                                        $questions[$key]['question'][$questionCustomIndex]['variant']['answers'][$i]['answer'] : $i;
                            }
                        } else {
                            if (isset($questions[$key]['answers'][$i]['result'])) {
                                $answerValue = $questions[$key]['answers'][$i]['result'];
                            } else {
                                $answerValue = isset($questions[$key]['answers'][$i]['answer']) ? $questions[$key]['answers'][$i]['answer'] : $i;
                            }
                        }
                        $respondentAnswer[] = _mb_lcfirst($answerValue);
                    }
                    $result[$key] = [
                        'question' => $questionText,
                        'answer' => $respondentAnswer
                    ];
                }
            }
        }

        return empty($result) ? false : $result;
    }
    
    /**
     * Вернуть список Методик(у), групп(ы) тестов
     * @param int $organizationId
     * @param int $methodicsId
     * @return array
     */
    protected function getMethodics($organizationId, $methodicsId = 0)
    {
        $db = DBConnect::getInstance();
        $where = $organizationId ? "LEFT JOIN `active_tests_premium` 
            ON `active_tests_premium`.`methodics_id` = `methodics`.`id` 
            AND `active_tests_premium`.`organizations_id` = $organizationId" : '';
        $where .= $methodicsId ? " WHERE `methodics`.`id` = $methodicsId" : '';
        $sql = <<<sql
        SELECT `methodics`.*, `active_tests_premium`.`id` AS `premium_enable` FROM `methodics` $where
        GROUP BY `methodics`.`id`
        ORDER BY `sort` ASC
sql;
        $query = $db->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Вернуть список тестов для заданной методики
     * @param int $methodicsId
     * @return array
     */
    protected function getTestsList($methodicsId) 
    {
        $db = DBConnect::getInstance();
        $sql = <<<sql
SELECT 
`t`.`id`, 
`mht`.`num`,
`t`.`content`->'$.meta.machine_name' AS `machine_name`,
`t`.`content`->'$.meta.name' AS `name`,
`t`.`content`->'$.meta.authors' AS `authors`
FROM `tests` AS `t`
INNER JOIN `methodics_has_tests` AS `mht` 
ON `mht`.`methodics_id` = ? AND `mht`.`tests_id` = `t`.`id`
ORDER BY `mht`.`num` ASC
sql;
        $query = $db->prepare($sql);
        $query->execute([$methodicsId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Вернуть id методики по id респондента
     * @param int $respondentId
     * @return int
     */
    protected function getMethodicId($respondentId) {
        $db = DBConnect::getInstance();
        $sql = <<<sql
SELECT `active_tests`.`methodics_id` FROM `active_tests`
INNER JOIN `respondents_pupils` ON `respondents_pupils`.`active_tests_id` = `active_tests`.`id`
WHERE `respondents_pupils`.`id` = ?
sql;
        $query = $db->prepare($sql);
        $query->execute([$respondentId]);
        return $query->fetchColumn();
    }
    
    /**
     * Зафиксировать начало прохождения тестирования
     * @param int $accountId
     * @return boolean or int
     */
    protected function start($accountId)
    {
        $pdo = DBConnect::getInstance();
        $sql = <<<sql
INSERT INTO `methodics_respondents_results`(`respondent_id`) VALUES(?)
sql;
        $query = $pdo->prepare($sql);  
        $result = $query->execute([$accountId]);
        return $result && $query->rowCount() ? $pdo->lastInsertId() : false;
    }

    //...

    

}
