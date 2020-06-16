<?php
/**
 * Handles all administrative article operations
 */
class ArticleManager
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
     * The current user's User object
     *
     * @var User
     */
    private $_current_user;

    /**
     * The action to take on the article
     *
     * @var string
     */
    private $_action;

    /**
     * The article id
     *
     * @var int
     */
    private $_id;

    /**
     * The page's title
     *
     * @var string
     */
    private $_title;

    /**
     * The article object
     *
     * @var Article
     */
    private $_article;

    /**
     * The posted data
     *
     * @var array
     */
    private $_data;

    /**
     * Class constructor
     */
    public function __construct()
    {
        // Set the database link and the notification array
        $this->_db            = &$GLOBALS['db'];
        $this->_notifications = &$GLOBALS['notifications'];
        $this->_current_user  = &$GLOBALS['current_user'];

        // Check if the current user is an admin
        if(!$this->_current_user->isAdmin()) die('Access denied.');

        // Get the action and ID from the request
        $this->_action = isset($_GET['action']) && !empty(trim($_GET['action'])) ? trim($_GET['action']) : '';
        $this->_id     = isset($_GET['id']) && !empty(trim($_GET['id'])) ? (int) trim($_GET['id']) : 0;

        // Determine which action to take
        switch($this->_action){
            // Edit the article
            case 'edit':
                // Set the page title
                $this->_title = "Edit Article";

                // Set a new article
                $this->_article = new Article();

                // Set the article data by ID
                $this->_article->setFromId($this->_id);
    
                break;

            // Delete the article
            case 'delete':
                // Delete the article from the database
                $this->_db->delete("news_articles","news_article_id = " . $this->_db->quote($this->_id));
            
                // Add a notification to the session and redirect the user to the articles list
                $notification = new Notification("The article has been deleted successfully.", "success");
                $notification->redirect("articles.php");
    
                break;

            // Add new article
            case 'new':
                // Set the page title
                $this->_title = "New Article";

                // Set a new article
                $this->_article = new Article();
    
                break;

            // There is not valid action
            default:
                // Add a notification to the session and redirect the user to the articles list
                $notification = new Notification("No action specified.");
                $notification->redirect("articles.php");
    
                break;
        }

        // Check if there are POST data and save the article
        if(isset($_POST['save_article'])){
            $this->_article->setFromArray($_POST);
            $this->_data = $_POST;
            $this->_save();
        }
    }

    /**
     * Returns the page title depending on the action
     *
     * @return string
     */
    public function getTitle(): string
    {
        return (string) $this->_title;
    }

    /**
     * Returns the form's HTML
     *
     * @return string
     */
    public function getForm(): string
    {
        // Get all categories
        $categories = $this->_db->query("SELECT * FROM news_categories");

        // Check if categories exist
        if(empty($categories)){
            // Create notification and redirect the user
            $notification = new Notification("There was a problem retrieving some information from the database.");
            $notification->redirect("articles.php");
        }

        // Set up the HTML
        $html = '
        <form method="POST">
            <label>Title</label> <input type="text" name="news_article_title" max="256" value="'.htmlentities($this->_article->getTitle()).'" />
            <label>Category</label>
            <select name="news_article_category">
                <option>-- Select Category --</option>
        ';

        foreach($categories as $category){
            if($this->_article->getCategory() && $this->_article->getCategory()->getId() == $category['news_category_id'])
                $html .= '<option value="'.htmlentities($category['news_category_id']).'" selected="selected">'.htmlentities($category['news_category_name']).'</option>';
            else
                $html .= '<option value="'.htmlentities($category['news_category_id']).'">'.htmlentities($category['news_category_name']).'</option>';
        }
        
        $html .= '
            </select>
            <label>Text</label> <textarea name="news_article_text" max="1000">'.htmlentities($this->_article->getText()).'</textarea>
            <label>Status</label>
            <select name="news_article_status">
                <option value="1" '.($this->_article->getStatus() === 1 ? 'selected="selected"' : '').'>Active</option>
                <option value="0" '.($this->_article->getStatus() === 0 ? 'selected="selected"' : '').'>Inactive</option>
            </select>

            <input type="submit" name="save_article" value="Save" />
        </form>
        ';

        return $html;
    }

    private function _save(): bool
    {
        // Validate the data
        if(!$this->_validate()) return false;

        //Prepare the data for submission
        $data = [
            'news_article_title' => $this->_data['news_article_title'],
            'news_article_category' => $this->_data['news_article_category'],
            'news_article_text' => $this->_data['news_article_text'],
            'news_article_author' => $this->_current_user->getId(),
            'news_article_status' => $this->_data['news_article_status']
        ];

        if($this->_action == 'new'){
            // Insert the data in the database
            if(!$this->_db->insert('news_articles', $data)){
                $this->_notifications[] = new Notification("There was an error adding the article. Please try again later.");
                return false;
            }
        } elseif($this->_action == 'edit'){
            // Update the data in the database
            if(!$this->_db->update('news_articles', $data, "news_article_id = " . $this->_db->quote($this->_id))){
                $this->_notifications[] = new Notification("There was an error updating the article. Please try again later.");
                return false;
            }
        }

        // Inform the user that the article has been saved
        $this->_notifications[] = new Notification("The article has been saved successfully.", 'success');
        return true;
    }

    private function _validate(): bool
    {
        // Trim any whitespace around each string in the data
        foreach($this->_data as $key => $value){
            $this->_data[$key] = trim($value);
        }

        // Validate the article title
        if(strlen(@$this->_data['news_article_title']) > 256 || strlen(@$this->_data['news_article_title']) < 2){
            $this->_notifications[] = new Notification("Please enter a title up to 256 characters long.");
            return false;
        }

        // Validate the category
        if(empty($this->_db->query("SELECT news_category_id FROM news_categories WHERE news_category_id = " . $this->_db->quote($this->_data['news_article_category'])))){
            $this->_notifications[] = new Notification("Please select a category from the list.");
            return false;
        }

        // Validate the text
        if(strlen(@$this->_data['news_article_text']) > 1000 || strlen(@$this->_data['news_article_text']) < 2){
            $this->_notifications[] = new Notification("Please enter some text up to 1000 characters long.");
            return false;
        }

        // Validate the status
        if(@$this->_data['news_article_status'] != 0 && @$this->_data['news_article_status'] != 1){
            $this->_notifications[] = new Notification("Please select the status.");
            return false;
        }

        // Return true for valid input
        return true;
    }
}