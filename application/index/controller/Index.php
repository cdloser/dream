<?php
namespace app\index\controller;
use think\Controller;
class Index extends Controller
{
    public function index()
    {
		
		$dlist=db('xuyuan')->field('id,type,typecolor,content,address,fromname,time')->order('id desc')->paginate(6);
		$page = $dlist->render();
		$lists=$dlist->toArray();
		$vtype=config('type');
		$tcolor=config('typecolor');
		foreach($lists['data'] as &$v)
		{
			$v['type']=$vtype[$v['type']];
			$v['typecolor']=$tcolor[$v['typecolor']-1];
			
		}
        $this->assign('page',$page);
		$this->assign('dlist',$lists);
        return $this->fetch();
    }

    public function newindex(){

     $dlist=db('xuyuan')->page(1,2)->select();
     $this->assign('dlist',$dlist);
     $countpage=ceil(db('xuyuan')->count()/2);
     $this->assign('countpage',$countpage);
     return $this->fetch();

    }

    public function getlist(){
     $page=input('page');
     $dlist=db('xuyuan')->page($page,2)->select();
     return json($dlist);
    
      


    }

	public function add(){
		$info=config('typecolor');
		$this->assign('name',$info);
		$list=config('type');
		$this->assign('list',$list);
		return $this->fetch();
	}

	public function do_add(){
		$data=input('post.');
		$type=config('type');
		// $color=config('typecolor');
		$types=array_search($data['type'],$type);
		// $colors=array_search($data['class'],$color);
		// $arr['types']=$types;
		// $arr['type']=$data['type'];
		// return json($arr);exit;
		$data['time']=time();
		$data['typecolor']=$data['class'];
		$data['type']=$types;
		$data['address']='山东省 临沂市';
		$captcha=$data['code'];
		unset($data['code']);
		unset($data['class']);
		$db=db('xuyuan');
		if(captcha_check($captcha)){
			$info=$db->insert($data);
			if($info){
				$result=[
					'msg'=>'许愿成功',
					'status'=>1,
				];
			}else{
				$result=[
					'msg'=>'许愿失败',
					'status'=>2,
				];	
			}
		}else{
			 $result=[
					'msg'=>'验证码错误',
					'status'=>3,
				];
		}
		return json($result);
	}
	public function checkenname(){
		 $captcha = input('post.names');
		if(!captcha_check($captcha)){
			$adata=[
				'status'=>1,
				'msg'=>'验证码错误',
			];
		}else{
			$adata=[
				'status'=>0,
				'msg'=>'验证码正确',
				];
		}
		return json($adata);
	}
	public function nei(){
		$id=input('id');
		// echo "$id";exit;
		$db=db('xuyuan');
		$info=$db->where('id='.$id)->find();
		$this->assign('name',$info);
		return $this->fetch();
	}
	public function fuxi(){
		$array1 = array ("a" => "green", "yellow","red", "blue", "red");
		$array2 = array ( "red");
		$result = array_diff ($array1, $array2);
		echo "<pre>";
		print_r($result);
	}
	
	public function read() //读取
	{
			$db=db('xuyuan');
			$id="1";
			$info=$db->where('Id='.$id)->select();
			$this->assign('info',$info);
			return $this->fetch();
	}

}
