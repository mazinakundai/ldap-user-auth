<?php
class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function fetchUsersGrouped() {
        $stmt = $this->pdo->query("SELECT uid, first_name, last_name, email, department, company FROM users ORDER BY company, department");
        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[$row['company']][$row['department']][] = $row['username'];
        }
        return $users;
    }
}
?>
