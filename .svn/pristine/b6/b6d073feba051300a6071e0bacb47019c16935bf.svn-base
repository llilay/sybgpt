<?php
/**
 * Created by PhpStorm.
 * User: varro
 * Date: 2018/4/19
 * Time: 14:19
 */

namespace Api\Controller;

use Common\Controller\CommonController;
use Ku;

class UserController extends CommonController
{
    /**
     * 用户登录
     *
     * @author varro
     */
    public function login()
    {
        try {
            $data = I('post.');

            if (empty($data['account'])) {
                $this->Error('account为空');
            }

            if (empty($data['pwd'])) {
                $this->Error('pwd为空');
            }

            $userInfo = D('Common/User')->getUserInfo(['account' => $data['account']]);
            if (empty($userInfo)) {
                $this->Error('用户名或密码错误#1');
            }

            $pwd = Ku\Utils::getUserPwd($data['pwd'], $userInfo['salt']);
            if ($pwd != $userInfo['pwd']) {
                $this->Error('用户名或密码错误#2');
            }

            if ($userInfo['status'] != 1) {
                $this->Error('该账号已锁定');
            }

            $userCompetence = $this->getCompetence($userInfo, true);
            if (!$userCompetence) {
                $this->ajaxReturn($this->returnData);
            }

            unset($userInfo['pwd']);
            unset($userInfo['salt']);

            session('user_data', $userInfo);
            session('user_competence', $userCompetence['competence']);
            session('menu', $userCompetence['menu']);

            $this->returnData['msg'] = '登录成功';
            $this->returnData['value'] = $userInfo;
        } catch (\Exception $e) {
            $this->returnError($e);
        } finally {
            $this->ajaxReturn($this->returnData);
        }
    }

    /**
     * 退出登录
     *
     * @author varro
     */
    public function logout()
    {
        session(null);
        $this->redirect('/Home/User/Login');
        exit();
    }

    /**
     * 获取角色权限
     *
     * @param array $data
     * @param bool $showMenu
     * @return array|bool
     * @author varro
     */
    protected function getCompetence(array $data, bool $showMenu = false)
    {
        try {
            $powerIdStr = $menuIdStr = $secondaryMenuIdStr = $tertiaryMenuIdStr ='';
            $competence = [];

            if (empty($data['role_id'])) {
                $this->Error('role_id为空');
            }

            //获取角色信息
            $role = D('Common/Role') -> getRoleList(['role_id_str' => trim($data['role_id'], ',')])['data'];
            $role ?: $this->Error('role不存在, role_id_str => ' . $data['role_id']);
            foreach ($role as $k => $v) {
                $powerIdStr .= $v['power_id'] . ',';
            }

            //获取权限信息
            $power = D('Common/Power')->getPowerList(['power_id_str' => trim($powerIdStr, ',')])['data'];
            $power ?: $this->Error('power不存在, power_id_str => ' . $powerIdStr);
            foreach ($power as $k => $v) {
                $menuIdStr .= $v['menu_id'] . ',';
            }

            //获取菜单信息
            $menu = D('Common/Menu')->getMenuList(['menu_id_str' => trim($menuIdStr, ',')]);
            $menu ?: $this->Error('menu不存在, menu_id_str => ' . $menuIdStr);
            $competenceTmp = $menu = Ku\Utils::buildArray($menu, 'menu_id');
            foreach ($menu as $k => $v) {
                $secondaryMenuIdStr .= $v['menu_id'] . ',';
            }

            //获取次级菜单信息
            $secondaryMenu = D('Common/Menu')->getMenuList(['pid_str' => trim($secondaryMenuIdStr, ',')]);
            if ($secondaryMenu) {
                $secondaryMenu = Ku\Utils::buildArray($secondaryMenu, 'menu_id');
                foreach ($secondaryMenu as $k => $v) {
                    $tertiaryMenuIdStr .= $v['menu_id'] . ',';
                }

                //将一级菜单与次级菜单合并
                $competenceTmp += $secondaryMenu;

                //获取三级菜单信息
                $tertiaryMenu = D('Common/Menu')->getMenuList(['pid_str' => trim($tertiaryMenuIdStr, ',')]);
                if ($tertiaryMenu) {
                    $tertiaryMenu = Ku\Utils::buildArray($tertiaryMenu, 'menu_id');

                    //将一级菜单与三级菜单合并
                    $competenceTmp += $tertiaryMenu;
                }
            }

            foreach ($competenceTmp as $k => $v) {
                $competence[$v['menu_id']] = Ku\Utils::subUri($v['url']);
            }

            if ($showMenu != false) {
                return ['menu' => $menu, 'competence' => $competence];
            }

            return $competence;
        } catch (\Exception $e) {
            $this->returnError($e);
            return false;
        }
    }

