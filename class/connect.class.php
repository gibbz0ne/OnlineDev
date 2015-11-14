<?PHP
	date_default_timezone_set('Asia/Manila');
	session_start();
    class getConnection{
     
        private $host = "apecsystem";
        //private $host = "10.33.187.129";
        private $username = "admin";
        private $password = "admin#123";
        private $db_name = "dummy_apec";
        public $conn;
		
        // get the database connection
        public function MySQLi(){
			
            $this->conn = null;
			
			$this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
			if($this->conn == null){
				die('Unable to connect to database.');
				exit;
			}
			
            return $this->conn;
        }
		
		public function MySQL(){
			
            $this->conn = null;
			
			$this->conn = mysql_connect($this->host, $this->username, $this->password, $this->db_name);
			if($this->conn == null){
				die('Unable to connect to database.');
				exit;
			}
			
            return $this->conn;
        }
		
		public function PDO() {
			
			$this->conn = null;

            try{
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
				$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->conn->query("SET NAMES 'utf8'");
				$this->conn->query("SET CHARACTER SET utf8");
				$this->conn->query("SET COLLATION_CONNECTION = 'utf8_unicode_ci'");
            }catch(PDOException $exception){
                echo "Connection error: " . $exception->getMessage();
            }

            return $this->conn;
		}
		
		public function PDO2() {
			
			$this->conn = null;

            try{
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=projectydb_new;", $this->username, $this->password);
				$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->conn->query("SET NAMES 'utf8'");
				$this->conn->query("SET CHARACTER SET utf8");
				$this->conn->query("SET COLLATION_CONNECTION = 'utf8_unicode_ci'");
            }catch(PDOException $exception){
                echo "Connection error: " . $exception->getMessage();
            }

            return $this->conn;
		}
    }
?>