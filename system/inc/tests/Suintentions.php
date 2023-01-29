<?php

namespace tests;

if (!defined('PRFK_SITE_ROOT_DIR')) {
  die();
}

use PDO, core\DBConnect as DBConnect;

/**
 * Подсчет теста "Психодиагностика суицидальных намерений" (А.А. Кучер, В.П. Костюкевич, В.П. Войцех)
 * @package tests
 */
class Suintentions extends Test
{
    
    /**
     * Производит подсчет ответов пользователя пошкально для простых тестов
     * в случае, если система подсчета сложная, метод переопределен в потомке
     * @param int $answersID идентификатор ответов пользователя
     * @return array
     */
    public function calculate($answersID, $data = false)
    {
        if ($data) {
            //1)подсчет баллов
            foreach ($data['scales'] as $dataKey => $dataValue) {
                $r[$dataKey]['Балл'] = $dataValue;
                $r[$dataKey]['Итог'] = '';
            }
            
            //2)подводим итог по шкале
            foreach ($data['scales'] as $dataValue2) {
                if ($data['respondent']['gender'] == 'мужской') {
                    switch ($data['respondent']['class_num']) {
                        case ($data['respondent']['class_num'] >= 5 && $data['respondent']['class_num'] <= 7):
                            $r['Алкоголь, наркотики']['Итог'] = $this->compare($r['Алкоголь, наркотики']['Балл'], 13, 15, 15);
                            $r['Несчастная любовь']['Итог'] = $this->compare($r['Несчастная любовь']['Балл'], 10, 12, 12);
                            $r['Противоправные действия']['Итог'] = $this->compare($r['Противоправные действия']['Балл'], 13, 15, 15);
                            $r['Деньги и проблемы с ними']['Итог'] = $this->compare($r['Деньги и проблемы с ними']['Балл'], 16, 17, 17);
                            $r['Добровольный уход из жизни']['Итог'] = $this->compare($r['Добровольный уход из жизни']['Балл'], 10, 11, 11);
                            $r['Семейные неурядицы']['Итог'] = $this->compare($r['Семейные неурядицы']['Балл'], 12, 14, 14);
                            $r['Потеря смысла жизни']['Итог'] = $this->compare($r['Потеря смысла жизни']['Балл'], 12, 13, 13);
                            $r['Чувство неполноценности, ущербности, уродливости']['Итог'] = $this->compare($r['Чувство неполноценности, ущербности, уродливости']['Балл'], 12, 14, 14);
                            $r['Школьные проблемы, проблема выбора жизненного пути']['Итог'] = $this->compare($r['Школьные проблемы, проблема выбора жизненного пути']['Балл'], 11, 13, 13);
                            $r['Отношения с окружающими']['Итог'] = $this->compare($r['Отношения с окружающими']['Балл'], 15, 18, 18);
                            break;
                        case ($data['respondent']['class_num'] >= 8 && $data['respondent']['class_num'] <= 9):
                            $r['Алкоголь, наркотики']['Итог'] = $this->compare($r['Алкоголь, наркотики']['Балл'], 11, 12, 12);
                            $r['Несчастная любовь']['Итог'] = $this->compare($r['Несчастная любовь']['Балл'], 11, 13, 13);
                            $r['Противоправные действия']['Итог'] = $this->compare($r['Противоправные действия']['Балл'], 13, 15, 15);
                            $r['Деньги и проблемы с ними']['Итог'] = $this->compare($r['Деньги и проблемы с ними']['Балл'], 16, 18, 18);
                            $r['Добровольный уход из жизни']['Итог'] = $this->compare($r['Добровольный уход из жизни']['Балл'], 10, 12, 12);
                            $r['Семейные неурядицы']['Итог'] = $this->compare($r['Семейные неурядицы']['Балл'], 11, 13, 13);
                            $r['Потеря смысла жизни']['Итог'] = $this->compare($r['Потеря смысла жизни']['Балл'], 11, 12, 12);
                            $r['Чувство неполноценности, ущербности, уродливости']['Итог'] = $this->compare($r['Чувство неполноценности, ущербности, уродливости']['Балл'], 12, 13, 13);
                            $r['Школьные проблемы, проблема выбора жизненного пути']['Итог'] = $this->compare($r['Школьные проблемы, проблема выбора жизненного пути']['Балл'], 10, 12, 12);
                            $r['Отношения с окружающими']['Итог'] = $this->compare($r['Отношения с окружающими']['Балл'], 14, 16, 16);
                            break;
                        case ($data['respondent']['class_num'] >= 10 && $data['respondent']['class_num'] <= 11):
                            $r['Алкоголь, наркотики']['Итог'] = $this->compare($r['Алкоголь, наркотики']['Балл'], 10, 11, 11);
                            $r['Несчастная любовь']['Итог'] = $this->compare($r['Несчастная любовь']['Балл'], 8, 10, 10);
                            $r['Противоправные действия']['Итог'] = $this->compare($r['Противоправные действия']['Балл'], 13, 15, 15);
                            $r['Деньги и проблемы с ними']['Итог'] = $this->compare($r['Деньги и проблемы с ними']['Балл'], 18, 20, 20);
                            $r['Добровольный уход из жизни']['Итог'] = $this->compare($r['Добровольный уход из жизни']['Балл'], 7, 8, 8);
                            $r['Семейные неурядицы']['Итог'] = $this->compare($r['Семейные неурядицы']['Балл'], 11, 13, 13);
                            $r['Потеря смысла жизни']['Итог'] = $this->compare($r['Потеря смысла жизни']['Балл'], 11, 12, 12);
                            $r['Чувство неполноценности, ущербности, уродливости']['Итог'] = $this->compare($r['Чувство неполноценности, ущербности, уродливости']['Балл'], 11, 13, 13);
                            $r['Школьные проблемы, проблема выбора жизненного пути']['Итог'] = $this->compare($r['Школьные проблемы, проблема выбора жизненного пути']['Балл'], 11, 12, 12);
                            $r['Отношения с окружающими']['Итог'] = $this->compare($r['Отношения с окружающими']['Балл'], 19, 19, 23);
                            break;
                    }
                } else if ($data['respondent']['gender'] == 'женский') {
                    switch ($data['respondent']['class_num']) {
                        case ($data['respondent']['class_num'] >= 5 && $data['respondent']['class_num'] <= 7):
                            $r['Алкоголь, наркотики']['Итог'] = $this->compare($r['Алкоголь, наркотики']['Балл'], 10, 11, 11);
                            $r['Несчастная любовь']['Итог'] = $this->compare($r['Несчастная любовь']['Балл'], 9, 11, 11);
                            $r['Противоправные действия']['Итог'] = $this->compare($r['Противоправные действия']['Балл'], 12, 14, 14);
                            $r['Деньги и проблемы с ними']['Итог'] = $this->compare($r['Деньги и проблемы с ними']['Балл'], 15, 17, 17);
                            $r['Добровольный уход из жизни']['Итог'] = $this->compare($r['Добровольный уход из жизни']['Балл'], 10, 11, 11);
                            $r['Семейные неурядицы']['Итог'] = $this->compare($r['Семейные неурядицы']['Балл'], 13, 14, 14);
                            $r['Потеря смысла жизни']['Итог'] = $this->compare($r['Потеря смысла жизни']['Балл'], 12, 13, 13);
                            $r['Чувство неполноценности, ущербности, уродливости']['Итог'] = $this->compare($r['Чувство неполноценности, ущербности, уродливости']['Балл'], 13, 14, 14);
                            $r['Школьные проблемы, проблема выбора жизненного пути']['Итог'] = $this->compare($r['Школьные проблемы, проблема выбора жизненного пути']['Балл'], 12, 14, 14);
                            $r['Отношения с окружающими']['Итог'] = $this->compare($r['Отношения с окружающими']['Балл'], 15, 18, 18);
                            break;
                        case ($data['respondent']['class_num'] >= 8 && $data['respondent']['class_num'] <= 9):
                            $r['Алкоголь, наркотики']['Итог'] = $this->compare($r['Алкоголь, наркотики']['Балл'], 11, 12, 12);
                            $r['Несчастная любовь']['Итог'] = $this->compare($r['Несчастная любовь']['Балл'], 11, 12, 12);
                            $r['Противоправные действия']['Итог'] = $this->compare($r['Противоправные действия']['Балл'], 13, 14, 14);
                            $r['Деньги и проблемы с ними']['Итог'] = $this->compare($r['Деньги и проблемы с ними']['Балл'], 16, 17, 17);
                            $r['Добровольный уход из жизни']['Итог'] = $this->compare($r['Добровольный уход из жизни']['Балл'], 9, 11, 11);
                            $r['Семейные неурядицы']['Итог'] = $this->compare($r['Семейные неурядицы']['Балл'], 11, 12, 12);
                            $r['Потеря смысла жизни']['Итог'] = $this->compare($r['Потеря смысла жизни']['Балл'], 11, 13, 13);
                            $r['Чувство неполноценности, ущербности, уродливости']['Итог'] = $this->compare($r['Чувство неполноценности, ущербности, уродливости']['Балл'], 11, 13, 13);
                            $r['Школьные проблемы, проблема выбора жизненного пути']['Итог'] = $this->compare($r['Школьные проблемы, проблема выбора жизненного пути']['Балл'], 11, 12, 12);
                            $r['Отношения с окружающими']['Итог'] = $this->compare($r['Отношения с окружающими']['Балл'], 15, 16, 16);
                            break;
                        case ($data['respondent']['class_num'] >= 10 && $data['respondent']['class_num'] <= 11):
                            $r['Алкоголь, наркотики']['Итог'] = $this->compare($r['Алкоголь, наркотики']['Балл'], 9, 9, 9);
                            $r['Несчастная любовь']['Итог'] = $this->compare($r['Несчастная любовь']['Балл'], 8, 10, 10);
                            $r['Противоправные действия']['Итог'] = $this->compare($r['Противоправные действия']['Балл'], 13, 15, 15);
                            $r['Деньги и проблемы с ними']['Итог'] = $this->compare($r['Деньги и проблемы с ними']['Балл'], 18, 20, 20);
                            $r['Добровольный уход из жизни']['Итог'] = $this->compare($r['Добровольный уход из жизни']['Балл'], 7, 8, 8);
                            $r['Семейные неурядицы']['Итог'] = $this->compare($r['Семейные неурядицы']['Балл'], 12, 13, 13);
                            $r['Потеря смысла жизни']['Итог'] = $this->compare($r['Потеря смысла жизни']['Балл'], 11, 13, 13);
                            $r['Чувство неполноценности, ущербности, уродливости']['Итог'] = $this->compare($r['Чувство неполноценности, ущербности, уродливости']['Балл'], 12, 13, 13);
                            $r['Школьные проблемы, проблема выбора жизненного пути']['Итог'] = $this->compare($r['Школьные проблемы, проблема выбора жизненного пути']['Балл'], 11, 13, 13);
                            $r['Отношения с окружающими']['Итог'] = $this->compare($r['Отношения с окружающими']['Балл'], 22, 25, 25);
                            break;
                    }
                } 
            }
        } else {
            //1)получить список ответов юзера
            $answers = $this->getUsersAnswers($answersID)['answers'];

            //2)получить список вопросов
            $test = $this->getTest('suIntentions');
            $questions = $test['questions'];

            //2)получаем данные о поле и возрасте (по классу) респондента
            $pdo = DBConnect::getInstance();
            $sql = <<<sql
SELECT `respondents_pupils`.`gender`, `respondents_pupils`.`class_num` FROM `respondents_pupils`
INNER JOIN `methodics_respondents_results` ON `methodics_respondents_results`.`respondent_id` = `respondents_pupils`.`id`
INNER JOIN `tests_accounts_answers` ON `tests_accounts_answers`.`methodics_respondents_results_id` = `methodics_respondents_results`.`id`
AND `tests_accounts_answers`.`id` = ?
sql;
            $query = $pdo->prepare($sql);
            $query->execute([$answersID]);
            $respondentData = $query->fetch(PDO::FETCH_ASSOC);
//        $respondentData['gender'] = 'мужской';
//        $respondentData['class_num'] = 10;

            //3)подсчет баллов
            foreach ($questions[1]['answers'] as $value) {
                $r[$value['answer']]['Балл'] = 0;
                $r[$value['answer']]['Итог'] = '';
            }
            foreach ($answers as $i => $answer) {
                $r[$questions[1]['answers'][$answer[0]]['answer']]['Балл']++;
                //подводим итог по шкале
                if ($respondentData['gender'] == 'мужской') {
                    switch ($respondentData['class_num']) {
                        case ($respondentData['class_num'] >= 5 && $respondentData['class_num'] <= 7):
                            $r['Алкоголь, наркотики']['Итог'] = $this->compare($r['Алкоголь, наркотики']['Балл'], 13, 15, 15);
                            $r['Несчастная любовь']['Итог'] = $this->compare($r['Несчастная любовь']['Балл'], 10, 12, 12);
                            $r['Противоправные действия']['Итог'] = $this->compare($r['Противоправные действия']['Балл'], 13, 15, 15);
                            $r['Деньги и проблемы с ними']['Итог'] = $this->compare($r['Деньги и проблемы с ними']['Балл'], 16, 17, 17);
                            $r['Добровольный уход из жизни']['Итог'] = $this->compare($r['Добровольный уход из жизни']['Балл'], 10, 11, 11);
                            $r['Семейные неурядицы']['Итог'] = $this->compare($r['Семейные неурядицы']['Балл'], 12, 14, 14);
                            $r['Потеря смысла жизни']['Итог'] = $this->compare($r['Потеря смысла жизни']['Балл'], 12, 13, 13);
                            $r['Чувство неполноценности, ущербности, уродливости']['Итог'] = $this->compare($r['Чувство неполноценности, ущербности, уродливости']['Балл'], 12, 14, 14);
                            $r['Школьные проблемы, проблема выбора жизненного пути']['Итог'] = $this->compare($r['Школьные проблемы, проблема выбора жизненного пути']['Балл'], 11, 13, 13);
                            $r['Отношения с окружающими']['Итог'] = $this->compare($r['Отношения с окружающими']['Балл'], 15, 18, 18);
                            break;
                        case ($respondentData['class_num'] >= 8 && $respondentData['class_num'] <= 9):
                            $r['Алкоголь, наркотики']['Итог'] = $this->compare($r['Алкоголь, наркотики']['Балл'], 11, 12, 12);
                            $r['Несчастная любовь']['Итог'] = $this->compare($r['Несчастная любовь']['Балл'], 11, 13, 13);
                            $r['Противоправные действия']['Итог'] = $this->compare($r['Противоправные действия']['Балл'], 13, 15, 15);
                            $r['Деньги и проблемы с ними']['Итог'] = $this->compare($r['Деньги и проблемы с ними']['Балл'], 16, 18, 18);
                            $r['Добровольный уход из жизни']['Итог'] = $this->compare($r['Добровольный уход из жизни']['Балл'], 10, 12, 12);
                            $r['Семейные неурядицы']['Итог'] = $this->compare($r['Семейные неурядицы']['Балл'], 11, 13, 13);
                            $r['Потеря смысла жизни']['Итог'] = $this->compare($r['Потеря смысла жизни']['Балл'], 11, 12, 12);
                            $r['Чувство неполноценности, ущербности, уродливости']['Итог'] = $this->compare($r['Чувство неполноценности, ущербности, уродливости']['Балл'], 12, 13, 13);
                            $r['Школьные проблемы, проблема выбора жизненного пути']['Итог'] = $this->compare($r['Школьные проблемы, проблема выбора жизненного пути']['Балл'], 10, 12, 12);
                            $r['Отношения с окружающими']['Итог'] = $this->compare($r['Отношения с окружающими']['Балл'], 14, 16, 16);
                            break;
                        case ($respondentData['class_num'] >= 10 && $respondentData['class_num'] <= 11):
                            $r['Алкоголь, наркотики']['Итог'] = $this->compare($r['Алкоголь, наркотики']['Балл'], 10, 11, 11);
                            $r['Несчастная любовь']['Итог'] = $this->compare($r['Несчастная любовь']['Балл'], 8, 10, 10);
                            $r['Противоправные действия']['Итог'] = $this->compare($r['Противоправные действия']['Балл'], 13, 15, 15);
                            $r['Деньги и проблемы с ними']['Итог'] = $this->compare($r['Деньги и проблемы с ними']['Балл'], 18, 20, 20);
                            $r['Добровольный уход из жизни']['Итог'] = $this->compare($r['Добровольный уход из жизни']['Балл'], 7, 8, 8);
                            $r['Семейные неурядицы']['Итог'] = $this->compare($r['Семейные неурядицы']['Балл'], 11, 13, 13);
                            $r['Потеря смысла жизни']['Итог'] = $this->compare($r['Потеря смысла жизни']['Балл'], 11, 12, 12);
                            $r['Чувство неполноценности, ущербности, уродливости']['Итог'] = $this->compare($r['Чувство неполноценности, ущербности, уродливости']['Балл'], 11, 13, 13);
                            $r['Школьные проблемы, проблема выбора жизненного пути']['Итог'] = $this->compare($r['Школьные проблемы, проблема выбора жизненного пути']['Балл'], 11, 12, 12);
                            $r['Отношения с окружающими']['Итог'] = $this->compare($r['Отношения с окружающими']['Балл'], 19, 19, 23);
                            break;
                    }
                } else if ($respondentData['gender'] == 'женский') {
                    switch ($respondentData['class_num']) {
                        case ($respondentData['class_num'] >= 5 && $respondentData['class_num'] <= 7):
                            $r['Алкоголь, наркотики']['Итог'] = $this->compare($r['Алкоголь, наркотики']['Балл'], 10, 11, 11);
                            $r['Несчастная любовь']['Итог'] = $this->compare($r['Несчастная любовь']['Балл'], 9, 11, 11);
                            $r['Противоправные действия']['Итог'] = $this->compare($r['Противоправные действия']['Балл'], 12, 14, 14);
                            $r['Деньги и проблемы с ними']['Итог'] = $this->compare($r['Деньги и проблемы с ними']['Балл'], 15, 17, 17);
                            $r['Добровольный уход из жизни']['Итог'] = $this->compare($r['Добровольный уход из жизни']['Балл'], 10, 11, 11);
                            $r['Семейные неурядицы']['Итог'] = $this->compare($r['Семейные неурядицы']['Балл'], 13, 14, 14);
                            $r['Потеря смысла жизни']['Итог'] = $this->compare($r['Потеря смысла жизни']['Балл'], 12, 13, 13);
                            $r['Чувство неполноценности, ущербности, уродливости']['Итог'] = $this->compare($r['Чувство неполноценности, ущербности, уродливости']['Балл'], 13, 14, 14);
                            $r['Школьные проблемы, проблема выбора жизненного пути']['Итог'] = $this->compare($r['Школьные проблемы, проблема выбора жизненного пути']['Балл'], 12, 14, 14);
                            $r['Отношения с окружающими']['Итог'] = $this->compare($r['Отношения с окружающими']['Балл'], 15, 18, 18);
                            break;
                        case ($respondentData['class_num'] >= 8 && $respondentData['class_num'] <= 9):
                            $r['Алкоголь, наркотики']['Итог'] = $this->compare($r['Алкоголь, наркотики']['Балл'], 11, 12, 12);
                            $r['Несчастная любовь']['Итог'] = $this->compare($r['Несчастная любовь']['Балл'], 11, 12, 12);
                            $r['Противоправные действия']['Итог'] = $this->compare($r['Противоправные действия']['Балл'], 13, 14, 14);
                            $r['Деньги и проблемы с ними']['Итог'] = $this->compare($r['Деньги и проблемы с ними']['Балл'], 16, 17, 17);
                            $r['Добровольный уход из жизни']['Итог'] = $this->compare($r['Добровольный уход из жизни']['Балл'], 9, 11, 11);
                            $r['Семейные неурядицы']['Итог'] = $this->compare($r['Семейные неурядицы']['Балл'], 11, 12, 12);
                            $r['Потеря смысла жизни']['Итог'] = $this->compare($r['Потеря смысла жизни']['Балл'], 11, 13, 13);
                            $r['Чувство неполноценности, ущербности, уродливости']['Итог'] = $this->compare($r['Чувство неполноценности, ущербности, уродливости']['Балл'], 11, 13, 13);
                            $r['Школьные проблемы, проблема выбора жизненного пути']['Итог'] = $this->compare($r['Школьные проблемы, проблема выбора жизненного пути']['Балл'], 11, 12, 12);
                            $r['Отношения с окружающими']['Итог'] = $this->compare($r['Отношения с окружающими']['Балл'], 15, 16, 16);
                            break;
                        case ($respondentData['class_num'] >= 10 && $respondentData['class_num'] <= 11):
                            $r['Алкоголь, наркотики']['Итог'] = $this->compare($r['Алкоголь, наркотики']['Балл'], 9, 9, 9);
                            $r['Несчастная любовь']['Итог'] = $this->compare($r['Несчастная любовь']['Балл'], 8, 10, 10);
                            $r['Противоправные действия']['Итог'] = $this->compare($r['Противоправные действия']['Балл'], 13, 15, 15);
                            $r['Деньги и проблемы с ними']['Итог'] = $this->compare($r['Деньги и проблемы с ними']['Балл'], 18, 20, 20);
                            $r['Добровольный уход из жизни']['Итог'] = $this->compare($r['Добровольный уход из жизни']['Балл'], 7, 8, 8);
                            $r['Семейные неурядицы']['Итог'] = $this->compare($r['Семейные неурядицы']['Балл'], 12, 13, 13);
                            $r['Потеря смысла жизни']['Итог'] = $this->compare($r['Потеря смысла жизни']['Балл'], 11, 13, 13);
                            $r['Чувство неполноценности, ущербности, уродливости']['Итог'] = $this->compare($r['Чувство неполноценности, ущербности, уродливости']['Балл'], 12, 13, 13);
                            $r['Школьные проблемы, проблема выбора жизненного пути']['Итог'] = $this->compare($r['Школьные проблемы, проблема выбора жизненного пути']['Балл'], 11, 13, 13);
                            $r['Отношения с окружающими']['Итог'] = $this->compare($r['Отношения с окружающими']['Балл'], 22, 25, 25);
                            break;
                    }
                }
            }
        }
        return $r;
    }  
    
