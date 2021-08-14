<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 20.08.16
 * Time: 09:48
 */

namespace app\components;


abstract class ActiveRecord extends \yii\db\ActiveRecord
{

    abstract public function hasDefaultImage(): bool;

    abstract public function getDefaultImage(string $alias = ''): string;

}
