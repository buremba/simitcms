<?php
define('PATH_BASE', url::base());
class Auth{
  var $dbTable  = 'users';
  var $fields = array(
  	'id'=> 'id', 
  	'login' => 'username',
  	'pass'  => 'password',
  	'mail' => 'email',
  );
  var $remember = 1296000; // 15 days
  var $cookieName = 'smauth';
  var $cookieDomain = PATH_BASE;
  var $passMethod = 'md5';
  
  var $userData=array();

  function __construct($dbTable = 'users', $idfield = 'id', $userfield= 'username', $passfield='password', $mailfield='email', $cookieName = 'smauth')
  {
		$this->cookieName = $cookieName;
		$this->dbTable = $dbTable;
        $this->fields = array('id'=> $idfield, 'login' => $userfield, 'pass'  => $passfield, 'mail' => $mailfield);
	    $this->cookieDomain = $this->cookieDomain == '' ? $_SERVER['HTTP_HOST'] : $this->cookieDomain;
	    if(isset($_COOKIE[$this->cookieName])){
        @list($id, $expiration, $hmac ) = explode('|', $_COOKIE[$this->cookieName]);
		$id = (int) $id;
		$this->userData = db::fetchone("SELECT * FROM `{$this->dbTable}` WHERE `{$this->fields['id']}` = '$id' LIMIT 1");
		$hash = hash_hmac( $this->passMethod, $id . $expiration . $this->userData['password'], SECRET_KEY);
			if ($hmac != $hash || $expiration < time()) {
				setcookie($this->cookieName, NULL, time()-600);
			}else {
				$this->loadUser($id, $this->userData);
			}
		}
  }
 
  function login($uname, $password, $remember, $redirect=FALSE)
  {
        $password = $this->encodepass($password);
		$res = db::fetchone("SELECT * FROM `{$this->dbTable}` WHERE `{$this->fields['login']}` = '$uname' AND `{$this->fields['pass']}` = '$password' LIMIT 1");
		if (!$res) {
			return false;
		} else {
			$this->userData = $res;
			$this->setcookie($remember);
		}
		db::update($this->dbTable, array('last_login' => time()), array($this->fields['login'] => $uname));
		if ($redirect && !headers_sent()) {header('Location: '.$redirect); exit;} else {
			return 2;
		}
	}
  /**
  	* Logout function
  	* param string $redirectTo
  	* @return bool
  */
  function setcookie($remember) {
	if (!$remember) {
		$remember = time()+86400;
		$cooktime=0;
	}else {
		$remember= time()+$this->remember;
		$cooktime=$remember;
	}
	$hash = hash_hmac( $this->passMethod, $this->get_property($this->fields['id']) . $remember . $this->userData['password'], SECRET_KEY);
	$cookie = $this->get_property($this->fields['id']) . '|' . $remember . '|' . $hash;
	return setcookie($this->cookieName, $cookie, $cooktime, '/');
  }
  
  function logout($redirectTo = false)
  {
	$this->userData = null;
	setcookie ($this->cookieName, "", time() - 3600, '/');
    if ($redirectTo){
	   header('Location: '.$redirectTo );
	   exit;
	}
	return true;
  }
  
    /**
  	* Get a property of a user. You should give here the name of the field that you seek from the user table
  	* @param string $property
  	* @return string
  */
  function get_property($property)
  {
	if (isset($this->userData[$property])) {
		return $this->userData[$property];
	}else {
		return false;
	}
  }
  
  /**
   * Is the user loaded?
   * @ return bool
   */
  function is_loaded() {
    return $this->get_property($this->fields['id']) ? true : false;
  }
  
  /**
  	* Activates the user account
  	* @return bool
  */
	function activate($user, $key) {
		$res = db::getvalue("SELECT count(*) FROM `{$this->dbTable}` WHERE `{$this->fields['id']}` = '$user' AND `activation` = '$key'");
		if($res==0) {
			return FALSE;
		} else {
			$res = db::sendquery("UPDATE `{$this->dbTable}` SET activation = null WHERE `id` = '".$user."'");
			return TRUE;
		}
	}
 
	function updateuser($id, $data){
		if (isset($data[$this->fields['pass']])) {
			$data[$this->fields['pass']] = $this->encodepass($this->passMethod, $data[$this->fields['pass']], SECRET_KEY);
		}
		$sql = db::update($this->dbTable, $data, array($this->fields['id'] => $this->get_property($this->fields['id'])));
		$this->userData = array_merge($this->userData, $data);
		$this->setcookie(false);
		return $sql;
	}
	
	function encodepass($pass) {
		return hash_hmac( $this->passMethod, $pass, SECRET_KEY);
	}
  
	function loadUser($id, $data = false) {	
		if(!$data) {
			$res = db::sendquery("SELECT * FROM `{$this->dbTable}` WHERE `{$this->fields['id']}` = '".$id."' LIMIT 1");
			if ( mysql_num_rows($res) == 0 ) {return false;}
			$this->userData = $data;
			$this->setcookie(false);
		}else {
			$this->userData = $data;
			$this->setcookie(false);
		}
		return true;
	}
	
	function getusersbydata($select, $data) {
		foreach ($data as $z => $b) {$wheres[] = "`{$z}` = '".mysql_real_escape_string($b)."'";}
		return db::fetchone("SELECT ".implode(',', $select)." FROM `{$this->dbTable}` WHERE ".implode(' AND ', $wheres));
	}

	function insertUser($data, $activation=false){
		$data[$this->fields['pass']] = $this->encodepass($data[$this->fields['pass']]);
		if($activation) {
			$data['activation'] = substr($this->encodepass(time()), 0, 12);
		}
		$sql = db::insert($this->dbTable, $data);
		if($sql) {
			return array('result' => true, $fields['id'] => mysql_insert_id());
		}else {
			if(mysql_errno() == '1062') {
				preg_match("/Duplicate entry '.*' for key '(.*)'/", mysql_error(), $matches);
				if($matches[1] == $this->fields['login']) {
					return array('result' => false, 'error' => array('duplicate' => $this->fields['login']));
				}else
				if($matches[1] == $this->fields['mail']) {
					return array('result' => false, 'error' => array('duplicate' => $this->fields['mail']));
				}
			}
			else {
				return array('result' => false, 'error' => 'undefined');
			}
		}
	}
 
	function is_free_mail($what) {
		$sql=mysql_result(db::sendquery("SELECT count(*)  FROM `{$this->dbTable}` WHERE `{$this->fields['email']}` = '$what'"), 0);
		if ($sql>0) {return FALSE;} else {return TRUE;}
	}
  
	function is_free_username($what) {
		$sql=mysql_result(db::sendquery("SELECT count(*)  FROM `{$this->dbTable}` WHERE `{$this->fields['login']}` = '$what'"), 0);
		if ($sql>0) {return FALSE;} else {return TRUE;}
	}
  
	function is_valid_username ($username, $min=4, $max= 15) {
		if(preg_match('/^[0-9a-z,_-]{'.$min.','.$max.'}$/i', $username)){return TRUE;} else{return FALSE;}
	}
	
	function is_valid_email($email)	{
		if(preg_match("/[a-zA-Z0-9_-.+]+@[a-zA-Z0-9-]+.[a-zA-Z]+/", $email)) {return TRUE;} else{return FALSE;}
	}
	
	function is_valid_password($password, $min=4, $max= 20)	{
		$password = trim($password);
		$eregi = eregi_replace('([a-zA-Z0-9%&-*!$/_]{'.$min.','.$max.'})','', $password);
		if(empty($eregi)){return TRUE;} else{return FALSE;}
	}
}
?>