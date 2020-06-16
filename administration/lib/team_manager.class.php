<?php
/**
 * Handles all administrative team operations
 */
class TeamManager
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
     * The action to take on the teammember
     *
     * @var string
     */
    private $_action;

    /**
     * The team member id
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
     * The team member image
     *
     * @var string
     */
    private $_image;

    /**
     * The team member object
     *
     * @var TeamMember
     */
    private $_teammember;

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
            // Edit the team member
            case 'edit':
                // Set the page name
                $this->_name = "Edit Team Member";

                // Set a new team member
                $this->_teammember = new TeamMember();

                // Set the team member data by ID
                $this->_teammember->setFromId($this->_id);
    
                break;

            // Delete the team member
            case 'delete':
                // Delete the team member from the database
                $this->_db->delete("theteam","member_id = " . $this->_db->quote($this->_id));
            
                // Add a notification to the session and redirect the user to the team members list
                $notification = new Notification("The team member has been deleted successfully.", "success");
                $notification->redirect("team.php");
    
                break;

            // Add new team member
            case 'new':
                // Set the page name
                $this->_name = "New Team Member";

                // Set the new team member image
                $this->_image = "";

                // Set a new team member
                $this->_teammember = new TeamMember();
    
                break;

            // There is not valid action
            default:
                // Add a notification to the session and redirect the user to the team members list
                $notification = new Notification("No action specified.");
                $notification->redirect("team.php");
    
                break;
        }

        // Check if there are POST data and save the team member
        if(isset($_POST['save_teammember'])){
            $this->_teammember->setFromArray($_POST);
            $this->_data = $_POST;
            $this->_save();
        }
    }

    /**
     * Returns the team member image
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
     * Returns the page title depending on the action
     *
     * @return string
     */
    public function getTitle(): string
    {
        return (string) $this->_title;
    }

    /**
     * Returns the member desc depending on the action
     *
     * @return string
     */
    public function getDesc(): string
    {
        return (string) $this->_desc;
    }

    /**
     * Returns the member desc depending on the action
     *
     * @return string
     */
    public function getFacebook(): string
    {
        return (string) $this->_facebook;
    }

    /**
     * Returns the member desc depending on the action
     *
     * @return string
     */
    public function getInstagram(): string
    {
        return (string) $this->_instagram;
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
                <label class="bmd-label-floating">Team Member Name</label>
                <input type="text" class="form-control" name="member_name" max="256" value="'.htmlentities($this->_teammember->getName()).'" />
            </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Team Member Title</label>
                <input type="text" class="form-control" name="member_title" max="256" value="'.htmlentities($this->_teammember->getTitle()).'" />
            </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Team Member Description</label>
                <input type="text" class="form-control" name="member_desc" max="256" rows="5" value="'.htmlentities($this->_teammember->getDesc()).'" />
            </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Team Member Facebook</label>
                <input type="text" class="form-control" name="member_facebook" max="256" value="'.htmlentities($this->_teammember->getFacebook()).'" />
            </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Team Member Instagram</label>
                <input type="text" class="form-control" name="member_instagram" max="256" value="'.htmlentities($this->_teammember->getInstagram()).'" />
            </div>
            </div>
            </div>
            <br />
            <div class="row">
            <div class="col-md-6">
            <p>Upload Team Member Image:</p>
            <input type="file" class="btn btn-primary pull-center" name="member_image" value="" />
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Status</label>
                <select class="form-control" name="member_status">
                <option value="1" '.($this->_teammember->getStatus() === 1 ? 'selected="selected"' : '').'>Active</option>
                <option value="0" '.($this->_teammember->getStatus() === 0 ? 'selected="selected"' : '').'>Inactive</option>
                </select>
            </div>
            </div>
            </div>
            <input type="submit" class="btn btn-primary pull-right" name="save_teammember" value="Save" />
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
                <label class="bmd-label-floating">Team Member Name</label>
                <input type="text" class="form-control" name="member_name" max="256" value="'.htmlentities($this->_teammember->getName()).'" />
            </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Team Member Title</label>
                <input type="text" class="form-control" name="member_title" max="256" value="'.htmlentities($this->_teammember->getTitle()).'" />
            </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Team Member Description</label>
                <input type="text" class="form-control" name="member_desc" max="256" value="'.htmlentities($this->_teammember->getDesc()).'" />
            </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Team Member Facebook</label>
                <input type="text" class="form-control" name="member_facebook" max="256" value="'.htmlentities($this->_teammember->getFacebook()).'" />
            </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Team Member Instagram</label>
                <input type="text" class="form-control" name="member_instagram" max="256" value="'.htmlentities($this->_teammember->getInstagram()).'" />
            </div>
            </div>
            </div>
            <br />
            <div class="row">
            <div class="col-md-6">
            <p>Change Team Member Image:</p>
            <input type="file" class="btn btn-primary pull-center" name="member_image" value="" />
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
            <p>Current Team Member Image:</p>
            <img src="data:image/jpg;charset=utf8;base64,'.base64_encode($this->_teammember->getImage()).'"style="height:225px;width:365px;border-radius:8px;" alt="'.htmlentities($this->_teammember->getName()).'" />
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                <label class="bmd-label-floating">Status</label>
                <select class="form-control" name="member_status">
                <option value="1" '.($this->_teammember->getStatus() === 1 ? 'selected="selected"' : '').'>Active</option>
                <option value="0" '.($this->_teammember->getStatus() === 0 ? 'selected="selected"' : '').'>Inactive</option>
                </select>
            </div>
            </div>
            </div>
            <input type="submit" class="btn btn-primary pull-right" name="save_teammember" value="Save" />
        </form>
        ';

        return $html;
    }

    private function _save(): bool
    {
        $encoded_image = base64_encode(file_get_contents($_FILES['member_image']['name']));
        $encodedimg = 'data:image/jpg;charset=utf8;base64,' . $encoded_image;

        // Validate the data
        if(!$this->_validate()) return false;

        //Prepare the data for submission
        $data = [
            'member_name' => $this->_data['member_name'],
            'member_title' => $this->_data['member_title'],
            $encodedimg => $this->_data['member_image'],
            'member_desc' => $this->_data['member_desc'],
            'member_facebook' => $this->_data['member_facebook'],
            'member_instagram' => $this->_data['member_instagram'],
            'member_status' => $this->_data['member_status']
        ];

        if($this->_action == 'new'){
            // Insert the data in the database
            if(!$this->_db->insert('theteam', $data)){
                $this->_notifications[] = new Notification("There was an error adding the member. Please try again later.");
                return false;
            }
        } elseif($this->_action == 'edit'){
            // Update the data in the database
            if(!$this->_db->update('theteam', $data, "member_id = " . $this->_db->quote($this->_id))){
                $this->_notifications[] = new Notification("There was an error updating the member. Please try again later.");
                return false;
            }
        }

        // Inform the user that the team member has been saved
        $this->_notifications[] = new Notification("The team member has been saved successfully.", 'success');
        return true;
    }

    private function _validate(): bool
    {
        // Trim any whitespace around each string in the data
        foreach($this->_data as $key => $value){
            $this->_data[$key] = trim($value);
        }

        // Validate the team member name
        if(strlen(@$this->_data['member_name']) > 256 || strlen(@$this->_data['member_name']) < 2){
            $this->_notifications[] = new Notification("Please enter a name up to 256 characters long.");
            return false;
        }

        // Validate the team member title
        if(strlen(@$this->_data['member_title']) > 256 || strlen(@$this->_data['member_title']) < 2){
            $this->_notifications[] = new Notification("Please enter a title up to 256 characters long.");
            return false;
        }

        // Validate the team member description
        if(strlen(@$this->_data['member_desc']) < 20){
            $this->_notifications[] = new Notification("Please enter a description at least 20 characters long.");
            return false;
        }

        // Validate the team member image
        if(strlen(@$this->_data['member_image']) < 2){
            $this->_notifications[] = new Notification("Please choose a valid image to upload.");
            return false;
        }

        // Validate the status
        if(@$this->_data['member_status'] != 0 && @$this->_data['member_status'] != 1){
            $this->_notifications[] = new Notification("Please select the status.");
            return false;
        }

        // Return true for valid input
        return true;
    }
}