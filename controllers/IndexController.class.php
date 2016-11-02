<?php

// 首页控制器
class IndexController extends Controller
{
	public function index()
	{	
		$this->display('Index/index.html');
	}
}