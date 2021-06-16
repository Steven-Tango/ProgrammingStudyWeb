<?php
namespace app\admin\controller;

use app\admin\model\Course;
use app\admin\model\UserC;
use app\admin\model\User;
use app\BaseController;

class Api extends BaseController
{
    public function initialize()
    {
        session_start();
        if (!$_SESSION['userid']) {
            return $this->redirectTo('login.html');
        }
        $this->userid = $_SESSION['userid'];
    }
    /* 创建账户 */
    public function createUser()
    {
        $res = request()->post();
        if (!isset($res['username']) || !isset($res['pwd'])) {
            return $this->res_json(500, "参数传递错误");
        }
        $roles = [
            "username" => "require",
            "pwd" => "require",
        ];
        $msgs = [
            "username.require" => "账户为空",
            "pwd.require" => "密码为空",
        ];
        try {
            $this->validate($res, $roles, $msgs);
        } catch (\Exception $e) {
            return $this->res_json(500, $e->getError());
        }
        $user = User::create_user($res["username"], $res["pwd"]);
        if (!$user) {
            return $this->res_json(500, "添加失败,账户名已存在");
        }

        return $this->res_json(200, "添加成功");
    }
    /* 重置密码 */
    public function resetPwd()
    {
        $res = request()->post();
        if (!isset($res['userid'])) {
            return $this->res_json(500, "未传入需要修改的密码");
        }
        if (User::resetPwd($res['userid'])) {
            return $this->res_json(200, "重置密码成功");
        }
        return $this->res_json(500, "重置密码失败");
    }
    /* 删除账户 */
    public function delUser()
    {
        $res = request()->post();
        if (!isset($res['userid'])) {
            return $this->res_json(500, "未传入需要删除的账户");
        }
        if (User::delUser($res['userid'])) {
            return $this->res_json(200, "删除账户成功");
        }
        return $this->res_json(500, "删除账户失败");
    }
    /* 添加课程 */
    public function addCourse(){
        $res=request()->post();
        // 验证？？
        if(Course::course_add($res,$this->userid)){
            return $this->res_json(200,"新增成功");
        }else{
            return $this->res_json(500,"该课程名称已存在");
        }        
    }

    /* 文件上传 */
    public function upload(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 上传到本地服务器
        $savename = \think\facade\Filesystem::putFile('courseImg', $file);
        return $this->res_json(200,$savename);
    }
    /* 修改课程 */
    public function changeCourse(){
        $res=request()->post();
        if(Course::course_change($res,$this->userid)){
            return $this->res_json(200,"修改成功");
        }else{
            return $this->res_json(500,"修改失败，请联系管理");
        }
    }
    public function courseById(){
        $id=request()->post("id");
        if($id){
            $db_res=Course::select_by_id($id);
            return $this->res_json(200,"查询成功",$db_res);
        }
        return $this->res_json(500,"未传入courseID值");
    }

    /* 删除课程 */
    public function delCour()
    {
        $res = request()->post();
        if (!isset($res['courseid'])) {
            return $this->res_json(500, "未传入需要删除的课程");
        }
        if (Course::delCour($res['courseid'])) {
            return $this->res_json(200, "删除课程成功");
        }
        return $this->res_json(500, "删除课程失败");
    }
    /* 重置用户名密码 */
    public function resetUserPwd()
    {
        $res = request()->post();
        if (!isset($res['userid'])) {
            return $this->res_json(500, "未传入需要修改的密码");
        }
        if (UserC::resetUserPwd($res['userid'])) {
            return $this->res_json(200, "重置密码成功");
        }
        return $this->res_json(500, "重置密码失败");
    }
    /* 添加用户 */
    public function createUsers()
    {
        $res = request()->post();
        if (!isset($res['username']) || !isset($res['pwd']) || !isset($res['tel'])) {
            return $this->res_json(500, "参数传递错误");
        }
        $roles = [
            "username" => "require",
            "pwd" => "require",
            "tel" => "require",
        ];
        $msgs = [
            "username.require" => "用户为空",
            "pwd.require" => "密码为空",
            "tel.require" => "电话为空",
        ];
        try {
            $this->validate($res, $roles, $msgs);
        } catch (\Exception $e) {
            return $this->res_json(500, $e->getError());
        }
        $user = UserC::create_user($res["username"], $res["pwd"],$res["tel"]);
        if (!$user) {
            return $this->res_json(500, "添加失败,用户名已存在");
        }

        return $this->res_json(200, "添加成功");
    }
    /* 删除用户 */
    public function delUsers()
    {
        $res = request()->post();
        if (!isset($res['userid'])) {
            return $this->res_json(500, "未传入需要删除的课程");
        }
        if (UserC::delUsers($res['userid'])) {
            return $this->res_json(200, "删除用户成功");
        }
        return $this->res_json(500, "删除用户失败");
    }

}
