<?php
/**
 * 功能说明：工具
 **/
namespace Ku;

class Utils
{
    /**
     * 时间显示处理
     *
     * @param $dt 输入格式 '2017-6-12 14:46:08'
     * @return false|string
     */
    public static function getShowTime($dt)
    {
    	$te = date('Y-m-d 23:59:59', time());//今天结束时间
    	$diff = strtotime($te) - strtotime($dt);
    	if($diff < 24*3600){
    		return date('H:i', strtotime($dt));
    	} else if($diff < 48*3600){
    		return "昨天";
    	} else if($diff < 72*3600){
    		return "前天";
    	} else return date('Y-m-d', strtotime($dt));
    }

    /**
     * 生成GUID（UUID）
     *
     * @return string
     */
    public static function createGuid()
    {
        mt_srand((double)microtime()*10000);//从mt_rand返回的最大随机值
        $charid = strtoupper(md5(uniqid(rand(), true)));//uniqid(rand(), true)生成一个唯一的随机数
        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid, 12, 4).$hyphen
            .substr($charid, 16, 4).$hyphen
            .substr($charid, 20, 12);
        return strtolower($uuid);
    }

    /**
     * 生成随机数
     *
     * @param int $length 随机数长度
     * @return int
     */
    public static function generateCode($length = 6)
    {
	    $min = pow(10 , ($length - 1));
	    $max = pow(10, $length) - 1;
	    return rand($min, $max);
	}

    /**
     * 生成随机字符串
     *
     * @param int $len
     * @param string $format
     * @return string
     */
    public static function randpw($len=8,$format='ALL')
    {
		$is_abc = $is_numer = 0;
		$password = $tmp =''; 
		switch($format){
			case 'ALL':
				$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
				break;
			case 'CHAR':
				$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
				break;
			case 'NUMBER':
				$chars='0123456789';
				break;
			default :
				$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
				break;
		}
		mt_srand((double)microtime() * 1000000 * getmypid());
		while(strlen($password) < $len){
			$tmp = substr($chars,(mt_rand()%strlen($chars)),1);
			if(($is_numer <> 1 && is_numeric($tmp) && $tmp > 0 )|| $format == 'CHAR'){
				$is_numer = 1;
			}
			if(($is_abc <> 1 && preg_match('/[a-zA-Z]/',$tmp)) || $format == 'NUMBER'){
				$is_abc = 1;
			}
			$password .= $tmp;
		}
		if($is_numer <> 1 || $is_abc <> 1 || empty($password) ){
			$password = randpw($len, $format);
		}
		return $password;
	}
	
	/**
	 * 产生随机字符串，不长于32位
	 * @param int $length
	 * @return 产生的随机字符串
	 */
	public static function getNonceStr($length = 32)
    {
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {  
			$str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		} 
		return $str;
	}

    /**
     * 获取访问平台
     *
     * @return bool
     */
    public static function isMobile()
    {
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        
        $is_pc = (strpos($agent, 'windows nt')) ? true : false;
        $is_mac = (strpos($agent, 'mac os')) ? true : false;
        $is_iphone = (strpos($agent, 'iphone')) ? true : false;
        $is_android = (strpos($agent, 'android')) ? true : false;
        $is_ipad = (strpos($agent, 'ipad')) ? true : false;
        
        if($is_pc) return false;
        if($is_mac) return true;
        if($is_iphone) return true;
        if($is_android) return true;
        if($is_ipad) return true;
	}

    /**
     * get远程访问
     *
     * @param $url
     * @return mixed
     */
	public static function serverHttpGet($url)
    {
		$ch = curl_init();  
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_POST, false);
		curl_setopt($ch, CURLOPT_HEADER, false); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 500); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$res = curl_exec($ch); 
        //\Think\Log::write('curl_error:'.curl_error($ch),'ERROR');
		curl_close($ch); 
		return $res;
    }

    /**
     * 获取当前运行脚本所在的文档根目录
     *
     * @return string
     */
    public static function serverFilePath()
    {
    	$path = $_SERVER['DOCUMENT_ROOT'];
    	$fPath = strstr($path, 'htdocs', true);
    	return $fPath;
    }

    /**
     * 生成用户密码
     *
     * @param string $pwd
     * @param string $salt
     * @return string
     * @author varro
     */
    public static function getUserPwd(string $pwd, string $salt) :string
    {
        return md5($pwd . $salt);
    }

    /**
     * 截取uri
     *
     * @param string $uri /api/user/login.html
     * @return string /api/user/login
     * @author varro
     */
    public static function subUri(string $uri) :string
    {
        return strtolower(substr($uri, 0, strpos($uri, '.')));
    }

    /**
     * 横向分类
     *
     * @param $arr
     * @param int $pid
     * @return array
     */
    public static function hTree($arr, $pid=0)
    {
        foreach($arr as $k => $v){
            if($v['pid'] == $pid){
                $data[$v['menu_id']] = $v;
                $data[$v['menu_id']]['children'] = self::hTree($arr, $v['menu_id']);
            }
        }
        return isset($data) ? $data : [];
    }

    /**
     * 纵向分类
     *
     * @param $arr
     * @param int $pid
     * @return array|mixed
     */
    public static function vTree($arr, $pid=0)
    {
        foreach($arr as $k => $v){
            if($v['pid'] == $pid){
                $data[$v['id']] = $v;
                $data += self::vTree($arr, $v['id']);
            }
        }
        return isset($data) ? $data : [];
    }

    /**
     * 分页
     *
     * @param int $page 页数
     * @param int $num 条数
     * @return string
     * @author varro
     */
    public static function setLimit($page = 1, $num = 20)
    {
        $skip = ($page - 1) * $num;
        return " Limit $skip,$num ";
    }

    /**
     * 验证手机号合法性
     *
     * @param $phone
     * @return bool
     * @author varro
     */
    public static function isMobileNum($phone)
    {
        if (!preg_match('/^((13[0-9])|(17[0-9])|(14[5|7])|(15([0-3]|[5-9]))|(18[0,5-9]))[0-9]{8}$/', $phone)) {
            return false;
        }

        return true;
    }

    /**
     * 通过制定键值$key重组二维数组
     *
     * @param array $currentArray 原数组
     * @param string $key 键值$key
     * @return array
     * @author zhangmh at 2017-8-24
     */
    public static function buildArray($currentArray, $key)
    {
        $tempArray = array();
        foreach ($currentArray as $v) {
            $tempArray[$v[$key]] = $v;
        }
        return $tempArray;
    }
}
