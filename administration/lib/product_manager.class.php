<?php
/**
 * Handles all administrative product operations
 */
class ProductManager
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
     * The product object
     *
     * @var Product
     */
    private $_product;

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
                $this->_name = "Edit Product";

                // Set a new category
                $this->_product = new Product();

                // Set the category data by ID
                $this->_product->setFromId($this->_id);
    
                break;

            // Delete the category
            case 'delete':
                // Delete the category from the database
                $this->_db->delete("products","product_id = " . $this->_db->quote($this->_id));
            
                // Add a notification to the session and redirect the user to the categories list
                $notification = new Notification("The product has been deleted successfully.", "success");
                $notification->redirect("products.php");
    
                break;

            // Add new Product
            case 'new':
                // Set the page name
                $this->_name = "New Product";

                // Set a new product
                $this->_product = new Product();
    
                break;

            // There is not valid action
            default:
                // Add a notification to the session and redirect the user to the categories list
                $notification = new Notification("No action specified.");
                $notification->redirect("products.php");
    
                break;
        }

        // Check if there are POST data and save the product
        if(isset($_POST['save_product'])){
            $this->_product->setFromArray($_POST);
            $this->_data = $_POST;
            $this->_save();
        }
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
     * Returns the page title depending on the action
     *
     * @return string
     */
    public function getMetatags(): string
    {
        return (string) $this->_metatags;
    }

    /**
     * Returns the form's HTML
     *
     * @return string
     */
    public function getAddForm(): string
    {
        // Get all categories
        $categories = $this->_db->query("SELECT * FROM product_categories");

        // Check if categories exist
        if(empty($categories)){
            // Create notification and redirect the user
            $notification = new Notification("There was a problem retrieving some information from the database.");
            $notification->redirect("products.php");
        }

        // Set up the HTML
        $html = '
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Product Name</label>
                <input type="text" class="form-control" name="product_name" max="256" value="'.htmlentities($this->_product->getName()).'" />
            </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Product Category</label>
                <select class="form-control" name="product_category_name">
                <option>-- Select Category --</option>
        ';

        foreach($categories as $category){
            if($this->_product->getCategory() && $this->_product->getCategory()->getId() == $category['product_category_id'])
                $html .= '<option value="'.htmlentities($category['product_category_id']).'" selected="selected">'.htmlentities($category['product_category_name']).'</option>';
            else
                $html .= '<option value="'.htmlentities($category['product_category_id']).'">'.htmlentities($category['product_category_name']).'</option>';
        }
        
        $html .= '
                </select>
            </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Product Description</label>
                <input type="text" class="form-control" name="product_desc" max="256" value="'.htmlentities($this->_product->getDesc()).'" />
            </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Product Meta Tags</label>
                <input type="text" class="form-control" name="product_metatags" max="256" value="'.htmlentities($this->_product->getMetatags()).'" />
            </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Status</label>
                <select class="form-control" name="product_category_status">
                <option value="1" '.($this->_product->getStatus() === 1 ? 'selected="selected"' : '').'>Active</option>
                <option value="0" '.($this->_product->getStatus() === 0 ? 'selected="selected"' : '').'>Inactive</option>
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
        // Get all categories
        $categories = $this->_db->query("SELECT * FROM product_categories");

        // Check if categories exist
        if(empty($categories)){
            // Create notification and redirect the user
            $notification = new Notification("There was a problem retrieving some information from the database.");
            $notification->redirect("products.php");
        }

        // Set up the HTML
        $html = '
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Product Name</label>
                <input type="text" class="form-control" name="product_name" max="256" value="'.htmlentities($this->_product->getName()).'" />
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
        // Validate the data
        if(!$this->_validate()) return false;

        //Prepare the data for submission
        $data = [
            'product_category' => $this->_data['product_category'],
            'product_name' => $this->_data['product_name'],
            'product_desc' => $this->_data['product_desc'],
            'product_metatags' => $this->_data['product_metatags'],
            'product_author' => $this->_data['product_author'],
            'product_created_on' => $this->_data['product_created_on'],
            'product_status' => $this->_data['product_status']
        ];

        if($this->_action == 'new'){
            // Insert the data in the database
            if(!$this->_db->insert('products', $data)){
                $this->_notifications[] = new Notification("There was an error adding the product. Please try again later.");
                return false;
            }
        } elseif($this->_action == 'edit'){
            // Update the data in the database
            if(!$this->_db->update('products', $data, "product_id = " . $this->_db->quote($this->_id))){
                $this->_notifications[] = new Notification("There was an error updating the product. Please try again later.");
                return false;
            }
        }

        // Inform the user that the product has been saved
        $this->_notifications[] = new Notification("The product has been saved successfully.", 'success');
        return true;
    }

    private function _validate(): bool
    {
        // Trim any whitespace around each string in the data
        foreach($this->_data as $key => $value){
            $this->_data[$key] = trim($value);
        }

        // Validate the product name
        if(strlen(@$this->_data['product_name']) > 256 || strlen(@$this->_data['product_name']) < 2){
            $this->_notifications[] = new Notification("Please enter a name up to 256 characters long.");
            return false;
        }

        // Validate the product category
        if(empty($this->_db->query("SELECT product_category_id FROM product_categories WHERE product_category_id = " . $this->_db->quote($this->_data['news_article_category'])))){
            $this->_notifications[] = new Notification("Please select a category from the list.");
            return false;
        }

        // Validate the status
        if(@$this->_data['product_status'] != 0 && @$this->_data['product_status'] != 1){
            $this->_notifications[] = new Notification("Please select the status.");
            return false;
        }

        // Return true for valid input
        return true;
    }
}