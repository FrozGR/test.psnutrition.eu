<?php
/**
 * Custom exceptions class to override the default
 */
class CustomException extends Exception
{
    /**
     * Initialises the parent class (Exception)
     * with the provided parameters
     *
     * @param Exception $e
     */
    public function __construct(Exception $e = null)
    {
        parent::__construct($e->getMessage());
    }
    
    /**
     * Logs the error to the log file and
     * returns a user-friendly message to
     * describe the error that was thrown
     *
     * @return string
     */
    public function __toString(): string
    {
        // Log the error to the log file
        $this->logError();

        // Return a user-friendly message to describe the error
        return '<h1>Oops! We encountered an error...</h1><code>'.$this->getMessage().'</code>';
    }

    /**
     * Logs the error to the log file
     *
     * @return void
     */
    private function logError(): void
    {
        // Open the file using the globally defined path in the config file
        $fh = fopen(LOGS_PATH, 'a') or die("Can't open logs file in '" . LOGS_PATH . "'.");

        // Write the messsage to the file
        fwrite($fh, $this->getLogMsg());

        // Close the file
        fclose($fh);
    }

    /**
     * Returns a descriptive error message
     * for debugging purposes
     *
     * @return string
     */
    private function getLogMsg(): string
    {
        // Compile the message
        $msg  = "[" . date('Y-m-d H:i:s') . "]";
        $msg .= "\n\t" . $this->getMessage();
        $msg .= "\n\tFile:\t" . $this->getFile() . " (" . $this->getLine() . ")";
        $msg .= "\n\tTrace:\t" . str_replace("\n", "\n\t\t\t", $this->getTraceAsString()) . "\n\n";

        // Return the message
        return  $msg;
    }
}