    /**
     * 获取所有用户列表(支持根据role_id, name搜索用户)
     *
     * @author varro
     */
    public function getUserList()
    {
        $data = I('request.');
        $data['page'] = $data['page'] ?: 1;
        $userData = D('Common/User')->getUserList($data);
        $this->returnWebData['data'] = $userData;
        $this->returnWebData['count'] = count($userData);
        $this->ajaxReturn($this->returnWebData);
    }

    /**
     * 添加用户
     *
     * @author varro
     */
    public function addUser()
    {
        try{
            $data = I('request.');
            $data['user_id'] = session('user_data')['user_id'] ?? $data['user_id'];

            if (empty($data['user_id'])) {
                $this->Error('user_id为空');
            }

            if (empty($data['role_id'])) {
                $this->Error('role_id为空');
            }

            if (empty($data['account'])) {
                $this->Error('account为空');
            }

            if (empty($data['name'])) {
                $this->Error('name为空');
            }

            if (empty($data['pwd_type'])) {
                $this->Error('pwd_type为空');
            }

            if (!in_array($data['role_id'], [1, 2, 5, 6])) {
                $this->Error('角色类型错误, role_id => ' . $data['role_id']);
            }

            if (!in_array($data['pwd_type'], [1, 2, 3])) {
                $this->Error('密码类型错误, pwd_type => ' . $data['pwd_type']);
            }

            $userInfo = D('Common/User')->getUserInfo(['user_id' => $data['user_id']]);
            if (empty($userInfo)) {
                $this->Error('非法操作,用户不存在');
            }

            if (!in_array($userInfo['role_id'], [1, 2])) {
                $this->Error('非法操作, role_id => ' . $userInfo['role_id']);
            }

            $userInfo2 = D('Common/User')->getUserAccount($data['account']);
            if (!empty($userInfo2)) {
                $this->Error('该用户名已存在');
            }

            $salt = rand(1000, 9999);

            if ($data['pwd_type'] == 1) {
                $pwd = '123456';
            }

            if ($data['pwd_type'] == 2) {
                $pwd = substr($data['account'], -6);
            }

            if ($data['pwd_type'] == 3) {
                $pwd = $data['account'];
            }

            $params = [
                'account' => $data['account'],
                'pwd' => Ku\Utils::getUserPwd($pwd, $salt),
                'salt' => $salt,
                'role_id' => $data['role_id'],
                'create_time' => time(),
                'update_time' => time(),
                'reg_time' => date('Y-m-d H:i:s'),
                'status' => 1
            ];

            $userId = D('Common/User')->addUser($params);

            if (!$userId) {
                $this->Error('用户添加失败');
            }

            $params2 = [
                'user_id' => $userId,
                'name' => $data['name'],
                'header_id' => 1,
                'status' => 1
            ];

            if ($params['role_id'] == 1) {
                $userId2 = D('Common/Admin')->add($params2);
            }

            if ($params['role_id'] == 2) {
                $userId2 = D('Common/Manager')->add($params2);
            }

            if ($params['role_id'] == 5) {
                $userId2 = D('Common/Teacher')->add($params2);
            }

            if ($params['role_id'] == 6) {
                $userId2 = D('Common/Student')->add($params2);
            }

            if (!$userId2) {
                $this->Error('角色添加失败');
            }

            $this->returnData['msg'] = '用户添加成功';
        } catch (\Exception $e) {
            $this->returnError($e);
        } finally {
            $this->ajaxReturn($this->returnData);
        }
    }

