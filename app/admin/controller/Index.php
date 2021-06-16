<?php

namespace app\admin\controller;

use app\admin\model\Course;
use app\admin\model\UserC;
use app\admin\model\User;
use app\BaseController;
use think\facade\View;

class Index extends BaseController
{
    private $userid;
    protected function initialize()
    {
        session_start();
        if (!isset($_SESSION['userid'])) {
            return $this->redirecTo('login.html');
        }
        $this->userid = $_SESSION['userid'];
        View::assign([
            "username" => $_SESSION['username'],
            "name" => $_SESSION['name'],
        ]);
        /* 菜单被选中方法 */
        $action = request()->action();
        View::assign("action", $action);
    }

    public function index()
    {
        return view();
    }
    /* 账户控制器 */
    public function userController()
    {
        $res = request()->get();
        if (isset($res['search'])) {
            $db_res = User::select_all($res['search']);
        } else {
            $db_res = User::select_all();
        }

        View::assign("userlist", $db_res);
        return view();
    }

    /* 课程控制器*/
    public function course()
    {

        $res = request()->get();
        if (isset($res['search'])) {
            $courses = Course::select_all($res['search']);
        } else {
            $courses = Course::select_all();
        }
        View::assign("courseList", $courses);
        return view();
        /*  $courses = Course::select_all();
    View::assign("couseList", $courses);
    return view(); */
    }

    /* 用户控制器 */
    public function userContro()
    {
        $res = request()->get();
        if (isset($res['search'])) {
            $users = UserC::select_all($res['search']);
        } else {
            $users = UserC::select_all();
        }
        View::assign("usersList", $users);
        return view();
    }

}
