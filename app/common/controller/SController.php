<?php
/**
 * 2017年9月26日
 * 总控制器
 * @author 李桦
 */
namespace app\common\controller;


use app\BaseController;

define(USERSESSIONNAME,"");

class SController extends BaseController {

    use Ponds;
	function __construct(){
		parent::__construct ();
		$this->assign("root",ROOT);
        $this->assign("d_root","http://".$_SERVER["HTTP_HOST"].ROOT);
        $this->assign("s_root","");
		$this->getSiteSys();
	}

	protected function setREQUEST_URI($method) {
	    $str = "";
		$method = str_replace("::", "/", $method);
		$method = str_replace("\\Controller\\", "/", $method);
		$method = str_replace("Controller", "", $method);
		$str.="/".$method;
		if(count($_GET)>0){
			foreach ($_GET as $key => $val){
				$str .= "/".$key."/".$val;
			}
		}
		$_SERVER["REQUEST_URI"] = $str;
		return $str;
	}

    protected function getJsSdk() {
        $jssdk = new Jssdk( WxPayConfig::APPID, WxPayConfig::APPSECRET );
        $signPackage = $jssdk->GetSignPackage ();
        return $signPackage;
    }
    /**
    * @Description: 上传图片并压制缩略图 参数是Form中file的name
    * @data: 下午 3:40 2017/11/8 0008
    */
    protected function uploadimg($filename){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file($filename);

        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
           // echo 444;
            $info = $file->move(ART_S_IMG);
            if($info){
                $imgsrc = ART_S_IMG.$info->getSaveName();
                $imgsrc = str_replace("\\","/",$imgsrc);
                $img = Image::open($imgsrc);
                $img->thumb(150,150,Image::THUMB_CENTER)->save(ART_S_IMG.$info->getSaveName());
                return $imgsrc;
            }else{
                return false;
            }
        }
    }
    protected function uploadimgnochange($filename){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file($filename);
        if($file){
            $info = $file->move(ART_S_IMG);
            if($info){
                $imgsrc = ART_S_IMG.$info->getSaveName();
                $imgsrc = str_replace("\\","/",$imgsrc);
                $img = Image::open($imgsrc);
                $img->thumb(640,"",Image::THUMB_SCALING)->save(ART_S_IMG.$info->getSaveName());
                return $imgsrc;
            }else{
                return false;
            }
        }
    }
    /**
     * @Description: 上传图片并压制缩略图 参数是Form中file的name
     * @data: 下午 3:40 2017/11/8 0008
     */
    protected function uploadGoodsimg($filename){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file($filename);
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            // echo 444;
            $info = $file->move(ART_S_IMG);
            if($info){
                $imgsrc = ART_S_IMG.$info->getSaveName();
                $imgsrc = str_replace("\\","/",$imgsrc);
                $img = Image::open($imgsrc);
                $img->thumb(800,600,Image::THUMB_CENTER)->save(ART_S_IMG.$info->getSaveName());
                return $imgsrc;
            }else{
                return false;
            }
        }
    }
    /**
     * @Description: 上传图片并压制缩略图 参数是Form中file的name
     * @data: 下午 3:40 2017/11/8 0008
     */
    protected function uploadHeadimg($filename){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file($filename);

        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(HEAD_IMG);
            if($info){
                $imgsrc = HEAD_IMG.$info->getSaveName();
                $imgsrc = str_replace("\\","/",$imgsrc);
                $img = Image::open($imgsrc);
                $img->thumb(1024,1024,Image::THUMB_SCALING)->save(HEAD_IMG.$info->getSaveName());
                return $imgsrc;
            }else{
                return false;
            }
        }
    }
    /**
     * @Description: 上传图片并压制缩略图 参数是Form中file的name
     * @data: 下午 3:40 2017/11/8 0008
     */
    protected function uploadHeadimg1($filename){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file($filename);

        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(HEAD_IMG);
            if($info){
                $imgsrc = HEAD_IMG.$info->getSaveName();
                $imgsrc = str_replace("\\","/",$imgsrc);
                $img = Image::open($imgsrc);
                $img->thumb(800,600)->save(HEAD_IMG.$info->getSaveName());
                return $imgsrc;
            }else{
                return false;
            }
        }
    }
    public  function exportToExcel($filename, $tileArray=[], $dataArray=[]){
        ini_set('memory_limit','1024M');
        ini_set('max_execution_time',0);
        ob_end_clean();
        ob_start();
        header("Content-Type: text/csv");
        header("Content-Disposition:filename=".$filename);
        $fp=fopen('php://output','w');
        fwrite($fp, chr(0xEF).chr(0xBB).chr(0xBF));//转码 防止乱码(比如微信昵称(乱七八糟的))
        fputcsv($fp,$tileArray);
        foreach ($dataArray as $item) {

            fputcsv($fp,$item);
        }

        ob_flush();
        flush();
        ob_end_clean();
    }
    function showimg($imgsrc){
        $imgsrc = base64_decode($imgsrc);
        $this->assign("imgsrc",$imgsrc);
        return $this->fetch("/phone");
    }
}