<?php
/**
 * Handles all administrative user operations
 */
class UserManager
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
     * The action to take on the user
     *
     * @var string
     */
    private $_action;

    /**
     * The user id
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
     * The user object
     *
     * @var User
     */
    private $_user;

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
            // Edit the user
            case 'edit':
                // Set the page title
                $this->_title = "Edit User";

                // Set a new user
                $this->_user = new User();

                // Set the user data by ID
                $this->_user->setFromId($this->_id);
    
                break;

            // Delete the user
            case 'delete':
                // Delete the user from the database
                $this->_db->delete("users", "user_id = " . $this->_db->quote($this->_id) . " AND user_id != " . $this->_db->quote($this->_current_user->getId()));
            
                // Add a notification to the session and redirect the user to the users list
                $notification = new Notification("The user has been deleted successfully.", "success");
                $notification->redirect("users.php");
    
                break;

            // Add new user
            case 'new':
                // Set the page title
                $this->_title = "New User";

                // Set a new user
                $this->_user = new User();
    
                break;

            // There is not valid action
            default:
                // Add a notification to the session and redirect the user to the users list
                $notification = new Notification("No action specified.");
                $notification->redirect("users.php");
    
                break;
        }

        // Check if there are POST data and save the user
        if(isset($_POST['save_user'])){
            $this->_user->setFromArray($_POST);
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
        // Set up the HTML
        $html = '
        <form method="POST">
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Name</label>
                <input type="text" class="form-control" name="user_name" max="128" value="'.htmlentities($this->_user->getName()).'" />
            </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Email</label>
                <input type="text" class="form-control" name="user_email" max="128" value="'.htmlentities($this->_user->getEmail()).'" />
            </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Password</label>
                <input type="password" class="form-control" name="user_password" max="128" />
            </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Re-enter Password</label>
                <input type="password" class="form-control" name="user_password_again" max="128" />
            </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Is Admin</label>
                <select class="form-control" name="user_is_admin">
                <option value="1" '.($this->_user->isAdmin() ? 'selected="selected"' : '').'>Yes</option>
                <option value="0" '.(!$this->_user->isAdmin() ? 'selected="selected"' : '').'>No</option>
                </select>
            </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Status</label>
                <select class="form-control" name="user_status">
                <option value="1" '.($this->_user->getStatus() === 1 ? 'selected="selected"' : '').'>Active</option>
                <option value="0" '.($this->_user->getStatus() === 0 ? 'selected="selected"' : '').'>Inactive</option>
                </select>
            </div>
            </div>
            </div>

            <input type="submit" name="save_user" value="Save" class="btn btn-primary pull-right"/>
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
            'user_name' => $this->_data['user_name'],
            'user_email' => $this->_data['user_email'],
            'user_is_admin' => $this->_data['user_is_admin'],
            'user_status' => $this->_data['user_status']
        ];

        // Include the password if not empty
        if(!empty($this->_data['user_password'])) $data['user_password'] = $this->generatePassword($this->_data['user_password']);

        if($this->_action == 'new'){
            // Insert the data in the database
            if(!$this->_db->insert('users', $data)){
                $this->_notifications[] = new Notification("There was an error adding the user. Please try again later.");
                return false;
            }
        } elseif($this->_action == 'edit') {
            // Update the data in the database
            if(!$this->_db->update('users', $data, "user_id = " . $this->_db->quote($this->_id))){
                $this->_notifications[] = new Notification("There was an error updating the user. Please try again later.");
                return false;
            }
        }

        // Inform the user that the user has been saved
        $this->_notifications[] = new Notification("The user has been saved successfully.", 'success');
        return true;
    }

    private function _validate(): bool
    {
        // Trim any whitespace around each string in the data
        foreach($this->_data as $key => $value){
            $this->_data[$key] = trim($value);
        }

        // Validate the full name field
        if(!preg_match('/^[a-zA-Z\s]{2,64}$/', @$this->_data['user_name'])){
            $this->_notifications[] = new Notification("Please enter the user name.");
            return false;
        }

        // Validate the email field
        if(!preg_match('/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/', @$this->_data['user_email'])){
            $this->_notifications[] = new Notification("Please enter the email address.");
            return false;
        }

        // Only check for duplicate email if the action is to add user
        if($this->_action == 'new'){
            // Search for a user with the same email
            $email = $this->_db->query("
                SELECT user_id
                FROM users
                WHERE user_email = ".$this->_db->quote($this->_data['user_email'])."
            ");

            // Check if the email is already registered
            if(!empty($email)){
                $this->_notifications[] = new Notification("There is already a registered user with that email address.");
                return false;
            }
        }

        // Only validate password if not empty (for edit) or if new
        if(!empty($this->_data['user_password']) || $this->_action == 'new'){
            // Validate the password field
            if(strlen(@$this->_data['user_password']) > 256 || strlen(@$this->_data['user_password']) < 2){
                $this->_notifications[] = new Notification("Please enter a password more that 2 and up to 256 characters long.");
                return false;
            }

            // Validate the re-entered password field
            if(@$this->_data['user_password'] != @$this->_data['user_password_again']){
                $this->_notifications[] = new Notification("Please enter the password again.");
                return false;
            }
        }

        // Return true for valid input
        return true;
    }

    /**
     * Returns a hashed string using the BCRYPT algorithm
     * and a cost of 10
     *
     * @param string $password
     * @return string
     */
    public function generatePassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, array("cost" => 10));
    }
}