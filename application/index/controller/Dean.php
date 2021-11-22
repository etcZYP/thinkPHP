<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Responese;
class dean extends Controller{
    public function allstudent()
    {
        $data=Db::query('select * from sc');
        $this->assign("data",$data);
        return view();
    }
    public function index()
    {
        return view();
    }


    public function check()
    {
        session_start();
        $username=$_POST['username'];
        $password=$_POST['password'];
        $_SESSION['dean_no']=$username;
        $data=Db::table("dean")->field("userid")->where("dean_no","=",$username)->select();
        $arr=[["userid"=>$password]];
        if($arr==($data))
        {
            $this->success("教务员登录成功","d_login_success");
        }
        else
        {
            $this->error("教务员登录失败");
        }

    }

    public function d_login_success()
    {
        session_start();
        $dean_no=$_SESSION['dean_no'];
        $_SESSION['dean_no']=$dean_no;
        $dean=Db::query('select * from dean where dean_no=?',[$dean_no]);
        $this->assign("dean",$dean);
        return view();
    }
    public function edit_grade()
    {
        $sno=$_GET['sno'];
        $cou_classno=$_GET['cou_classno'];
        session_start();
        $data=Db::query('select * from sc where sno=? and cou_classno=?',[$sno,$cou_classno]);
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
        $data = Request::instance()->only(['usual_score', 'final_score', 'grade', 'sno', 'cou_classno']);
        $usual_score = $data["usual_score"];
        $final_score = $data["final_score"];
        $grade = $data["grade"];
        $sno = $data["sno"];
        $cou_classno = $data["cou_classno"];
        dump($usual_score);
        dump($final_score);
        dump($grade);
        dump($sno);
        dump($cou_classno);
        $code = Db::execute('update sc set usual_score=?,final_score=?,grade=? where sno=? and cou_classno=? ', [$usual_score, $final_score, $grade, $sno, $cou_classno]);
        dump($code);
        if ($code) {
            $this->success("成绩更新成功", "allstudent");
        } else {
            $this->error("成绩更新失败");
        }
    }
        public function done()
    {
        $data=Db::query('select * from cou_class natural join course where cou_class.cno=course.cno and tno is not null');
        $this->assign("data",$data);
        return view();
    }
    public function undone()
    {
        $data=Db::query('select * from cou_class natural join course where cou_class.cno=course.cno and tno is  null');
        $this->assign("data",$data);
        return view();
    }
    public function edit_teacher()
    {
        $cou_classno=$_GET['cou_classno'];
        $data=Db::query('select * from cou_class natural join course where cou_class.cno=course.cno and tno is null and cou_classno=?',[$cou_classno]);
        $this->assign("data",$data);
        return view();
    }
    public function update_teacher(Request $request)
    {
        $data = Request::instance()->only(['tno', 'cou_classno']);
        $tno = $data["tno"];
        $cou_classno = $data["cou_classno"];
        dump($tno);dump($cou_classno);
        $code = Db::execute('update cou_class set tno=? where cou_classno=?', [$tno, $cou_classno]);
        if ($code) {
            $this->success("课程分配成功", "undone");
        } else {
            $this->error("课程分配失败");
        }
    }
    public function report()
    {
        $data=Db::query('select cou_classname,avg(grade) as avg_grade,number,sum(case when grade>=60 then 1 else 0 end) as pass from sc,cou_class,class_class where sc.cou_classno=cou_class.cou_classno and cou_class.cou_classno=class_class.classno group by sc.cou_classno; ');
        $this->assign("data",$data);
        return view();
    }
    public function find()
    {
        return view();
    }
    public function do_find()
    {
        $sno=$_POST['sno'];
        $data=Db::table("sc")->where("sno","=",$sno)->select();
        $this->assign("data",$data);
        return view();
    }

}