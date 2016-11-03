<?php

// 用户控制器
class UserController extends Controller
{	
	// 存放模型对象
	private $model = null;
	// 构造方法
	public function __construct()
	{
		// 调用 父级的构造方法
		parent::__construct();
		$this->model = new Model('users');
	}

	public function index()
	{	
		if (empty($_POST['names'])) {
	      $where = "";
	    }else{
	      $where = "name like '%$_POST[names]%'";
	    }

		$page = new Page($this->model->where($where)->count(),8);
		$list = $this->model->where($where)->limit($page->limit())->select();
		$fenye = $page->show();
		$this->assign('title','HWmall用户列表');
		$this->assign('list',$list);
		$this->assign('fenye',$fenye);
		$this->display('User/index.html');
	}

	// 执行删除
	public function del()
	{
		if ($this->model->delete($_GET['id'])) {
			$this->redirect('恭喜您，删除成功！','./index.php?c=User');
		} else {
			$this->redirect('Sorry，删除失败！','./index.php?c=User');
		}
	}

	// 加载添加表单
	public function add()
	{
		$this->assign('title','HWmall添加用户');
		$this->display('User/add.html');
	}

	// 执行添加
	public function insert()
    {
        if ($this->model->add() > 0) {
            $this->redirect('恭喜您，添加成功！','./index.php?c=User');
        } else {
            $this->redirect('Sorry，添加失败！');
        }
    }

    //加载编辑表单
    public function edit()
    {
        $data = $this->model->find('id',$_GET['id']);
        $this->assign('title','HWmall编辑用户');
        $this->assign('data',$data);
        $this->display('User/edit.html');
    }

    //执行添加
    public function update()
    {
        if ($this->model->save()) {
            $this->redirect('恭喜您,修改成功！','./index.php?c=User');
        } else {
            $this->redirect('Sorry，修改失败！');
        }
    }
}