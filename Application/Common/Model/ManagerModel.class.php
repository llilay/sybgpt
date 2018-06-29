<?php
/**
 * Created by PhpStorm.
 * User: varro
 * Date: 2018/4/27
 * Time: 16:59
 */

namespace Common\Model;

use Think\Model;

class ManagerModel extends Model
{
    protected $tableName = 'manager';
    protected $pk = 'manager_id';

    public function addManager($params)
    {
        return !$this->create($params) ?: $this->add($params);
    }

    public function updateManager($params, string $where)
    {
        return $this->where($where)->save($params);
    }

}