<?php


require_once dirname(__FILE__) . '/constants.php';
require_once dirname(__FILE__) . '/helpers.php';

// Session configuration
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
    session_start();
}

class Database {
    private $host = 'localhost';
    private $dbname = 'smiledesk';
    private $username = 'root';
    private $password = '';
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            die("Database connection failed. Please try again later.");
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}

// Global database instance
$db = new Database();
$pdo = $db->getConnection();

// Helper functions
function executeQuery($query, $params = []) {
    global $pdo;
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        // Add more detailed error message for debugging
        throw new Exception("Database operation failed: " . $e->getMessage() . " | Query: $query | Params: " . json_encode($params));
    }
}

function fetchAll($query, $params = []) {
    $stmt = executeQuery($query, $params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetchOne($query, $params = []) {
    $stmt = executeQuery($query, $params);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// $query = "
// CREATE TABLE IF NOT EXISTS `appointment_logs` (
//   `id` int(11) NOT NULL AUTO_INCREMENT,
//   `user_id` int(11) NOT NULL COMMENT 'Dentist who owns this log',
//   `appointment_id` int(11) NOT NULL,
//   `scheduled_time` time NOT NULL,
//   `actual_start_time` time DEFAULT NULL,
//   `actual_end_time` time DEFAULT NULL,
//   `wait_time` int(11) DEFAULT NULL,
//   `log_date` date NOT NULL,
//   `created_at` timestamp NULL DEFAULT current_timestamp(),
//   PRIMARY KEY (`id`),
//   KEY `user_id` (`user_id`),
//   KEY `appointment_id` (`appointment_id`)
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
// ";
// executeQuery($query);
?>
