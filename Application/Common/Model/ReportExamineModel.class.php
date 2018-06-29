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

class ReportExamineModel extends Model
{
    protected $tableName = 'report_examine';
    protected $pk = 'report_examine_id';

    public function getReportExamine($reportId)
    {
        return $this->where("report_id = $reportId")->find();
    }

    public function addReportExamine($params)
    {
        return !$this->create($params) ?: $this->add($params);
    }

    public function updateReportExamine($params, string $where)
    {
        return $this->where($where)->save($params);
    }

    public function getReportExamineList(array $data = [])
    {
        $sql = "
            SELECT
                a.report_examine_id,
                a.report_id,
                a.teacher_id,
                a.experiment_id,
                a.file_name,
                a.file_url as examine_file_url,
                a.file_path as examine_file_path,
                FROM_UNIXTIME( a.create_time ) AS report_examine_create_time,
                FROM_UNIXTIME( a.update_time ) AS report_examine_update_time,
                a.STATUS AS approval_status,
                IF( a.STATUS = 1, '审核已通过', '审核未通过' ) AS approval_status_name,
                b.file_url as report_file_url,
                b.file_path as report_file_path,
                b.STATUS AS examine_status,
                IF( b.STATUS = 1, '已批改', '未批改' ) AS examine_status_name,
                b.score,
                b.is_pass,
                IF( b.is_pass = 1, '及格', '不及格' ) AS is_pass_name,
                FROM_UNIXTIME( b.create_time ) AS report_create_time,
                c.experiment_name,
                d.course_id,
                d.course_name,
                f.NAME AS teacher_name,
                g.student_id,
                g.NAME AS student_name
                
            FROM
                report_examine a
                LEFT JOIN report b ON a.report_id = b.report_id
                LEFT JOIN experiment c ON c.experiment_id = b.experiment_id
                LEFT JOIN course d ON d.course_id = c.course_id
                LEFT JOIN teacher f ON f.teacher_id = a.teacher_id
                LEFT JOIN student g ON g.student_id = b.student_id;
                ";


        $whereArray = [];

        if (isset($data['examine_status'])) {
            $whereArray[] = "b.status = {$data['examine_status']}";
        }

        if (!empty($data['approval_status'])) {
            $whereArray[] = "a.status = {$data['approval_status']}";
        }

        if (!empty($data['teacher_name'])) {
            $whereArray[] = "f.name like '%{$data['teacher_name']}%'";
        }

        if (!empty($data['student_name'])) {
            $whereArray[] = "g.name like '%{$data['student_name']}%'";
        }

        $where = implode(' and ', $whereArray);
        $sql .= $where ? " where {$where}" : "";

        if (!empty($data['page']) && !empty($data['limit'])) {
            $sql .= Ku\Utils::setLimit($data['page']);
        }

        return $this->query($sql);
    }
}
