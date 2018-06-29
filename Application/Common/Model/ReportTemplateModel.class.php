<?php
/**
 * Created by PhpStorm.
 * User: varro
 * Date: 2018/4/14
 * Time: 13:48
 */

namespace Common\Model;

use Think\Model;

class ReportTemplateModel extends Model
{
    protected $tableName = 'report_template';
    protected $pk = 'temp_id';

    public function getReportTemplate($experimentId)
    {
        return $this->where("experiment_id = $experimentId")->find();
    }

    public function addReportTemplate($params)
    {
        return !$this->create($params) ?: $this->add($params);
    }

    public function getReportTemplateList(array $data = [])
    {
        $sql = "SELECT
                    a.temp_id,
                    a.experiment_id,
                    a.teacher_id,
                    a.template_name,
                    a.file_url,
                    a.create_time,
                    a.update_time,
                    b.experiment_name,
                    c.course_id,
                    c.course_name,
                    e.name as teacher_name
                FROM
                    report_template a
                    LEFT JOIN experiment b ON b.experiment_id = a.experiment_id
                    LEFT JOIN course c ON c.course_id = b.course_id
                    LEFT JOIN course_teacher d on d.course_id = c.course_id
                    LEFT JOIN teacher e on e.teacher_id = d.teacher_id
                WHERE
                    a.status = 1";

        return $this->query($sql);
    }
}
