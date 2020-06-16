<?php
/**
 * Handles the user operations
 */
class User
{
    /**
     * The database link
     *
     * @var Database
     */
    private $_db;

    /**
     * The notifications array
     *
     * @var array
     */
    private $_notifications = [];

    /**
     * The user ID
     *
     * @var int
     */
    private $_id;

    /**
     * The user's email address
     *
     * @var string
     */
    private $_email;

    /**
     * The user's name
     *
     * @var string
     */
    private $_name;

    /**
     * The timestamp the user was created
     *
     * @var string
     */
    private $_created_on;

    /**
     * Whether the user is an administrator
     *
     * @var boolean
     */
    private $_is_admin;

    /**
     * The user's status
     *
     * @var int
     */
    private $_status;

    /**
     * The user's articles
     *
     * @var array
     */
    private $_articles;

    /**
     * Initialises the class object
     */
    public function __construct()
    {
        // Set the database link and the notification array
        $this->_db = &$GLOBALS['db'];
        $this->_notifications = &$GLOBALS['notifications'];
    }

    /**
     * Returns the user's ID
     *
     * @return integer
     */
    public function getId(): int
    {
        return (int) $this->_id;
    }

    /**
     * Returns the user's email address
     *
     * @return string
     */
    public function getEmail(): string
    {
        return (string) $this->_email;
    }

    /**
     * Returns the user's name
     *
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->_name;
    }

    /**
     * Returns the timestamp of the creation of the user
     *
     * @return string
     */
    public function getCreatedOn(): string
    {
        return (string) $this->_created_on;
    }

    /**
     * Returns whether the user is an admin or not
     *
     * @return boolean
     */
    public function isAdmin(): bool
    {
        return !!$this->_is_admin;
    }

    /**
     * Returns the user's status
     *
     * @return integer
     */
    public function getStatus(): int
    {
        return (int) $this->_status;
    }

    /**
     * Sets the data of the object from the array
     * provided
     *
     * @param array $data
     * @return void
     */
    public function setFromArray(array $data): void
    {
        // Iterate through the data and set them to the object
        $this->_id          = isset($data['user_id']) ? $data['user_id'] : null;
        $this->_email       = isset($data['user_email']) ? $data['user_email'] : null;
        $this->_name        = isset($data['user_name']) ? $data['user_name'] : null;
        $this->_created_on  = isset($data['user_created_on']) ? $data['user_created_on'] : null;
        $this->_is_admin    = isset($data['user_is_admin']) ? $data['user_is_admin'] : null;
        $this->_status      = isset($data['user_status']) ? $data['user_status'] : null;
    }

    /**
     * Returns a tree of comments as an array
     *
     * @param integer $parent
     * @return array
     */
    public function getComments(int $parent = 0): array
    {
        // Get the parent comments
        $comments = $this->_db->query("
            SELECT *, news_article_id, news_article_title
            FROM comments
            LEFT JOIN news_articles ON (comment_article = news_article_id)
            WHERE comment_user_id = ".$this->_db->quote($this->_id)."
                  AND comment_status = 1
            ORDER BY comment_id DESC
        ");

        // Return the comment tree
        return $comments;
    }

    /**
     * Returns an array of articles that belong
     * to the user
     *
     * @return array
     */
    public function getArticles(): array
    {
        // Check if we arleady retrieved the articles
        // This acts as a cache for the system
        if(!empty($this->_articles)) return $this->_articles;

        $this->_articles = [];

        // Initialise the where clause of the query
        $where = '';

        // Check if the id is valid and override the where clause
        // to get the articles that belong to the category
        if($this->_id > 0)
            $where = "AND a.news_article_author = ".$this->_db->quote($this->_id);

        // Get the articles
        $articles = $this->_db->query("
            SELECT 	a.news_article_id AS news_article_id,
                    a.news_article_title AS news_article_title,
                    a.news_article_text AS news_article_text,
                    a.news_article_image AS news_article_image,
                    a.news_article_created_on AS news_article_created_on,
                    a.news_article_status AS news_article_status,
                    c.news_category_id AS news_article_category,
                    u.user_id AS news_article_author,
                    COUNT(co.comment_id) AS news_article_comments_num
            FROM news_articles AS a
            LEFT JOIN news_categories AS c ON (a.news_article_category = c.news_category_id)
            LEFT JOIN users AS u ON (a.news_article_author = u.user_id)
            LEFT JOIN comments AS co ON (a.news_article_id = co.comment_article AND co.comment_status = 1)
            WHERE 	a.news_article_status = 1
                    AND c.news_category_status = 1
                    ".$where."
            GROUP BY a.news_article_id
            ORDER BY a.news_article_created_on DESC
        ");

        // Check if you got any articles
        if(empty($articles)) return $this->_articles;

        // Initialise a temporary array to hold the article objects
        $tmp_articles = [];

        // Iterate through the articles
        foreach($articles as $article){
            // Create a new object of Article
            $tmp_article = new Article();

            // Set the data
            $tmp_article->setFromArray($article);

            // Set the object to the temp array
            $tmp_articles[] = $tmp_article;
        }

        // Set the articles and return them
        return $this->_articles = $tmp_articles;
    }

    /**
     * Returns the HTML for the comments tree
     *
     * @param array $comments
     * @return string
     */
    public function displayComments(array $comments = []): string
    {
        $html = '';

        if(empty($comments)) return $html;
        
        foreach($comments as $comment){
            $html .= '
            <div class="comments-list">
                <div class="comment-container">
                    <div class="comment-main">
                        <h4><a href="article.php?id='.htmlentities($comment['news_article_id']).'">Article: "'.htmlentities($comment['news_article_title']).'"</a></h4>
                        <span class="comment-meta">'.htmlentities($this->getName()).' commented on '.date('F jS, Y', strtotime($comment['comment_created_on'])).':</span>
                        <div class="comment-content">'.nl2br(htmlentities($comment['comment_text'])).'</div>
                    </div>
                </div>
            </div>
            ';
        }

        return $html;
    }

    /**
     * Gets all the user information from the database
     * using the provided ID and sets them in the object
     *
     * @param int $id
     * @return boolean
     */
    public function setFromId(int $id): bool
    {
        // Make a query to the database
        $user = $this->_db->query("
            SELECT *
            FROM  users
            WHERE user_id = " . $this->_db->quote($id) ."
        ");

        // Check if we received any data
        if(empty($user)) return false;

        // Set the data using the array
        $this->setFromArray($user[0]);
        
        return true;
    }

    /**
     * Authorises the current user based on the session
     * and sets their information to the object
     *
     * @return void
     */
    public function authorise(): void
    {
        // Set default information for guest user
        $this->setFromArray([
            'user_id' => 0,
            'user_email' => '',
            'user_name' => 'Guest',
            'user_created_on' => '',
            'user_is_admin' => 0,
            'user_status' => 1
        ]);

        // Clear all sessions that have expired
        $this->_db->delete("user_sessions", "session_end <= " . $this->_db->quote(date('Y-m-d H:i:s')));
        
        // Get the user's information using the session id and IP address
        $user = $this->_db->query("
            SELECT user_id, user_email, user_name, user_created_on, user_is_admin, user_status
            FROM users
            LEFT JOIN user_sessions ON (session_user_id = user_id)
            WHERE user_status = 1
                  AND session_ip = ".$this->_db->quote($_SERVER['REMOTE_ADDR'])."
                  AND session_id = ".$this->_db->quote((string)@$_SESSION['NNSID'])."
        ");

        // If the session is valid and the user is active, set the info to the object
        if(!empty($user)) $this->setFromArray($user[0]);
    }
}