    /**
     * 用户绑定手机号
     *
     * @throws \Exception
     * @author varro
     */
    public function bindPhone()
    {
        try {
            $data = I('request.');
            $data['user_id'] = session('user_data')['user_id'] ?? $data['user_id'];

            if (empty($data['user_id'])) {
                $this->Error('user_id为空');
            }

            if (empty($data['phone'])) {
                $this->Error('phone为空');
            }

            if (empty($data['code'])) {
                $this->Error('code为空');
            }

            if (!Ku\Utils::isMobileNum($data['phone'])) {
                $this->Error('手机号格式不正确');
            }

            $userInfo = D('Common/User')->getUserInfo(['user_id' => $data['user_id']]);
            if (empty($userInfo)) {
                $this->Error('用户不存在');
            }

            if (!in_array($userInfo['role_id'], [1, 2, 5, 6])) {
                $this->Error('用户角色不存在');
            }

            $userPhoneInfo = D('Common/User')->getUserList(['phone' => $data['phone']]);
            if ($userPhoneInfo) {
                $this->Error('该手机号已被绑定');
            }

            $smsInfo = D('Common/SmsCode')->getSmsCode($data['phone']);
            if (empty($smsInfo)) {
                $this->Error('请先获取验证码');
            }

            if ($smsInfo['code'] != $data['code']) {
                $this->Error('验证码错误');
            }

            if ($userInfo['role_id'] == 1) {
                D('Common/Admin')->updateAdmin(['phone' => $data['phone']], "user_id={$data['user_id']}");
            }

            if ($userInfo['role_id'] == 2) {
                D('Common/Manager')->updateManager(['phone' => $data['phone']], "user_id={$data['user_id']}");
            }

            if ($userInfo['role_id'] == 5) {
                D('Common/Teacher')->updateTeacher(['phone' => $data['phone']], "user_id={$data['user_id']}");
            }

            if ($userInfo['role_id'] == 6) {
                D('Common/Student')->updateStudent(['phone' => $data['phone']], "user_id={$data['user_id']}");
            }

            $this->returnData['msg'] = '设置成功';
        } catch (\Exception $e) {
            $this->returnError($e);
        } finally {
            $this->ajaxReturn($this->returnData);
        }
    }

    /**
     * 发送验证码
     *
     * @author varro
     */
    public function sendVerifyCode()
    {
        try {
            $time = time();
            $verifyCode = rand(1000, 9999);
            $phone = I('phone');

            if (empty($phone)) {
                $this->Error('phone为空');
            }

            if (!Ku\Utils::isMobileNum($phone)) {
                $this->Error('手机号格式不正确');
            }

            $params = [
                'phone' => $phone,
                'code' => $verifyCode,
                'create_time' => time()
            ];

            $smsInfo = D('Common/SmsCode')->getSmsCode($phone);
            if (empty($smsInfo)) {
                if (!Ku\Sms::sendSms($phone, '74525', "#code#=$verifyCode")) {
                    $this->Error('短信发送失败, 请稍后重试#1');
                }

                if (!D('Common/SmsCode')->addSmsCode($params)) {
                    $this->Error('短信发送失败, 请稍后重试#2');
                }

            } else {
                $sec = $time - $smsInfo['create_time'];
                if ($sec <= 60) {
                    $this->Error('请在' . (60 - $sec) . '秒后重新发送');
                }

                if (!Ku\Sms::sendSms($phone, '74525', "#code#=$verifyCode")) {
                    $this->Error('短信发送失败, 请稍后重试#3');
                }

                if (!D('Common/SmsCode')->updateSmsCode($params, "phone=$phone")) {
                    $this->Error('短信发送失败, 请稍后重试#4');
                }
            }

            $this->returnData['msg'] = '短信发送成功';
        } catch (\Exception $e) {
            $this->returnError($e);
        } finally {
            $this->ajaxReturn($this->returnData);
        }
    }

    /**
     * 修改密码
     *
     * @author varro
     */
    public function changePwd()
    {
        try {
            $data = I('request.');
            $data['user_id'] = session('user_data')['user_id'] ?? $data['user_id'];

            if (empty($data['user_id'])) {
                $this->Error('user_id为空');
            }

            if (empty($data['pwd'])) {
                $this->Error('pwd为空');
            }

            $userInfo = D('Common/User')->getUserInfo(['user_id' => $data['user_id']]);
            if (empty($userInfo)) {
                $this->Error('用户不存在');
            }

            $salt = rand(1000, 9999);
            $pwd = Ku\Utils::getUserPwd($data['pwd'], $salt);

            if (!D('Common/User')->updateUserInfo(['pwd' => $pwd, 'salt' => $salt], "user_id={$data['user_id']}")) {
                $this->Error('修改密码失败');
            }

            $this->returnData['msg'] = '修改成功, 请重新登录';
        } catch (\Exception $e) {
            $this->returnError($e);
        } finally {
            session(null);
            $this->ajaxReturn($this->returnData);
        }
    }

