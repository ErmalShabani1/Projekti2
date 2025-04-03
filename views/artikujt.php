<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($article['title']) ?></title>
    <link rel="stylesheet" href="lajmet.css">
</head>
<body>
    <header id="head1">
        <button id="LajmetButton"><a href="index.php">Home</a></button>
        <h1 id="T1">Rreth Focus Flow!</h1>
    </header>
    <section id="sec1">
        <h2 id="T2"><?= htmlspecialchars($article['title']) ?></h2>
        <p id="T3">
            <img src="<?= htmlspecialchars($article['image']) ?>" id="image1"> <br>
            <?= htmlspecialchars($article['content']) ?>
        </p>
        <form method="POST" action="">
            <input type="hidden" name="save_page_name" value="<?= htmlspecialchars($article['page']) ?>">
            <input type="submit" value="Save to Profile">
        </form>

        <form method="POST" action="">
            <input type="hidden" name="like_page_name" value="<?= htmlspecialchars($article['page']) ?>">
            <input type="submit" value="Like Page">
        </form>

        <?php if ($message): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        
        <!-- Comments Section -->
        <div class="comments-section">
            <h3>Comments</h3>
            
            <!-- Add Comment Form -->
            <form method="POST" action="" class="comment-form">
                <textarea name="comment_text" placeholder="Add your comment here..." class="comment-text" required></textarea>
                <button type="submit" name="add_comment" class="comment-submit">Post Comment</button>
            </form>
            
            <!-- Display Existing Comments -->
            <?php if (!empty($comments)): ?>
                <div class="comments-list">
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment">
                            <div class="comment-header">
                                <strong><?= htmlspecialchars($comment['username']) ?></strong>
                                
                                <?php if ($user->getUserId() == $comment['user_id'] || $user->isAdmin()): ?>
                                    <form method="POST" action="" style="display: inline;">
                                        <input type="hidden" name="comment_id" value="<?= htmlspecialchars($comment['id']) ?>">
                                        <button type="submit" name="delete_comment" class="delete-comment" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                            <div class="comment-content">
                                <?= htmlspecialchars($comment['comment']) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No comments yet. Be the first to comment!</p>
            <?php endif; ?>
        </div>
    </section>
    <footer>
        <p id="T18">© 2024 Our Company. All rights reserved.</p>
    </footer>
</body>
</html>