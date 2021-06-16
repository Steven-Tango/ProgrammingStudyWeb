<?php
    namespace app\admin\model;
    use think\model;
    
    class UserC extends Model 
    {
            /* 定义用户表 */
            protected $table = "user";
            /* 定义用户表中的字段   防止数据库非法传入数据*/
            protected $schema = [
                "id" => "int",
                "name" => "string",
                "pwd" => "string",
                "createtime" => "datetime",
                "lastlogin" => "datetime",
                "tel" => "int",
            ];

            /* 查询所有 */
            public static function select_all($search = null)
        {
            if ($search) {
                return UserC::where("name", 'like', "%" . $search . "%")->select();
            }
            return UserC::select();
        }

        /* 创建用户 */
        public static function create_user($name,$pwd,$tel){
            $user=new UserC;
            if(UserC::where('name',$name)->find()){
                return "账户名已存在";
            }
           $user=new UserC;
           $user->save([
               'name'=>$name,
               'pwd'=>md5($pwd),
               'tel'=>$tel
           ]);
           return $user->id;
        }

        /* 根据姓名查找 */
        public static function select_by_name($name){
            return UserC::where('name',$name)->findOrEmpty();
        }
        /* 添加登录时间 */
        public static function add_Login($id){
            $user=UserC::where("id",$id)->find();
            if($user){
                $user->lastlogin=date("Y-m-d H:i:s");
                $user->save();
            }
        }
        /* 重置用户名密码 */
        public static function resetUserPwd($userid,$pwd="123456789"){
            $user=UserC::where("id",$userid)->findOrEmpty();
            if($user){
                $user->pwd=md5($pwd);
                $user->save();
                return true;
            }
            return false;
        }
        /* 删除用户 */
        public static function delUsers($userid){
            $user=UserC::where('id',$userid)->find();
            if(!$user) return 0;
            $user->delete();
            return true;
        }
    }
    
?>
