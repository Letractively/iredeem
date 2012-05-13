<?php
define('UN_SET', '_this_is_a_unseted_value_');
define('LAZY_INIT', '_this_is_a_lazy_value_');
class Dao {
	public static $_cache = array();
	public static $listner = NULL;
	const EVENT_INSERT = 'insert';
	const EVENT_UPDATE = 'update';
	const EVENT_DELETE = 'delete';
	/**
	 *
	 * Enter description here ...
	 * @param string/object $obj object or class name
	 */
	public static function uncache($obj = NULL) {
		if($obj == NULL) {
			foreach(array_keys(self::$_cache) as $cn) {
				if(isset(self::$_cache[$cn])) {
					self::$_cache[$cn] = array();
				}
			}
        } elseif(is_string($obj)) {
			$classname = $obj;
			if(isset(self::$_cache[$classname])) {
				self::$_cache[$classname] = array();
			}
		} else {
			$classname = get_class($obj);
			if($classname != false && isset(self::$_cache[$classname])) {
				if($obj->id() != NULL && $obj->id() != UN_SET && isset(self::$_cache[$classname][$obj->id()])) {
					unset(self::$_cache[$classname][$obj->id()]);
				}
			}
		}
	}
	public static function cache($obj){
		$classname = get_class($obj);
		if($classname != false && isset(self::$_cache[$classname])
				&& $obj->id() != NULL && $obj->id() != UN_SET)
			self::$_cache[$classname][$obj->id()] = clone $obj;
	}
	public static function get($classname, $id, $clone = true) {
		if(isset(self::$_cache[$classname]) && isset(self::$_cache[$classname][$id])) {
			if($clone)
				return clone self::$_cache[$classname][$id];
			else
				return self::$_cache[$classname][$id];
		} else
			return NULL;
	}
	public static function trigger($event, $object) {
		if(function_exists(self::$listner)) {
			$listner = self::$listner;
			$listner($event, $object);
		}
	}
	public static function commit($newTransaction = true) {
		global $_DB_;
		foreach ($_DB_ as $_alias=>$_db) {
			if(self::hasPDOForWrite($_alias)) {
				$dbh = self::getPDOForWrite($_alias);
				$dbh->commit();
				if($newTransaction) $dbh->beginTransaction();
			}
		}
	}
	public static function rollback($newTransaction = true) {
		global $_DB_;
		foreach ($_DB_ as $_alias=>$_db) {
			if(self::hasPDOForWrite($_alias)) {
				$dbh = self::getPDOForWrite($_alias);
				$dbh->rollBack();
				if($newTransaction) $dbh->beginTransaction();
			}
		}
	}
	private static function getPDO($type, $db) {
		if(DEBUG) {
			$dbh = new MyPDO($db['dsn'], $db['user'], $db['psw']);
		} else {
			$dbh = new PDO($db['dsn'], $db['user'], $db['psw']);
		}
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_UPPER);
		if($type == 'mysql') {
			$dbh->exec('SET NAMES UTF8');
			$dbh->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');
		} else if($type == 'oracle') {
			$dbh->exec("alter session set nls_date_format='YYYY-MM-DD HH24:MI:SS'");
		}
		return $dbh;
	}
	/**
	 *
	 * @return PDO
	 */
	public static function getPDOForWrite($alias){
		global $_DB_;
		if(isset($_DB_[$alias]['master'])) {
			if(isset($_DB_[$alias]['master']['_PDO_'])) {
				return $_DB_[$alias]['master']['_PDO_'];
			} else {
				$dbh = self::getPDO($_DB_[$alias]['type'], $_DB_[$alias]['master']);
				$_DB_[$alias]['master']['_PDO_'] = $dbh;
				$dbh->beginTransaction();
				return $dbh;
			}
		} else {
			if(isset($_DB_[$alias]['_PDO_'])) {
				return $_DB_[$alias]['_PDO_'];
			} else {
				$dbh = self::getPDO($_DB_[$alias]['type'], $_DB_[$alias]);
				$_DB_[$alias]['_PDO_'] = $dbh;
				$dbh->beginTransaction();
				return $dbh;
			}
		}
	}
	/**
	 *
	 * @return boolean
	 */
	private static function hasPDOForWrite($alias){
		global $_DB_;
		if(isset($_DB_[$alias]['master'])) {
			if(isset($_DB_[$alias]['master']['_PDO_'])) {
				return true;
			} else {
				return false;
			}
		} else {
			if(isset($_DB_[$alias]['_PDO_'])) {
				return true;
			} else {
				return false;
			}
		}
	}
	/**
	 *
	 * @return PDO
	 */
	public static function getPDOForRead($alias, $dirty = false){
		global $_DB_;
		if($dirty || (!isset($_DB_[$alias]['slaves']))) {
			return self::getPDOForWrite($alias);
		}
		if(isset($_DB_[$alias]['slaves']['_PDO_'])) {
			return $_DB_[$alias]['slaves']['_PDO_'];
		} else {
			$db = $_DB_[$alias]['slaves'][rand(0, count($_DB_[$alias]['slaves']) - 1)];
			$dbh = self::getPDO($_DB_[$alias]['type'], $db);
			$_DB_[$alias]['slaves']['_PDO_'] = $dbh;
			return $dbh;
		}
	}
	public static function pdoWhere($where, &$params){
		$keys = array_keys($where);
		$parts = array();
		$used = array();
		foreach ($keys as $pk) {
			preg_match('/^([a-zA-Z0-9_\.]*)(.*)$/', $pk, $matches);
			$column = $matches[1];//strpos($matches[1], '.')===false?"_.{$matches[1]}":$matches[1];
			$operator = $matches[2]===''?'=':$matches[2];
			$paramName = str_replace('.', '_', $column);
			if(isset($used[":$paramName"])) {
				$i = 0;
				while(isset($used[":{$paramName}_$i"])) {
					$i++;
				}
				$paramName = "{$paramName}_$i";
			}
			switch ($operator) {
				case '=':
					if($where[$pk] === null) {
						$parts[] = " $column is null ";
						break;
					}
				case '!=':
				case '<>':
					if($where[$pk] === null) {
						$parts[] = " $column is not null ";
						break;
					}
				case '>':
				case '>=':
				case '<':
				case '<=':
					if(is_array($where[$pk])) {
						foreach($where[$pk] as $j=>$value) {
							$parts[] = " $column $operator :{$paramName}_$j ";
							$used[":{$paramName}_$j"] = $value;
						}
					} else {
						$parts[] = " $column $operator :$paramName ";
						$used[":$paramName"] = $where[$pk];
					}
					break;
				case '%':
					if(is_array($where[$pk])) {
						foreach($where[$pk] as $j=>$value) {
							$parts[] = " $column like :{$paramName}_$j ";
							$used[":{$paramName}_$j"] =  '%'.$value.'%';
						}
					} else {
						$parts[] = " $column like :$paramName ";
						$used[":$paramName"] =  '%'.$where[$pk].'%';
					}
					break;
				case '^':
					if(is_array($where[$pk])) {
						foreach($where[$pk] as $j=>$value) {
							$parts[] = " $column like :{$paramName}_$j ";
							$used[":{$paramName}_$j"] =  $value.'%';
						}
					} else {
						$parts[] = " $column like :$paramName ";
						$used[":$paramName"] =  $where[$pk].'%';
					}
					break;
				case '$':
					if(is_array($where[$pk])) {
						foreach($where[$pk] as $j=>$value) {
							$parts[] = " $column like :{$paramName}_$j ";
							$used[":{$paramName}_$j"] =  '%'.$value;
						}
					} else {
						$parts[] = " $column like :$paramName ";
						$used[":$paramName"] =  '%'.$where[$pk];
					}
					break;
				case '*':
					$value = $where[$pk];
					$in = array();
					foreach($value as $f) $in[] = $pdo->quote($f);
					$parts[] = " $column in (".implode(',', $in).") ";
					break;
				case '@':
					if(is_array($where[$pk])) {
						foreach($where[$pk] as $j=>$value) {
							$parts[] = " $column in ({$value}) ";
						}
					} else {
						$parts[] = " $column in ({$where[$pk]}) ";
					}
					break;
				default:
					;
			}
		}
		if($parts)
		$ret = implode(' AND ', $parts);
		else
		$ret = ' 1=1 ';
		$params = $used;
		return $ret;
	}
}
$_DOMAIN_CLASSES_ = array();
function _autoload_dao_classes($name){
	global $_DOMAIN_CLASSES_;
	if(array_key_exists($name, $_DOMAIN_CLASSES_)) {
		require_once(dirname(__FILE__) . "/Dao.$name.php");
		return true;
	} else {
		return false;
	}
}
spl_autoload_register('_autoload_dao_classes');
$_DOMAIN_CLASSES_['Member'] = array(
	'name' => 'Member',
);
$_DOMAIN_CLASSES_['Device'] = array(
	'name' => 'Device',
);
$_DOMAIN_CLASSES_['Country'] = array(
	'name' => 'Country',
);
$_DOMAIN_CLASSES_['Code'] = array(
	'name' => 'Code',
);
$_DOMAIN_CLASSES_['Log'] = array(
	'name' => 'Log',
);
