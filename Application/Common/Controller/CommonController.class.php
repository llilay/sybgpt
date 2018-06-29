<?php
namespace Common\Controller;

use Think\Controller;
use Ku;

class CommonController extends Controller
{
    //当前访问的模块名称
    protected $moduleName = null;

    //当前访问的控制器名称
    protected $controllerName = null;

    //当前访问的方法名称
    protected $actionName = null;

    //当前访问的Uri
    protected $uri = null;

    //用户信息
    protected $userData = null;

    //用户权限
    protected $userCompetence = null;

    //允许忽略权限验证的目录
    protected $allowDirectory = ['resources'];

    //允许忽略权限验证的模块名称
    protected $allowModule = ['api'];

    //允许忽略权限验证的控制器名称
    protected $allowController = ['index'];

    //允许忽略权限验证的方法名称
    protected $allowAction = [
        'login',
        'checkreport',
        'examinereport',
        'saveexaminereport'
    ];

    //API默认返回数组
    public $returnData = [
        'status' => 1,
        'code' => 'success',
        'value' => '',
        'msg' => '成功'
    ];

    //LayUi默认返回数组
    public $returnWebData = [
        'code' => 0,
        'msg' => '',
        'count' => 0,
        'data' => ''
    ];

    /**
     * 初始化方法
     *
     * @author varro
     */
    public function _initialize()
    {
        $this->moduleName = strtolower(MODULE_NAME);
        $this->controllerName = strtolower(CONTROLLER_NAME);
        $this->actionName = strtolower(ACTION_NAME);
        $this->uri = '/' .$this->moduleName . '/' . $this->controllerName . '/' . $this->actionName;
        $this->userData = session('user_data');
        $this->userCompetence = session('user_competence');
        $this->checkUserLoginStatus();
        $this->checkUserCompetence();
    }

    /**
     * 检测用户登录状态
     *
     * @author varro
     */
    public function checkUserLoginStatus()
    {
        if (in_array($this->actionName, $this->allowAction)) {
            return true;
        }

        if (empty($this->userData)) {
            $this->redirect('/Home/User/Login');
            exit();
        }
    }

    /**
     * 检测用户权限
     *
     * @author varro
     */
    public function checkUserCompetence()
    {
        if (in_array($this->moduleName, $this->allowModule)) {
            return true;
        }

        if (in_array($this->controllerName, $this->allowController)) {
            return true;
        }

        if (in_array($this->actionName, $this->allowAction)) {
            return true;
        }

        if (!in_array($this->uri, $this->userCompetence)) {
            parent::error("无权限执行该操作");
            exit();
        }
    }

    /**
     * 抛出异常
     *
     * @param string $msg 错误信息
     * @throws \Exception
     * @author varro
     */
    public function Error(string $msg)
    {
        throw new \Exception($msg);
    }

    /**
     * 定义异常属性
     *
     * @param \Exception $e
     * @param mixed $value 异常数据
     * @author varro
     */
    public function returnError(\Exception $e, $value = '')
    {
        $trace = current($e->getTrace());
        $this->returnData['status'] = -1;
        $this->returnData['msg'] = $e->getMessage();
        $this->returnData['code'] = [
            'Line' => $trace['line'],
            'File' => $trace['file']
        ];
        $this->returnData['value'] = $value;

        $this->returnWebData ['code'] = [
            'Line' => $trace['line'],
            'File' => $trace['file']
        ];
        $this->returnWebData ['msg'] = $e->getMessage();
    }
}
