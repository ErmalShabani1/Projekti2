<?php
// BaseController.php
class BaseController {
    protected $user;
    protected $pageManager;
    protected $db;
    
    // Konstruktor: Inicializon bazen e te dhënave, krijon objektin e perdoruesit  
    // dhe menaxhon faqet nëse perdoruesi është i kyçur.
    public function __construct() {
        $this->db = Database::getInstance();
        $this->user = new User();
        
        if ($this->user->isLoggedIn()) {
            $this->pageManager = new PageManager($this->user->getUserId());
        }
    }
    
    //Kontrollon nese perdoruesi eshte i kyqur
    protected function requireLogin() {
        if (!$this->user->isLoggedIn()) {
            header("Location: login.php");
            exit();
        }
    }
    
    //Kontrollon nese perdoruesi eshte admin
    protected function requireAdmin() {
        if (!$this->user->isAdmin()) {
            header("Location: index.php");
            exit();
        }
    }

    //Shfaqim view sipas te dhenave te japura
    protected function render($view, $data = []) {
        extract($data);
        include_once "views/$view.php";
    }
}