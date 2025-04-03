<?php
// PageManager.php
class PageManager {
    private $db;
    private $userId;
    
    // Krijon nje instance te PageManager per nje perdorues te caktuar
    public function __construct($userId) {
        $this->db = Database::getInstance();
        $this->userId = $userId;
    }
    
    //Metoda per ruajtjen e nje faqeje
    public function savePage($pageName) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("INSERT INTO saved_pages (user_id, page_name) VALUES (?, ?)");
        $stmt->bind_param("is", $this->userId, $pageName);
        return $stmt->execute();
    }
    
    //Metoda per pelqimin e nje faqeje
    public function likePage($pageName) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("INSERT INTO liked_pages (user_id, page_name) VALUES (?, ?)");
        $stmt->bind_param("is", $this->userId, $pageName);
        return $stmt->execute();
    }
    
    //Metoda per marrjen e faqeve te ruajtura
    public function getSavedPages() {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT page_name FROM saved_pages WHERE user_id = ?");
        $stmt->bind_param("i", $this->userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    //Metoda per marrjen e faqeve te pelqyera
    public function getLikedPages() {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT page_name FROM liked_pages WHERE user_id = ?");
        $stmt->bind_param("i", $this->userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    //Metoda per largimin e nje faqe te ruajtur
    public function removeSavedPage($pageName) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("DELETE FROM saved_pages WHERE user_id = ? AND page_name = ?");
        $stmt->bind_param("is", $this->userId, $pageName);
        return $stmt->execute();
    }

    //Metoda per largimin e nje pelqimi
    public function removeLikedPage($pageName) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("DELETE FROM liked_pages WHERE user_id = ? AND page_name = ?");
        $stmt->bind_param("is", $this->userId, $pageName);
        return $stmt->execute();
    }
    
    //Metoda per marrjen e statistikave te faqes
    public static function getPageStats() {
        $db = Database::getInstance();
        $conn = $db->getConnection();
        
        $savedStats = [];
        $result = $conn->query("SELECT page_name, COUNT(user_id) AS user_count FROM saved_pages GROUP BY page_name HAVING user_count > 0");
        while ($row = $result->fetch_assoc()) {
            $savedStats[] = $row;
        }
        
        $likedStats = [];
        $result = $conn->query("SELECT page_name, COUNT(user_id) AS user_count FROM liked_pages GROUP BY page_name HAVING user_count > 0");
        while ($row = $result->fetch_assoc()) {
            $likedStats[] = $row;
        }
        
        return ['saved' => $savedStats, 'liked' => $likedStats];
    }
    
    // Metoda specifike per admin

    //metode per fshirjen e te gjitha faqeve te ruajtura
    public static function deleteAllSavedPages($pageName, User $user) {
        if (!$user->isAdmin()) {
            return false;
        }
        
        $db = Database::getInstance();
        $conn = $db->getConnection();
        
        $stmt = $conn->prepare("DELETE FROM saved_pages WHERE page_name = ?");
        $stmt->bind_param("s", $pageName);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    //metode per fshirjen e te gjitha faqeve te pelqyera
    public static function deleteAllLikedPages($pageName, User $user) {
        if (!$user->isAdmin()) {
            return false;
        }
        
        $db = Database::getInstance();
        $conn = $db->getConnection();
        
        $stmt = $conn->prepare("DELETE FROM liked_pages WHERE page_name = ?");
        $stmt->bind_param("s", $pageName);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    //metode per fshirjen e te gjitha faqeve te ruajtura
    public static function deleteAllPageReferences($pageName, User $user) {
        if (!$user->isAdmin()) {
            return false;
        }
        
        $savedResult = self::deleteAllSavedPages($pageName, $user);
        $likedResult = self::deleteAllLikedPages($pageName, $user);
        
        return $savedResult || $likedResult;
    }
}