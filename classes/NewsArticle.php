<?php
// NewsArticle.php
class NewsArticle {
    private $id;
    private $title;
    private $content;
    private $image;
    private $db;
    
    public function __construct($id = null) {
        $this->db = Database::getInstance();
        
        if ($id) {
            $this->loadArticle($id);
        }
    }
    
    //Metoda qe perdorim per marrjen e artikujve
    public static function getArticleById($id) {
        // Krijojme artikujt
        $articles = [
            1 => [
                'title' => 'Granit Xhaka nenshkruan me Bayer Leverkusen',
                'content' => 'Lojtari i kombëtares zvicerane Granit Xhaka kaloj nga ekipi gjigand anglez Arsenal ne Gjermani tek Bayer Leverkusen për 21.4 Milion £...',
                'image' => 'images/image3.jpg',
                'page' => 'faqja1.php'
            ],
            2 => [
                'title' => 'TikTok ndalohet ne USA',
                'content' => 'TikTok është ndaluar në disa shtete të SHBA-së për shkak të shqetësimeve mbi sigurinë kombëtare dhe mbrojtjen e të dhënave personale...',
                'image' => 'images/image4.jpg',
                'page' => 'faqja2.php'
            ],
            3 => [
                'title' => 'Donald Trump lanson kriptomonedhen $Trump',
                'content' => 'Donald Trump ka lançuar një kriptovalutë të re të quajtur $TRUMP. Kjo monedhë meme u prezantua më 17 janar 2025, disa ditë para inaugurimit të tij si president i Shteteve të Bashkuara. Në fillim, çmimi i $TRUMP u rrit me më shumë se 300% brenda një nate, duke arritur një vlerë tregu prej mbi 27 miliardë dollarësh. Pas lançimit të $MELANIA, një tjetër kriptovalutë e krijuar nga Melania Trump, vlera e $TRUMP ka pësuar rënie.',
                'image' => 'images/image5.jpg',
                'page' => 'faqja3.php'
            ],
            4 => [
                'title' => 'Lojtari i Manchester United kalon tek Real Betis',
                'content' => 'Antony, sulmuesi i Manchester United, është afër një marrëveshjeje huazimi te Real Betis deri në fund të sezonit 2024-2025. 
            Raportet sugjerojnë se marrëveshja është në fazë të avancuar dhe pritet të finalizohet së shpejti. 
            Nëse huazimi te Real Betis finalizohet, kjo mund të ofrojë mundësi të reja për Antony për të rikthyer formën e tij dhe për të kontribuar në betejen e Real Betis ne Spanje. ',
                'image' => 'images/image6.jpg',
                'page' => 'faqja4.php'
            ],
            // Shtojm meshum artikuj sipas nevojes
        ];
        
        return isset($articles[$id]) ? $articles[$id] : null;
    }
    
    public static function getAllArticles() {
        // Kthejme te gjith artikujt
        return [
            self::getArticleById(1),
            self::getArticleById(2),
            self::getArticleById(3),
            self::getArticleById(4)
        ];
    }
}