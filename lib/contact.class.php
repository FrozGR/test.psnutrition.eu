<?php
/**
 * Handles the contact form submissions
 */
class Contact
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
        if(isset($this->_data['contact'])) $this->submit();
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
            'submission_full_name' => $this->_data['full_name'],
            'submission_email' => $this->_data['email'],
            'submission_message' => $this->_data['message']
        ];

        // Insert the data in the database
        if(!$this->_db->insert('contact_form_submissions', $data)){
            $this->_notifications[] = new Notification("There was an error submitting your message. Please try again later.");
            return false;
        }

        // Inform the user that the message has been submitted
        $this->_notifications[] = new Notification("Thank you for contacting us! Your message has been submitted.", 'success');
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

        // Validate the message field
        if(strlen(@$this->_data['message']) > 256 || strlen(@$this->_data['message']) < 2){
            $this->_notifications[] = new Notification("Please enter a message up to 256 characters long.");
            return false;
        }

        // Return true for valid input
        return true;
    }
}