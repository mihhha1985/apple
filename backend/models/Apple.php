<?php

namespace backend\models;

use Yii;

/**
 * ContactForm is the model behind the contact form.
 */
class Apple
{
    const HANGING_ON_A_TREE = 1;
    const FELL_TO_THE_GROUND = 2;
    const ROTTEN_APPLE = 3;

    public $color;
    public $create;
    public $update;
    public $status;
    public $size;

    public function __construct($color)
    {
        $this->color = $color;
        $this->create = time();
        $this->update = 0;
        $this->status = self::HANGING_ON_A_TREE;
        $this->size = 100;
    }

    public static function getAppleColor($value)
    {
        if($value % 2 == 0){
            $color = 'Желтый';
        }elseif($value % 3 == 0){
            $color = 'Красный';
        }else{
            $color = 'Зелёный';
        }

        return $color;
    }

    public static function getCreateDate($value)
    {
        return date("d.m.Y H:i", $value);
    }

    public static function getUpdateDate($value)
    {
        if($value == false){
            return  0;
        }else{
            return date("d.m.Y H:i", $value);
        }
    }

    public static function getAppleStatus($value)
    {
        switch($value){
            case self::HANGING_ON_A_TREE:
                $status = 'Висит на дереве';
                break;
            case self::FELL_TO_THE_GROUND:
                $status = 'Упало на землю';
                break;
            case self::ROTTEN_APPLE:
                $status = 'Гнилое яблоко'; 
                break;       
        }

        return $status;
    }

    public static function setTimeDecay()
    {
        $now = time();
        $apples = Yii::$app->db->createCommand("SELECT * FROM apple")->queryAll();
        foreach($apples as $apple){
            if($apple['updated_at'] == 0) continue;
            $date = $now - $apple['updated_at'];
            $day = $date / 86400;
            
            if($day > 5){
                Yii::$app->db->createCommand('UPDATE apple SET status = 3 WHERE id= ' . $apple['id'])->execute();
            }
        }
    }
}    