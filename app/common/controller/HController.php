<?php
/**
 * 2017年9月26日
 * 后台控制器 包含权限管理
 * @author 李桦
 */

namespace app\common\controller;

use think\facade\Session;

class HController extends SController
{
    protected $userinfo = [];
    function __construct($app)
    {
        parent::__construct($app);
        $usersession = Session::get(USERSESSIONNAME);
        if ($usersession != null) {
            $distime = 7200;
            $this->assign("username", $usersession ["username"]);
            if ($usersession ["outtime"] != "") {
                if ($usersession ["outtime"] < time()) {
                    $this->loginout("登录时间已超出，请重新登录。");
                }else{
                    $usersession ["outtime"] = time() + $distime;
                }
            } else {
                $usersession ["outtime"] = time() + $distime;
                Session::set(USERSESSIONNAME, $usersession);
            }
        } else {
            $this->loginout("请您先登录。");
        }

        $this->checkuserpowermenu();
        $power = Session::get(USERSESSIONNAME)["uslevel"];
        if ($power != 10 and $power != 100) {
            $this->assign("lmenu", $this->getUserPowerView()->getPowerMenu());
        } else {
            $this->assign("lmenu", $this->getApowertype()->getAll());
        }

        $this->assign("usernums", $this->getUsers()->getUserNums());

        $mcount = Message::getInstance()->where("toid", "eq", $usersession["id"])->where("status", "eq", 1)->count();
        if ($mcount > 0) {
            $this->assign("hasnewmessage", 1);
        }
        $this->userinfo = $this->getCurUser();
        $org = Organization::getInstance()->where("id",$this->userinfo["arid"])->find();
        $this->assign("orgname",$org["name"]);
    }

    private function getInfo($info)
    {
        return "<div style='color:#2ED62E;width:100%;text-align:center;'>" . $info . "</div>";
    }

}