<?php
Dao::$_cache['AppData'] = array();
//TODO: rule
class AppData { // 应用数据: appdata
	const TABLE_NAME = 'appdata';
	public static $_FIELDS_ = array( 'name','itunesUrl', );
	public static $_REF_FIELDS_ = array( 'memberId', );
	public static $_OBJECT_FIELDS_ = array( 'itunesData', );
	private static $_IS_DIRTY_ = false;
	private $__saved = false;
	private $_id = NULL;
	private $_id_changed = false;
	private $_memberId = NULL; // 用户
	private $_memberId_changed = false;
	private $_itunesData = NULL; // iTunes数据
	private $_itunesData_changed = false;
	private $_name = NULL; // 名称
	private $_name_changed = false;
	private $_itunesUrl = NULL; // iTunes浏览链接
	private $_itunesUrl_changed = false;
	public function __get($name) {
		if($name == 'id' || in_array($name, self::$_FIELDS_) || in_array($name, self::$_REF_FIELDS_) || in_array($name, self::$_OBJECT_FIELDS_) ) {
			return $this->$name();
		} else if(in_array($name . "Id", self::$_REF_FIELDS_)) {
			return $this->$name();
		} else {
			$trace = debug_backtrace();
       		trigger_error("Undefined property via __get(): '$name'  in '{$trace[0]['file']}' on line {$trace[0]['line']}", E_USER_WARNING);
		}
	}
	public function __construct () {
	}
	private function _lazy($level) {
		$id = $this->_id;
		switch ($level) {
		}
	}
	public function id( $id = UN_SET) {
		if($id !== UN_SET) {
			$ret = $this->_id;
			$this->_id = $id;
			$this->_id_changed = true;
			return $ret;
		} else {
			return $this->_id;
		}
	}
	/**
	 * 名称 text
	 *
	 */
	public function name( $name = UN_SET) {
		if($name !== UN_SET) {
			$ret = $this->_name;
			$this->_name = $name;
			$this->_name_changed = true;
			return $ret;
		} else {
			return $this->_name;
		}
	}
	/**
	 * iTunes浏览链接 text
	 *
	 */
	public function itunesUrl( $itunesUrl = UN_SET) {
		if($itunesUrl !== UN_SET) {
			$ret = $this->_itunesUrl;
			$this->_itunesUrl = $itunesUrl;
			$this->_itunesUrl_changed = true;
			return $ret;
		} else {
			return $this->_itunesUrl;
		}
	}
	/**
	 * iTunes数据
	 *
	 */
	public function itunesData( $itunesData = UN_SET) {
		if($itunesData !== UN_SET) {
			$ret = json_decode($this->_itunesData, true);
			$this->_itunesData = json_encode($itunesData);
			$this->_itunesData_changed = true;
			return $ret;
		} else {
			return json_decode($this->_itunesData, true);
		}
	}
	/**
	 * 用户 id
	 *
	 */
	public function memberId( $memberId = UN_SET) {
		if($memberId !== UN_SET) {
			$ret = $this->_memberId;
			$this->_memberId = $memberId;
			$this->_memberId_changed = true;
			return $ret;
		} else {
			return $this->_memberId;
		}
	}
	/**
	 *
	 * @return Member
	 */
	public function member() {
		if($this->_memberId === NULL)
			return NULL;
		else
			return Member::get($this->_memberId);
	}
	/**
	 *
	 * @param $id
	 * @return AppData
	 */
	public static function get($id, $forUpdate = false) {
		if($id === NULL) return NULL;
		$cache = $forUpdate? NULL : Dao::get('AppData', $id);
		if($cache !== NULL) return $cache;
		$sql = "select id, memberid, name, itunesurl, itunesdata from " . AppData::TABLE_NAME . " where id = :id ";
		//TODO:
		if($forUpdate) {
			$pdo = Dao::getPDOForWrite('sqlite');
			$sth = $pdo->prepare($sql . " for update");
			if($sth === false) {
				$errorInfo = $pdo->errorInfo();
				throw new DataBaseException("AppData::get: " . $errorInfo[2]);
			}
		} else {
			$pdo = Dao::getPDOForRead('sqlite', self::$_IS_DIRTY_);
			$sth = $pdo->prepare($sql);
			if($sth === false) {
				$errorInfo = $pdo->errorInfo();
				throw new DataBaseException("AppData::get: " . $errorInfo[2]);
			}
		}
		$sth->bindValue(':id', $id);
		if($sth->execute() === false) {
			$errorInfo = $sth->errorInfo();
			throw new DataBaseException('AppData::get: ' . $errorInfo[2]);
		}
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		$sth = NULL;
		if($row) {
			$o = self::instance($row);
			Dao::cache($o);
			return $o;
		} else {
			return NULL;
		}
	}
	/**
	 *
	 * @param $conditions
	 * @param $orderBy
	 * @param $from
	 * @param $size
	 * @param $table
	 * @return AppData[]
	 */
	public static function getList($conditions = array(), $orderBy = NULL, $from = 0, $size = 10000, $table =  AppData::TABLE_NAME) {
		$fields = 'id, memberid, name, itunesurl, itunesdata';
		$pdo = Dao::getPDOForRead('sqlite', self::$_IS_DIRTY_);
		$sql = "select $fields from $table where " . Dao::pdoWhere($conditions, $params) . (empty($orderBy)? "": " order by $orderBy ") . " limit $size offset $from";
		$sth = $pdo->prepare($sql);
		if($sth === false) {
			$errorInfo = $pdo->errorInfo();
			throw new DataBaseException("AppData::getList: " . $errorInfo[2]);
		}
		if($sth->execute($params) === false) {
			$errorInfo = $sth->errorInfo();
			throw new DataBaseException('AppData::getList: ' . $errorInfo[2]);
		}
		$ret = array();
		$i = 0;
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$o = self::instance($row);
			Dao::cache($o);
			$ret[] = $o;
		}
		$sth = NULL;
		return $ret;
	}
	/**
	 *
	 * @param $conditions
	 * @param $orderBy
	 * @param $table
	 * @return AppData
	 */
	public static function getOne($conditions = array(), $orderBy = NULL, $table =  AppData::TABLE_NAME) {
		$list = self::getList($conditions, $orderBy, 0, 1, $table);
		if($list) return $list[0];
		else return NULL;
	}
	/**
	 *
	 * @param $conditions
	 * @param $table
	 * @return int
	 */
	public static function getSize($conditions = array(), $table = AppData::TABLE_NAME) {
		$pdo = Dao::getPDOForRead('sqlite', self::$_IS_DIRTY_);
		$sql = "select count(*) as COUNT_ from $table where " . Dao::pdoWhere($conditions, $params);
		$sth = $pdo->prepare($sql);
		if($sth === false) {
			$errorInfo = $pdo->errorInfo();
			throw new DataBaseException("AppData::size: " . $errorInfo[2]);
		}
		if($sth->execute($params) === false) {
			$errorInfo = $sth->errorInfo();
			throw new DataBaseException('AppData::size: ' . $errorInfo[2]);
		}
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		return $row['COUNT_'];
	}
	/**
	 *
	 * @param array $conditions
	 * @param array $fields
	 * @param string $table
	 * @throws DataBaseException
	 * return [Code, int]
	 */
	public static function getGroupSize($conditions = array(), $fields = array(), $table = Code::TABLE_NAME) {
		$pdo = Dao::getPDOForRead('sqlite', self::$_IS_DIRTY_);
		if(!$fields) $fields = array('id');
		$fieldNames = implode(',', $fields);
		$sql = "select $fieldNames, count(*) as COUNT_ from $table where " . Dao::pdoWhere($conditions, $params) . " group by $fieldNames";
		$sth = $pdo->prepare($sql);
		if($sth === false) {
			$errorInfo = $pdo->errorInfo();
			throw new DataBaseException("Code::size: " . $errorInfo[2]);
		}
		if($sth->execute($params) === false) {
			$errorInfo = $sth->errorInfo();
			throw new DataBaseException('Code::size: ' . $errorInfo[2]);
		}
		$ret = array();
		$i = 0;
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$o = self::instance($row);
			Dao::cache($o);
			$ret[] = array($o, $row['COUNT_']);
		}
		$sth = NULL;
		return $ret;
	}
	/**
	 *
	 * @param $event
	 * @return void
	 */
	public function save($event = false) {
		$params = array();
		if($this->__saved === false) {
			// insert
			$keys = array();
			$values = array();
			if($this->_memberId_changed) {
				$keys[] = "memberid";
				$values[] = ":memberId";
				$params[':memberId'] = $this->_memberId;
			}
			if($this->_itunesData_changed) {
				$keys[] = "itunesdata";
				$values[] = ":itunesData";
				$params[':itunesData'] = $this->_itunesData;
			}
			if($this->_name_changed) {
				$keys[] = "name";
				$values[] = ":name";
				$params[':name'] = $this->_name;
			}
			if($this->_itunesUrl_changed) {
				$keys[] = "itunesurl";
				$values[] = ":itunesUrl";
				$params[':itunesUrl'] = $this->_itunesUrl;
			}
			$sql = "insert into " . AppData::TABLE_NAME . " (" . implode(',', $keys) . ") values (" . implode(',', $values) . ")";
			$pdo = Dao::getPDOForWrite('sqlite');
			$sth = $pdo->prepare($sql);
			if($sth === false) {
				$errorInfo = $pdo->errorInfo();
				throw new DataBaseException("AppData::save: " . $errorInfo[2]);
			}
			foreach($params as $pname=>$pvalue) {
				$sth->bindValue($pname, $pvalue);
			}
			if($sth->execute() === false) {
				$errorInfo = $sth->errorInfo();
				throw new DataBaseException('AppData::save: ' . $errorInfo[2]);
			}
			$sth = NULL;
			self::$_IS_DIRTY_ = true;
			if(!$this->_id_changed) {
				$id = $pdo->lastInsertId();
				$this->_id = intval($id);
			}
			$this->__saved = true;
			$this->_save_many();
			if($event) Dao::trigger(Dao::EVENT_INSERT, $this);
		} else {
			// update
			$this->_save_many();
			$sql = "update " . AppData::TABLE_NAME . " set ";
			$sets = array();
			if($this->_memberId_changed) {
				$sets[] = "memberid = :memberId";
				$params[':memberId'] = $this->_memberId;
			}
			if($this->_itunesData_changed) {
				$sets[] = "itunesdata = :itunesData";
				$params[':itunesData'] = $this->_itunesData;
			}
			if($this->_name_changed) {
				$sets[] = "name = :name";
				$params[':name'] = $this->_name;
			}
			if($this->_itunesUrl_changed) {
				$sets[] = "itunesurl = :itunesUrl";
				$params[':itunesUrl'] = $this->_itunesUrl;
			}
			if(count($sets) == 0) {
				return;
			}
			$sql .= implode(',', $sets) . " where id = :id";
			$pdo = Dao::getPDOForWrite('sqlite');
			$sth = $pdo->prepare($sql);
			if($sth === false) {
				$errorInfo = $pdo->errorInfo();
				throw new DataBaseException("AppData::save: " . $errorInfo[2]);
			}
			$sth->bindValue(':id', $this->_id);
			foreach($params as $pname=>$pvalue) {
				$sth->bindValue($pname, $pvalue);
			}
			if($sth->execute() === false) {
				$errorInfo = $sth->errorInfo();
				throw new DataBaseException('AppData::save: ' . $errorInfo[2]);
			}
			$sth = NULL;
			self::$_IS_DIRTY_ = true;
			if($event) Dao::trigger(Dao::EVENT_UPDATE, $this);
		}
		$this->_memberId_changed = false;
		$this->_name_changed = false;
		$this->_itunesUrl_changed = false;
		$this->_itunesData_changed = false;
		Dao::cache($this);
	}
	/**
	 *
	 * @param $event
	 * @return void
	 */
	public function delete($event = false) {
		$sql = "delete from " . AppData::TABLE_NAME . " where id = :id";
		$pdo = Dao::getPDOForWrite('sqlite');
		$sth = $pdo->prepare($sql);
		if($sth === false) {
			$errorInfo = $pdo->errorInfo();
			throw new DataBaseException("AppData::delete: " . $errorInfo[2]);
		}
		$sth->bindValue(':id', $this->_id);
		if($sth->execute() === false) {
			$errorInfo = $sth->errorInfo();
			throw new DataBaseException('AppData::delete: ' . $errorInfo[2]);
		}
		$sth = NULL;
		self::$_IS_DIRTY_ = true;
		if($event) Dao::trigger(Dao::EVENT_DELETE, $this);
		Dao::uncache($this);
	}
	public static function instance(&$row) {
		$o = new AppData();
		$o->__saved = true;
		if(array_key_exists('ID', $row)) {
			$o->_id = intval($row['ID']);
		}
		if(array_key_exists('MEMBERID', $row)) {
			$o->_memberId = intval($row['MEMBERID']);
		}
		if(!array_key_exists('NAME', $row)) {
			$o->_name = LAZY_INIT;
		} else {
			$o->_name = $row['NAME'];
					}
		if(!array_key_exists('ITUNESURL', $row)) {
			$o->_itunesUrl = LAZY_INIT;
		} else {
			$o->_itunesUrl = $row['ITUNESURL'];
					}
		if(!array_key_exists('ITUNESDATA', $row)) {
			$o->_itunesData = LAZY_INIT;
		} else {
			$o->_itunesData = $row['ITUNESDATA'];
		}
		return $o;
	}
	/**
	 * @param $objs array
	 * @return void
	 */
	public static function batchInsert($objs, $batchSize = 20, $event = false) {
		$objIds = array();
		$objLen = count($objs);
		if($objLen == 0) return array();
		$keys = 'name, itunesUrl, memberId';
		for($batch = 0; $batch * $batchSize < $objLen; $batch++) {
			$params = array();
			$values = array();
			$from = $batch * $batchSize;
			for($j = 0; $j < $batchSize; $j++) {
				$i = $from + $j;
				if($i >= $objLen) break;
				$obj = $objs[$i];
				$columns = array();
				$columns[] = ":memberId_$i";
				if($obj->_memberId_changed) {
					$params[":memberId_$i"] = $obj->_memberId;
				} else {
					$params[":memberId_$i"] = NULL;
				}
			if($obj->_itunesData_changed) {
				$columns[] = ":itunesData_$i";
				$params[":itunesData_$i"] = $obj->_itunesData;
			}
				$columns[] = ":name_$i";
				if($obj->_name_changed) {
					$params[":name_$i"] = $obj->_name;
				} else {
					$params[":name_$i"] = NULL;
				}
				$columns[] = ":itunesUrl_$i";
				if($obj->_itunesUrl_changed) {
					$params[":itunesUrl_$i"] = $obj->_itunesUrl;
				} else {
					$params[":itunesUrl_$i"] = NULL;
				}
				$values[] = ' ' . implode(',', $columns) . ' ';
			}
			$rows = array();
			foreach($values as $v) {
				$rows[] = "select $v ";
			}
			$sql = "insert into " . AppData::TABLE_NAME . " ($keys) " . implode(' union ', $rows);
			$pdo = Dao::getPDOForWrite('sqlite');
			$sth = $pdo->prepare($sql);
			if($sth === false) {
				$errorInfo = $pdo->errorInfo();
				throw new DataBaseException("AppData::batchInsert: " . $errorInfo[2]);
			}
			foreach($params as $pname=>$pvalue) {
				$sth->bindValue($pname, $pvalue);
			}
			if($sth->execute() === false) {
				$errorInfo = $sth->errorInfo();
				throw new DataBaseException('AppData::batchInsert: ' . $errorInfo[2]);
			}
			$sth = NULL;
			self::$_IS_DIRTY_ = true;
			$firstId = Dao::getPDOForWrite('sqlite')->lastInsertId();
			for($j = 0; $j < $batchSize; $j++) {
				$i = $from + $j;
				if($i >= $objLen) break;
				$obj = $objs[$i];
				$obj->_id = $firstId + $j;
				$objIds[] = $obj->_id;
				$obj->_memberId_changed = false;
				$obj->_name_changed = false;
				$obj->_itunesUrl_changed = false;
				Dao::cache($obj);
			}
		}
		return $objIds;
	}
	/**
	 *
	 * @param $conditions
	 * @param $from
	 * @param $size
	 * @param $orderBy
	 * @param $table
	 * @return AppDataIterator
	 */
	public static function getIterator($conditions = array(), $orderBy = NULL, $from = 0, $size = 10000, $table = AppData::TABLE_NAME) {
		$fields = 'id, memberid, name, itunesurl, itunesdata';
		$pdo = Dao::getPDOForRead('sqlite', self::$_IS_DIRTY_);
		$sql = "select $fields from $table where " . Dao::pdoWhere($conditions, $params) . (empty($orderBy)? "": " order by $orderBy ") . " limit $size offset $from";
		$sth = $pdo->prepare($sql);
		if($sth === false) {
			$errorInfo = $pdo->errorInfo();
			throw new DataBaseException("AppData::getIterator: " . $errorInfo[2]);
		}
		if($sth->execute($params) === false) {
			$errorInfo = $sth->errorInfo();
			throw new DataBaseException('AppData::getIterator: ' . $errorInfo[2]);
		}
		$iterator = new AppDataIterator($sth);
		$sth = NULL;
		return $iterator;
	}
	private function _save_many() {
	}
}
class AppDataIterator implements Iterator {
	private $position = -1;
	private $obj = NULL;
	/**
	 * @var PDOStatement
	 */
	private $sth = NULL;
	public function __construct($sth) {
		$this->sth = $sth;
	}
	function rewind() {
		if($this->position != -1) throw new DataBaseException("don't call AppDataIterator::rewind again ..");
		$this->next();
	}
	function current() {
		if($this->position == -1) $this->next();
		return $this->obj;
	}
	function key() {
		return $this->position;
	}
	function next() {
		++$this->position;
		$row = $this->sth->fetch(PDO::FETCH_ASSOC);
		if(!$row) {
			$this->obj = false;
		} else {
			$this->obj = AppData::instance($row);
		}
	}
	/**
	 * @return boolean
	 */
	function valid() {
		if($this->position == -1) $this->next();
		return $this->obj !== false;
	}
}
