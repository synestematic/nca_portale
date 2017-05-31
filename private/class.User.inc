<?php
class User {

	public $id;
	public $branch_id;
	public $branch;
	public $dept_id;
	public $dept;

	public $is_branch = false;
	public $is_agency = false;
	public $nas_share = "";
	public $main_share = "";
	public $upload_file = "";

	public $email;
	public $full_name;
	public $initials;
	public $address = '';
	public $pwd;
	public $hashed_pwd;
	public $ddi;
	public $access = "0";
	public $su = "0";
	public $table;
	public $field;
	public $last_sql = "";

	public $tmp_dir ;

	private static function instantiate($result, $sql) {
		$object = new self;
		isset($result["id"]) ? $object->id = $result["id"] : false ;
		$object->tmp_dir = 'tmp'.DS.$object->id.DS;

		$object->table = 'chiamate_bc';

		isset($result["address"]) ? $object->address = $result["address"] : false ;

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
			if (strpos($user_dept->reparto, 'ops') !== false) {
				$object->table = 'chiamate_ops';
			}
		} else {
			$object->dept = '';
		}

		if (isset($result["email"])) {
			$object->email = $result["email"];

			$email_section = explode(".", $object->email);
			if (isset($email_section[1]) && $email_section[1] === 'branch@auto1') {
				$object->is_branch = true;
				$object->nas_share = $email_section[0];
				$object->main_share = 'BRANCHES'.DS.'BRCH_'.$object->nas_share.DS.strftime("%Y").DS ;
			}
			if (isset($email_section[1]) && $email_section[1] === 'agency@auto1') {
				$object->is_agency = true;
				$object->nas_share = $email_section[0];
				$object->main_share = 'AGENZIE'.DS.'AG_'.$object->nas_share.DS.strftime("%Y").DS ;
			}
		}

