<?php
/**
 * Created by PhpStorm.
 * User: jonni
 * Date: 26.01.18
 * Time: 21:04
 */

namespace app\models;


use yii\db\ActiveRecord;

class Client extends ActiveRecord
{
    public function getUserAddress()
    {
        return $this->hasMany(ClientAddress::className(), ['client_id' => 'id']);
    }
}