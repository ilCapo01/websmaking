<?php
/**
 * @author Bar771 ( https://twitter.com/bar771h )
 * github.com/bar771
 */

class Database {
	private $pdo = null;
	public static $host = 'localhost', $user = '', $pword = '', $dbname = '';

	function __construct($opt = array()) {
		$this->openConnection($opt);
	}

	public function openConnection($opt = array()) {
		try {
			if (!empty($opt) || count($opt) == 4) {
				$this->pdo = new PDO('mysql:dbname='.$opt['db'].';host='.$opt['host'], $opt['user'], $opt['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			}else{
				$this->pdo = new PDO('mysql:dbname='.self::db.';host='.self::host, self::user, self::pword, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			}
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		} catch (PDOException $e) {
			die ($e->getMessage());
		}
	}

	public function close() {
		return $this->pdo = null;
	}

	public function prepare($sql) {
		return $this->pdo->prepare($sql);
	}

	public function query($sql) {
		return $this->pdo->query($sql);
	}

	public function getRowsCount($table) {
		$stmt = $this->pdo->prepare('SELECT COUNT(*) AS count FROM '.$table);
		//$stmt->bindParam(':tablename', $table, PDO::PARAM_STR);
		$stmt->closeCursor();
		$stmt->execute();
		$row = $stmt->fetch();
		//return ($row['count'] == 0 ? 1 : $row['count']);
		return $row['count'];
	}
	
	public function getContentTotalContentSize($siteID) {
	    $pages = $this->prepare('SELECT * FROM `buildp_pages` WHERE siteid=?');
	    $pages->execute(array($siteID));
	    $pages = $pages->fetchAll();
	    
	    $site = $this->prepare('SELECT * FROM `buildp_sites` WHERE id=?');
	    $site->execute(array($siteID));
	    $site = $site->fetch();
	    
	    $total_size = 0;
	    $total_size += mb_strlen($site['header'], '8bit');
	    $total_size += mb_strlen($site['footer'], '8bit');
	    $total_size += mb_strlen($site['sidebar'], '8bit');
	    for ($i=0; $i<sizeof($pages); $i++) {
	        $total_size += mb_strlen($pages[$i]['text'], '8bit');
	    }
	    return $total_size;
	}
}

?>
