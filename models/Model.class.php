<?php
	/*
		数据库操作类
		@auther shineky
		@date 2016-10-09
	*/ 
	class Model{
		private $link = null; //数据库的链接资源，这样写便于开启与关闭
		private $tabName = null; //数据库表名
		private $pk; //主键字段名
		private $fieldList = array(); //字段列表
		private $where; 	//条件
		private $fields; 	//字段
		private $order; 	//排序
		private $limit;     //分页

		/*
			构造方法 链接数据库
			@param string $tabName
		*/
		public function __construct($tabName){
			// 链接数据库
			$this->link = mysqli_connect(HOST,USER,PWD) or die('数据库链接失败');
			// 选择数据库
			mysqli_select_db($this->link,DBNAME);
			// 设置字符集编码
			mysqli_set_charset($this->link,'utf8');
			// 通过参数给属性赋值，告诉链接，链到哪张表
			$this->tabName = $tabName;
			// 获取主键
			$this->getFieldList();
		}

		/*
			查询所有数据
			@return array $data 返回二维数组
		*/
		public function select(){
			// 判断有无字段条件
			$fields = "*";
			if(!empty($this->fields)){
				$fields = $this->fields;
				// 清除上一次赋予的条件
				$this->fields = null;
			}
			// 判断有无查询条件
			$where = '';
			if(!empty($this->where)){
				$where = " where ".$this->where;
				// 清除上一次赋予的条件
				$this->where = null;
			}
			// 判断有无排序条件
			$order ='';
			if(!empty($this->order)){
				$order = " order by ".$this->order;
				// 清除上一次赋予的条件
				$this->order = null;
			}
			// 判断有无分页条件
			$limit = '';
			if(!empty($this->limit)){
				$limit = " limit ".$this->limit;
				// 清除上一次赋予的条件
				$this->limit = null;
			}

			// 定义sql
			$sql = "select {$fields} from {$this->tabName} {$where} {$order} {$limit}";
			// echo $sql;exit;
			// 返回查询结果
			return $this->query($sql);
		}

		/*
			查询单条数据
			@param string $pkValue
			@return array $data 一维数组
		*/
		public function find($field,$pkValue){
			// 定义sql;
			$sql = "select * from {$this->tabName} where {$field}='{$pkValue}' limit 1";
			// echo $sql;exit;
			// 执行sql
			$data = $this->query($sql);
			if(empty($data)){
				return false;
			}
			return $data[0];
		}

		/*
			删除一条数据
			@param string $value
		*/
		public function delete($value){
			// 定义sql
			$sql = "delete from {$this->tabName} where id='{$value}'";
			mysqli_query($this->link,$sql);
			// 判断是否成功
			return mysqli_affected_rows($this->link);
		}

		/*
			数据的修改
			@param array $data $_POST
			@return int 影响行数
		*/
		public function save($data = array()){
			if (empty($data)) {
				$data = $_POST;
			}
			if (empty($data['pass'])) {
				return false;
			}else{
				$data['pass']=md5($data['pass']);//将密码加密处理
			}

			$data['updatetime']=time();//获取当前时间戳

			// 对POST进行匹配
			foreach ($data as $k => $v) {
				if (in_array($k,$this->fieldList) && $k!=$this->pk) {
					$values[] = "{$k}='{$v}'";
				}
			}

			// 定义sql
			$sql = "update {$this->tabName} set ".implode(',',$values)." where {$this->pk}='{$data[$this->pk]}'";
			// echo $sql;edit;
			mysqli_query($this->link,$sql);
			// 返回修改成功的行数
			return mysqli_affected_rows($this->link);
		}



		/*
			执行添加
			@param array $data 要添加的数据 从$_POST获取
			@return 自增ID
		*/
		public function add($data = array()){
			// 如果$data为空 赋值$_POST
			if (empty($data)) {
				$data = $_POST;
			}
			$data['pass']=md5($data['pass']);//将密码加密处理
			$data['addtime']=time();//获取当前时间戳
			// var_dump($data);die;
			$fields = array();//存放满足条件的字段
			$values = array();//存在满足条件的值

			foreach ($data as $k => $v) {
				if (in_array($k,$this->fieldList)) {
					$fields[] = $k;
					$values[] = $v;
				}
			}

			// 定义sql implode($arr,',')
			$sql = "insert into {$this->tabName} (".implode(',',$fields).")values('".implode("','",$values)."')";
			// var_dump($sql);die;
			// 发送执行
			mysqli_query($this->link,$sql);
			// 添加成功后，返回被添加的数据的自增id
			return mysqli_insert_id($this->link);
		}

		/*
			自动获取主键
		*/ 
		private function getFieldList(){
			// 定义sql
			$sql = "desc {$this->tabName}";
			// 发送sql
			$result = mysqli_query($this->link,$sql);
			// 处理结果集
			$fieldList = array();
			while($rows = mysqli_fetch_assoc($result)){
				$fieldList[] = $rows['Field'];
				if($rows['Key'] == 'PRI'){
					$this->pk = $rows['Field'];
				}
			}

			// 给属性赋值
			$this->fieldList = $fieldList;
		}

		/*
			fields 设置要查询的字段
			@param string $fields 查询的字段
			@return object $this
		*/ 
		public function fields($fields){
			$this->fields = $fields;
			return $this;
		}

		/*
			where 设置要查询的字段
			@param string $where 查询的字段
			@return object $this
		*/ 
		public function where($where){
			$this->where = $where;
			return $this;
		}

		/*
			order 设置要查询的字段
			@param string $order 查询的字段
			@return object $this
		*/ 
		public function order($order){
			$this->order = $order;
			return $this;
		}

		/*
			limit 设置要查询的字段
			@param string $limit 查询的字段
			@return object $this
		*/ 
		public function limit($limit){
			$this->limit = $limit;
			return $this;
		}



		/*
			直接解析sql 查询操作
			@param $sql sql语句
			@return array 二维数组
		*/
		private function query($sql){
			// 发送sql执行
			$result = mysqli_query($this->link,$sql);
			// 判断处理结果集
			if(!$result){
				return false;
			}

			$data = array(); //定义空数组

			// 进行遍历 结果集 将资源变为数组
			while($rows = mysqli_fetch_assoc($result)){
				$data[] = $rows;
			}

			// 释放结果集
			mysqli_free_result($result);
			// 返回数据
			return $data;
		}

		// 计算总条数
		public function count(){
			// 判断有无查询条件
			$where = "";
			if (!empty($this->where)) {
				$where = " where ".$this->where;
				// 清除条件
				$this->where=null;
			}
			$sql = "select count(*) from {$this->tabName} {$where}";
			$result = $this->query($sql);
			return $result[0]["count(*)"];
		}
		

		/*
			析构方法 关闭资源
		*/ 
		public function __destruct(){
			mysqli_close($this->link);
		}
	}
?>