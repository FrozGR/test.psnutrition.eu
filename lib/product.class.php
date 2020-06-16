<?php

class Product
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
     * The article id
     *
     * @var int
     */
    private $_id;

    /**
     * The article category
     *
     * @var Category
     */
    private $_category;

    /**
     * The product name
     *
     * @var string
     */
    private $_name;

    /**
     * The product description
     *
     * @var string
     */
    private $_desc;

    /**
     * The product metatags
     *
     * @var string
     */
    private $_metatags;

    /**
     * The article author
     *
     * @var User
     */
    private $_author;

    /**
     * The date of creation of the article
     *
     * @var string
     */
    private $_created_on;

    /**
     * The article status
     *
     * @var int
     */
    private $_status;

    /**
     * The number of images belonging to the product
     *
     * @var int
     */
    private $_images_num;

    /**
     * The number of comments belonging to the product
     *
     * @var int
     */
    private $_comments_num;

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
     * Returns the product ID
     *
     * @return integer
     */
    public function getId(): int
    {
        return (int) $this->_id;
    }

    /**
     * Returns the product Category object
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->_category;
    }

    /**
     * Returns the product name
     *
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->_name;
    }

    /**
     * Returns the product description
     *
     * @return string
     */
    public function getDesc(): string
    {
        return (string) $this->_desc;
    }

    /**
     * Returns the product description
     *
     * @return string
     */
    public function getMetatags(): string
    {
        return (string) $this->_metatags;
    }

    /**
     * Returns the User object of the author
     *
     * @return User
     */
    public function getUser()
    {
        return $this->_author;
    }

    /**
     * Returns the date of creation of the product
     *
     * @return string
     */
    public function getCreatedOn(): string
    {
        return (string) $this->_created_on;
    }

    /**
     * Returns the status of the product
     *
     * @return integer
     */
    public function getStatus(): int
    {
        return (int) $this->_status;
    }

     /**
     * Returns the number of images the product has
     *
     * @return integer
     */
    public function getImagesNum(): int
    {
        return (int) $this->_images_num;
    }

    /**
     * Returns the number of comments the product has
     *
     * @return integer
     */
    public function getCommentsNum(): int
    {
        return (int) $this->_comments_num;
    }

    /**
     * Returns a tree of images as an array
     *
     * @param integer $parent
     * @return array
     */
    public function getImages(int $parent = 0): array
    {
        // Get the images list
        $images = $this->_db->query("
            SELECT *
            FROM product_images
            WHERE product_image_parent = ".$this->_db->quote($this->_id)."
                  AND product_image_status = 1
            ORDER BY product_image_id DESC
        ");

        // Check if we got any comments
        if(empty($images)) return [];
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
            SELECT *
            FROM comments
            WHERE comment_product = ".$this->_db->quote($this->_id)."
                  AND comment_parent_id " . (($parent > 0) ? (" = " . $parent) : " IS NULL ") . "
                  AND comment_status = 1
            ORDER BY comment_id DESC
        ");

        // Check if we got any comments
        if(empty($comments)) return [];

        // Set a new comment tree array
        $tree = [];

        // Iterate through the array and get the child comments
        foreach($comments as $comment){
            $tree[] = ['comment' => $comment, 'children' => $this->getComments($comment['comment_id'])];
        }

        // Return the comment tree
        return $tree;
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
                        <span class="comment-meta"><a href="user.php?id='.htmlentities($comment['comment']['comment_user_id']).'">User</a> commented on '.date('F jS, Y', strtotime($comment['comment']['comment_created_on'])).':</span>
                        <div class="comment-content">'.nl2br(htmlentities($comment['comment']['comment_text'])).'</div>
                        '.( $GLOBALS['current_user']->getId() ? '
                        <a href="#" class="comment-reply">Reply</a>
                        <div class="comment-form reply-comment hidden">
                            <form method="POST">
                                <p>Post a reply:</p>
                                <input type="hidden" name="comment_parent_id" value="'.htmlentities($comment['comment']['comment_id']).'" />
                                <label>Comment:</label> <textarea name="comment_text"></textarea>
                                <input type="submit" name="comment" value="Post Reply" />
                            </form>
                            <div class="clear"></div>
                        </div>' : '').'
                    </div>
                    <div class="comment-children">
                        '.$this->displayComments($comment['children']).'
                    </div>
                </div>
            </div>
            ';
        }

        return $html;
    }

    /**
     * Posts a comment to the article
     *
     * @return boolean
     */
    public function postComment(): bool
    {
        // Check if the user is logged in
        if(!$GLOBALS['current_user']->getId()){
            $this->_notifications[] = new Notification("You need to be logged in to post a comment.");
            return false;
        }

        // Store the form data
        $form_data = $_POST;

        // Trim any whitespace around each string in the data
        foreach($form_data as $key => $value){
            $form_data[$key] = trim($value);
        }

        // Validate the comment field
        if(strlen(@$form_data['comment_text']) > 256 || strlen(@$form_data['comment_text']) < 2){
            $this->_notifications[] = new Notification("Please enter a message up to 256 characters long.");
            return false;
        }

        //Prepare the data for submission
        $data = [
            'comment_product' => $this->getId(),
            'comment_text' => $form_data['comment_text'],
            'comment_user_id' => $GLOBALS['current_user']->getId()
        ];

        // Check if the comment is a reply
        if($form_data['comment_parent_id']) $data['comment_parent_id'] = $form_data['comment_parent_id'];

        // Insert the data in the database
        if(!$this->_db->insert('comments', $data)){
            $this->_notifications[] = new Notification("There was an error submitting your comment. Please try again later.");
            return false;
        }

        // Inform the user that the comment has been submitted
        $notification = new Notification("Success! Your comment has been submitted for approval.", 'success');
        $notification->redirect('product.php?id='.$this->getId());
        return true;
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
        $this->_id              = isset($data['product_id']) ? trim($data['product_id']) : null;
        $this->_name            = isset($data['product_name']) ? trim($data['product_name']) : null;
        $this->_category        = isset($data['product_category']) ? trim($data['product_category']) : null;
        $this->_desc            = isset($data['product_desc']) ? trim($data['product_desc']) : null;
        $this->_created_on      = isset($data['product_created_on']) ? trim($data['product_created_on']) : null;
        $this->_status          = isset($data['product_status']) ? trim($data['product_status']) : null;
        $this->_images_num    = isset($data['product_images_num']) ? trim($data['product_images_num']) : null;
        $this->_comments_num    = isset($data['product_comments_num']) ? trim($data['product_comments_num']) : null;

        // Check if there is a category ID provided
        if(isset($data['product_category'])){
            // Initialise a new Category object
            $category = new Category();
            
            // Set the data from the ID given
            $category->setFromId((int) $data['product_category']);

            // Set the category from the object
            $this->_category = $category;
        }

        // Check if there is a user ID provided
        if(isset($data['product_author'])){
            // Initialise a new User object
            $user = new User();
            
            // Set the data from the ID given
            $user->setFromId((int) $data['product_author']);

            // Set the user from the object
            $this->_author = $user;
        }
    }

    /**
     * Gets all the product information from the database
     * using the provided ID and sets them in the object
     *
     * @param int $id
     * @return boolean
     */
    public function setFromId(int $id): bool
    {
        // Make a query to the database
        $product = $this->_db->query("
            SELECT a.*, COUNT(co.comment_id) AS product_comments_num
            FROM  products AS a
            LEFT JOIN comments AS co ON (a.product_id = co.comment_product AND co.comment_status = 1)
            LEFT JOIN product_images AS im ON (a.product_id = im.product_image_parent AND im.product_image_status = 1)
            WHERE a.product_id = " . $this->_db->quote($id) ."
        ");

        // Check if we received any data
        if(empty($product)) return false;

        // Set the data using the array
        $this->setFromArray($product[0]);

        return true;
    }
}