    /**
     * 绑定班级
     *
     * @author varro
     */
    public function bindClass()
    {
        try {
            $data = I('request.');
            $data['manager_id'] = session('user_data')['user_id'];

            if (empty($data['manager_id'])) {
                $this->Error('manager_id为空');
            }

            if (empty($data['user_id'])) {
                $this->Error('user_id为空');
            }

            if (empty($data['class_id'])) {
                $this->Error('class_id为空');
            }

            $managerInfo = D('Common/User')->getUserInfo(['user_id' => $data['manager_id']]);
            if (empty($managerInfo)) {
                $this->Error('用户不存在#1');
            }

            if (!in_array($managerInfo['role_id'], [1, 2])) {
                $this->Error('非法操作#1, role_id => ' . $managerInfo['role_id']);
            }

            $userInfo = D('Common/User')->getUserInfo(['user_id' => $data['user_id']]);
            if (empty($userInfo)) {
                $this->Error('用户不存在#2');
            }

            if ($userInfo['role_id'] != 6) {
                $this->Error('非法操作#2, role_id => ' . $userInfo['role_id']);
            }

            if (!empty($userInfo['class_id'])) {
                $this->Error('该用户已绑定班级, class_id => ' . $userInfo['class_id']);
            }

            $classInfo = D('Common/Class')->getClass($data['class_id']);
            if (!$classInfo) {
                $this->Error('该班级不存在');
            }

            if (!D('Common/Student')->updateStudent(['class_id' => $data['class_id']], "user_id={$data['user_id']}")) {
                $this->Error('更新信息失败');
            }

            $this->returnData['msg'] = '更新成功';
        } catch (\Exception $e) {
            $this->returnError($e);
        } finally {
            $this->ajaxReturn($this->returnData);
        }
    }

    /**
     * 绑定课程
     *
     * @author varro
     */
    public function bindCourse()
    {
        try {
            $data = I('request.');
            $data['manager_id'] = session('user_data')['user_id'];

            if (empty($data['manager_id'])) {
                $this->Error('manager_id为空');
            }

            if (empty($data['user_id'])) {
                $this->Error('user_id为空');
            }

            $managerInfo = D('Common/User')->getUserInfo(['user_id' => $data['manager_id']]);
            if (empty($managerInfo)) {
                $this->Error('用户不存在#1');
            }

            if (!in_array($managerInfo['role_id'], [1, 2])) {
                $this->Error('非法操作#1, role_id => ' . $managerInfo['role_id']);
            }

            $userInfo = D('Common/User')->getUserInfo(['user_id' => $data['user_id']]);
            if (empty($userInfo)) {
                $this->Error('用户不存在#2');
            }

            if ($userInfo['role_id'] != 5) {
                $this->Error('非法操作#2, role_id => ' . $userInfo['role_id']);
            }

            $sql = "
                SELECT
                    GROUP_CONCAT( course_id ) AS course_id_str 
                FROM
                    course_teacher 
                WHERE
                    teacher_id = {$data['user_id']} 
                GROUP BY
                    teacher_id";
            $node = M()->query($sql);

            $courseIdStrOld = !empty($node) ? explode(',', $node[0]['course_id_str']) : [];
            $courseIdStrNew = $data['course_id']  ?? [];

            $addIdArray = array_unique(array_diff($courseIdStrNew, $courseIdStrOld));
            $delIdArray = array_unique(array_diff($courseIdStrOld, $courseIdStrNew));

            if (!empty($addIdArray)) {
                $insertArray = [];
                foreach ($addIdArray as $k => $v) {
                    $insertArray[$k]['course_id'] = $v;
                    $insertArray[$k]['teacher_id'] = $data['user_id'];
                    $insertArray[$k]['create_time'] = time();
                    $insertArray[$k]['status'] = 1;
                }

                if (!M('course_teacher')->addAll(array_values($insertArray))) {echo M()->getLastSql();
                    $this->Error('更新失败#1');
                }
            }

            if (!empty($delIdArray)) {
                $delIdStr = implode(',', $delIdArray);
                if (!M('course_teacher')->where("teacher_id = '{$data['user_id']}' AND course_id IN ($delIdStr)")->delete()) {

                    $this->Error('更新失败#2');
                }
            }

            $this->returnData['msg'] = '更新成功';
        } catch (\Exception $e) {
            $this->returnError($e);
        } finally {
            $this->ajaxReturn($this->returnData);
        }
    }
}
