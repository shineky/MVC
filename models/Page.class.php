<?php
	/*
		数据分页类
		@author shineky
		@date 2016-10-9		
	*/
	class Page{
		protected $totalRows;
		protected $pageSize;
		protected $page;
		protected $totalPages;
		protected $url;

		// 构造方法 传入参数
		public function __construct($totalRows,$pageSize=10){
			$this->totalRows = $totalRows;
			$this->pageSize = $pageSize;
			// 计算总页数
			$this->getTotalPages();
			// 获取当前页 页码
			$this->getPage();
			// 获取URL 给翻页按钮
			$this->getUrl();
		}

		// 生成分页按钮 html内容
		public function show(){
			$html = "<div class='col-md-4'><nav><ul class='pagination' style='margin:0'><li><a href='{$this->url}?p=1'>&laquo;</a></li>";
			$html .= "<li><a href='{$this->url}?p=".($this->page-1)."&name=".@$_GET['name']."'>&lt;</a></li>";
			$html .= "<li><a href='javascript:void(0)'>{$this->page}/{$this->totalPages}</a></li>";
			$html .= "<li><a href='{$this->url}?p=".($this->page+1)."&name=".@$_GET['name']."'>&gt;</a></li>";
			$html .= "<li><a href='{$this->url}?p={$this->totalPages}'>&raquo;</a></li></ul></nav></div>";
			$html .= "<div class='col-md-4'></div><div class='col-md-4'><button class='btn btn-primary' type='button'>共 <span class='badge'>{$this->totalRows}</span> 条数据</button></div>";
			return $html;
		}

		// 生成limit条件
		public function limit(){
			// (页码-1)*单页条数,每页条数
			// limit  ($this->page-1)*$this->pageSize,$this->pageSize
			return ($this->page-1)*$this->pageSize.",".$this->pageSize;
		}

		// 计算总页数
		public function getTotalPages(){
			$this->totalPages = ceil($this->totalRows/$this->pageSize);
		}
		// 获取当前页
		protected function getPage(){
			// 从get中获取页码
			$page = empty($_GET['p'])?1:$_GET['p'];
			// 判断范围
			if ($page<1) {
				$page = 1;
			}
			if ($page>$this->totalPages) {
				$page=$this->totalPages;
			}
			$this->page = $page;
		}

		//获取当前url
		protected function getUrl(){
			$this->url = $_SERVER['PHP_SELF'];
		} 
	}
	
?>