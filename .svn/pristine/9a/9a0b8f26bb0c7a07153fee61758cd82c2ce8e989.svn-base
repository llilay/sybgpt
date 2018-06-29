<?php

namespace Common\Model;

use Think\Model;

class MenuModel extends Model
{
    protected $tableName = 'menu';
    protected $pk = 'menu_id';
    /**
     * 获取角色列表
     */
    public function addMenu($data){
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
    public function updateMenu($where,$data){
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
     * 获取所有菜单的名称
     */
    public function getAllMenuName(){
        return $this->where('status=1')->field('menu_id, menu_name') -> order('sort asc')->select();
    }
    
    /**
     * 获取所有首页可见的菜单
     */
    public function getAllMenu()
    {
        return $this->where('status=1 AND is_show=1') -> select();
    }
    public function getMenuPageList($page=1, $limit=10)
    {
        $count = $this->count();
        $data = $this->limit(($page-1) * $limit, $limit)->select();
        $temp = $this->menuTableList($count, $data);
        return $temp;
    }
    public function getMenuById($id)
    {
        if($id){
            $where = 'menu_id = '.$id;
        }else {
            return $re = array('status'=>-1, 'code'=>'serverErr', 'value'=>'', 'msg'=>'参数错误');
        }
        $data  = $this->where($where)->select();
        if($data){
            $count = count($data);
            $temp = $this->menuTableList($count, $data);
            return $re = array('status'=>1, 'code'=>'success', 'value'=>$temp, 'msg'=>'ok');
        }else {
            return $re = array('status'=>-1, 'code'=>'serverErr', 'value'=>'', 'msg'=>'error');
        }
    }

    private function menuTableList($count,$data)
    {
        $temp = array();
        $temp['code'] = 0;
        $temp['msg'] = '';
        $temp['count'] = (int)$count;
        $temp['data'] = $data;
        return $temp;
    }
    
    /**
     * 分类排序（降序）
     */
    static public function sort($arr, $cols){
        //子分类排序
        foreach ($arr as $k => &$v) {
            if(!empty($v['sub'])){
                $v['sub'] = self::sort($v['sub'], $cols);
            }
            $sort[$k] = $v[$cols];
        }
        if(isset($sort))
            array_multisort($sort, SORT_DESC,$arr);
            return $arr;
    }

    /**
     * 重组数组，无限分类
     * @param unknown $arr
     */
    public function getNewMenuArr(){
        $data = $this->getAllMenu();
        if($data){
            $data = $this->hTree($data);
        }else {
            $data = array();
        }
        return $data;
    }

    /**
     * 横向分类树
     */
    static public function hTree($arr,$pid=0){
        foreach($arr as $k => $v){
            if($v['pid'] == $pid){
                $data[$v['menu_id']] = $v;
                $data[$v['menu_id']]['children']=self::hTree($arr,$v['menu_id']);
            }
        }
        return isset($data)?$data:array();
    }

    /**
     * 纵向分类树
     */
    static public function vTree($arr,$pid=0){
        foreach($arr as $k => $v){
            if($v['pid']==$pid){
                $data[$v['id']]=$v;
                $data += self::vTree($arr,$v['id']);
            }
        }
        return isset($data)?$data:array();
    }

    /**
     * 获取菜单列表
     *
     * @param array $data
     * @return mixed 获取成功返回array, 失败返回bool
     * @author varro
     */
    public function getMenuList(array $data = [])
    {
        $whereArray = [];
        $sql = "SELECT * FROM menu";

        if (!empty($data['menu_id'])) {
            $whereArray[] = "menu_id = {$data['menu_id']}";
        }

        if (!empty($data['menu_id_str'])) {
            $whereArray[] = "menu_id in ({$data['menu_id_str']})";
        }

        if (!empty($data['pid_str'])) {
            $whereArray[] = "pid in ({$data['pid_str']})";
        }

        $where = implode($whereArray, ' and ');
        $sql .= $where ? " where $where " : "";
        return $this->query($sql);
    }
}
