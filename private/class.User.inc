<?php
class User {

	public $id;
	public $branch_id;
	public $branch;
	public $dept_id;
	public $dept;
	public $email;
	public $full_name;
	public $pwd;
	public $hashed_pwd;
	public $ddi;
	public $admin = "0";
	public $su = "0";
	public $table;
	public $field;

	private static function instantiate($result) {
		$object = new self;
		// use describe_table() to cycle thru fields
		isset($result["id"]) ? $object->id = $result["id"] : false ;

		$object->table = 'chiamate_bc';

		if (isset($result["branch_id"])) {
			$object->branch_id = $result["branch_id"];
			$user_branch = Branch::find_by_id($object->branch_id);
			isset($user_branch->filiale) ? $object->branch = $user_branch->filiale : false ;
		} else {
			$object->branch = '';
		}
		if (isset($result["dept_id"])) {
			$object->dept_id = $result["dept_id"];
			$user_dept = Dept::find_by_id($object->dept_id);
			isset($user_dept->reparto) ? $object->dept = $user_dept->reparto : false ;
			if (strpos($user_dept->reparto, 'hunter') !== false) {
				$object->table = 'chiamate_sales';
			}
			if (strpos($user_dept->reparto, 'farmer') !== false) {
				$object->table = 'chiamate_sales';
			}
			if (strpos($user_dept->reparto, 'ops') !== false) {
				$object->table = 'chiamate_ops';
			}
		} else {
			$object->dept = '';
		}

		isset($result["email"]) ? $object->email = $result["email"] : false ;
		isset($result["full_name"]) ? $object->full_name = $result["full_name"] : false ;
		isset($result["hashed_pwd"]) ? $object->hashed_pwd = $result["hashed_pwd"] : false ;
		//get password from hash?
		isset($result["ddi"]) ? $object->ddi = $result["ddi"] : false ;
		isset($result["admin"]) ? $object->admin = $result["admin"] : false ;
		isset($result["su"]) ? $object->su = $result["su"] : false ;
		isset($result["Field"]) ? $object->field = $result["Field"] : false ;
		return $object;
	}

	public static function find_by_sql($sql="") {
		global $local_db;
		$result_set = $local_db->query($sql);
		$object_array = array();
		while ($result = mysqli_fetch_assoc($result_set)) {
			$object_array[] = self::instantiate($result);
		}
		return $object_array;
	}

	public static function describe_table() {
		return self::find_by_sql("DESCRIBE users");
	}

	public static function find_all() {
		return self::find_by_sql("SELECT * FROM users");
	}

	public static function find_by_id($id=1) {
		global $local_db;
		$sql = 'SELECT * FROM users WHERE id = "';
		$sql .= $local_db->escape_string($id);
		$sql .= '" LIMIT 1';
		$result = self::find_by_sql($sql);
		return !empty($result) ? array_shift($result) : false ;
		// $result_array = self::find_by_sql("SELECT * FROM users WHERE id = {$id};");
		// return !empty($result_array) ? array_shift($result_array) : false ;
	}

	public static function find_by_full_name($full_name='') {
		global $local_db;
		$sql = 'SELECT * FROM users WHERE full_name = "';
		$sql .= $local_db->escape_string($full_name);
		$sql .= '" LIMIT 1';
		$result = self::find_by_sql($sql);
		return !empty($result) ? array_shift($result) : false ;
		// $result_array = self::find_by_sql("SELECT * FROM users WHERE full_name = '{$full_name}';");
		// return !empty($result_array) ? array_shift($result_array) : false ;
	}

	public static function find_by_email($email='') {
		global $local_db;
		$sql = 'SELECT * FROM users WHERE email = "';
		$sql .= $local_db->escape_string($email);
		$sql .= '" LIMIT 1';
		$result = self::find_by_sql($sql);
		return !empty($result) ? array_shift($result) : false ;
		// $result_array = self::find_by_sql("SELECT * FROM users WHERE email = '{$email}' LIMIT 1;");
		// return !empty($result_array) ? array_shift($result_array) : false ;
	}

	public static function find_by_dept_id($dept_id='1') {
		global $local_db;
		$sql = 'SELECT * FROM users WHERE dept_id = ';
		$sql .= $local_db->escape_string($dept_id);
		return self::find_by_sql($sql);
		// return self::find_by_sql("SELECT * FROM users WHERE dept_id = '{$dept_id}';");
	}

