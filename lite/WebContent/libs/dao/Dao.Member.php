<?php
Dao::$_cache['Member'] = array();
//TODO: rule
class Member { // 会员: member
	const TABLE_NAME = 'member';
	public static $_FIELDS_ = array( 'email','password','notifyNewPromotion','notifyNewGift','signupTime','lastLoginTime','addTime','timestamp', );
	public static $_REF_FIELDS_ = array(  );
	public static $_OBJECT_FIELDS_ = array(  );
	private static $_IS_DIRTY_ = false;
	private $__saved = false;
	private $_id = NULL;
	private $_id_changed = false;
	private $_email = NULL; // Email
	private $_email_changed = false;
	private $_password = NULL; // 密码
	private $_password_changed = false;
	private $_notifyNewPromotion = NULL; // 提醒新任务
	private $_notifyNewPromotion_changed = false;
	const NOTIFYNEWPROMOTION_YES = 1; // 是
	const NOTIFYNEWPROMOTION_NO = 0; // 否
	private $_notifyNewGift = NULL; // 提醒新Gift
	private $_notifyNewGift_changed = false;
	const NOTIFYNEWGIFT_YES = 1; // 是
	const NOTIFYNEWGIFT_NO = 0; // 否
	private $_signupTime = NULL; // 注册时间
	private $_signupTime_changed = false;
	private $_lastLoginTime = NULL; // 最后登录时间
	private $_lastLoginTime_changed = false;
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
		$this->_notifyNewPromotion = 1;
		$this->_notifyNewPromotion_changed = true;
		$this->_notifyNewGift = 1;
		$this->_notifyNewGift_changed = true;
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
	 * Email text
	 *
	 */
	public function email( $email = UN_SET) {
		if($email !== UN_SET) {
			$ret = $this->_email;
			$this->_email = $email;
			$this->_email_changed = true;
			return $ret;
		} else {
			return $this->_email;
		}
	}
	/**
	 * 密码 text
	 *
	 */
	public function password( $password = UN_SET) {
		if($password !== UN_SET) {
			$ret = $this->_password;
			$this->_password = $password;
			$this->_password_changed = true;
			return $ret;
		} else {
			return $this->_password;
		}
	}
	/**
	 * 提醒新任务 boolean
	 *
	 */
	public function notifyNewPromotion( $notifyNewPromotion = UN_SET) {
		if($notifyNewPromotion !== UN_SET) {
			$ret = $this->_notifyNewPromotion;
			$this->_notifyNewPromotion = $notifyNewPromotion;
			$this->_notifyNewPromotion_changed = true;
			return $ret;
		} else {
			return $this->_notifyNewPromotion;
		}
	}
	/**
	 * 提醒新Gift boolean
	 *
	 */
	public function notifyNewGift( $notifyNewGift = UN_SET) {
		if($notifyNewGift !== UN_SET) {
			$ret = $this->_notifyNewGift;
			$this->_notifyNewGift = $notifyNewGift;
			$this->_notifyNewGift_changed = true;
			return $ret;
		} else {
			return $this->_notifyNewGift;
		}
	}
	/**
	 * 注册时间 datetime
	 *
	 */
	public function signupTime( $signupTime = UN_SET) {
		if($signupTime !== UN_SET) {
			$ret = $this->_signupTime;
			$this->_signupTime = $signupTime;
			$this->_signupTime_changed = true;
			return $ret;
		} else {
			return $this->_signupTime;
		}
	}
	/**
	 * 最后登录时间 datetime
	 *
	 */
	public function lastLoginTime( $lastLoginTime = UN_SET) {
		if($lastLoginTime !== UN_SET) {
			$ret = $this->_lastLoginTime;
			$this->_lastLoginTime = $lastLoginTime;
			$this->_lastLoginTime_changed = true;
			return $ret;
		} else {
			return $this->_lastLoginTime;
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
	 *
	 * @param $id
	 * @return Member
	 */
	public static function get($id, $forUpdate = false) {
		if($id === NULL) return NULL;
		$cache = $forUpdate? NULL : Dao::get('Member', $id);
		if($cache !== NULL) return $cache;
		$sql = "select id, email, password, notifynewpromotion, notifynewgift, signuptime, lastlogintime, addtime, timestamp_ from " . Member::TABLE_NAME . " where id = :id ";
		//TODO:
		if($forUpdate) {
			$pdo = Dao::getPDOForWrite('sqlite');
			$sth = $pdo->prepare($sql . " for update");
			if($sth === false) {
				$errorInfo = $pdo->errorInfo();
				throw new DataBaseException("Member::get: " . $errorInfo[2]);
			}
		} else {
			$pdo = Dao::getPDOForRead('sqlite', self::$_IS_DIRTY_);
			$sth = $pdo->prepare($sql);
			if($sth === false) {
				$errorInfo = $pdo->errorInfo();
				throw new DataBaseException("Member::get: " . $errorInfo[2]);
			}
		}
		$sth->bindValue(':id', $id);
		if($sth->execute() === false) {
			$errorInfo = $sth->errorInfo();
			throw new DataBaseException('Member::get: ' . $errorInfo[2]);
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
	 * @return Member[]
	 */
	public static function getList($conditions = array(), $orderBy = NULL, $from = 0, $size = 10000, $table =  Member::TABLE_NAME) {
		$fields = 'id, email, password, notifynewpromotion, notifynewgift, signuptime, lastlogintime, addtime, timestamp_';
		$pdo = Dao::getPDOForRead('sqlite', self::$_IS_DIRTY_);
		$sql = "select $fields from $table where " . Dao::pdoWhere($conditions, $params) . (empty($orderBy)? "": " order by $orderBy ") . " limit $size offset $from";
		$sth = $pdo->prepare($sql);
		if($sth === false) {
			$errorInfo = $pdo->errorInfo();
			throw new DataBaseException("Member::getList: " . $errorInfo[2]);
		}
		if($sth->execute($params) === false) {
			$errorInfo = $sth->errorInfo();
			throw new DataBaseException('Member::getList: ' . $errorInfo[2]);
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
	 * @return Member
	 */
	public static function getOne($conditions = array(), $orderBy = NULL, $table =  Member::TABLE_NAME) {
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
	public static function getSize($conditions = array(), $table = Member::TABLE_NAME) {
		$pdo = Dao::getPDOForRead('sqlite', self::$_IS_DIRTY_);
		$sql = "select count(*) as COUNT_ from $table where " . Dao::pdoWhere($conditions, $params);
		$sth = $pdo->prepare($sql);
		if($sth === false) {
			$errorInfo = $pdo->errorInfo();
			throw new DataBaseException("Member::size: " . $errorInfo[2]);
		}
		if($sth->execute($params) === false) {
			$errorInfo = $sth->errorInfo();
			throw new DataBaseException('Member::size: ' . $errorInfo[2]);
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
			if($this->_email_changed) {
				$keys[] = "email";
				$values[] = ":email";
				$params[':email'] = $this->_email;
			}
			if($this->_password_changed) {
				$keys[] = "password";
				$values[] = ":password";
				$params[':password'] = $this->_password;
			}
			if($this->_notifyNewPromotion_changed) {
				$keys[] = "notifynewpromotion";
				$values[] = ":notifyNewPromotion";
				$params[':notifyNewPromotion'] = $this->_notifyNewPromotion;
			}
			if($this->_notifyNewGift_changed) {
				$keys[] = "notifynewgift";
				$values[] = ":notifyNewGift";
				$params[':notifyNewGift'] = $this->_notifyNewGift;
			}
			if($this->_signupTime_changed) {
				$keys[] = "signuptime";
				$values[] = ":signupTime";
				$params[':signupTime'] = $this->_signupTime;
			}
			if($this->_lastLoginTime_changed) {
				$keys[] = "lastlogintime";
				$values[] = ":lastLoginTime";
				$params[':lastLoginTime'] = $this->_lastLoginTime;
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
			$sql = "insert into " . Member::TABLE_NAME . " (" . implode(',', $keys) . ") values (" . implode(',', $values) . ")";
			$pdo = Dao::getPDOForWrite('sqlite');
			$sth = $pdo->prepare($sql);
			if($sth === false) {
				$errorInfo = $pdo->errorInfo();
				throw new DataBaseException("Member::save: " . $errorInfo[2]);
			}
			foreach($params as $pname=>$pvalue) {
				$sth->bindValue($pname, $pvalue);
			}
			if($sth->execute() === false) {
				$errorInfo = $sth->errorInfo();
				throw new DataBaseException('Member::save: ' . $errorInfo[2]);
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
			$sql = "update " . Member::TABLE_NAME . " set ";
			$sets = array();
			if($this->_email_changed) {
				$sets[] = "email = :email";
				$params[':email'] = $this->_email;
			}
			if($this->_password_changed) {
				$sets[] = "password = :password";
				$params[':password'] = $this->_password;
			}
			if($this->_notifyNewPromotion_changed) {
				$sets[] = "notifynewpromotion = :notifyNewPromotion";
				$params[':notifyNewPromotion'] = $this->_notifyNewPromotion;
			}
			if($this->_notifyNewGift_changed) {
				$sets[] = "notifynewgift = :notifyNewGift";
				$params[':notifyNewGift'] = $this->_notifyNewGift;
			}
			if($this->_signupTime_changed) {
				$sets[] = "signuptime = :signupTime";
				$params[':signupTime'] = $this->_signupTime;
			}
			if($this->_lastLoginTime_changed) {
				$sets[] = "lastlogintime = :lastLoginTime";
				$params[':lastLoginTime'] = $this->_lastLoginTime;
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
				throw new DataBaseException("Member::save: " . $errorInfo[2]);
			}
			$sth->bindValue(':id', $this->_id);
			foreach($params as $pname=>$pvalue) {
				$sth->bindValue($pname, $pvalue);
			}
			if($sth->execute() === false) {
				$errorInfo = $sth->errorInfo();
				throw new DataBaseException('Member::save: ' . $errorInfo[2]);
			}
			$sth = NULL;
			self::$_IS_DIRTY_ = true;
			if($event) Dao::trigger(Dao::EVENT_UPDATE, $this);
		}
		$this->_email_changed = false;
		$this->_password_changed = false;
		$this->_notifyNewPromotion_changed = false;
		$this->_notifyNewGift_changed = false;
		$this->_signupTime_changed = false;
		$this->_lastLoginTime_changed = false;
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
		$sql = "delete from " . Member::TABLE_NAME . " where id = :id";
		$pdo = Dao::getPDOForWrite('sqlite');
		$sth = $pdo->prepare($sql);
		if($sth === false) {
			$errorInfo = $pdo->errorInfo();
			throw new DataBaseException("Member::delete: " . $errorInfo[2]);
		}
		$sth->bindValue(':id', $this->_id);
		if($sth->execute() === false) {
			$errorInfo = $sth->errorInfo();
			throw new DataBaseException('Member::delete: ' . $errorInfo[2]);
		}
		$sth = NULL;
		self::$_IS_DIRTY_ = true;
		if($event) Dao::trigger(Dao::EVENT_DELETE, $this);
		Dao::uncache($this);
	}
	public static function instance(&$row) {
		$o = new Member();
		$o->__saved = true;
		$o->_id = intval($row['ID']);
		if(!array_key_exists('EMAIL', $row)) {
			$o->_email = LAZY_INIT;
		} else {
			$o->_email = $row['EMAIL'];
					}
		if(!array_key_exists('PASSWORD', $row)) {
			$o->_password = LAZY_INIT;
		} else {
			$o->_password = $row['PASSWORD'];
					}
		if(!array_key_exists('NOTIFYNEWPROMOTION', $row)) {
			$o->_notifyNewPromotion = LAZY_INIT;
		} else {
			$o->_notifyNewPromotion = $row['NOTIFYNEWPROMOTION'] === NULL? NULL : $row['NOTIFYNEWPROMOTION'] + 0;
		}
		if(!array_key_exists('NOTIFYNEWGIFT', $row)) {
			$o->_notifyNewGift = LAZY_INIT;
		} else {
			$o->_notifyNewGift = $row['NOTIFYNEWGIFT'] === NULL? NULL : $row['NOTIFYNEWGIFT'] + 0;
		}
		if(!array_key_exists('SIGNUPTIME', $row)) {
			$o->_signupTime = LAZY_INIT;
		} else {
			$o->_signupTime = $row['SIGNUPTIME'];
					}
		if(!array_key_exists('LASTLOGINTIME', $row)) {
			$o->_lastLoginTime = LAZY_INIT;
		} else {
			$o->_lastLoginTime = $row['LASTLOGINTIME'];
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
		$keys = 'email, password, notifyNewPromotion, notifyNewGift, signupTime, lastLoginTime, addTime, timestamp';
		for($batch = 0; $batch * $batchSize < $objLen; $batch++) {
			$params = array();
			$values = array();
			$from = $batch * $batchSize;
			for($j = 0; $j < $batchSize; $j++) {
				$i = $from + $j;
				if($i >= $objLen) break;
				$obj = $objs[$i];
				$columns = array();
				$columns[] = ":email_$i";
				if($obj->_email_changed) {
					$params[":email_$i"] = $obj->_email;
				} else {
					$params[":email_$i"] = NULL;
				}
				$columns[] = ":password_$i";
				if($obj->_password_changed) {
					$params[":password_$i"] = $obj->_password;
				} else {
					$params[":password_$i"] = NULL;
				}
				$columns[] = ":notifyNewPromotion_$i";
				if($obj->_notifyNewPromotion_changed) {
					$params[":notifyNewPromotion_$i"] = $obj->_notifyNewPromotion;
				} else {
					$params[":notifyNewPromotion_$i"] = NULL;
				}
				$columns[] = ":notifyNewGift_$i";
				if($obj->_notifyNewGift_changed) {
					$params[":notifyNewGift_$i"] = $obj->_notifyNewGift;
				} else {
					$params[":notifyNewGift_$i"] = NULL;
				}
				$columns[] = ":signupTime_$i";
				if($obj->_signupTime_changed) {
					$params[":signupTime_$i"] = $obj->_signupTime;
				} else {
					$params[":signupTime_$i"] = NULL;
				}
				$columns[] = ":lastLoginTime_$i";
				if($obj->_lastLoginTime_changed) {
					$params[":lastLoginTime_$i"] = $obj->_lastLoginTime;
				} else {
					$params[":lastLoginTime_$i"] = NULL;
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
			$sql = "insert into " . Member::TABLE_NAME . " ($keys) " . implode(' union ', $rows);
			$pdo = Dao::getPDOForWrite('sqlite');
			$sth = $pdo->prepare($sql);
			if($sth === false) {
				$errorInfo = $pdo->errorInfo();
				throw new DataBaseException("Member::batchInsert: " . $errorInfo[2]);
			}
			foreach($params as $pname=>$pvalue) {
				$sth->bindValue($pname, $pvalue);
			}
			if($sth->execute() === false) {
				$errorInfo = $sth->errorInfo();
				throw new DataBaseException('Member::batchInsert: ' . $errorInfo[2]);
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
				$obj->_email_changed = false;
				$obj->_password_changed = false;
				$obj->_notifyNewPromotion_changed = false;
				$obj->_notifyNewGift_changed = false;
				$obj->_signupTime_changed = false;
				$obj->_lastLoginTime_changed = false;
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
	 * @return MemberIterator
	 */
	public static function getIterator($conditions = array(), $orderBy = NULL, $from = 0, $size = 10000, $table = Member::TABLE_NAME) {
		$fields = 'id, email, password, notifynewpromotion, notifynewgift, signuptime, lastlogintime, addtime, timestamp_';
		$pdo = Dao::getPDOForRead('sqlite', self::$_IS_DIRTY_);
		$sql = "select $fields from $table where " . Dao::pdoWhere($conditions, $params) . (empty($orderBy)? "": " order by $orderBy ") . " limit $size offset $from";
		$sth = $pdo->prepare($sql);
		if($sth === false) {
			$errorInfo = $pdo->errorInfo();
			throw new DataBaseException("Member::getIterator: " . $errorInfo[2]);
		}
		if($sth->execute($params) === false) {
			$errorInfo = $sth->errorInfo();
			throw new DataBaseException('Member::getIterator: ' . $errorInfo[2]);
		}
		$iterator = new MemberIterator($sth);
		$sth = NULL;
		return $iterator;
	}
	private function _save_many() {
	}
}
class MemberIterator implements Iterator {
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
		if($this->position != -1) throw new DataBaseException("don't call MemberIterator::rewind again ..");
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
			$this->obj = Member::instance($row);
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
