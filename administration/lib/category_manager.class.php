<?php
/**
 * Handles all administrative category operations
 */
class CategoryManager
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
     * The action to take on the category
     *
     * @var string
     */
    private $_action;

    /**
     * The category id
     *
     * @var int
     */
    private $_id;

    /**
     * The page's name
     *
     * @var string
     */
    private $_name;

    /**
     * The category image
     *
     * @var string
     */
    private $_image;

    /**
     * The category object
     *
     * @var Category
     */
    private $_category;

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
            // Edit the category
            case 'edit':
                // Set the page name
                $this->_name = "Edit Category";

                // Set a new category
                $this->_category = new Category();

                // Set the category data by ID
                $this->_category->setFromId($this->_id);
    
                break;

            // Delete the category
            case 'delete':
                // Delete the category from the database
                $this->_db->delete("product_categories","product_category_id = " . $this->_db->quote($this->_id));
            
                // Add a notification to the session and redirect the user to the categories list
                $notification = new Notification("The category has been deleted successfully.", "success");
                $notification->redirect("categories.php");
    
                break;

            // Add new category
            case 'new':
                // Set the page name
                $this->_name = "New Category";

                // Set the new category image
                $this->_image = "";

                // Set a new category
                $this->_category = new Category();
    
                break;

            // There is not valid action
            default:
                // Add a notification to the session and redirect the user to the categories list
                $notification = new Notification("No action specified.");
                $notification->redirect("categories.php");
    
                break;
        }

        // Check if there are POST data and save the category
        if(isset($_POST['save_category'])){
            $this->_category->setFromArray($_POST);
            $this->_data = $_POST;
            $this->_save();
        }
    }

    /**
     * Returns the category image
     *
     * @return string
     */
    public function getImage(): string
    {
        return (string) $this->_image;
    }

    /**
     * Returns the page title depending on the action
     *
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->_name;
    }

    /**
     * Returns the form's HTML
     *
     * @return string
     */
    public function getAddForm(): string
    {
        // Set up the HTML
        $html = '
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Category Name</label>
                <input type="text" class="form-control" name="product_category_name" max="256" value="'.htmlentities($this->_category->getName()).'" />
            </div>
            </div>
            </div>
            <br />
            <div class="row">
            <div class="col-md-6">
            <p>Upload new Category Image:</p>
            <input type="file" class="btn btn-primary pull-center" name="product_category_image" value="" />
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Status</label>
                <select class="form-control" name="product_category_status">
                <option value="1" '.($this->_category->getStatus() === 1 ? 'selected="selected"' : '').'>Active</option>
                <option value="0" '.($this->_category->getStatus() === 0 ? 'selected="selected"' : '').'>Inactive</option>
                </select>
            </div>
            </div>
            </div>
            <input type="submit" class="btn btn-primary pull-right" name="save_category" value="Save" />
        </form>
        ';

        return $html;
    }

        /**
     * Returns the form's HTML
     *
     * @return string
     */
    public function getEditForm(): string
    {
        // Set up the HTML
        $html = '
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Category Name</label>
                <input type="text" class="form-control" name="product_category_name" max="256" value="'.htmlentities($this->_category->getName()).'" />
            </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
            <p>Current Category Image:</p>
            <img src="data:image/jpg;charset=utf8;base64,'.base64_encode($this->_category->getImage()).'"style="height:225px;width:365px;border-radius:8px;" alt="'.htmlentities($this->_category->getName()).'" />
            </div>
            </div>
            <br />
            <div class="row">
            <div class="col-md-6">
            <p>Change Category Image:</p>
            <input type="file" class="btn btn-primary pull-center" name="product_category_image" value="" />
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Status</label>
                <select class="form-control" name="product_category_status">
                <option value="1" '.($this->_category->getStatus() === 1 ? 'selected="selected"' : '').'>Active</option>
                <option value="0" '.($this->_category->getStatus() === 0 ? 'selected="selected"' : '').'>Inactive</option>
                </select>
            </div>
            </div>
            </div>
            <input type="submit" class="btn btn-primary pull-right" name="save_category" value="Save" />
        </form>
        ';

        return $html;
    }

    private function _save(): bool
    {
        $encoded_image = base64_encode(file_get_contents($_FILES['product_category_image']['name']));
        $encodedimg = 'data:image/jpg;charset=utf8;base64,' . $encoded_image;

        // Validate the data
        if(!$this->_validate()) return false;

        //Prepare the data for submission
        $data = [
            'product_category_name' => $this->_data['product_category_name'],
            $encodedimg => $this->_data['product_category_image'],
            'product_category_status' => $this->_data['product_category_status']
        ];

        if($this->_action == 'new'){
            // Insert the data in the database
            if(!$this->_db->insert('product_categories', $data)){
                $this->_notifications[] = new Notification("There was an error adding the category. Please try again later.");
                return false;
            }
        } elseif($this->_action == 'edit'){
            // Update the data in the database
            if(!$this->_db->update('product_categories', $data, "product_category_id = " . $this->_db->quote($this->_id))){
                $this->_notifications[] = new Notification("There was an error updating the category. Please try again later.");
                return false;
            }
        }

        // Inform the user that the category has been saved
        $this->_notifications[] = new Notification("The category has been saved successfully.", 'success');
        return true;
    }

    private function _validate(): bool
    {
        // Trim any whitespace around each string in the data
        foreach($this->_data as $key => $value){
            $this->_data[$key] = trim($value);
        }

        // Validate the category name
        if(strlen(@$this->_data['product_category_name']) > 256 || strlen(@$this->_data['product_category_name']) < 2){
            $this->_notifications[] = new Notification("Please enter a name up to 256 characters long.");
            return false;
        }

        // Validate the category image
        if(strlen(@$this->_data['product_category_image']) < 2){
            $this->_notifications[] = new Notification("Please choose a valid image to upload.");
            return false;
        }

        // Validate the status
        if(@$this->_data['product_category_status'] != 0 && @$this->_data['product_category_status'] != 1){
            $this->_notifications[] = new Notification("Please select the status.");
            return false;
        }

        // Return true for valid input
        return true;
    }
}