		isset($result["full_name"]) ? $object->full_name = $result["full_name"] : false ;
		isset($result["hashed_pwd"]) ? $object->hashed_pwd = $result["hashed_pwd"] : false ;
		isset($result["ddi"]) ? $object->ddi = $result["ddi"] : false ;
		isset($result["access"]) ? $object->access = $result["access"] : false ;
		isset($result["su"]) ? $object->su = $result["su"] : false ;
		isset($result["Field"]) ? $object->field = $result["Field"] : false ;
		$object->last_sql = $sql;
		return $object;
	}

	public static function find_by_sql($sql="") {
		global $local_db;
		$result_set = $local_db->query($sql);
		$object_array = array();
		while ($result = mysqli_fetch_assoc($result_set)) {
			$object_array[] = self::instantiate($result , $sql);
		}
		return $object_array;
	}

	public static function describe_table() {
		return self::find_by_sql("DESCRIBE users");
	}

	public static function find_all($requested_field="id", $order="ASC") {
		return self::find_by_sql("SELECT * FROM users ORDER BY $requested_field $order");
	}

	public static function find_hr_users($requested_field="id", $order="ASC") {
		return self::find_by_sql("SELECT * FROM users WHERE email NOT LIKE '%branch%' AND email NOT LIKE '%agency%' ORDER BY $requested_field $order");
	}

	public static function set_order($requested_field, $order, $existing_field) {
			$foo = false ;
			if ( $existing_field !== $requested_field ) {
					$order = 'ASC';
			}
			if ( $existing_field === $requested_field && $order === 'ASC') {
				  $order = 'DESC';
				  $foo = true;
			}
			if ( $existing_field === $requested_field && $order === 'DESC' && $foo !== true ) {
				  $order = 'ASC';
			}
			return $order ;
	}

	public static function find_by_id($id=1) {
		global $local_db;
		$sql = 'SELECT * FROM users WHERE id = "';
		$sql .= $local_db->escape_string($id);
		$sql .= '" LIMIT 1';
		$result = self::find_by_sql($sql);
		return !empty($result) ? array_shift($result) : false ;
	}

	public static function find_by_full_name($full_name='') {
		global $local_db;
		$sql = 'SELECT * FROM users WHERE full_name = "';
		$sql .= $local_db->escape_string($full_name);
		$sql .= '" LIMIT 1';
		$result = self::find_by_sql($sql);
		return !empty($result) ? array_shift($result) : false ;
	}

	public static function find_by_email($email='') {
		global $local_db;
		$sql = 'SELECT * FROM users WHERE email = "';
		$sql .= $local_db->escape_string($email);
		$sql .= '" LIMIT 1';
		$result = self::find_by_sql($sql);
		return !empty($result) ? array_shift($result) : false ;
	}

	public function set_branch($branch='') {
		$this->branch === $branch;
		$this->set_branch_id_from_branch();
	}

	public static function find_by_dept_id($dept_id='1') {
		global $local_db;
		$sql = 'SELECT * FROM users WHERE dept_id = ';
		$sql .= $local_db->escape_string($dept_id);
		return self::find_by_sql($sql);
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

	public function get_initials() {
		$names = explode(" ", $this->full_name);
		foreach ($names as $name) {
		  $this->initials .= $name[0];
		}
		return $this->initials ;
	}

	public function set_upload_filename($stock_id="", $targa="", $day_contents) {
			// SET FILENAME FORMAT (branch vs agency)
			if ($this->is_branch) {
					$this->upload_file = basename($stock_id)."_".basename($targa);
			}
			if ($this->is_agency) {
					$this->upload_file = basename($targa);
			}
			// CHECK if dir exists NOT NEEDED ATM
			// foreach ($day_contents as $item) {
			//     if ( $item->getName() === $this->upload_file . '.pdf' ) {
			//       $this->upload_file = $this->upload_file . '_copia';
			//     }
			// }
			$this->upload_file = $this->upload_file . '.pdf';
			}

			// public function check_exists_or_rename($filename) {
			// 	// RECURSIVE FUNCTION Where would this go ???
			// 	if ( $filename === $this->upload_file . '.pdf' ) {
			// 		return $filename ;
			// 	} else {
			// 		check_exists_or_rename($filename.'_copia');
			// 	}
			// }

	//////////////// CRUD ////////////////
	public function save() {
		return isset($this->id) ? $this->update() : $this->create() ;
	}

	protected function create() {
		global $local_db;
		$this->hashed_pwd = self::password_encrypt($this->pwd);

		isset($this->branch) ? $this->set_branch_id_from_branch() : false ;
		isset($this->dept) ? $this->set_dept_id_from_dept() : false ;

		$sql =  "INSERT INTO users (";
		$sql .= "branch_id,dept_id,email,full_name,access,su,hashed_pwd";
		$sql .= ") VALUES (";
		$sql .= "'".$local_db->escape_string($this->branch_id) ."',";
		$sql .= "'".$local_db->escape_string($this->dept_id) ."',";
		$sql .= "'".$local_db->escape_string($this->email) ."',";
		$sql .= "'".$local_db->escape_string($this->full_name) ."',";
		$sql .= $local_db->escape_string($this->access) .",";
		$sql .= $local_db->escape_string($this->su) .",";
		$sql .= "'".$this->hashed_pwd ."'";
		$sql .= ")";
		if ($local_db->query($sql)) {
			$this->id = $local_db->insert_id();
			$this->last_sql = $sql;
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
		$sql .= "branch_id='". $local_db->escape_string($this->branch_id) . "',";
		$sql .= "dept_id='". $local_db->escape_string($this->dept_id) . "',";
		$sql .= "email='". $local_db->escape_string($this->email) . "',";
		$sql .= "full_name='". $local_db->escape_string($this->full_name) . "',";
		$sql .= "access=". $local_db->escape_string($this->access) . ",";
		$sql .= "su=". $local_db->escape_string($this->su) . ", ";
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

	//////////////// AUTHENTICATION ////////////////

	public static function authenticate($email='', $password='') {
		global $local_db;
		$user = self::find_by_email($local_db->escape_string($email));
		if ($user && $user->access === '1' && $user->password_check($local_db->escape_string($password))) {
			return $user;
		} else {
			return false;
		}
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
		$hash_format = "$2y$10$";	// use Blowfish with a "cost" of 10
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

}

?>
