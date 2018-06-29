<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2018/4/14
 * Time: 10:52
 */

namespace Common\Model;

use Think\Model;

class FacultyModel extends Model
{
    protected $tableName = 'faculty';
    protected $pk = 'faculty_id';
    /**
     * 添加院系
     */
    public function addFaculty($data){
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
     * 获取院系列表
     * @return array
     */
    public function facultyList(){
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
            $value = $this->getTableList($count, $data);
            $re = array('status'=>-1,'code'=>'serverErr', 'value'=>'', 'msg'=>'服务器繁忙');
        }
        return $re;
    }

    /**
     * 获取所有院系的名称
     */
    public function getAllFacultyName(){
        return $this->where('status=1')->field('faculty_id,faculty_name')->select();
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