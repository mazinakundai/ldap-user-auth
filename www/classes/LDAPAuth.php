<?php
class LDAPAuth {
    private $ldap_host;
    private $ldap_dn;
    private $mysql_servername;
    private $mysql_username;
    private $mysql_password;
    private $mysql_dbname;
    private $pdo;

    public function __construct() {
        $this->ldap_host = getenv('LDAP_HOST') ?: 'ldap-server';
        $this->ldap_dn = getenv('LDAP_BASE_DN') ?: 'dc=mycompany,dc=com';
        $this->mysql_servername = getenv('MYSQL_HOST');
        $this->mysql_username = getenv('MYSQL_USER');
        $this->mysql_password = getenv('MYSQL_PASSWORD');
        $this->mysql_dbname = getenv('MYSQL_DATABASE');
        try {
            $dsn = "mysql:host=$this->mysql_servername;dbname=$this->mysql_dbname;charset=utf8mb4";
            $this->pdo = new PDO($dsn, $this->mysql_username, $this->mysql_password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connecting to the database...\n";
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }

    }

    public function getUser($uid) {
        $sql = "SELECT uid, uid_number, first_name, last_name, email, department, company, `group_id` 
                FROM users 
                WHERE uid = :username";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
    
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        $this->pdo = null; // Close the connection
    
        return $user;
    }
    

    public function authenticate($username, $password) {
        $user = $this->getUser($username);
        if (!empty($user)) {
            $cn = "{$user['first_name']} {$user['last_name']}";
        } else {
            die("User does not exist ...");
        }

        $ldap = ldap_connect($this->ldap_host);
        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

        // Construct the DN for binding
        $dn = "cn={$cn},ou=users,{$this->ldap_dn}"; // Adjust as needed, e.g., using uid=$username instead of cn=$username
        echo "Attempting to bind with DN: $dn"; // Debugging

        if ($bind = @ldap_bind($ldap, $dn, $password)) {
            $filter = "(uid=$username)"; // Adjust based on your LDAP schema, e.g., sAMAccountName, cn, uid
            $result = ldap_search($ldap, $this->ldap_dn, $filter);
            $entries = ldap_get_entries($ldap, $result);

            if ($entries['count'] > 0) {
                return true;
            }
        } else {
            echo "LDAP bind failed"; // Debugging
        }
        return false;
    }
}
?>
