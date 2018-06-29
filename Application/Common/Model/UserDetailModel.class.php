<?php
namespace Common\Model;
use Think\Model;
class UserDetailModel extends Model{
    protected $tableName = 'user_profiles';
    protected $pk = 'user_id';
    /**
     * 修改用户附属数据
     * @date: 2017年10月24日上午12:38:09
     * @author: Hhb
     * @param unknown $data
     * @param unknown $user_id
     */
    public function updateUserDetail($data, $user_id){
        if(empty($user_id)){
            return false;
        }
        $time = time();
        $re = $this->getUserDetailById($user_id);
        if(empty($re)){
            $data['user_id'] = $user_id;
            $data['create_time'] = $time;
            $data['update_time'] = $time;
            $data['sys_time'] = date("Y-m-d H:i:s",$time);
            $res = $this->data($data)->add();
        }else{
            $data['update_time'] = $time;
            $res = $this->data($data)->where(array('user_id'=>$user_id))->save();
        }
        return $res;
        
    }
    /**
     * 获取用户附属数据
     * @date: 2017年10月24日上午12:37:58
     * @author: Hhb
     * @param unknown $user_id
     * @return mixed|boolean|NULL|string|unknown|object
     */
    public function getUserDetailById($user_id){
        return $this->where(array('user_id'=>$user_id))->find();
    }
}