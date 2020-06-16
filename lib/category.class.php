<?php
/**
 * Handles the products category operations
 */
class Category
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
     * The category id
     *
     * @var int
     */
    private $_id;

    /**
     * The category name
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
     * The date of the category creation
     *
     * @var string
     */
    private $_created_on;

    /**
     * The status of the category
     *
     * @var int
     */
    private $_status;

    /**
     * An array of products that belong to the category
     *
     * @var array
     */
    private $_products = [];

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
     * Returns the category id
     *
     * @return integer
     */
    public function getId(): int
    {
        return (int) $this->_id;
    }

    /**
     * Returns the category name
     *
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->_name;
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
     * Returns the creation date of the category
     *
     * @param string $format
     * @return string
     */
    public function getCreatedOn(string $format = 'Y-m-d H:i:s'): string
    {
        return (string) date($format, strtotime($this->_created_on));
    }

    /**
     * Returns the status of the category
     *
     * @return integer
     */
    public function getStatus(): int
    {
        return (int) $this->_status;
    }

    /**
     * Returns an array of products that belong
     * to the category
     *
     * @return array
     */
    public function getProducts(): array
    {
        // Check if we arleady retrieved the products
        // This acts as a cache for the system
        if(!empty($this->_products)) return $this->_products;

        // Initialise the where clause of the query
        $where = '';

        // Check if the id is valid and override the where clause
        // to get the products that belong to the category
        if($this->_id > 0)
            $where = "AND a.product_category = ".$this->_db->quote($this->_id);

        // Get the products
        $products = $this->_db->query("
            SELECT 	a.product_id AS product_id,
                    a.product_name AS product_name,
                    a.product_desc AS product_desc,
                    a.product_created_on AS product_created_on,
                    a.product_status AS product_status,
                    c.product_category_id AS product_category,
                    u.user_id AS product_author
            FROM products AS a
            LEFT JOIN product_categories AS c ON (a.product_category = c.product_category_id)
            LEFT JOIN users AS u ON (a.product_author = u.user_id)
            WHERE 	a.product_status = 1
                    AND c.product_category_status = 1
                    ".$where."
            GROUP BY a.product_id
            ORDER BY a.product_created_on DESC
        ");

        // Check if you got any products
        if(empty($products)) return $this->_products;

        // Initialise a temporary array to hold the product objects
        $tmp_products = [];

        // Iterate through the products
        foreach($products as $product){
            // Create a new object of product
            $tmp_product = new Product();

            // Set the data
            $tmp_product->setFromArray($product);

            // Set the object to the temp array
            $tmp_products[] = $tmp_product;
        }

        // Set the products and return them
        return $this->_products = $tmp_products;
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
        $this->_id          = isset($data['product_category_id']) ? $data['product_category_id'] : null;
        $this->_name        = isset($data['product_category_name']) ? $data['product_category_name'] : null;
        $this->_image       = isset($data['product_category_image']) ? $data['product_category_image'] : null;
        $this->_created_on  = isset($data['product_category_created_on']) ? $data['product_category_created_on'] : null;
        $this->_status      = isset($data['product_category_status']) ? $data['product_category_status'] : null;
    }

    /**
     * Gets all the category information from the database
     * using the provided ID and sets them in the object
     *
     * @param int $id
     * @return boolean
     */
    public function setFromId(int $id): bool
    {
        // Make a query to the database
        $category = $this->_db->query("
            SELECT *
            FROM  product_categories
            WHERE product_category_id = " . $this->_db->quote($id) ."
        ");

        // Check if we received any data
        if(empty($category)) return false;

        // Set the data using the array
        $this->setFromArray($category[0]);

        return true;
    }
}