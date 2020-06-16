<?php
/**
 * Handles the registration form submissions
 */
class Registration
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
     * Initialises the class object
     */
    public function __construct()
    {
        // Set the database link and the notification array
        $this->_db = &$GLOBALS['db'];
        $this->_notifications = &$GLOBALS['notifications'];

        // Store the form data
        $this->_data = $_POST;

        // Check if the form has been submitted and handle it
        if(isset($this->_data['register'])) $this->submit();
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

        //Prepare the data for submission
        $data = [
            'user_name' => $this->_data['full_name'],
            'user_email' => $this->_data['email'],
            'user_password' => $this->generatePassword($this->_data['password'])
        ];

        // Insert the data in the database
        if(!$this->_db->insert('users', $data)){
            $this->_notifications[] = new Notification("There was an error registering you. Please try again later.");
            return false;
        }

        // Inform the user that the message has been submitted
        $this->_notifications[] = new Notification("Thank you for registering! You can now login with your credentials.", 'success');
        return false;
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

        // Validate the full name field
        if(!preg_match('/^[a-zA-Z\s]{2,64}$/', @$this->_data['full_name'])){
            $this->_notifications[] = new Notification("Please enter your full name.");
            return false;
        }

        // Validate the email field
        if(!preg_match('/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/', @$this->_data['email'])){
            $this->_notifications[] = new Notification("Please enter your email address.");
            return false;
        }

        // Search for a user with the same email
        $email = $this->_db->query("
            SELECT user_id
            FROM users
            WHERE user_email = ".$this->_db->quote($this->_data['email'])."
        ");

        // Check if the email is already registered
        if(!empty($email)){
            $this->_notifications[] = new Notification("There is already a registered user with that email address.");
            return false;
        }

        // Validate the password field
        if(strlen(@$this->_data['password']) > 256 || strlen(@$this->_data['password']) < 2){
            $this->_notifications[] = new Notification("Please enter a password more that 2 and up to 256 characters long.");
            return false;
        }

        // Validate the re-entered password field
        if(@$this->_data['password'] != @$this->_data['password_again']){
            $this->_notifications[] = new Notification("Please enter your password again.");
            return false;
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