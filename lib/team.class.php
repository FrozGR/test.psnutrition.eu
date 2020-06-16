<?php
/**
 * Handles the teammember operations
 */
class TeamMember
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
     * The team member id
     *
     * @var int
     */
    private $_id;

    /**
     * The team member name
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
     * The date of the team member creation
     *
     * @var string
     */
    private $_created_on;

    /**
     * The status of the team member
     *
     * @var int
     */
    private $_status;

    /**
     * The team member title
     *
     * @var string
     */
    private $_title;

        /**
     * The team member image
     *
     * @var string
     */
    private $_desc;

    /**
     * The team member image
     *
     * @var string
     */
    private $_facebook;

    /**
     * The team member image
     *
     * @var string
     */
    private $_instagram;

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
     * Returns the team member id
     *
     * @return integer
     */
    public function getId(): int
    {
        return (int) $this->_id;
    }

    /**
     * Returns the team member name
     *
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->_name;
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
     * Returns the team member image
     *
     * @return string
     */
    public function getTitle(): string
    {
        return (string) $this->_title;
    }

    /**
     * Returns the team member description
     *
     * @return string
     */
    public function getDesc(): string
    {
        return (string) $this->_desc;
    }

    /**
     * Returns the team member Facebook
     *
     * @return string
     */
    public function getFacebook(): string
    {
        return (string) $this->_facebook;
    }

    /**
     * Returns the team member Instagram
     *
     * @return string
     */
    public function getInstagram(): string
    {
        return (string) $this->_instagram;
    }

    /**
     * Returns the status of the team member
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
        $this->_id          = isset($data['member_id']) ? $data['member_id'] : null;
        $this->_name        = isset($data['member_name']) ? $data['member_name'] : null;
        $this->_title        = isset($data['member_title']) ? $data['member_title'] : null;
        $this->_image       = isset($data['member_image']) ? $data['member_image'] : null;
        $this->_desc        = isset($data['member_desc']) ? $data['member_desc'] : null;
        $this->_facebook  = isset($data['member_facebook']) ? $data['member_facebook'] : null;
        $this->_instagram  = isset($data['member_instagram']) ? $data['member_instagram'] : null;
        $this->_status      = isset($data['member_status']) ? $data['member_status'] : null;
    }

    /**
     * Gets all the teammember information from the database
     * using the provided ID and sets them in the object
     *
     * @param int $id
     * @return boolean
     */
    public function setFromId(int $id): bool
    {
        // Make a query to the database
        $teammember = $this->_db->query("
            SELECT *
            FROM  theteam
            WHERE member_id = " . $this->_db->quote($id) ."
        ");

        // Check if we received any data
        if(empty($teammember)) return false;

        // Set the data using the array
        $this->setFromArray($teammember[0]);

        return true;
    }
}