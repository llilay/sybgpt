<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2018/4/14
 * Time: 11:47
 */

namespace Common\Model;

use Think\Model;

class MajorModel extends Model
{
    protected $tableName = 'major';
    protected $pk = 'major_id';
    /**
     * 添加专业
     */
    public function addMajor($data){
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
     * 获取专业列表
     * @return array
     */
    public function majorList(){
        $data = $this
            ->join('LEFT JOIN faculty ON major.faculty_id = faculty.faculty_id' )
            ->field('major.*,faculty.faculty_name')
            ->select();
        if($data){
            $count = count($data);
            $value = $this->getTableList($count, $data);
            if ($value){
                $re = array('status'=>1,'code'=>'success', 'value'=>$value, 'msg'=>'ok');
            }else {
                $re = array('status'=>-1,'code'=>'serverErr', 'value'=>'', 'msg'=>'服务器繁忙');
            }
        }else {
            $re = array('status'=>-1,'code'=>'serverErr', 'value'=>'', 'msg'=>'服务器繁忙');
        }
        return $re;
    }

    /**
     * 获取某一院系专业的名称
     */
    public function getAllMajorName(){
        $pid = I('pid');
        $where = array();
        $where['status'] = array("eq",1);
        $where['faculty_id'] = array("eq",$pid);
        return $this->where($where)->field('major_id,major_name')->select();
    }

    private function getTableList($count,$data){
        $temp = array();
        $temp['code'] = 0;
        $temp['msg'] = '';
        $temp['count'] = (int)$count;
        $temp['data'] = $data;
        return $temp;
    }
}