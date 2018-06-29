<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2018/4/14
 * Time: 13:48
 */

namespace Common\Model;

use Think\Model;
use Ku;

class GradeModel extends Model
{
    protected $tableName = 'grade';
    protected $pk = 'grade_id';

    /**
     * 获取年级列表
     *
     * @param array $data
     * @return mixed
     * @author varro
     */
    public function getGradeList(array $data = [])
    {
        $whereArray = [];

        if (!empty($data['status'])) {
            $whereArray[] = "status = {$data['status']}";
        } else {
            $whereArray[] = "status = 1";
        }

        if (!empty($data['grade_name'])) {
            $whereArray[] = "grade_name = {$data['grade_name']}";
        }

        $sql = "SELECT * FROM grade";

        $where = implode(' and ', $whereArray);
        $sql .= $where ? " where {$where}" : "";

        if (!empty($data['page']) && !empty($data['limit'])) {
            $sql .= Ku\Utils::setLimit($data['page']);
        }

        return $this->query($sql);
    }

    /**
     * 添加年级
     *
     * @param $params
     * @return mixed
     * @author varro
     */
    public function addGrade($params)
    {
        return !$this->create($params) ?: $this->add($params);
    }

    /**
     * 获取所有菜单的名称
     */
    public function getAllGradeName()
    {
        return $this->where('status = 1')->field('grade_id, grade_name')->select();
    }

}