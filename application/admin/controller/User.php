<?php
/**
 * Created by PhpStorm.
 * User:  barry
 * Email: 530027054@qq.com
 * Date:  2018/7/20
 * Time:  17:51
 */

namespace app\admin\controller;


use app\admin\model\UserModel;
use app\admin\model\UserValidate;

class User extends Base
{
    protected $User;
    protected $UserValidate;
    public function __construct()
    {
        $this->needUser=false;
        parent::__construct();
        $this->User=new UserModel();
        $this->UserValidate=new UserValidate();
    }

    public function login(){
//        $rec = $_POST;
        if(isset($_POST['username'])){
            $rec = $_POST;
        }else{
            $request_data = file_get_contents ('php://input');
            $rec = json_decode ($request_data,true);
        }

        $res = $this->UserValidate->check($rec, '', 'login');
        if ($res) {
            $rec['password']=md5($rec['password']);
            $result=$this->User->where('username','=',$rec['username'])->where('password','=',$rec['password'])->field('name,roles')->find();
            if($result){
                session('user',$result);
                return $this->SuccessReturn("success",$result);
            }else{
                return $this->ErrorReturn($this->User->getError());
            }
        } else {
            return $this->ErrorReturn($this->UserValidate->getError());
        }
    }

    public function logout(){
        unsetUser();
        return $this->SuccessReturn();
    }

    public function info(){
        if(getUser()){
            return $this->SuccessReturn('success',getUser());
        }else{
            return $this->ErrorReturn('获取信息失败');
        }
    }

}