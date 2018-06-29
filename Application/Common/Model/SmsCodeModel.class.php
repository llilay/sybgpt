<?php
/**
 * Created by PhpStorm.
 * User: varro
 * Date: 2018/4/27
 * Time: 16:59
 */

namespace Common\Model;

use Think\Model;

class SmsCodeModel extends Model
{
    protected $tableName = 'sms_code';
    protected $pk = 'id';

    public function addSmsCode($params)
    {
        return !$this->create($params) ?: $this->add($params);
    }

    public function getSmsCode($phone)
    {
        return $this->where("phone = '$phone'")->find();
    }

    public function updateSmsCode($params, string $where)
    {
        return $this->where($where)->save($params);
    }
}
