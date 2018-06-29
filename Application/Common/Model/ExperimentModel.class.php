<?php
namespace Common\Model;

use Think\Model;

class ExperimentModel extends Model
{
    protected $tableName = 'experiment';
    protected $pk = 'experiment_id';

    public function getExperiment($experimentId = 0)
    {
        return $this->where("experiment_id = $experimentId")->find();
    }

    /**
     * 添加实验
     */
    public function addExperiment($data){
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
     * 获取某一课程所有实验的名称
     */
    public function getAllExperimentName(){
        $pid = I('pid');
        $where = array();
        $where['status'] = array("eq",1);
        $where['course_id'] = array("eq",$pid);
        return $this->where($where)->field('experiment_id,experiment_name')->select();
    }

    /**
     * 获取所有实验列表
     *
     * @return mixed
     * @author varro
     */
    public function getExperimentList()
    {
        return $this->select();
    }

    /**
     * 获取所有实验列表
     *
     * @param $studentId
     * @return mixed
     * @author varro
     */
    public function getExperimentListByStudent($studentId = 0)
    {
        $sql = "
            SELECT
                a.*
            FROM
                experiment a
                LEFT JOIN class_course b ON b.course_id = a.course_id
                LEFT JOIN student c ON c.class_id = b.class_id 
            WHERE c.student_id = $studentId";

        return $this->query($sql);
    }
}
