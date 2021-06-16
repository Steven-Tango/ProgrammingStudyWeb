<?php

namespace app\admin\controller;
use app\BaseController;
use app\admin\model\User;

class Login extends BaseController{
    public function index(){
        return view();
    }
    public function login(){
        /* 1. 得到用户输入的内容 */
        $res=request()->post();
        /* 2. 验证输入的内容是否有误 */
        //验证规则
         $roles=[
            "name"=>"require|length:4,30",
            "pwd"=>"require|length:4,20",
            "code"=>"require",
        ];
        //如果没有满足应该抛出的异常
        $msg=[
            "name.require"=>"账户名为空",
            "name.length"=>"账户名长度为4到30",
            "pwd.require"=>"密码为空",
            "pwd.length"=>"密码长度为4到20",
            "code.require"=>"验证码为空"
        ];

        try {
            $this->validate($res,$roles,$msg);
        } catch (ValidateException $e) {
            return $this->res_json(500,$e->message);
        } catch (\Exception $e){
            return $this->res_json(500,$e->getError());
        }

        /* 3.验证验证码是否正确 */
        if(!captcha_check($res['code'])) return $this->res_json(500,"验证码错误");

        /* 4.验证数据库 */
        $user = User::select_by_name($res['name']);
            //1. 验证当前name是否存在数据库
            if(!$user) return $this->res_json(500,"账户不存在");
            //2. 验证当前密码和数据库保存密码是否相同
            if($user->pwd !=md5($res['pwd'])) return $this->res_json(500,"密码不正确");
            //验证成功后需要把当前时间存入登录时间里面
            User::add_login($user->id);
            //保存session
            session_start();
            $_SESSION['userid']=$user->id;
            $_SESSION['username']=$user->name;
            $_SESSION['name']="唐杰";
            return $this->res_json(200,"登录成功"); 
    }

    public function resetPwd(){
        $res=request()->post();
        if(!isset($res['pwd']) || !$res['pwd']){
            return $this->res_json(500,'未输入需要更改密码');
        }
        session_start();
        if(!isset($_SESSION['userid'])){
            return $this->res_json(501,'您并未登录，请重新登录');
        }
        if(!User::resetPwd($_SESSION['userid'],$res['pwd'])){
            return $this->res_json(502,'修改失败，请联系管理员');
        }
        return $this->res_json(200,'修改密码成功');
    }
    public function loginOut(){
        session_start();
        $_SESSION=array();
        session_destroy();
        return $this->res_json(200,'退出成功');
    }
}
?>