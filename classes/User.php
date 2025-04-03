<?php
// User.php
class User {
    private $id;
    private $username;
    private $email;
    private $role;
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->startSession();
    }
    
    private function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function login($username, $password) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($user = $result->fetch_assoc()) {
            if ($password === $user['password']) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                $this->id = $user['id'];
                $this->username = $user['username'];
                $this->role = $user['role'];
                
                return true;
            }
        }
        return false;
    }
    
    public function register($username, $email, $password) {
        $conn = $this->db->getConnection();
        
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return false;
        }
        
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
        $stmt->bind_param("sss", $username, $email, $password);
        return $stmt->execute();
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['id']);
    }
    
    public function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }
    
    public function logout() {
        session_unset();
        session_destroy();
    }
    
    public function getUserId() {
        return isset($_SESSION['id']) ? $_SESSION['id'] : null;
    }
    
    public function getUsername() {
        return isset($_SESSION['username']) ? $_SESSION['username'] : null;
    }
    
    public function getRole() {
        return isset($_SESSION['role']) ? $_SESSION['role'] : null;
    }
    
    // Metoda vetem per admin:
    
    //Metode per marrjen e te gjith userave
    public function getAllUsers() {
        if (!$this->isAdmin()) {
            return false;
        }
        
        $conn = $this->db->getConnection();
        $result = $conn->query("SELECT id, username, email, role FROM users ORDER BY id");
        
        $users = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        
        return $users;
    }
    

    //metoda per fshirjen e nje useri
    public function deleteUser($userId) {
        if (!$this->isAdmin() || $userId == $this->getUserId()) {
            return false; // Can't delete yourself or if not admin
        }
        
        $conn = $this->db->getConnection();
        
        // Delete user's saved and liked pages first to maintain referential integrity
        $stmt = $conn->prepare("DELETE FROM saved_pages WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
        
        $stmt = $conn->prepare("DELETE FROM liked_pages WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
        
        // Delete the user
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    //metoda per ndrrimin e rolit
    public function changeUserRole($userId, $newRole) {
        if (!$this->isAdmin() || $userId == $this->getUserId()) {
            return false; 
        }
        
        if ($newRole !== 'user' && $newRole !== 'admin') {
            return false;
        }
        
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $newRole, $userId);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
}