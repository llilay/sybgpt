<?php
/**
 * Created by PhpStorm.
 * User: varro
 * Date: 2018/4/27
 * Time: 16:59
 */

namespace Common\Model;

use Think\Model;

class StudentModel extends Model
{
    protected $tableName = 'student';
    protected $pk = 'student_id';

    public function addStudent($params)
    {
        return !$this->create($params) ?: $this->add($params);
    }

    public function updateStudent($params, string $where)
    {
        return $this->where($where)->save($params);
    }
}
