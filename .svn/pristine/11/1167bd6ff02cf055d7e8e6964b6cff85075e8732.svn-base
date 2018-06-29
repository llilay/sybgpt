<?php
namespace Common\Model;

use Think\Model;
use Ku;

class RoleModel extends Model
{
    protected $tableName = 'role';
    protected $pk = 'role_id';

    /**
     * 获取角色列表
     *
     * @param array $data
     * @return mixed 获取成功返回array, 失败返回bool
     * @author varro
     */
    public function getRoleList(array $data = [])
    {
        $whereArray = [];
        $sql = "SELECT * FROM role";

        if (!empty($data['role_id'])) {
            $whereArray[] = "role_id = {$data['role_id']}";
        }

        if (!empty($data['role_id_str'])) {
            $whereArray[] ="role_id in ({$data['role_id_str']})";
        }

        if (!empty($data['key'])) {
            $whereArray[] = "role_name like '%{$data['key']}%'";
        }

        $where = implode($whereArray, ' and ');
        $sql .= $where ? " where $where " : "";

        if (!empty($data['page']) && !empty($data['limit'])) {
            $sql .= Ku\Utils::setLimit($data['page'], $data['limit']);
        }

        return array('count' => $this->count(), 'data' => $this->query($sql));
    }


    /**
     * 根据id获取角色信息
     * @param unknown $id
     */
    public function getRoleById($id){
        if($id){
            $where = 'role_id = '.$id;
        }else {
            return $re = array('status'=>-1, 'code'=>'serverErr', 'value'=>'', 'msg'=>'参数错误');
        }
        $data  = $this->where($where)->select();
        if($data){
            $count = count($data);
            $temp = $this->getTableList($count, $data);
            return $re = array('status'=>1, 'code'=>'success', 'value'=>$temp, 'msg'=>'ok');
        }else {
            $count = 0;
            $value = $this->getTableList($count, $data);
            return $re = array('status'=>-1, 'code'=>'serverErr', 'value'=>$value, 'msg'=>'error');
        }
    }

    /**
     * 添加角色信息
     * @param unknown $data
     * @return number[]|string[]|mixed[]|boolean[]|unknown[]
     */
    public function addRole($data){
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
     * 修改角色信息
     * @param unknown $where
     * @param unknown $data
     */
    public function updateRole($where,$data){
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

    private function getTableList($count,$data){
        $temp = array();
        $temp['code'] = 0;
        $temp['msg'] = '';
        $temp['count'] = (int)$count;
        $temp['data'] = $data;
        return $temp;
    }
}
