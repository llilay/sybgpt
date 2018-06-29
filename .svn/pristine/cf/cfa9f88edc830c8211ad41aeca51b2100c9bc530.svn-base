<?php
/**
 * Created by PhpStorm.
 * User: varro
 * Date: 2018/4/14
 * Time: 10:49
 */

namespace Api\Controller;

use Common\Controller\CommonController;

class TemplateController extends CommonController
{
    /**
     * 获取实验报告模板列表
     * 任何角色都可以看到所有实验报告模板
     *
     * @return array
     * @author varro
     */
    public function getReportTemplateList() :array
    {
        try {
            $data = D('Common/ReportTemplate')->getReportTemplateList();
            $this->returnWebData['data'] = $data;
            $this->returnWebData['count'] = count($data);
        } catch (\Exception $e) {
            $this->returnError($e);
        } finally {
            $this->ajaxReturn($this->returnWebData);
        }
    }

    /**
     * 教师上传实验报告模板
     *
     * @return array
     * @author varro
     */
    public function addReportTemplate() :array
    {
        try {
            $files = $_FILES;
            $data = I('post.');

            $date = date('Ymd');
            $data['user_id'] = session('user_data')['user_id'] ?? $data['user_id'];

            if (empty($files)) {
                $this->Error('未上传文件');
            }

            if (!isset($data['user_id'])) {
                $this->Error('user_id为空');
            }

            if (!isset($data['experiment_id'])) {
                $this->Error('experiment_id为空');
            }

            if (!isset($data['template_name'])) {
                $this->Error('template_name为空');
            }

            if (count($files) != 1) {
                $this->Error('仅支持单文件上传');
            }

            $file = current($files);
            if ($file['error'] != 0) {
                $this->Error('文件上传失败');
            }

            if (!preg_match('/doc|docx/i', pathinfo($file['name'], PATHINFO_EXTENSION))) {
                $this->Error('仅支持上传doc/docx格式');
            }

            //获取教师信息
            $teacherInfo = D('Common/User')->getTeacher($data['user_id']);
            if (!$teacherInfo) {
                $this->Error('未知user_id => ' . $data['user_id']);
            }

            //是否有提交权限
            if ($teacherInfo['status'] != 1) {
                $$this->Error('该用户账号被冻结');
            }

            //获取实验信息
            $experimentInfo = D('Common/Experiment')->getExperiment($data['experiment_id']);
            if (!$experimentInfo) {
                $this->Error('未知experiment_id => ' . $data['experiment_id']);
            }

            //获取课程信息
            $courseInfo = D('Common/Course')->getCourseInfo($experimentInfo['course_id']);
            if (!$courseInfo) {
                $this->Error('未知course_id => ' .  $experimentInfo['course_id']);
            }

            //检测实验报告模板是否已存在
            $reportTempInfo = D('Common/ReportTemplate')->getReportTemplate($data['experiment_id']);
            if ($reportTempInfo && $reportTempInfo['status'] == 1 && file_exists($reportTempInfo['file_path'])) {
                $this->Error('文件已存在, 请勿重复上传');
            }

            //生成实验报告保存目录, 文件名, 文件后缀
            $saveDir = "Report/Template/{$courseInfo['course_id']}/{$experimentInfo['experiment_id']}/";
            $saveName = "$date-CID-{$data['experiment_id']}-EID-{$experimentInfo['experiment_id']}";
            $saveExt = pathinfo($file['name'], PATHINFO_EXTENSION);

            //文件的磁盘路径
            $savePath = C('RESOURCES_UPLOAD_PATH') . $saveDir . $saveName . '.' . $saveExt;
            //文件的远程路径
            $saveUrl =  C('RESOURCES_URL') . $saveDir . $saveName . '.' . $saveExt;

            //若保存目录不存在, 递归创建目录
            if (!is_dir(C('RESOURCES_UPLOAD_PATH') . $saveDir)) {
                mkdir(C('RESOURCES_UPLOAD_PATH') . $saveDir, 0755, true);
            }

            if (!move_uploaded_file($file['tmp_name'], $savePath)){
                $this->Error('文件移动失败');
            }

            //将模板信息保存到report_template表
            $insertParams = [
                'experiment_id' => $data['experiment_id'],
                'teacher_id' => $teacherInfo['teacher_id'],
                'template_name' => $data['template_name'],
                'file_name' => $saveName . '.' . $saveExt,
                'file_type' => $saveExt,
                'file_size' => $file['size'],
                'file_url' => $saveUrl,
                'file_path' => $savePath,
                'create_time' => time(),
                'update_time' => time(),
                'status' => 1
            ];
            if (!D('Common/ReportTemplate')->addReportTemplate($insertParams)) {
                $this->Error('文件保存失败');
            }

            $this->returnData['value'] = $saveUrl;
        } catch (\Exception $e) {
            $this->returnError($e);
        } finally {
            $this->ajaxReturn($this->returnData);
        }
    }
}
