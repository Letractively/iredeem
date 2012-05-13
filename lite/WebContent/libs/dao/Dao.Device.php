<?php
Dao::$_cache['Device'] = array();
//TODO: rule
class Device { // 设备: device
	const TABLE_NAME = 'device';
	public static $_FIELDS_ = array( 'name','fma','udid','credits','creditsUpdatedTime','state','addTime','timestamp', );
	public static $_REF_FIELDS_ = array( 'memberId', );
	public static $_OBJECT_FIELDS_ = array( 'fmaGifts', );
	private static $_IS_DIRTY_ = false;
	private $__saved = false;
	private $_id = NULL;
	private $_id_changed = false;
	private $_memberId = NULL; // 用户
	private $_memberId_changed = false;
	private $_fmaGifts = NULL; // FMA礼品
	private $_fmaGifts_changed = false;
	private $_name = NULL; // 名称
	private $_name_changed = false;
	private $_fma = NULL; // FMA链接
	private $_fma_changed = false;
	private $_udid = NULL; // UDID
	private $_udid_changed = false;
	private $_credits = NULL; // 分数
	private $_credits_changed = false;
	private $_creditsUpdatedTime = NULL; // 更新credits时间
	private $_creditsUpdatedTime_changed = false;
	private $_state = NULL; // 状态
	private $_state_changed = false;
	const STATE_ENABLED = 1; // 可用
	const STATE_DISABLED = 0; // 禁用
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
		$this->_credits = 0;
		$this->_credits_changed = true;
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
	 * FMA链接 text
	 *
	 */
	public function fma( $fma = UN_SET) {
		if($fma !== UN_SET) {
			$ret = $this->_fma;
			$this->_fma = $fma;
			$this->_fma_changed = true;
			return $ret;
		} else {
			return $this->_fma;
		}
	}
	/**
	 * UDID text
	 *
	 */
	public function udid( $udid = UN_SET) {
		if($udid !== UN_SET) {
			$ret = $this->_udid;
			$this->_udid = $udid;
			$this->_udid_changed = true;
			return $ret;
		} else {
			return $this->_udid;
		}
	}
	/**
	 * 分数 integer
	 *
	 */
	public function credits( $credits = UN_SET) {
		if($credits !== UN_SET) {
			$ret = $this->_credits;
			$this->_credits = $credits;
			$this->_credits_changed = true;
			return $ret;
		} else {
			return $this->_credits;
		}
	}
	/**
	 * 更新credits时间 datetime
	 *
	 */
	public function creditsUpdatedTime( $creditsUpdatedTime = UN_SET) {
		if($creditsUpdatedTime !== UN_SET) {
			$ret = $this->_creditsUpdatedTime;
			$this->_creditsUpdatedTime = $creditsUpdatedTime;
			$this->_creditsUpdatedTime_changed = true;
			return $ret;
		} else {
			return $this->_creditsUpdatedTime;
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
	 * FMA礼品
	 *
	 */
	public function fmaGifts( $fmaGifts = UN_SET) {
		if($fmaGifts !== UN_SET) {
			$ret = json_decode($this->_fmaGifts, true);
			$this->_fmaGifts = json_encode($fmaGifts);
			$this->_fmaGifts_changed = true;
			return $ret;
		} else {
			return json_decode($this->_fmaGifts, true);
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
	 * @return Device
	 */
	public static function get($id, $forUpdate = false) {
		if($id === NULL) return NULL;
		$cache = $forUpdate? NULL : Dao::get('Device', $id);
		if($cache !== NULL) return $cache;
		$sql = "select id, memberid, name, fma, udid, credits, creditsupdatedtime, state, addtime, timestamp_, fmagifts from " . Device::TABLE_NAME . " where id = :id ";
		//TODO:
		if($forUpdate) {
			$pdo = Dao::getPDOForWrite('sqlite');
			$sth = $pdo->prepare($sql . " for update");
			if($sth === false) {
				$errorInfo = $pdo->errorInfo();
				throw new DataBaseException("Device::get: " . $errorInfo[2]);
			}
		} else {
			$pdo = Dao::getPDOForRead('sqlite', self::$_IS_DIRTY_);
			$sth = $pdo->prepare($sql);
			if($sth === false) {
				$errorInfo = $pdo->errorInfo();
				throw new DataBaseException("Device::get: " . $errorInfo[2]);
			}
		}
		$sth->bindValue(':id', $id);
		if($sth->execute() === false) {
			$errorInfo = $sth->errorInfo();
			throw new DataBaseException('Device::get: ' . $errorInfo[2]);
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
	 * @return Device[]
	 */
	public static function getList($conditions = array(), $orderBy = NULL, $from = 0, $size = 10000, $table =  Device::TABLE_NAME) {
		$fields = 'id, memberid, name, fma, udid, credits, creditsupdatedtime, state, addtime, timestamp_, fmagifts';
		$pdo = Dao::getPDOForRead('sqlite', self::$_IS_DIRTY_);
		$sql = "select $fields from $table where " . Dao::pdoWhere($conditions, $params) . (empty($orderBy)? "": " order by $orderBy ") . " limit $size offset $from";
		$sth = $pdo->prepare($sql);
		if($sth === false) {
			$errorInfo = $pdo->errorInfo();
			throw new DataBaseException("Device::getList: " . $errorInfo[2]);
		}
		if($sth->execute($params) === false) {
			$errorInfo = $sth->errorInfo();
			throw new DataBaseException('Device::getList: ' . $errorInfo[2]);
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
	 * @return Device
	 */
	public static function getOne($conditions = array(), $orderBy = NULL, $table =  Device::TABLE_NAME) {
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
	public static function getSize($conditions = array(), $table = Device::TABLE_NAME) {
		$pdo = Dao::getPDOForRead('sqlite', self::$_IS_DIRTY_);
		$sql = "select count(*) as COUNT_ from $table where " . Dao::pdoWhere($conditions, $params);
		$sth = $pdo->prepare($sql);
		if($sth === false) {
			$errorInfo = $pdo->errorInfo();
			throw new DataBaseException("Device::size: " . $errorInfo[2]);
		}
		if($sth->execute($params) === false) {
			$errorInfo = $sth->errorInfo();
			throw new DataBaseException('Device::size: ' . $errorInfo[2]);
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
			if($this->_fmaGifts_changed) {
				$keys[] = "fmagifts";
				$values[] = ":fmaGifts";
				$params[':fmaGifts'] = $this->_fmaGifts;
			}
			if($this->_name_changed) {
				$keys[] = "name";
				$values[] = ":name";
				$params[':name'] = $this->_name;
			}
			if($this->_fma_changed) {
				$keys[] = "fma";
				$values[] = ":fma";
				$params[':fma'] = $this->_fma;
			}
			if($this->_udid_changed) {
				$keys[] = "udid";
				$values[] = ":udid";
				$params[':udid'] = $this->_udid;
			}
			if($this->_credits_changed) {
				$keys[] = "credits";
				$values[] = ":credits";
				$params[':credits'] = $this->_credits;
			}
			if($this->_creditsUpdatedTime_changed) {
				$keys[] = "creditsupdatedtime";
				$values[] = ":creditsUpdatedTime";
				$params[':creditsUpdatedTime'] = $this->_creditsUpdatedTime;
			}
			if($this->_state_changed) {
				$keys[] = "state";
				$values[] = ":state";
				$params[':state'] = $this->_state;
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
			$sql = "insert into " . Device::TABLE_NAME . " (" . implode(',', $keys) . ") values (" . implode(',', $values) . ")";
			$pdo = Dao::getPDOForWrite('sqlite');
			$sth = $pdo->prepare($sql);
			if($sth === false) {
				$errorInfo = $pdo->errorInfo();
				throw new DataBaseException("Device::save: " . $errorInfo[2]);
			}
			foreach($params as $pname=>$pvalue) {
				$sth->bindValue($pname, $pvalue);
			}
			if($sth->execute() === false) {
				$errorInfo = $sth->errorInfo();
				throw new DataBaseException('Device::save: ' . $errorInfo[2]);
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
			$sql = "update " . Device::TABLE_NAME . " set ";
			$sets = array();
			if($this->_memberId_changed) {
				$sets[] = "memberid = :memberId";
				$params[':memberId'] = $this->_memberId;
			}
			if($this->_fmaGifts_changed) {
				$sets[] = "fmagifts = :fmaGifts";
				$params[':fmaGifts'] = $this->_fmaGifts;
			}
			if($this->_name_changed) {
				$sets[] = "name = :name";
				$params[':name'] = $this->_name;
			}
			if($this->_fma_changed) {
				$sets[] = "fma = :fma";
				$params[':fma'] = $this->_fma;
			}
			if($this->_udid_changed) {
				$sets[] = "udid = :udid";
				$params[':udid'] = $this->_udid;
			}
			if($this->_credits_changed) {
				$sets[] = "credits = :credits";
				$params[':credits'] = $this->_credits;
			}
			if($this->_creditsUpdatedTime_changed) {
				$sets[] = "creditsupdatedtime = :creditsUpdatedTime";
				$params[':creditsUpdatedTime'] = $this->_creditsUpdatedTime;
			}
			if($this->_state_changed) {
				$sets[] = "state = :state";
				$params[':state'] = $this->_state;
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
				throw new DataBaseException("Device::save: " . $errorInfo[2]);
			}
			$sth->bindValue(':id', $this->_id);
			foreach($params as $pname=>$pvalue) {
				$sth->bindValue($pname, $pvalue);
			}
			if($sth->execute() === false) {
				$errorInfo = $sth->errorInfo();
				throw new DataBaseException('Device::save: ' . $errorInfo[2]);
			}
			$sth = NULL;
			self::$_IS_DIRTY_ = true;
			if($event) Dao::trigger(Dao::EVENT_UPDATE, $this);
		}
		$this->_memberId_changed = false;
		$this->_name_changed = false;
		$this->_fma_changed = false;
		$this->_udid_changed = false;
		$this->_credits_changed = false;
		$this->_creditsUpdatedTime_changed = false;
		$this->_state_changed = false;
		$this->_addTime_changed = false;
		$this->_timestamp_changed = false;
		$this->_fmaGifts_changed = false;
		Dao::cache($this);
	}
	/**
	 *
	 * @param $event
	 * @return void
	 */
	public function delete($event = false) {
		$sql = "delete from " . Device::TABLE_NAME . " where id = :id";
		$pdo = Dao::getPDOForWrite('sqlite');
		$sth = $pdo->prepare($sql);
		if($sth === false) {
			$errorInfo = $pdo->errorInfo();
			throw new DataBaseException("Device::delete: " . $errorInfo[2]);
		}
		$sth->bindValue(':id', $this->_id);
		if($sth->execute() === false) {
			$errorInfo = $sth->errorInfo();
			throw new DataBaseException('Device::delete: ' . $errorInfo[2]);
		}
		$sth = NULL;
		self::$_IS_DIRTY_ = true;
		if($event) Dao::trigger(Dao::EVENT_DELETE, $this);
		Dao::uncache($this);
	}
	public static function instance(&$row) {
		$o = new Device();
		$o->__saved = true;
		$o->_id = intval($row['ID']);
		if(array_key_exists('MEMBERID', $row)) {
			$o->_memberId = intval($row['MEMBERID']);
		}
		if(!array_key_exists('NAME', $row)) {
			$o->_name = LAZY_INIT;
		} else {
			$o->_name = $row['NAME'];
					}
		if(!array_key_exists('FMA', $row)) {
			$o->_fma = LAZY_INIT;
		} else {
			$o->_fma = $row['FMA'];
					}
		if(!array_key_exists('UDID', $row)) {
			$o->_udid = LAZY_INIT;
		} else {
			$o->_udid = $row['UDID'];
					}
		if(!array_key_exists('CREDITS', $row)) {
			$o->_credits = LAZY_INIT;
		} else {
			$o->_credits = $row['CREDITS'] === NULL? NULL : $row['CREDITS'] + 0;
		}
		if(!array_key_exists('CREDITSUPDATEDTIME', $row)) {
			$o->_creditsUpdatedTime = LAZY_INIT;
		} else {
			$o->_creditsUpdatedTime = $row['CREDITSUPDATEDTIME'];
					}
		if(!array_key_exists('STATE', $row)) {
			$o->_state = LAZY_INIT;
		} else {
			$o->_state = $row['STATE'] === NULL? NULL : $row['STATE'] + 0;
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
		if(!array_key_exists('FMAGIFTS', $row)) {
			$o->_fmaGifts = LAZY_INIT;
		} else {
			$o->_fmaGifts = $row['FMAGIFTS'];
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
		$keys = 'name, fma, udid, credits, creditsUpdatedTime, state, addTime, timestamp, memberId';
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
			if($obj->_fmaGifts_changed) {
				$columns[] = ":fmaGifts_$i";
				$params[":fmaGifts_$i"] = $obj->_fmaGifts;
			}
				$columns[] = ":name_$i";
				if($obj->_name_changed) {
					$params[":name_$i"] = $obj->_name;
				} else {
					$params[":name_$i"] = NULL;
				}
				$columns[] = ":fma_$i";
				if($obj->_fma_changed) {
					$params[":fma_$i"] = $obj->_fma;
				} else {
					$params[":fma_$i"] = NULL;
				}
				$columns[] = ":udid_$i";
				if($obj->_udid_changed) {
					$params[":udid_$i"] = $obj->_udid;
				} else {
					$params[":udid_$i"] = NULL;
				}
				$columns[] = ":credits_$i";
				if($obj->_credits_changed) {
					$params[":credits_$i"] = $obj->_credits;
				} else {
					$params[":credits_$i"] = NULL;
				}
				$columns[] = ":creditsUpdatedTime_$i";
				if($obj->_creditsUpdatedTime_changed) {
					$params[":creditsUpdatedTime_$i"] = $obj->_creditsUpdatedTime;
				} else {
					$params[":creditsUpdatedTime_$i"] = NULL;
				}
				$columns[] = ":state_$i";
				if($obj->_state_changed) {
					$params[":state_$i"] = $obj->_state;
				} else {
					$params[":state_$i"] = NULL;
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
			$sql = "insert into " . Device::TABLE_NAME . " ($keys) " . implode(' union ', $rows);
			$pdo = Dao::getPDOForWrite('sqlite');
			$sth = $pdo->prepare($sql);
			if($sth === false) {
				$errorInfo = $pdo->errorInfo();
				throw new DataBaseException("Device::batchInsert: " . $errorInfo[2]);
			}
			foreach($params as $pname=>$pvalue) {
				$sth->bindValue($pname, $pvalue);
			}
			if($sth->execute() === false) {
				$errorInfo = $sth->errorInfo();
				throw new DataBaseException('Device::batchInsert: ' . $errorInfo[2]);
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
				$obj->_fma_changed = false;
				$obj->_udid_changed = false;
				$obj->_credits_changed = false;
				$obj->_creditsUpdatedTime_changed = false;
				$obj->_state_changed = false;
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
	 * @return DeviceIterator
	 */
	public static function getIterator($conditions = array(), $orderBy = NULL, $from = 0, $size = 10000, $table = Device::TABLE_NAME) {
		$fields = 'id, memberid, name, fma, udid, credits, creditsupdatedtime, state, addtime, timestamp_, fmagifts';
		$pdo = Dao::getPDOForRead('sqlite', self::$_IS_DIRTY_);
		$sql = "select $fields from $table where " . Dao::pdoWhere($conditions, $params) . (empty($orderBy)? "": " order by $orderBy ") . " limit $size offset $from";
		$sth = $pdo->prepare($sql);
		if($sth === false) {
			$errorInfo = $pdo->errorInfo();
			throw new DataBaseException("Device::getIterator: " . $errorInfo[2]);
		}
		if($sth->execute($params) === false) {
			$errorInfo = $sth->errorInfo();
			throw new DataBaseException('Device::getIterator: ' . $errorInfo[2]);
		}
		$iterator = new DeviceIterator($sth);
		$sth = NULL;
		return $iterator;
	}
	private function _save_many() {
	}
}
class DeviceIterator implements Iterator {
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
		if($this->position != -1) throw new DataBaseException("don't call DeviceIterator::rewind again ..");
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
			$this->obj = Device::instance($row);
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
