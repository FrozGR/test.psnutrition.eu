<?php
/**
 * Handles the authentication for the system
 */
class Authentication
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
     * Stores the form data
     *
     * @var array
     */
    private $_data;

    /**
     * The user object to login
     *
     * @var User
     */
    private $_user;

    /**
     * Initialises the class object
     */
    public function __construct()
    {
        // Set the database link and the notification array
        $this->_db = &$GLOBALS['db'];
        $this->_notifications = &$GLOBALS['notifications'];

        // Store the form data
        $this->_data = $_POST;

        // Check if the login form has been submitted and handle it
        if(isset($this->_data['login'])) $this->submit();
    }

    /**
     * Inserts the validated data to the database
     *
     * @return boolean
     */
    public function submit(): bool
    {
        // Validate the data
        if(!$this->validate()) return false;

        // Inform the user that they logged in successfully
        $notification = new Notification("Welcome, " . htmlentities($this->_user->getName()) . "! You logged in successfully.", 'success');
        
        if($this->_user->isAdmin()){
            // Redirect them to the homepage
            $notification->redirect(HOMEPAGE);
        }
        else {
            // Redirect them to the Distributors homepage
            $notification->redirect(DISTRIBUTORS);
        }
        return true;
    }

    /**
     * Validates the data submitted
     *
     * @return boolean
     */
    public function validate(): bool
    {
        // Trim any whitespace around each string in the data
        foreach($this->_data as $key => $value){
            $this->_data[$key] = trim($value);
        }

        // Validate the email field
        if(!preg_match('/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/', @$this->_data['email'])){
            $this->_notifications[] = new Notification("Please enter your email address.");
            return false;
        }

        // Validate the password field
        if(strlen(@$this->_data['password']) > 256 || strlen(@$this->_data['password']) < 2){
            $this->_notifications[] = new Notification("Please enter your password.");
            return false;
        }

        // Search for a user with the same email
        $data = $this->_db->query("
            SELECT *
            FROM users
            WHERE user_email = ".$this->_db->quote($this->_data['email'])."
                  AND user_status = 1
        ");

        // Check if the user was fetched
        if(empty($data)){
            $this->_notifications[] = new Notification("The email you entered is incorrect.");
            return false;
        }

        // Verify the password is correct
        if(!password_verify($this->_data['password'], $data[0]['user_password'])){
            $this->_notifications[] = new Notification("The password you entered is incorrect.");
            return false;
        }

        // Create a User object
        $this->_user = new User();
        
        // Populate the data
        $this->_user->setFromArray($data[0]);

        // Login the user
        return $this->login();
    }

    /**
     * Creates a new session for the user and
     * returns true on success, false on error
     *
     * @return boolean
     */
    public function login(): bool
    {
        // Remove all sessions for the user
        $this->_db->delete("user_sessions", "session_user_id = " . $this->_db->quote($this->_user->getId()));

        // Generate a new session ID
        do {
            $session_id = hash('sha256', time() . $this->_user->getId());
        } while(!empty($this->_db->query("SELECT session_id FROM user_sessions WHERE session_id = " . $this->_db->quote($session_id))));

        // Gather all necessary session data
        $session = [
            'session_id' => $session_id,
            'session_ip' => $_SERVER['REMOTE_ADDR'],
            'session_user_id' => $this->_user->getId(),
            'session_end' => date('Y-m-d H:i:s', strtotime('+1 day'))
        ];

        // Insert the session to the database
        $this->_db->insert("user_sessions", $session);

        // Add the session to the global $_SESSION variable
        $_SESSION['NNSID'] = $session_id;

        // Report successful login
        return true;
    }

    /**
     * Removes the session and redirects the user to the homepage
     *
     * @return void
     */
    public function logout(): void
    {
        // Remove the session from the database
        $this->_db->delete("user_sessions", "session_user_id = " . $this->_db->quote($GLOBALS['current_user']->getId()));

        // Unset the session
        unset($_SESSION['NNSID']);

        // Redirect the user with a success message
        $notification = new Notification("You were logged out successfully.", 'success');
        $notification->redirect(HOMEPAGE);
    }
}