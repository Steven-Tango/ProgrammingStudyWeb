<?php
namespace app\admin\model;

use think\model;

class Course extends Model
{
    /* 定义课程表 */
    protected $table = "course";
    /* 定义课程表中的字段 */
    protected $schema = [
        "id" => "int",
        "coursename" => "string",
        "coursedesc" => "string",
        "courseimg" => "string",
        "createtime" => "datetime",
        "admin_id" => "int",
    ];
    public static function select_by_name($name)
    {
        // 使用模型进行数据库查询coursename名等于传入的$coursename 查询出一条结果或者没有
        return Course::where('coursename', $coursename)->findOrEmpty();
    }
    /* 添加创建时间方法 */
    public static function add_createTime($id)
    {
        $course = Course::where("id", $id)->find();
        if ($course) {
            $course->createtime = date("Y-m-d H:i:s");
            $course->save();
        }
    }

    //连接admin_user表
    public function user()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
    /* 查询 */
    public static function select_all($search = null)
    {
        if ($search) {
            return Course::where("coursename", 'like', "%" . $search . "%")->select();
        }
        return Course::select();
    }

   /*新增课程 */
    public static function course_add($course,$admin_id){
        $db_res=Course::where("coursename",$course['coursename'])->find();
        if($db_res){
            return false;
        }
        $c=new Course;
        $c->save([
            "coursename"=>$course['coursename'],
            "coursedesc"=>$course['coursedesc'],
            "courseimg"=>$course['courseimg'],
            "createtime"=>date("Y-m-d H:i:s"),
            "admin_id"=>$admin_id
        ]);
        return $c->id;
    }

    
    /* 修改课程 */
    public static function course_change($course,$admin_id){
        $db_res=Course::where("id",$course['id'])->find();
        if(!$db_res){
            return false;
        }
        $db_res2=Course::where("coursename",$course['coursename'])->where("id","<>",$db_res->id)-find();
       
        if($db_res2){
            return false;
        }
        $c=$db_res;
        $c->save([
            "coursename"=>$course['coursename'],
            "coursedesc"=>$course['coursedesc'],
            "courseimg"=>$course['courseimg'],
            "createtime"=>date("Y-m-d H:i:s"),
            "admin_id"=>$admin_id
        ]);
        
        return $c->id;
    }

    public static function select_by_id($id){
        return Course::where("id",$id)->find();
    }
    /* 删除课程 */
    public static function delCour($courseid)
    {
        $course = Course::where('id', $courseid)->find();
        if (!$course) {
            return 0;
        }

        $course->delete();
        return true;
    }

    
}
