<?php
/**
 * Created by PhpStorm.
 * User: varro
 * Date: 2018/4/14
 * Time: 10:49
 */

namespace Api\Controller;

use Common\Controller\CommonController;

class ReportController extends CommonController
{
    /**
     * 获取学生上传的实验报告列表
     *「学生」只可看到学生自己上传的实验报告
     *「教师」可以看到教师自己教授的课程下的所有实验报告,(例如物理老师可以看到物理这门课下的实验报告, 但不可看到化学下的实验报告)
     *
     * @author varro
     */
    public function getReportList()
    {
        try {
            $data = I('post.');
            $data['user_id'] = session('user_data')['user_id'] ?? $data['user_id'];

            if (!$data['user_id']) {
                $this->Error('user_id为空');
            }

            $reportList = D('Common/Report')->getReportList($data['user_id']);
            $this->returnWebData['data'] = $reportList;
            $this->returnWebData['count'] = count($reportList);
        } catch (\Exception $e) {
            $this->returnError($e);
        } finally {
            $this->ajaxReturn($this->returnWebData);
        }
    }

    /**
     * 学生上传实验报告
     *
     * @author varro
     */
    public function addReport()
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

            //获取学生信息
            $studentInfo = D('Common/User')->getStudent($data['user_id']);
            if (!$studentInfo) {
                $this->Error('未知user_id => ' . $data['user_id']);
            }

            //是否有提交权限
            if ($studentInfo['status'] != 1) {
                $$this->Error('该用户账号被冻结');
            }

            //获取实验信息
            $experimentInfo = D('Common/Experiment')->getExperiment($data['experiment_id']);
            if (!$experimentInfo) {
                $this->Error('未知experiment_id => ' . $data['experiment_id']);
            }

            //检查实验报告是否已提交
            $reportStudentInfo = D('Common/Report')->isExistReport($studentInfo['student_id'], $data['experiment_id']);
            if ($reportStudentInfo && file_exists($reportStudentInfo['file_path'])) {
                $this->Error('文件已存在, 请勿重复上传');
            }

            //生成实验报告保存目录, 文件名, 文件后缀
            $saveDir = "Report/Original/ExperimentId-{$data['experiment_id']}/";
            $saveName = "$date-EID-{$data['experiment_id']}-SID-{$data['user_id']}";
            $saveExt = pathinfo($file['name'], PATHINFO_EXTENSION);

            //文件的磁盘路径
            $savePath = C('RESOURCES_UPLOAD_PATH') . $saveDir . $saveName . '.' . $saveExt;
            //文件的远程路径
            $saveUrl =  C('RESOURCES_URl') . $saveDir . $saveName . '.' . $saveExt;

            //若保存目录不存在, 递归创建目录
            if (!is_dir(C('RESOURCES_UPLOAD_PATH') . $saveDir)) {
                mkdir(C('RESOURCES_UPLOAD_PATH') . $saveDir, 0755, true);
            }

            if (!move_uploaded_file($file['tmp_name'], $savePath)){
                $this->Error('文件移动失败');
            }

            $insertParams = [
                'student_id' => $studentInfo['student_id'],
                'experiment_id' => $data['experiment_id'],
                'file_name' => $saveName . '.' . $saveExt,
                'file_type' => $saveExt,
                'file_size' => $file['size'],
                'file_url' => $saveUrl,
                'file_path' => $savePath,
                'create_time' => time(),
                'update_time' => time(),
                'status' => 0
            ];
            if (!D('Common/Report')->addReport($insertParams)) {
                $this->Error('文件保存失败');
            }

