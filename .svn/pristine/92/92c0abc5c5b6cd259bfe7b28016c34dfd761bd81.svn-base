<?php

namespace Common\Model;

use Think\Model;
use Ku;

class PowerModel extends Model
{
    protected $tableName = 'power';
    protected $pk = 'power_id';

    /**
     * 获取权限列表
     *
     * @param array $data
     * @return mixed 获取成功返回array, 失败返回bool
     * @author varro
     */
    public function getPowerList(array $data = [])
    {
        $whereArray = [];
        $sql = "SELECT * FROM power";

        if (!empty($data['key'])) {
            $whereArray[] = "power_name like '%{$data['key']}%'";
        }

        if (!empty($data['power_id'])) {
            $whereArray[] = "power_id = {$data['power_id']}";
        }

        if (!empty($data['power_id_str'])) {
            $whereArray[] = "power_id in ({$data['power_id_str']})";
        }

        $where = implode($whereArray, ' and ');
        $sql .= $where ? " where $where " : "";

        if (!empty($data['page']) && !empty($data['limit'])) {
            $sql .= Ku\Utils::setLimit($data['page'], $data['limit']);
        }

        return array('count' => $this->count(), 'data' => $this->query($sql));
    }

    //..
    public function powerList(){
        $data = $this->select();
        if($data){
            $count = count($data);
            $value = $this->getTableList($count, $data);
            if ($value){
                $re = array('status'=>1,'code'=>'success', 'value'=>$value, 'msg'=>'ok');
            }else {
                $re = array('status'=>-1,'code'=>'serverErr', 'value'=>'', 'msg'=>'服务器繁忙');
            }
        }else {
            $count = 0;
            $value = $this->getTableList($count, $data,'暂无数据');
            $re = array('status'=>-1,'code'=>'serverErr', 'value'=>$value, 'msg'=>'暂无数据');
        }
        return $re;
    }

    /**
     * 通过id获取数据
     * @param unknown $id
     */
    public function getMsgById($id){
        if($id){
            $where = 'power_id = '.$id;
        }else {
            return $re = array('status'=>-1, 'code'=>'serverErr', 'value'=>'', 'msg'=>'参数错误');
        }
        $data  = $this->where($where)->select();
        if($data){
            $count = count($data);
            $temp = $this->getTableList($count, $data);
            return $re = array('status'=>1, 'code'=>'success', 'value'=>$temp, 'msg'=>'ok');
        }else {
            return $re = array('status'=>-1, 'code'=>'serverErr', 'value'=>'', 'msg'=>'error');
        }
    }
    /**
     * 添加数据
     * @param unknown $data
     * @return number[]|string[]|mixed[]|boolean[]|unknown[]
     */
    public function addMssage($data){
        if(!$this->create($data,4)){
            $re = array('status'=>-1, 'code'=>'paramsErr', 'value'=>'', 'msg'=>$this->getError());
        }else{
            $res = $this->add($data);
            if($res){
                $re = array('status'=>1, 'code'=>'success', 'value'=>$res, 'msg'=>'ok');
            }else{
                $re = array('status'=>-1,'code'=>'serverErr', 'value'=>'', 'msg'=>'服务器繁忙');
            }
        }
        return $re;
    }
    /**
     * 修改数据
     * @param unknown $where
     * @param unknown $data
     */
    public function updateList($where,$data){
        if($where&&$data){
            $value = $this->where($where)->data($data)->save();
        }
        if($value){
            $re = array('status'=>1, 'code'=>'success', 'value'=>$value, 'msg'=>'ok');
        }else {
            $re = array('status'=>-1,'code'=>'serverErr', 'value'=>'', 'msg'=>'服务器繁忙');
        }
        return $re;
    }
    /**
     * 设置layui table需要的数据格式
     * @param unknown $count
     * @param unknown $data
     * @param string $msg
     */
    private function getTableList($count,$data,$msg=''){
        $temp = array();
        $temp['code'] = 0;
        $temp['msg'] = $msg;
        $temp['count'] = (int)$count;
        $temp['data'] = $data;
        return $temp;
    }
}
