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

    public static function findOneView($id)
    {
        $client_obj = parent::findOne($id);
        $born_date = new \DateTime($client_obj->born_date);
        $phone = $client_obj->phone;
        $view['born_date'] = $born_date->format('d.m.Y');
        $view['sex'] = $client_obj->sex == 0 ? 'Ж' : 'М';
        $view['id'] = $client_obj->id;
        $view['first_name'] = $client_obj->first_name;
        $view['last_name'] = $client_obj->last_name;
        $view['phone'] = '+7(' . substr($phone, 0, 3) . ')' . substr($phone, 3, 2) . '-' . substr($phone,5);
        $view['db_phone'] = $phone;
        $view['db_sex'] = $client_obj->sex;
        return $view;
    }
}