	protected function set_branch_from_branch_id() {
		$user_branch = Branch::find_by_id($this->branch_id);
		isset($user_branch->filiale) ? $this->branch = $user_branch->filiale : false ;
	}
	protected function set_branch_id_from_branch() {
		$user_branch = Branch::find_by_filiale($this->branch);
		isset($user_branch->id) ? $this->branch_id = $user_branch->id : false ;
	}

	protected function set_dept_from_dept_id() {
		$user_dept = Dept::find_by_id($this->dept_id);
		isset($user_dept->reparto) ? $this->dept = $user_dept->reparto : false ;
	}
	protected function set_dept_id_from_dept() {
		$user_dept = Dept::find_by_reparto($this->dept);
		isset($user_dept->id) ? $this->dept_id = $user_dept->id : false ;
	}

	//CRUD
	public function save() {
		return isset($this->id) ? $this->update() : $this->create() ;
	}

	protected function create() {
		global $local_db;
		$this->hashed_pwd = self::password_encrypt($this->pwd);

		isset($this->branch) ? $this->set_branch_id_from_branch() : false ;
		isset($this->dept) ? $this->set_dept_id_from_dept() : false ;

		$sql = "INSERT INTO users (";
		$sql .= "branch_id, dept_id, email, full_name, admin, su, hashed_pwd";
		$sql .= ") VALUES ('";
		$sql .= $local_db->escape_string($this->branch_id) ."', '";
		$sql .= $local_db->escape_string($this->dept_id) ."', '";
		$sql .= $local_db->escape_string($this->email) ."', '";
		$sql .= $local_db->escape_string($this->full_name) ."', '";
		$sql .= $local_db->escape_string($this->admin) ."', '";
		$sql .= $local_db->escape_string($this->su) ."', '";
		$sql .= $this->hashed_pwd ."')";
		if ($local_db->query($sql)) {
			$this->id = $local_db->insert_id();
			return true;
		} else {
			return false;
		}
	}

	protected function update() {
		global $local_db;
		$this->hashed_pwd = self::password_encrypt($this->pwd);

		isset($this->branch) ? $this->set_branch_id_from_branch() : false ;
		isset($this->dept) ? $this->set_dept_id_from_dept() : false ;

		$sql = "UPDATE users SET ";
		$sql .= "branch_id='". $local_db->escape_string($this->branch_id) . "', ";
		$sql .= "dept_id='". $local_db->escape_string($this->dept_id) . "', ";
		$sql .= "email='". $local_db->escape_string($this->email) . "', ";
		$sql .= "full_name='". $local_db->escape_string($this->full_name) . "', ";
		$sql .= "admin='". $local_db->escape_string($this->su) . "', ";
		$sql .= "hashed_pwd='". $this->hashed_pwd . "' ";
		$sql .= "WHERE id=". $local_db->escape_string($this->id);
		$sql .= " LIMIT 1";
		$local_db->query($sql);
		return ($local_db->affected_rows() == 1) ? true : false ;
	}

	public function delete() {
		global $local_db;
		$sql = "DELETE FROM users ";
		$sql .= "WHERE id = ". $local_db->escape_string($this->id);
		$sql .= " LIMIT 1";
		$local_db->query($sql);
		return ($local_db->affected_rows() == 1) ? true : false ;
	}

	public static function generate_salt($length) {
		// mtrand produces a random string
		// uniqid produces a unique id
		// md5 hashes it to 32 characters
		  $unique_random_string = md5(uniqid(mt_rand(), true));
		// we use base64 to convert to valid salt chars:[a-zA-Z0-9./]
		  $base64_string = base64_encode($unique_random_string);
		// but we need to replace '+' with '.'
		  $modified_base64_string = str_replace('+', '.', $base64_string);
		// truncate new string to correct length
		  $salt = substr($modified_base64_string, 0, $length);
		return $salt;
	}

	private	function password_encrypt($password) {
		$hash_format = "$2y$10$";	// Tells PHP to use Blowfish with a "cost" of 10
		$salt_length = 22; 		// Blowfish salts should be 22-characters or more
		$salt = self::generate_salt($salt_length);
		$format_and_salt = $hash_format . $salt;
		$hash = crypt($password, $format_and_salt);
		return $hash;
	}

	private function password_check($pwd) {
	  $hash = crypt($pwd, $this->hashed_pwd);
	  if ($hash === $this->hashed_pwd) {
	    return true;
	  } else {
	    return false;
	  }
	}

	public static function authenticate($email='', $password='') {
		global $local_db;
		$email = $local_db->escape_string($email);
		$password = $local_db->escape_string($password);
		$user = self::find_by_email($email);
		if ($user) {
			if ($user->password_check($password)) {
					return $user;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

}

?>
