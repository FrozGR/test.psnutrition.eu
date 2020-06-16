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
     * The product id
     *
     * @var int
     */
    private $_id;

    /**
     * The product category
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
     * The product desc
     *
     * @var string
     */
    private $_desc;

    /**
     * The product author
     *
     * @var User
     */
    private $_author;

    /**
     * The date of creation of the product
     *
     * @var string
     */
    private $_created_on;

    /**
     * The product status
     *
     * @var int
     */
    private $_status;

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
     * Returns the product desc
     *
     * @return string
     */
    public function getDesc(): string
    {
        return (string) $this->_desc;
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
        $this->_name           = isset($data['product_name']) ? trim($data['product_name']) : null;
        $this->_desc            = isset($data['product_desc']) ? trim($data['product_desc']) : null;
        $this->_created_on      = isset($data['product_created_on']) ? trim($data['product_created_on']) : null;
        $this->_status          = isset($data['product_status']) ? trim($data['product_status']) : null;

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
            SELECT a.*, 
            FROM  products AS a
            WHERE a.product_id = " . $this->_db->quote($id) ."
        ");

        // Check if we received any data
        if(empty($product)) return false;

        // Set the data using the array
        $this->setFromArray($product[0]);

        return true;
    }
}