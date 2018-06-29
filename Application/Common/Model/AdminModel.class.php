<?php
/**
 * Created by PhpStorm.
 * User: varro
 * Date: 2018/4/27
 * Time: 16:59
 */

namespace Common\Model;

use Think\Model;

class AdminModel extends Model
{
    protected $tableName = 'admin';
    protected $pk = 'admin_id';

    public function addAdmin($params)
    {
        return !$this->create($params) ?: $this->add($params);
    }

    public function updateAdmin($params, string $where)
    {
        return $this->where($where)->save($params);
    }
}