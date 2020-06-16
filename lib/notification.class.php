<?php
/**
 * Handles the notifications system
 */
class Notification
{
    /**
     * The message and type of the notification
     *
     * @var string
     */
    private $_message, $_type;

    /**
     * Initialises the notification object
     *
     * @param string $message
     * @param string $type
     */
    public function __construct(string $message, string $type = 'error')
    {
        $this->_message = $message;
        $this->_type = $type;
    }

    /**
     * Sets the message of the notification
     *
     * @param string $message
     * @return void
     */
    public function setMessage(string $message): void
    {
        $this->_message = $message;
    }

    /**
     * Sets the type of the notification
     *
     * @param string $type
     * @return void
     */
    public function setType(string $type): void
    {
        $this->_type = $type;
    }

    /**
     * Returns the message of the notification
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->_message;
    }

    /**
     * Returns the type of the notification
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->_type;
    }

    /**
     * Prints the HTML representation of the notification
     *
     * @return void
     */
    public function display(): void
    {
        echo '<p class="' . $this->_type . '_msg notification">' . $this->_message . '</p>';
    }

    /**
     * Adds the message to the session and redirects to the
     * provided url
     *
     * @param string $url
     * @return void
     */
    public function redirect(string $url): void
    {
        // Add the message and type to the session
        $_SESSION['notifications'][] = [$this->getMessage(), $this->getType()];
        
        // Redirect the page and exit
        header("Location: " . $url);
        die();
    }
}