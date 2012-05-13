<?php
Dao::$_cache['Code'] = array();
//TODO: rule
class Code { // 兑换码: code
	const TABLE_NAME = 'code';
	public static $_FIELDS_ = array( 'app','code','state','note','addTime','timestamp', );
	public static $_REF_FIELDS_ = array( 'memberId','countryId', );
	public static $_OBJECT_FIELDS_ = array(  );
	private static $_IS_DIRTY_ = false;
	private $__saved = false;
	private $_id = NULL;
	private $_id_changed = false;
	private $_memberId = NULL; // 用户
	private $_memberId_changed = false;
	private $_countryId = NULL; // 区域
	private $_countryId_changed = false;
	private $_app = NULL; // 应用
	private $_app_changed = false;
	private $_code = NULL; // 码
	private $_code_changed = false;
	private $_state = NULL; // 状态
	private $_state_changed = false;
	const STATE_NEW = 1; // 新
	const STATE_USED = 2; // 已使用
	private $_note = NULL; // 备注
	private $_note_changed = false;
	private $_addTime = NULL; // 添加时间
	private $_addTime_changed = false;
	private $_timestamp = NULL; // 更新时间戳
	private $_timestamp_changed = false;
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
		$this->_state = 1;
		$this->_state_changed = true;
		$this->_addTime = date('Y-m-d H:i:s', time());
		$this->_addTime_changed = true;
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
	 * 应用 text
	 *
	 */
	public function app( $app = UN_SET) {
		if($app !== UN_SET) {
			$ret = $this->_app;
			$this->_app = $app;
			$this->_app_changed = true;
			return $ret;
		} else {
			return $this->_app;
		}
	}
	/**
	 * 码 text
	 *
	 */
	public function code( $code = UN_SET) {
		if($code !== UN_SET) {
			$ret = $this->_code;
			$this->_code = $code;
			$this->_code_changed = true;
			return $ret;
		} else {
			return $this->_code;
		}
	}
	/**
	 * 状态 integer
	 *
	 */
	public function state( $state = UN_SET) {
		if($state !== UN_SET) {
			$ret = $this->_state;
			$this->_state = $state;
			$this->_state_changed = true;
			return $ret;
		} else {
			return $this->_state;
		}
	}
	/**
	 * 备注 text
	 *
	 */
	public function note( $note = UN_SET) {
		if($note !== UN_SET) {
			$ret = $this->_note;
			$this->_note = $note;
			$this->_note_changed = true;
			return $ret;
		} else {
			return $this->_note;
		}
	}
	/**
	 * 添加时间 datetime
	 *
	 */
	public function addTime( $addTime = UN_SET) {
		if($addTime !== UN_SET) {
			$ret = $this->_addTime;
			$this->_addTime = $addTime;
			$this->_addTime_changed = true;
			return $ret;
		} else {
			return $this->_addTime;
		}
	}
	/**
	 * 更新时间戳 datetime
	 *
	 */
	public function timestamp( $timestamp = UN_SET) {
		if($timestamp !== UN_SET) {
			$ret = $this->_timestamp;
			$this->_timestamp = $timestamp;
			$this->_timestamp_changed = true;
			return $ret;
		} else {
			return $this->_timestamp;
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
	 * 区域 id
	 *
	 */
	public function countryId( $countryId = UN_SET) {
		if($countryId !== UN_SET) {
			$ret = $this->_countryId;
			$this->_countryId = $countryId;
			$this->_countryId_changed = true;
			return $ret;
		} else {
			return $this->_countryId;
		}
	}
	/**
	 *
	 * @return Country
	 */
	public function country() {
		if($this->_countryId === NULL)
			return NULL;
		else
			return Country::get($this->_countryId);
	}
	/**
	 *
	 * @param $id
	 * @return Code
	 */
	public static function get($id, $forUpdate = false) {
		if($id === NULL) return NULL;
		$cache = $forUpdate? NULL : Dao::get('Code', $id);
		if($cache !== NULL) return $cache;
		$sql = "select id, memberid, countryid, app, code, state, note, addtime, timestamp_ from " . Code::TABLE_NAME . " where id = :id ";
		//TODO:
		if($forUpdate) {
			$pdo = Dao::getPDOForWrite('sqlite');
			$sth = $pdo->prepare($sql . " for update");
			if($sth === false) {
				$errorInfo = $pdo->errorInfo();
				throw new DataBaseException("Code::get: " . $errorInfo[2]);
			}
		} else {
			$pdo = Dao::getPDOForRead('sqlite', self::$_IS_DIRTY_);
			$sth = $pdo->prepare($sql);
			if($sth === false) {
				$errorInfo = $pdo->errorInfo();
				throw new DataBaseException("Code::get: " . $errorInfo[2]);
			}
		}
		$sth->bindValue(':id', $id);
		if($sth->execute() === false) {
			$errorInfo = $sth->errorInfo();
			throw new DataBaseException('Code::get: ' . $errorInfo[2]);
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
	 * @return Code[]
	 */
	public static function getList($conditions = array(), $orderBy = NULL, $from = 0, $size = 10000, $table =  Code::TABLE_NAME) {
		$fields = 'id, memberid, countryid, app, code, state, note, addtime, timestamp_';
		$pdo = Dao::getPDOForRead('sqlite', self::$_IS_DIRTY_);
		$sql = "select $fields from $table where " . Dao::pdoWhere($conditions, $params) . (empty($orderBy)? "": " order by $orderBy ") . " limit $size offset $from";
		$sth = $pdo->prepare($sql);
		if($sth === false) {
			$errorInfo = $pdo->errorInfo();
			throw new DataBaseException("Code::getList: " . $errorInfo[2]);
		}
		if($sth->execute($params) === false) {
			$errorInfo = $sth->errorInfo();
			throw new DataBaseException('Code::getList: ' . $errorInfo[2]);
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
	 * @return Code
	 */
	public static function getOne($conditions = array(), $orderBy = NULL, $table =  Code::TABLE_NAME) {
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
	public static function getSize($conditions = array(), $table = Code::TABLE_NAME) {
		$pdo = Dao::getPDOForRead('sqlite', self::$_IS_DIRTY_);
		$sql = "select count(*) as COUNT_ from $table where " . Dao::pdoWhere($conditions, $params);
		$sth = $pdo->prepare($sql);
		if($sth === false) {
			$errorInfo = $pdo->errorInfo();
			throw new DataBaseException("Code::size: " . $errorInfo[2]);
		}
		if($sth->execute($params) === false) {
			$errorInfo = $sth->errorInfo();
			throw new DataBaseException('Code::size: ' . $errorInfo[2]);
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
			if($this->_countryId_changed) {
				$keys[] = "countryid";
				$values[] = ":countryId";
				$params[':countryId'] = $this->_countryId;
			}
			if($this->_app_changed) {
				$keys[] = "app";
				$values[] = ":app";
				$params[':app'] = $this->_app;
			}
			if($this->_code_changed) {
				$keys[] = "code";
				$values[] = ":code";
				$params[':code'] = $this->_code;
			}
			if($this->_state_changed) {
				$keys[] = "state";
				$values[] = ":state";
				$params[':state'] = $this->_state;
			}
			if($this->_note_changed) {
				$keys[] = "note";
				$values[] = ":note";
				$params[':note'] = $this->_note;
			}
			if($this->_addTime_changed) {
				$keys[] = "addtime";
				$values[] = ":addTime";
				$params[':addTime'] = $this->_addTime;
			}
			if(!$this->_timestamp_changed) {
				$this->_timestamp = date('Y-m-d H:i:s', time());
				$this->_timestamp_changed = true;
			}
			if($this->_timestamp_changed) {
				$keys[] = "timestamp_";
				$values[] = ":timestamp";
				$params[':timestamp'] = $this->_timestamp;
			}
			$sql = "insert into " . Code::TABLE_NAME . " (" . implode(',', $keys) . ") values (" . implode(',', $values) . ")";
			$pdo = Dao::getPDOForWrite('sqlite');
			$sth = $pdo->prepare($sql);
			if($sth === false) {
				$errorInfo = $pdo->errorInfo();
				throw new DataBaseException("Code::save: " . $errorInfo[2]);
			}
			foreach($params as $pname=>$pvalue) {
				$sth->bindValue($pname, $pvalue);
			}
			if($sth->execute() === false) {
				$errorInfo = $sth->errorInfo();
				throw new DataBaseException('Code::save: ' . $errorInfo[2]);
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
			$sql = "update " . Code::TABLE_NAME . " set ";
			$sets = array();
			if($this->_memberId_changed) {
				$sets[] = "memberid = :memberId";
				$params[':memberId'] = $this->_memberId;
			}
			if($this->_countryId_changed) {
				$sets[] = "countryid = :countryId";
				$params[':countryId'] = $this->_countryId;
			}
			if($this->_app_changed) {
				$sets[] = "app = :app";
				$params[':app'] = $this->_app;
			}
			if($this->_code_changed) {
				$sets[] = "code = :code";
				$params[':code'] = $this->_code;
			}
			if($this->_state_changed) {
				$sets[] = "state = :state";
				$params[':state'] = $this->_state;
			}
			if($this->_note_changed) {
				$sets[] = "note = :note";
				$params[':note'] = $this->_note;
			}
			if($this->_addTime_changed) {
				$sets[] = "addtime = :addTime";
				$params[':addTime'] = $this->_addTime;
			}
			if(!$this->_timestamp_changed) {
				$this->_timestamp = date('Y-m-d H:i:s', time());
				$this->_timestamp_changed = true;
			}
			if($this->_timestamp_changed) {
				$sets[] = "timestamp_ = :timestamp";
				$params[':timestamp'] = $this->_timestamp;
			}
			if(count($sets) == 0) {
				return;
			}
			$sql .= implode(',', $sets) . " where id = :id";
			$pdo = Dao::getPDOForWrite('sqlite');
			$sth = $pdo->prepare($sql);
			if($sth === false) {
				$errorInfo = $pdo->errorInfo();
				throw new DataBaseException("Code::save: " . $errorInfo[2]);
			}
			$sth->bindValue(':id', $this->_id);
			foreach($params as $pname=>$pvalue) {
				$sth->bindValue($pname, $pvalue);
			}
			if($sth->execute() === false) {
				$errorInfo = $sth->errorInfo();
				throw new DataBaseException('Code::save: ' . $errorInfo[2]);
			}
			$sth = NULL;
			self::$_IS_DIRTY_ = true;
			if($event) Dao::trigger(Dao::EVENT_UPDATE, $this);
		}
		$this->_memberId_changed = false;
		$this->_countryId_changed = false;
		$this->_app_changed = false;
		$this->_code_changed = false;
		$this->_state_changed = false;
		$this->_note_changed = false;
		$this->_addTime_changed = false;
		$this->_timestamp_changed = false;
		Dao::cache($this);
	}
	/**
	 *
	 * @param $event
	 * @return void
	 */
	public function delete($event = false) {
		$sql = "delete from " . Code::TABLE_NAME . " where id = :id";
		$pdo = Dao::getPDOForWrite('sqlite');
		$sth = $pdo->prepare($sql);
		if($sth === false) {
			$errorInfo = $pdo->errorInfo();
			throw new DataBaseException("Code::delete: " . $errorInfo[2]);
		}
		$sth->bindValue(':id', $this->_id);
		if($sth->execute() === false) {
			$errorInfo = $sth->errorInfo();
			throw new DataBaseException('Code::delete: ' . $errorInfo[2]);
		}
		$sth = NULL;
		self::$_IS_DIRTY_ = true;
		if($event) Dao::trigger(Dao::EVENT_DELETE, $this);
		Dao::uncache($this);
	}
	public static function instance(&$row) {
		$o = new Code();
		$o->__saved = true;
		if(array_key_exists('ID', $row)) {
			$o->_id = intval($row['ID']);
		}
		if(array_key_exists('MEMBERID', $row)) {
			$o->_memberId = intval($row['MEMBERID']);
		}
		if(array_key_exists('COUNTRYID', $row)) {
			$o->_countryId = intval($row['COUNTRYID']);
		}
		if(!array_key_exists('APP', $row)) {
			$o->_app = LAZY_INIT;
		} else {
			$o->_app = $row['APP'];
					}
		if(!array_key_exists('CODE', $row)) {
			$o->_code = LAZY_INIT;
		} else {
			$o->_code = $row['CODE'];
					}
		if(!array_key_exists('STATE', $row)) {
			$o->_state = LAZY_INIT;
		} else {
			$o->_state = $row['STATE'] === NULL? NULL : $row['STATE'] + 0;
		}
		if(!array_key_exists('NOTE', $row)) {
			$o->_note = LAZY_INIT;
		} else {
			$o->_note = $row['NOTE'];
					}
		if(!array_key_exists('ADDTIME', $row)) {
			$o->_addTime = LAZY_INIT;
		} else {
			$o->_addTime = $row['ADDTIME'];
					}
		if(!array_key_exists('TIMESTAMP_', $row)) {
			$o->_timestamp = LAZY_INIT;
		} else {
			$o->_timestamp = $row['TIMESTAMP_'];
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
		$keys = 'app, code, state, note, addTime, timestamp, memberId, countryId';
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
				$columns[] = ":countryId_$i";
				if($obj->_countryId_changed) {
					$params[":countryId_$i"] = $obj->_countryId;
				} else {
					$params[":countryId_$i"] = NULL;
				}
				$columns[] = ":app_$i";
				if($obj->_app_changed) {
					$params[":app_$i"] = $obj->_app;
				} else {
					$params[":app_$i"] = NULL;
				}
				$columns[] = ":code_$i";
				if($obj->_code_changed) {
					$params[":code_$i"] = $obj->_code;
				} else {
					$params[":code_$i"] = NULL;
				}
				$columns[] = ":state_$i";
				if($obj->_state_changed) {
					$params[":state_$i"] = $obj->_state;
				} else {
					$params[":state_$i"] = NULL;
				}
				$columns[] = ":note_$i";
				if($obj->_note_changed) {
					$params[":note_$i"] = $obj->_note;
				} else {
					$params[":note_$i"] = NULL;
				}
				$columns[] = ":addTime_$i";
				if($obj->_addTime_changed) {
					$params[":addTime_$i"] = $obj->_addTime;
				} else {
					$params[":addTime_$i"] = NULL;
				}
				if(!$obj->_timestamp_changed) {
					$obj->_timestamp = date('Y-m-d H:i:s', time());
					$obj->_timestamp_changed = true;
				}
				$columns[] = ":timestamp_$i";
				if($obj->_timestamp_changed) {
					$params[":timestamp_$i"] = $obj->_timestamp;
				} else {
					$params[":timestamp_$i"] = NULL;
				}
				$values[] = ' ' . implode(',', $columns) . ' ';
			}
			$rows = array();
			foreach($values as $v) {
				$rows[] = "select $v ";
			}
			$sql = "insert into " . Code::TABLE_NAME . " ($keys) " . implode(' union ', $rows);
			$pdo = Dao::getPDOForWrite('sqlite');
			$sth = $pdo->prepare($sql);
			if($sth === false) {
				$errorInfo = $pdo->errorInfo();
				throw new DataBaseException("Code::batchInsert: " . $errorInfo[2]);
			}
			foreach($params as $pname=>$pvalue) {
				$sth->bindValue($pname, $pvalue);
			}
			if($sth->execute() === false) {
				$errorInfo = $sth->errorInfo();
				throw new DataBaseException('Code::batchInsert: ' . $errorInfo[2]);
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
				$obj->_countryId_changed = false;
				$obj->_app_changed = false;
				$obj->_code_changed = false;
				$obj->_state_changed = false;
				$obj->_note_changed = false;
				$obj->_addTime_changed = false;
				$obj->_timestamp_changed = false;
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
	 * @return CodeIterator
	 */
	public static function getIterator($conditions = array(), $orderBy = NULL, $from = 0, $size = 10000, $table = Code::TABLE_NAME) {
		$fields = 'id, memberid, countryid, app, code, state, note, addtime, timestamp_';
		$pdo = Dao::getPDOForRead('sqlite', self::$_IS_DIRTY_);
		$sql = "select $fields from $table where " . Dao::pdoWhere($conditions, $params) . (empty($orderBy)? "": " order by $orderBy ") . " limit $size offset $from";
		$sth = $pdo->prepare($sql);
		if($sth === false) {
			$errorInfo = $pdo->errorInfo();
			throw new DataBaseException("Code::getIterator: " . $errorInfo[2]);
		}
		if($sth->execute($params) === false) {
			$errorInfo = $sth->errorInfo();
			throw new DataBaseException('Code::getIterator: ' . $errorInfo[2]);
		}
		$iterator = new CodeIterator($sth);
		$sth = NULL;
		return $iterator;
	}
	private function _save_many() {
	}
}
class CodeIterator implements Iterator {
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
		if($this->position != -1) throw new DataBaseException("don't call CodeIterator::rewind again ..");
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
			$this->obj = Code::instance($row);
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
