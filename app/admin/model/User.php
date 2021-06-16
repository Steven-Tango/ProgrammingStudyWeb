<?php
    namespace app\admin\model;

    use think\Model;

    class User extends Model{
        /* 防止数据库非法传入数据 */
        protected $table="admin_user";
        protected $schema=[
            "id"=>"int",
            "name"=>"string",
            "pwd"=>"string",
            "createtime"=>"datetime",
            "lastlogin"=>"datetime"
        ];

        public static function select_by_name($name){
            return User::where('name',$name)->findOrEmpty();
        }

        public static function add_login($id){
            $user=User::where('id',$id)->find();
            if($user){
                $user->lastlogin=date("Y-m-d H:i:s");
                $user->save();
            }
        }
        /* 重置密码 */
        public static function resetPwd($userid,$pwd="1234"){
            $user=User::where('id',$userid)->findOrEmpty();
            if($user){
                $user->pwd=md5($pwd);
                $user->save();
                return true;
            }
            return false;
        }
        /* 查询 */
        public static function select_all($search=null){
            if($search){
                return User::where("name",'like',"%".$search."%")->select();
            }
            return User::select();
        }

        public static function create_user($username,$pwd){
            $user=new User;
            if(User::where('name',$username)->find()){
                return "账户名已存在";
            }
           $user=new User;
           $user->save([
               'name'=>$username,
               'pwd'=>md5($pwd)
           ]);
           return $user->id;
        }
        /* 删除账户 */
        public static function delUser($userid){
            $user=User::where('id',$userid)->find();
            if(!$user) return 0;
            $user->delete();
            return true;
        }
    }
?>