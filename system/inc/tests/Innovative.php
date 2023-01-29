<?php

namespace tests;

if (!defined('PRFK_SITE_ROOT_DIR')) {
  die();
}

/**
 * Подсчет теста Интересы и склонности
 */
class Innovative extends Test {

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

        //2)подсчет баллов
        $result = [
            'Креативность' => 0,
            'Риск ради успеха' => 0,
            'Ориентация на будущее' => 0,
            'Общий индекс инновативности' => 0,
        ];
        foreach ($answersArr as $k => $a) {
            switch ($k) {
                case in_array($k, [4,5,8,10]):
                    $result['Креативность'] += $a[0];
                    break;
                case in_array($k, [3,6,11,12]):
                    $result['Риск ради успеха'] += $a[0];
                    break;
                case in_array($k, [1,2,7,9]):
                    $result['Ориентация на будущее'] += $a[0];
                    break;
            }  
        }
        $result['Креативность'] = round( $result['Креативность'] / 4, 1 );
        $result['Риск ради успеха'] = round( $result['Риск ради успеха'] / 4, 1 );
        $result['Ориентация на будущее'] = round( $result['Ориентация на будущее'] / 4, 1 );
        $result['Общий индекс инновативности'] = round( ($result['Креативность'] + $result['Риск ради успеха'] + $result['Ориентация на будущее']) / 3, 1 );
        return $result;
    }

}
