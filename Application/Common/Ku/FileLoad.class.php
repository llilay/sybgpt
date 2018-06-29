<?php
/**
 * 功能说明：小文件上传下载
 **/
namespace Ku;

class FileLoad {
	// 上传
    public static function upload($field) {
    	
    }
    
    // 下载
    public static function download($id) {
    	
    }
    
    // 上传图片
    public static function upimg($field, $url = null) {
    	$fileId = Utils::createGuid();
    	$data['file_id'] = $fileId;
    	$time = time();
    	$data['create_time'] = $time;
    	$data['upload_time'] = date('Y-m-d H:i:s', $time);
		// 上传图片
		if(empty($_FILES[$field]['tmp_name'])){
            $this->error('没有上传的图片');
            exit;
        }
        $type = $_FILES[$field]['type']; //文件类型
        $uptypes = array("image/jpg","image/jpeg","image/png","image/pjpeg","image/gif","image/bmp","image/x-png");
        if(!in_array($type, $uptypes)){ //图片格式判断
        	$this->error('上传图片的文件格式不正确');
            exit;
        }
    	$data['file_type'] = $type;
    	$data['file_size'] = $_FILES[$field]['size'];
    	$data['file_name'] = $_FILES[$field]['name'];
	    $data['file_body'] = file_get_contents($_FILES[$field]['tmp_name']);
    	$data['md5_str'] = md5($data['file_body']);
    	
    	// 缓存图片到磁盘
    	if($url != null){
    		$filename = $_SERVER['DOCUMENT_ROOT'].$url;
	    	if(file_exists($filename)) {}
	    	else {
	    		mkdir(dirname($filename), 0777, true);
	    		file_put_contents($filename, $data['file_body'], true);
	    	}
    	}
    	
    	$m = M('file_info');
    	$obj = $m->field('file_id,md5_str')->where(array('md5_str'=>$data['md5_str']))->find();
	    if($obj == null){
	    	$m->add($data);
	    } else {
	    	$fileId = $obj['file_id'];
	    }
        return $fileId;
    }
    
    // 显示图片
    public static function showimg($id) {
    	$img = F('img/'.$id);
		if($img == null) {
			$obj = M("file_info")->where(array('FILE_ID'=>$id))->find();
			$img['type'] = $obj['file_tpye'];
			$img['body'] = $obj['file_body'];
			F('img/'.$id, $img);
		}
		Header( "Content-Type:".$img['type']); //直接输出显示图片
		echo $img['body'];
    }
    
    // 缓存图片是否存在
    public static function setLocalImg($id, $url) {
    	$filename = $_SERVER['DOCUMENT_ROOT'].$url;
    	if(file_exists($filename)) {}
    	else {
    		$obj = M("file_info")->where(array('file_id'=>$id))->find();
    		mkdir(dirname($filename),0777,true);
    		file_put_contents($filename, $obj['file_body'], true);
    	}
    }
    
	
}
?>