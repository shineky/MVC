<?php

// 首页控制器
class IndexController
{
	public function index()
	{	
		global $smarty;
		$smarty->display('Index/index.html');
	}
}