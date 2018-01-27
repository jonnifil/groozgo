<?php
/**
 * Created by PhpStorm.
 * User: jonni
 * Date: 26.01.18
 * Time: 21:56
 */

namespace app\models;


use yii\db\ActiveRecord;

class ClientAddress extends ActiveRecord
{

    public function getUser()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }
}