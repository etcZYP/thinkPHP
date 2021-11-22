<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Responese;
class student extends Controller{

    public function index()
    {
        return view();
    }
    public function check()
    {
        session_start();
        $username = $_POST['username'];
        $password = $_POST['password'];
        $_SESSION['sno'] = $username;
        $data = Db::table("student")->field("userid")->where("sno", "=", $username)->select();
        $arr = [["userid" => $password]];
        if ($arr == ($data)) {
            $this->success("学生登录成功","s_login_success");
        } else {
            $this->error("学生登录失败");
        }
    }
    public function s_login_success()
    {
        session_start();
        $sno=$_SESSION['sno'];
        $_SESSION['sno']=$sno;
        $student=Db::query('select * from student where sno=?',[$sno]);
        $data=Db::query('select * from sc where sno=?',[$sno]);
        $this->assign("student",$student);
        $this->assign("data",$data);
        return view();
    }

}