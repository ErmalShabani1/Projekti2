<?php
// ArticleController.php
class ArticleController extends BaseController {
    //Metoda per paraqitjen e artikujve
    public function showArticle($id) {
        $this->requireLogin();
        
        $article = NewsArticle::getArticleById($id);
        $message = '';
        
        // Marrim komentet
        $comments = $this->getCommentsByPostId($id);
        
        // Menagjojme submisions
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //save page
            if (isset($_POST['save_page_name'])) {
                $page_name = $_POST['save_page_name'];
                if ($this->pageManager->savePage($page_name)) {
                    $message = "Page saved to your profile successfully!";
                } else {
                    $message = "Error saving page to your profile.";
                }
            } 
            // like page
            elseif (isset($_POST['like_page_name'])) {
                $page_name = $_POST['like_page_name'];
                if ($this->pageManager->likePage($page_name)) {
                    $message = "Page liked successfully!";
                } else {
                    $message = "Error liking the page.";
                }
            }
            // add comment
            elseif (isset($_POST['add_comment']) && isset($_POST['comment_text'])) {
                $commentText = trim($_POST['comment_text']);
                if (!empty($commentText)) {
                    if ($this->addComment($this->user->getUserId(), $id, $commentText)) {
                        $message = "Comment added successfully!";
                        // Bejm refresh komentet
                        $comments = $this->getCommentsByPostId($id);
                    } else {
                        $message = "Error adding comment.";
                    }
                } else {
                    $message = "Comment cannot be empty.";
                }
            } 
            // delete comment
            elseif (isset($_POST['delete_comment']) && isset($_POST['comment_id'])) {
                $commentId = $_POST['comment_id'];
                if ($this->deleteComment($commentId)) {
                    $message = "Comment deleted successfully!";
                    // Bejm refresh komentet
                    $comments = $this->getCommentsByPostId($id);
                } else {
                    $message = "Error deleting comment.";
                }
            }
        }
        
        $this->render('artikujt', [
            'article' => $article,
            'message' => $message,
            'comments' => $comments,
            'user' => $this->user
        ]);
    }
    
    // Metoda per menaxhimin e komenteve
    private function getCommentsByPostId($postId) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("
            SELECT c.*, u.username 
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.post_id = ?
        ");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $comments = [];
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }
        
        return $comments;
    }
    
    //meteoda per krijimin e komenteve
    private function addComment($userId, $postId, $comment) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("INSERT INTO comments (user_id, post_id, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $userId, $postId, $comment);
        return $stmt->execute();
    }
    
    //Metoda per fshirjen e komenteve
    private function deleteComment($commentId) {
        $conn = $this->db->getConnection();
        
        // Shikojm nese useri mund ta fshije ate koment
        $stmt = $conn->prepare("SELECT user_id FROM comments WHERE id = ?");
        $stmt->bind_param("i", $commentId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $commentUserId = $row['user_id'];
            
            // Vetem komentuesi ose admini mund te bejne fshirjen
            if ($this->user->getUserId() == $commentUserId || $this->user->isAdmin()) {
                $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
                $stmt->bind_param("i", $commentId);
                return $stmt->execute();
            }
        }
        
        return false;
    }
}