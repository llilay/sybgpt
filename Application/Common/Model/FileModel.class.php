<?php
namespace Common\Model;
use Think\Model;
use Ku\Utils;
class FileModel extends Model{
    protected $tableName = 'file_info';
    protected $pk = 'file_id';
    /**
     * 单文件上传（图片）
     * @param unknown $file_data
     * @param array $options
     */
    public function saveUploadOneFile($file_data, $options = array()){
        if(empty($file_data)){
             return array('status'=>-1,'code'=>'paramErr','value'=>'','msg'=>'参数异常');
        }
        $file_body = file_get_contents($file_data['tmp_name']);
        $uploads = new \Think\Upload($options);
        $re = $uploads->uploadOne($file_data);
        $err = $uploads->getError();
        if(empty($err) && !empty($re)){
            $file_id = Utils::createGuid();
            $time = time();
            $data = array(
                'file_id' => $file_id,
                'create_time' => $time,
                'upload_time' => date('Y-m-d H:i:s',$time),
                'file_type' => $file_data['type'],
                'file_size' => $file_data['size'],
                'file_name' => $file_data['name'],
                'md5_str' => md5($file_body),
                'file_body' => $file_body
            );
            $obj = $this->field('file_id,md5_str')->where(array('md5_str'=>$data['md5_str']))->find();
            if($obj == null){
                $res = $this->add($data);
                if(empty($res)){
                    return array('status'=>-1,'code'=>'serverErr','value'=>'','msg'=>'数据保存失败');
                }
            }else{
                $file_id = $obj['file_id'];
            }
            $return = array(
                'img_url'=>"/Uploads/".$re['savepath'].$re['savename'],
                'file_id'=>$file_id
            );
            return array('status'=>1,'code'=>'success','value'=>$return,'msg'=>'ok');
        }else{
            return array('status'=>-1,'code'=>'serverErr','value'=>'','msg'=>$err);
        }
    }
    /**
     * 检测缓存图片，不存在时由FILE_ID生成
     * @param unknown $id
     * @param unknown $url
     */
    public function setLocalImg($id, $url) {
        $filename = $_SERVER['DOCUMENT_ROOT'].$url;
        if(file_exists($filename)) {
            
        }else{
            $obj = $this->where(array('file_id'=>$id))->find();
            if(!empty($obj)){
                mkdir(dirname($filename),0777,true);
                file_put_contents($filename, $obj['file_body'], true);
            }
        }
    }
}