    /**
     * Сравнить по шаблону кода и вернуть Итог для шкалы
     * @param int $score
     * @param int $warnMin
     * @param int $warnMax
     * @param int $alert
     * @return string
     */
    private function compare($score, $warnMin, $warnMax, $alert) 
    {
        if ($score >= $warnMin && $score <= $warnMax) {
            return 'Требуется особое внимание';
        } else if ($score > $alert) {
            return 'Требуется формирование антисуицидальных факторов';
        } else {
            return 'Норма';
        }
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
        
        //1) Факторы суицидального риска «Добровольный уход из жизни»
        $sqlQuestion1 = <<<sql
SELECT 
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Добровольный уход из жизни"."Итог"' = 'Требуется формирование антисуицидальных факторов' THEN `mrr`.`respondent_id` END)) `Требуется формирование антисуицидальных факторов`,
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Добровольный уход из жизни"."Итог"' = 'Требуется особое внимание' THEN `mrr`.`respondent_id` END)) `Требуется особое внимание`,
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Добровольный уход из жизни"."Итог"' = 'Норма' THEN `mrr`.`respondent_id` END)) `Риск суицидального поведения не выражен`
FROM `methodics_respondents_results` `mrr`
INNER JOIN `respondents_pupils` ON `respondents_pupils`.`id` = `mrr`.`respondent_id`
WHERE `mrr`.`results` IS NOT NULL
AND `mrr`.`results`->'$."methodic"' = 'suicide'
AND `respondents_pupils`.`active_tests_id` = ?
$schoolsClass
sql;
        $queryQuestion1 = $pdo->prepare($sqlQuestion1);
        $stat['Факторы суицидального риска «Добровольный уход из жизни»'] = $queryQuestion1->execute($data) ? $queryQuestion1->fetch($pdo::FETCH_ASSOC) : null;
        
        //2) Фактор «Алкоголь, наркотики»
        $sqlQuestion2 = <<<sql
SELECT 
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Алкоголь, наркотики"."Итог"' = 'Требуется формирование антисуицидальных факторов' THEN `mrr`.`respondent_id` END)) `Треб. форм. антисуицид. факторов`,
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Алкоголь, наркотики"."Итог"' = 'Требуется особое внимание' THEN `mrr`.`respondent_id` END)) `Требуется особое внимание`,
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Алкоголь, наркотики"."Итог"' = 'Норма' THEN `mrr`.`respondent_id` END)) `Норма`             
FROM `methodics_respondents_results` `mrr`
INNER JOIN `respondents_pupils` ON `respondents_pupils`.`id` = `mrr`.`respondent_id`
WHERE `mrr`.`results` IS NOT NULL
AND `mrr`.`results`->'$."methodic"' = 'suicide'
AND `respondents_pupils`.`active_tests_id` = ?
$schoolsClass
sql;
        $queryQuestion2 = $pdo->prepare($sqlQuestion2);
        $stat['Фактор «Алкоголь, наркотики»'] = $queryQuestion2->execute($data) ? $queryQuestion2->fetch($pdo::FETCH_ASSOC) : null;
        
        //3) Фактор «Несчастная любовь»
        $sqlQuestion3 = <<<sql
SELECT 
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Несчастная любовь"."Итог"' = 'Требуется формирование антисуицидальных факторов' THEN `mrr`.`respondent_id` END)) `Треб. форм. антисуицид. факторов`,
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Несчастная любовь"."Итог"' = 'Требуется особое внимание' THEN `mrr`.`respondent_id` END)) `Требуется особое внимание`,
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Несчастная любовь"."Итог"' = 'Норма' THEN `mrr`.`respondent_id` END)) `Норма`             
FROM `methodics_respondents_results` `mrr`
INNER JOIN `respondents_pupils` ON `respondents_pupils`.`id` = `mrr`.`respondent_id`
WHERE `mrr`.`results` IS NOT NULL
AND `mrr`.`results`->'$."methodic"' = 'suicide'
AND `respondents_pupils`.`active_tests_id` = ?
$schoolsClass
sql;
        $queryQuestion3 = $pdo->prepare($sqlQuestion3);
        $stat['Фактор «Несчастная любовь»'] = $queryQuestion3->execute($data) ? $queryQuestion3->fetch($pdo::FETCH_ASSOC) : null;
        
        //4) Фактор «Семейные неурядицы»
        $sqlQuestion4 = <<<sql
SELECT 
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Семейные неурядицы"."Итог"' = 'Требуется формирование антисуицидальных факторов' THEN `mrr`.`respondent_id` END)) `Треб. форм. антисуицид. факторов`,
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Семейные неурядицы"."Итог"' = 'Требуется особое внимание' THEN `mrr`.`respondent_id` END)) `Требуется особое внимание`,
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Семейные неурядицы"."Итог"' = 'Норма' THEN `mrr`.`respondent_id` END)) `Норма`             
FROM `methodics_respondents_results` `mrr`
INNER JOIN `respondents_pupils` ON `respondents_pupils`.`id` = `mrr`.`respondent_id`
WHERE `mrr`.`results` IS NOT NULL
AND `mrr`.`results`->'$."methodic"' = 'suicide'
AND `respondents_pupils`.`active_tests_id` = ?
$schoolsClass
sql;
        $queryQuestion4 = $pdo->prepare($sqlQuestion4);
        $stat['Фактор «Семейные неурядицы»'] = $queryQuestion4->execute($data) ? $queryQuestion4->fetch($pdo::FETCH_ASSOC) : null;
        
        //5) Фактор «Противоправные действия»
        $sqlQuestion5 = <<<sql
SELECT 
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Противоправные действия"."Итог"' = 'Требуется формирование антисуицидальных факторов' THEN `mrr`.`respondent_id` END)) `Треб. форм. антисуицид. факторов`,
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Противоправные действия"."Итог"' = 'Требуется особое внимание' THEN `mrr`.`respondent_id` END)) `Требуется особое внимание`,
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Противоправные действия"."Итог"' = 'Норма' THEN `mrr`.`respondent_id` END)) `Норма`             
FROM `methodics_respondents_results` `mrr`
INNER JOIN `respondents_pupils` ON `respondents_pupils`.`id` = `mrr`.`respondent_id`
WHERE `mrr`.`results` IS NOT NULL
AND `mrr`.`results`->'$."methodic"' = 'suicide'
AND `respondents_pupils`.`active_tests_id` = ?
$schoolsClass
sql;
        $queryQuestion5 = $pdo->prepare($sqlQuestion5);
        $stat['Фактор «Противоправные действия»'] = $queryQuestion5->execute($data) ? $queryQuestion5->fetch($pdo::FETCH_ASSOC) : null;
        
        //6) Фактор «Деньги и проблемы с ними»
        $sqlQuestion6 = <<<sql
SELECT 
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Деньги и проблемы с ними"."Итог"' = 'Требуется формирование антисуицидальных факторов' THEN `mrr`.`respondent_id` END)) `Треб. форм. антисуицид. факторов`,
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Деньги и проблемы с ними"."Итог"' = 'Требуется особое внимание' THEN `mrr`.`respondent_id` END)) `Требуется особое внимание`,
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Деньги и проблемы с ними"."Итог"' = 'Норма' THEN `mrr`.`respondent_id` END)) `Норма`             
FROM `methodics_respondents_results` `mrr`
INNER JOIN `respondents_pupils` ON `respondents_pupils`.`id` = `mrr`.`respondent_id`
WHERE `mrr`.`results` IS NOT NULL
AND `mrr`.`results`->'$."methodic"' = 'suicide'
AND `respondents_pupils`.`active_tests_id` = ?
$schoolsClass
sql;
        $queryQuestion6 = $pdo->prepare($sqlQuestion6);
        $stat['Фактор «Деньги и проблемы с ними»'] = $queryQuestion6->execute($data) ? $queryQuestion6->fetch($pdo::FETCH_ASSOC) : null;
        
        //7) Фактор «Потеря смысла жизни»
        $sqlQuestion7 = <<<sql
SELECT 
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Потеря смысла жизни"."Итог"' = 'Требуется формирование антисуицидальных факторов' THEN `mrr`.`respondent_id` END)) `Треб. форм. антисуицид. факторов`,
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Потеря смысла жизни"."Итог"' = 'Требуется особое внимание' THEN `mrr`.`respondent_id` END)) `Требуется особое внимание`,
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Потеря смысла жизни"."Итог"' = 'Норма' THEN `mrr`.`respondent_id` END)) `Норма`             
FROM `methodics_respondents_results` `mrr`
INNER JOIN `respondents_pupils` ON `respondents_pupils`.`id` = `mrr`.`respondent_id`
WHERE `mrr`.`results` IS NOT NULL
AND `mrr`.`results`->'$."methodic"' = 'suicide'
AND `respondents_pupils`.`active_tests_id` = ?
$schoolsClass
sql;
        $queryQuestion7 = $pdo->prepare($sqlQuestion7);
        $stat['Фактор «Потеря смысла жизни»'] = $queryQuestion7->execute($data) ? $queryQuestion7->fetch($pdo::FETCH_ASSOC) : null;
        
        //8) Фактор «Чувство неполноценности, ущербности, уродливости»
        $sqlQuestion8 = <<<sql
SELECT 
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Чувство неполноценности, ущербности, уродливости"."Итог"' = 'Требуется формирование антисуицидальных факторов' THEN `mrr`.`respondent_id` END)) `Треб. форм. антисуицид. факторов`,
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Чувство неполноценности, ущербности, уродливости"."Итог"' = 'Требуется особое внимание' THEN `mrr`.`respondent_id` END)) `Требуется особое внимание`,
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Чувство неполноценности, ущербности, уродливости"."Итог"' = 'Норма' THEN `mrr`.`respondent_id` END)) `Норма`             
FROM `methodics_respondents_results` `mrr`
INNER JOIN `respondents_pupils` ON `respondents_pupils`.`id` = `mrr`.`respondent_id`
WHERE `mrr`.`results` IS NOT NULL
AND `mrr`.`results`->'$."methodic"' = 'suicide'
AND `respondents_pupils`.`active_tests_id` = ?
$schoolsClass
sql;
        $queryQuestion8 = $pdo->prepare($sqlQuestion8);
        $stat['Фактор «Чувство неполноценности, ущербности, уродливости»'] = $queryQuestion8->execute($data) ? $queryQuestion8->fetch($pdo::FETCH_ASSOC) : null;
        
        //9) Фактор «Школьные проблемы, проблема выбора жизненного пути»
        $sqlQuestion9 = <<<sql
SELECT 
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Школьные проблемы, проблема выбора жизненного пути"."Итог"' = 'Требуется формирование антисуицидальных факторов' THEN `mrr`.`respondent_id` END)) `Треб. форм. антисуицид. факторов`,
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Школьные проблемы, проблема выбора жизненного пути"."Итог"' = 'Требуется особое внимание' THEN `mrr`.`respondent_id` END)) `Требуется особое внимание`,
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Школьные проблемы, проблема выбора жизненного пути"."Итог"' = 'Норма' THEN `mrr`.`respondent_id` END)) `Норма`             
FROM `methodics_respondents_results` `mrr`
INNER JOIN `respondents_pupils` ON `respondents_pupils`.`id` = `mrr`.`respondent_id`
WHERE `mrr`.`results` IS NOT NULL
AND `mrr`.`results`->'$."methodic"' = 'suicide'
AND `respondents_pupils`.`active_tests_id` = ?
$schoolsClass
sql;
        $queryQuestion9 = $pdo->prepare($sqlQuestion9);
        $stat['Фактор «Школьные проблемы, проблема выбора жизненного пути»'] = $queryQuestion9->execute($data) ? $queryQuestion9->fetch($pdo::FETCH_ASSOC) : null;
        
        //10) Фактор «Отношения с окружающими»
        $sqlQuestion10 = <<<sql
SELECT 
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Отношения с окружающими"."Итог"' = 'Требуется формирование антисуицидальных факторов' THEN `mrr`.`respondent_id` END)) `Треб. форм. антисуицид. факторов`,
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Отношения с окружающими"."Итог"' = 'Требуется особое внимание' THEN `mrr`.`respondent_id` END)) `Требуется особое внимание`,
COUNT(DISTINCT (CASE WHEN `mrr`.`results`->'$."tests"."suIntentions"."Отношения с окружающими"."Итог"' = 'Норма' THEN `mrr`.`respondent_id` END)) `Норма`             
FROM `methodics_respondents_results` `mrr`
INNER JOIN `respondents_pupils` ON `respondents_pupils`.`id` = `mrr`.`respondent_id`
WHERE `mrr`.`results` IS NOT NULL
AND `mrr`.`results`->'$."methodic"' = 'suicide'
AND `respondents_pupils`.`active_tests_id` = ?
$schoolsClass
sql;
        $queryQuestion10 = $pdo->prepare($sqlQuestion10);
        $stat['Фактор «Отношения с окружающими»'] = $queryQuestion10->execute($data) ? $queryQuestion10->fetch($pdo::FETCH_ASSOC) : null;

        return $stat;
    }
    
}
