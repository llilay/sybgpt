<?php
/**
 * Created by PhpStorm.
 * User: varro
 * Date: 2018/4/24
 * Time: 13:48
 */

namespace Common\Model;

use Think\Model;
use Ku;

class ExamineHistoryModel extends Model
{
    protected $tableName = 'examine_history';
    protected $pk = 'history_id';

    public function getExamineHistory($reportId = 0)
    {
        return $this->where("report_id = $reportId")->find();
    }

    public function addExamineHistory($params)
    {
        return !$this->create($params) ?: $this->add($params);
    }

    public function updateExamineHistory($params)
    {
        return !$this->create($params) ?: $this->add($params);
    }

    public function getExamineHistoryList(array $data = [])
    {
        $sql = "
            SELECT
                a.examine_time,
                if(d.name IS NULL,'系统管理员',d.name) as name,
                c.experiment_name
            FROM
                examine_history a
                LEFT JOIN report b ON a.report_id = b.report_id
                LEFT JOIN experiment c ON c.experiment_id = b.experiment_id
                LEFT JOIN teacher d ON d.user_id = a.user_id";

        $whereArray = [];
        if (!empty($data['student_id'])) {
            $whereArray[] = "b.student_id = {$data['student_id']}";
        }

        if (!empty($data['teacher_id'])) {
            $whereArray[] = "a.user_id = {$data['teacher_id']}";
        }

        $where = implode(' and ', $whereArray);
        $sql .= $where ? " where {$where}" : "";

        if (!empty($data['page']) && !empty($data['limit'])) {
            $sql .= Ku\Utils::setLimit($data['page'], $data['limit']);
        }

        return array('count' => $this->count(), 'data' => $this->query($sql));
    }
}