            $this->returnData['value'] = $saveUrl;
        } catch (\Exception $e) {
            $this->returnError($e);
        } finally {
            $this->ajaxReturn($this->returnData);
        }
    }

    /**
     * 查看/批改实验报告
     *
     * @throws \java_IllegalArgumentException
     * @throws \java_IllegalStateException
     * @author varro
     */
    public function checkReport()
    {
        $data = I('get.');
        $data['user_id'] = session('user_data')['user_id'] ?? $data['user_id'];

        if (empty($data['report_id'])) {
            exit($this->msgFormat('report_id为空'));
        }

        if (empty($data['user_id'])) {
            exit($this->msgFormat('user_id为空'));
        }

        if (!in_array($data['operation'], [1, 2])) {
            exit($this->msgFormat('无效操作'));
        }

        $userInfo = D('Common/User')->getUserInfo(['user_id' => $data['user_id']]);
        if (empty($userInfo)) {
            exit($this->msgFormat('该用户不存在, user_id => ' . $data['user_id']));
        }

        $reportInfo = D('Common/Report')->getReport($data['report_id']);
        if (empty($reportInfo)) {
            exit($this->msgFormat('该实验报告不存在, report_id => ' . $data['report_id']));
        }

        //operation => 「操作类型」: 1以只读方式打开文件, 2读写方式打开
        if ($data['operation'] == 1) {
            $this->examineReport($reportInfo['report_id'], $reportInfo['file_url'], $reportInfo['file_path'], $userInfo['teacher_id'], false);
        }

        if ($data['operation'] == 2) {
            //获取实验报告批改信息
            $reportExamineInfo = D('Common/ReportExamine')->getReportExamine($data['report_id']);
            $filePath = $reportExamineInfo ? $reportExamineInfo['file_path'] : $reportInfo['file_path'];
            $fileUrl = $reportExamineInfo ? $reportExamineInfo['file_url'] : $reportInfo['file_url'];

            $this->examineReport($reportInfo['report_id'], $fileUrl, $filePath, $userInfo['teacher_id'], true);
        }
    }

    /**
     * 查看/批改实验报告(可通过web服务器访问, 也可被其他方法调用)
     *
     * @param int $reportID     实验报告ID
     * @param string $fileUrl   文件远程地址
     * @param string $filePath  文件本地地址
     * @param int $teacherId    教师ID
     * @param bool $save        是否以只读方式打开文件
     * @throws \java_IllegalArgumentException
     * @throws \java_IllegalStateException
     * @author varro
     */
    public function examineReport($reportID = null, $fileUrl = null, $filePath = null, $teacherId = 0, $save = false)
    {
        $data = I('request.');
        $reportID = $data['report_id'] ?? $reportID;
        $fileUrl = $data['file_url'] ?? $fileUrl;
        $filePath = $data['file_path'] ?? $filePath;
        $save = $data['save'] ?: $save;

        if (!is_file($filePath)) {
            exit($this->msgFormat('该文件不存在'));
        }

        //获取本机IP
        $ip = 'http://' . GetHostByName($_SERVER['SERVER_NAME']);

        //连接java环境
        require_once("$ip:8080/JavaBridge/java/Java.inc");
        $PageOfficeCtrl = new \Java("com.zhuozhengsoft.pageoffice.PageOfficeCtrlPHP");

        //设置服务器页面
        $PageOfficeCtrl->setServerPage("$ip:8080/JavaBridge/poserver.zz");

        //涉及到中文必须设置中文编码
        java_set_file_encoding("GBK");

        //文档保存时回调此方法
        if ($save == true) {
            $PageOfficeCtrl->setSaveFilePage("$ip/api/report/saveExamineReport?report_id={$reportID}&teacher_id={$teacherId}");
        }

        //兼容谷歌浏览器
        $PageOfficeCtrl->UserAgent = $_SERVER['HTTP_USER_AGENT'];

        //打开word文档
        $OpenMode = new \Java("com.zhuozhengsoft.pageoffice.OpenModeType");
        $PageOfficeCtrl->webOpen($ip . $fileUrl, $save ? $OpenMode->docNormalEdit : $OpenMode->docReadOnly, "user");
        //生成word编辑窗口
        echo $PageOfficeCtrl->getDocumentView("PageOfficeCtrl1");
    }

    /**
     * 保存批改后的实验报告
     *
     * @throws \java_IllegalArgumentException
     * @throws \java_IllegalStateException
     * @author varo
     */
    public function saveExamineReport()
    {
        $data = I('request.');
        $date = date('Ymd');

        //获取本机IP
        $ip = 'http://' . GetHostByName($_SERVER['SERVER_NAME']);

        //连接java环境
        require_once("$ip:8080/JavaBridge/java/Java.inc");

        //获取文件对象
        $fs = new \Java("com.zhuozhengsoft.pageoffice.FileSaverPHP");
        $fs->load(file_get_contents("php://input"));

        //获取用户信息
        $userInfo = D('Common/User')->getUserByTeacher(['teacher_id' => $data['teacher_id']]);
        if (!$userInfo) {
            exit($this->msgFormat('该用户不存在, teacher_id => ' . $data['teacher_id']));
        }

        //获取实验报告信息
        $reportInfo = D('Common/Report')->getReport($data['report_id']);
        if (empty($reportInfo)) {
            exit($this->msgFormat('该实验报告不存在, report_id => ' . $data['report_id']));
        }

        if ($reportInfo['status'] != 0) {
            exit($this->msgFormat('该实验报告已提交批改, report_id => ' . $data['report_id']));
        }

        //获取实验报告批改信息
        $reportExamineInfo = D('Common/ReportExamine')->getReportExamine($data['report_id']);

        //检查实验报告此前是否有批改记录
        if (empty($reportExamineInfo)) { //若此前未曾批改, 则创建批改文件与批改记录
            $saveDir = "Report/Examine/ExperimentId-{$reportInfo['experiment_id']}/";
            $saveName = "$date-RID-{$data['report_id']}-TID-{$data['teacher_id']}";
            $saveExt = pathinfo($fs->getFileName(), PATHINFO_EXTENSION);

            //文件的磁盘路径
            $savePath = C('RESOURCES_UPLOAD_PATH') . $saveDir . $saveName . '.' . $saveExt;
            //文件的远程路径
            $saveUrl =  C('RESOURCES_URl') . $saveDir . $saveName . '.' . $saveExt;

            $insertParams = [
                'teacher_id' => $data['teacher_id'],
                'report_id' => $reportInfo['report_id'],
                'experiment_id' => $reportInfo['experiment_id'],
                'file_name' => $saveName . '.' . $saveExt,
                'file_type' => $saveExt,
                'file_size' => rand(1000, 100000),
                'file_url' => $saveUrl,
                'file_path' => $savePath,
                'create_time' => time(),
                'update_time' => time(),
                'status' => 0
            ];
            if (!D('Common/ReportExamine')->addReportExamine($insertParams)) {
                exit($this->msgFormat('添加实验报告批改文件失败, report_id => ' . $data['report_id']));
            }

        } else { //若批改过, 则继续上次批改的进度继续批改, 并覆盖此前的批改文件, 更新批改记录

            $updateParams = [
                'teacher_id' => $data['teacher_id'],
                'file_size' => rand(1000, 100000),
                'update_time' => time()
            ];
            if (!D('Common/ReportExamine')->updateReportExamine($updateParams, "report_examine_id = {$reportExamineInfo['report_examine_id']}")) {
                exit($this->msgFormat('更新实验报告批改文件失败, report_id => ' . $data['report_id']));
            }

            $saveUrl = $reportExamineInfo['file_url'];
            $savePath = $reportExamineInfo['file_path'];
        }

        //添加批改日志
        $insertParams2 = [
            'report_id' => $data['report_id'],
            'examine_name' => date('YmdHis') . "-RID{$data['report_id']}-TID-{$data['teacher_id']}",
            'user_id' => $userInfo[0]['user_id'], //教师id
            'examine_time' => time(),
            'type' => 1
        ];
        if (!D('Common/ExamineHistory')->addExamineHistory($insertParams2)) {
            exit($this->msgFormat('添加审批记录失败'));
        }

        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . pathinfo($saveUrl, PATHINFO_DIRNAME))) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . pathinfo($saveUrl, PATHINFO_DIRNAME), 777, true);
        }

        $fs->saveToFile($savePath); //保存文件
        echo $fs->Close();
    }

    /**
     * 教师提交批改后的实验报告
     *
     * @author varro
     */
    public function confirmExamineReport()
    {
        try {
            $data = I('request.');
            $data['user_id'] = session('user_data')['user_id'] ?? $data['user_id'];

            if (!isset($data['user_id'])) {
                $this->Error('user_id为空');
            }

            if (!isset($data['report_id'])) {
                $this->Error('report_id为空');
            }

            if (!isset($data['score'])) {
                $this->Error('score为空');
            }

            $data['score'] = floatval($data['score']);
            if ($data['score'] <= 0 || $data['score'] > 100) {
                $this->Error('score小于0或大于100');
            }

            $userInfo = D('Common/User')->getUserInfo(['user_id' => $data['user_id']]);
            if (!$userInfo) {
                $this->Error('该用户不存在, user_id => ' . $data['user_id']);
            }

            if (!in_array($userInfo['role_id'], [1, 5])) {
                $this->Error('无权限执行该操作, role_id => ' . $userInfo['role_id']);
            }

            $reportInfo = D('Common/Report')->getReport($data['report_id']);
            if (!$reportInfo) {
                $this->Error('该实验报告不存在, report_id => ' . $data['report_id']);
            }

            if ($reportInfo['status'] != 0) {
                $this->Error('该实验报告已批改, report_id => ' . $data['report_id']);
            }

            $reportExamineInfo = D('Common/ReportExamine')->getReportExamine($data['report_id']);
            if (!$reportExamineInfo) {
                $this->Error('该实验报告不存在批改记录, report_id => ' . $data['report_id']);
            }

            if ($reportExamineInfo['status'] != 0) {
                $this->Error('该批改文件审核已通过, report_examine_id => ' . $reportExamineInfo['report_examine_id']);
            }

            if (!is_file($reportExamineInfo['file_path'])) {
                $this->Error('该批改文件不存在, file_path => ' . $reportExamineInfo['file_path']);
            }

            $updateParams = [
                'score' => $data['score'],
                'is_pass' => $data['score'] >= 60 ? 1 : 0,
                'last_time' => date('Y-m-d H:i:s'),
                'status' => 1
            ];

            if (!D('Common/Report')->updateReport($updateParams, "report_id = {$data['report_id']}")) {
                $this->Error('提交批改数据失败, report_examine_id => ' . $reportExamineInfo['report_examine_id']);
            }

            $this->returnData['msg'] = '批改成功';
        } catch (\Exception $e) {
            $this->returnError($e);
        } finally {
            $this->ajaxReturn($this->returnData);
        }
    }

    /**
     * 获取教师批改后的实验报告列表
     *
     * @author varro
     */
    public function getExamineReportList()
    {
        try {
            $data = I('request.');
            $reportList = D('Common/ReportExamine')->getReportExamineList($data);

            foreach ($reportList as $k => $v) {
                $reportList[$k]['view_report_url'] = "?report_id={$v['report_id']}&file_url={$v['report_file_url']}&file_path={$v['report_file_path']}', 'width=1050px;height=900px;'";
                $reportList[$k]['view_examine_url'] = "?report_id={$v['report_id']}&file_url={$v['report_file_url']}&file_path={$v['report_file_path']}', 'width=1050px;height=900px;'";
            }

            $this->returnWebData['data'] = $reportList;
            $this->returnWebData['count'] = count($reportList);
        } catch (\Exception $e) {
            $this->returnError($e);
        } finally {
            $this->ajaxReturn($this->returnWebData);
        }
    }

    /**
     * 管理员审核教师批改后的实验报告
     *
     * @author varro
     */
    public function approvalExamineReport()
    {
        try {
            $data = I('request.');
            $date = date('Ymd');
            $data['user_id'] = session('user_data')['user_id'] ?? $data['user_id'];

            if (!isset($data['user_id'])) {
                $this->Error('user_id为空');
            }

            if (!isset($data['report_id'])) {
                $this->Error('report_id为空');
            }

            if (!isset($data['status'])) {
                $this->Error('status为空');
            }

            if (!in_array($data['status'], [0, 1])) {
                $this->Error('status参数错误');
            }

            $userInfo = D('Common/User')->getUserInfo(['user_id' => $data['user_id']]);
            if (!$userInfo) {
                $this->Error('该用户不存在, user_id => ' . $data['user_id']);
            }

            if (!in_array($userInfo['role_id'], [1, 2])) {
                $this->Error('无权限执行该操作, role_id => ' . $userInfo['role_id']);
            }

            $reportInfo = D('Common/Report')->getReport($data['report_id']);
            if (!$reportInfo) {
                $this->Error('该实验报告不存在, report_id => ' . $data['report_id']);
            }

            if ($reportInfo['status'] != 1) {
                $this->Error('该实验报告未批改, report_id => ' . $data['report_id']);
            }

            $reportExamineInfo = D('Common/ReportExamine')->getReportExamine($data['report_id']);
            if (!$reportExamineInfo) {
                $this->Error('该实验报告不存在批改记录, report_id => ' . $data['report_id']);
            }

            if ($reportExamineInfo['status'] != 0) {
                $this->Error('该批改文件审核已通过, report_examine_id => ' . $reportExamineInfo['report_examine_id']);
            }

            if (!is_file($reportExamineInfo['file_path'])) {
                $this->Error('该批改文件不存在, file_path => ' . $reportExamineInfo['file_path']);
            }

            if ($data['status'] == 0) {
                if (!D('Common/Report')->updateReport(['status' => 0, 'score' => 0, 'is_pass' => 0], "report_id = {$data['report_id']}")) {
                    $this->Error('审核失败, report_id => ' . $data['report_id']);
                }
            }

            if ($data['status'] == 1) {
                if (!D('Common/ReportExamine')->updateReportExamine(['status' => 1], "report_id = {$data['report_id']}")) {
                    $this->Error('审核失败, report_id => ' . $data['report_id']);
                }
            }

            //添加审核日志
            $insertParams = [
                'report_id' => $date['report_id'],
                'examine_name' => date('YmdHis') . "-RID{$data['report_id']}-UID-{$userInfo['user_id']}",
                'user_id' => $data['user_id'],
                'examine_time' => time(),
                'type' => 2
            ];
            if (!D('Common/ExamineHistory')->addExamineHistory($insertParams)) {
                $this->Error('添加审批记录失败, report_examine_id => ' . $reportExamineInfo['report_examine_id']);
            }

            $this->returnData['msg'] = '审核成功';
        } catch (\Exception $e) {
            $this->returnError($e);
        } finally {
            $this->ajaxReturn($this->returnData);
        }
    }

    /**
     * 获取实验报告批改历史列表
     *
     * @author varro
     */
    public function getReportExamineHistoryList()
    {
        try {
            $data = I('request.');
            $data['user_id'] = session('user_data')['user_id'] ?? $data['user_id'];

            if (!isset($data['user_id'])) {
                $this->Error('user_id为空');
            }

            $userInfo = D('Common/User')->getUserInfo(['user_id' => $data['user_id']]);
            if (!$userInfo) {
                $this->Error('该用户不存在, user_id => ' . $data['user_id']);
            }

            if (!empty($userInfo['student_id'])) {
                $data['student_id'] = $userInfo['student_id'];
            }

            if (!empty($userInfo['teacher_id'])) {
                $data['teacher_id'] = $userInfo['teacher_id'];
            }

            $historyList =  D('Common/ExamineHistory')->getExamineHistoryList($data);
            $this->returnWebData['data'] = $historyList['data'];
            $this->returnWebData['count'] = $historyList['count'];
        } catch (\Exception $e) {
            $this->returnError($e);
        } finally {
            $this->ajaxReturn($this->returnWebData);
        }

    }

    /**
     * 定义消息格式
     *
     * @param  string $msg 错误信息
     * @return string
     * @author varro
     */
    protected function msgFormat(string $msg)
    {
        return "<p><h1>$msg</h1></p>";
    }
}
