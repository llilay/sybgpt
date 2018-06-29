<?php
/**
 * Created by PhpStorm.
 * User: varro
 * Date: 2018/4/14
 * Time: 13:48
 */

namespace Common\Model;

use Think\Model;

class ReportModel extends Model
{
    protected $tableName = 'report';
    protected $pk = 'report_id';

    public function getReport($reportId)
    {
        return $this->where("report_id = $reportId")->find();
    }

    public function isExistReport($studentId, $experimentId)
    {
        return $this->where(['student_id' => ['eq', $studentId], 'experiment_id' => ['eq', $experimentId]])->find();
    }

    public function addReport($params)
    {
        return !$this->create($params) ?: $this->add($params);
    }

    public function updateReport($params, string $where)
    {
        return $this->where($where)->save($params);
    }

    public function getReportList($userID)
    {
        $userInfo = D('Common/User')->getUserInfo(['user_id' => $userID]);
        if (empty($userInfo)) {
            return false;
        }

        $sql = "SELECT
                    a.report_id,
                    a.file_name,
                    a.file_url,
                    a.file_path,
                    a.create_time,
                    a.update_time,
                    a.status as report_status,
                    a.score,
                    a.is_pass,
                    b.experiment_id,
                    b.experiment_name,
                    c.course_id,
                    c.course_name,
                    e.teacher_id,
                    e.name as teacher_name,
                    f.student_id,
                    f.name as student_name,
                    g.file_url as examine_file_url,
                    g.file_path as examine_file_path
                FROM
                    report a
                    LEFT JOIN experiment b ON b.experiment_id = a.experiment_id
                    LEFT JOIN course c ON c.course_id = b.course_id
                    LEFT JOIN course_teacher d on d.course_id = c.course_id
                    LEFT JOIN teacher e on e.teacher_id = d.teacher_id
                    LEFT JOIN student f on f.student_id = a.student_id
                    LEFT JOIN report_examine g on g.report_id = a.report_id";

        if (isset($userInfo['student_id'])) {
            $sql .= " WHERE f.student_id = {$userInfo['student_id']}";
            return $this->query($sql);
        }

        if (isset($userInfo['teacher_id'])) {
            $sql .= " WHERE e.teacher_id = {$userInfo['teacher_id']}";
            return $this->query($sql);
        }

        if (isset($userInfo['manager_id'])) {
            return $this->query($sql);
        }

        if (isset($userInfo['admin_id'])) {
            return $this->query($sql);
        }

        return [];
    }
}
