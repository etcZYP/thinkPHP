<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Responese;
class teacher extends Controller{

	public function index()
	{
		return view();
	}

	public function check()
	{
		session_start();
		$username=$_POST['username'];
		$password=$_POST['password'];
		$_SESSION['tno']=$username;
		$data=Db::table("teacher")->field("userid")->where("tno","=",$username)->select();
		 $arr=[["userid"=>$password]];
		if($arr==($data))
		{
			$this->success("教师登录成功",url('teacher/t_login_success'));
		}
		else
		{
			$this->error("教师登录失败");
		}

	}
	public function t_login_success()
	{
		session_start();
		$tno=$_SESSION['tno'];
		$_SESSION['tno']=$tno;
		$teacher=Db::query('select * from teacher where tno=?',[$tno]);
		$data=Db::query('select * from course join cou_class using (cno) where tno=?',[$tno]);
		$this->assign("teacher",$teacher);
		$this->assign("data",$data);
		return view();
	}
	public function create()
	{
		return view();
	}
	public function insert_grade(Request $request)
	{
		$data=input("post.");
		dump($data);
		$code=Db::execute("insert into sc value(:sno,:cou_classno,:cname,:credit,:type,:state,:usual_score,:final_score,:grade)",$data);
		dump($code);
		if($code)
		{
			$this->success("添加成功","t_login_success");
		}
		else
		{
			$this->error("添加失败");
		}


	}
	public function  delete()
	{
		$data=Db::execute("delete from sc where sno='s5'");
		dump($data);
	}
	public function allstudent()
	{
		session_start();
		$tno=$_SESSION['tno'];
		$data=Db::query('select * from sc,cou_class where sc.cou_classno=cou_class.cou_classno and tno=?',[$tno]);
		$this->assign("data",$data);
		return view();
	}
	public function allclass()
	{
		session_start();
		$tno=$_SESSION['tno'];
		$data=Db::query('select * from course join cou_class using (cno) where tno=?',[$tno]);
		$this->assign("data",$data);
		return view();
	}
	public function allproportion()
	{
		session_start();
		$tno=$_SESSION['tno'];
		$data=Db::query('select cou_class.cou_classno,usual_grade,final_grade from cou_class,grade_set,teacher where teacher.tno=cou_class.tno and cou_class.cou_classno=grade_set.cou_classno and cou_class.tno=?',[$tno]);
		$this->assign("data",$data);
		return view();
	}
	public function not_class()
	{

		session_start();

		$tno=$_SESSION['tno'];
		$data=Db::query('select * from sc,cou_class where cou_class.cou_classno=sc.cou_classno and grade is null and tno=?',[$tno]);

		$this->assign("data",$data);
		return view();
	}
	public function edit()
	{
		$sno=$_GET['sno'];
		session_start();
		$tno=$_SESSION['tno'];
		$data=Db::query('select * from sc,cou_class where cou_class.cou_classno=sc.cou_classno and grade is null and tno=? and sno=?',[$tno,$sno]);
		$this->assign("data",$data);
		return view();
	}
	public function update_grade(Request $request)
	{
		$data=Request::instance()->only(['usual_score','final_score','grade','sno','cou_classno']);
		$usual_score=$data["usual_score"];
		$final_score=$data["final_score"];
		$grade=$data["grade"];
		$sno=$data["sno"];
		$cou_classno=$data["cou_classno"];
		dump($usual_score);dump($final_score);dump($grade);dump($sno);
		$code=Db::execute('update sc set usual_score=?,final_score=?,grade=? where sno=? and cou_classno=? ',[$usual_score,$final_score,$grade,$sno,$cou_classno]);
		dump($code);
		if($code)
		{
			$this->success("成绩更新成功","not_class");
		}
		else
		{
			$this->error("成绩更新失败");
		}

	}

}

?>
