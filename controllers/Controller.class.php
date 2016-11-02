<?php

class Controller extends Smarty
{
	public function __construct()
	{
		// 配置smarty
		$this->setTemplateDir(VIEWS)  //模板
		       ->setCompileDir(VIEWS_C)  //编译
		       ->setConfigDir(CONFIGS)  //配置
		       ->setCacheDir(CACHES);  //缓存
		$this->left_delimiter =  LEFT_D;
		$this->right_delimiter = RIGHT_D;
		$this->caching = CACHING;
		$this->cache_lifetime = CACHE_LIFETIME;
	}

	// 跳转
	public function redirect($message, $url=null)
	{
		echo "<script>alert('{$message}')</script>";
		if (empty($url)) {
			echo "<script>history.back()</script>";
		}else{
			echo "<script>location.href='{$url}'</script>";
		}
	}

	// 处理调用不存在的方法
	public function __call($fun, $params)
	{
		header("HTTP/1.0 404 not found");
		echo "<h1>404 NOT FOUND</h1>";
		exit;
	}
}