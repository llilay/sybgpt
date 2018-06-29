<?php
/**
 * Created by PhpStorm.
 * User: varro
 * Date: 2018/4/27
 * Time: 16:59
 */

namespace Common\Model;

use Think\Model;

class TeacherModel extends Model
{
    protected $tableName = 'teacher';
    protected $pk = 'teacher_id';

    public function addTeacher($params)
    {
        return !$this->create($params) ?: $this->add($params);
    }

    public function updateTeacher($params, string $where)
    {
        return $this->where($where)->save($params);
    }
}
