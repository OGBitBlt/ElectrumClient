<?php
namespace OGBitBlt\Electrum;
/** 
 * Provides tracing and diagnostics information
 *  
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $$ 
 * @access public  
 */
class Diagnostics 
{
    /**
     * @var DIAGNOSTICS_OFF pass to setFlag to turn off all diagnostics
     */
    const DIAGNOSTICS_OFF           = 0b0000;
    /**
     * @var DIAGNOSTICS_WARNINGS_ONLY pass to setFlag to trace warnings
     */
    const DIAGNOSTICS_WARNINGS_ONLY = 0b0001;
    /**
     * @var DIAGNOSTICS_CRITICAL pass to setFlat to capture crtical warnings
     */
    const DIAGNOSTICS_CRITICAL      = 0b0010;
    /**
     * @var DIAGNOSTICS_LOG_CONSOLE pass to setFlag to log errors to STDERROR
     */
    const DIAGNOSTICS_LOG_CONSOLE   = 0b0100;
    /**
     * @var DIAGNOSTICS_LOG_FILE pass to setFlag to log errors to a file
     */
    const DIAGNOSTICS_LOG_FILE      = 0b1000;
    /**
     * @var DIAGNOSTICS_ALL pass to setFlag to turn on all diagnostics flags
     */
    const DIAGNOSTICS_ALL           = 0b1111;

    /**
     * @var ERROR_LEVEL_TRACE used to identify the error as a trace level error
     */
    const ERROR_LEVEL_TRACE         = 0b0000;
    /**
     * @var ERROR_LEVEL_WARN used to identify the error as a warning 
     */
    const ERROR_LEVEL_WARN          = 0b0001;
    /**
     * @var ERROR_LEVEL_CRITICAL used to identify the error as critical
     */
    const ERROR_LEVEL_CRITICAL      = 0b0010;

    /**
     * @var $_diagnosticFlags stores the currently specified error flags
     */
    private static $_diagnosticFlags = self::DIAGNOSTICS_OFF;
    /**
     * @var $_logFile The file location of the file to use for logging
     */
    private static $_logFile = '/tmp/ogbitblt-electrum-client.log';

    /**
     * setLogFile -- sets the location of the file used for logging info to
     * @param string path to the file used to log error ino
     */
    public static function setLogFile(string $logFile) : void
    {
        self::$_logFile = $logFile;
    }

    /**
     * setFlag -- specifies what level of error info to report and 
     * how it should be reported.
     * Example:
     *          (log critical errors to file)
     *          setFlag(Diagnostics::DIAGNOSTICS_CRITICAL|Diagnostics::DIAGNOSTICS_LOG_FILE)
     *          (log warning level and up errors to file and stderr) 
     *          setFlag(Diagnostics::DIAGNOSTICS_WARNINGS_ONLY|Diagnostics::DIAGNOSTICS_LOG_CONSOLE|Diagnostics::DIAGNOSTICS_LOG_FILE)
     *          (log all info)
     *          setFlag(Diagnostics::DIAGNOSTICS_ALL)
     *          (turn all diagnostics info off) 
     *          setFlag(Diagnostics::DIAGNOSTICS_OFF)
     * @param uInt32 $flags
     * @return void
     */
    public static function setFlag(int $flags) : void
    {
        self::$_diagnosticFlags = $flags;
    }

    /**
     * Trace - Used to write output either to the console or to a file.
     * @param string $message - The message to be logged
     * @param int $errorLevel - The error level of the message
     * @return void
     */
    public static function Trace(string $message, int $errorLevel = self::ERROR_LEVEL_TRACE) : void
    {
        // just return if diagnostics is turned off.
        if(self::$_diagnosticFlags & self::DIAGNOSTICS_OFF) return;
        
        // build the ouput message based on the error level
        switch($errorLevel){
            case self::ERROR_LEVEL_CRITICAL:
                $output = sprintf("[%d][ERROR] : %s\n",time(),$message);
                break;
            case self::ERROR_LEVEL_WARN:
                $output = sprintf("[%d][WARNING] : %s\n",time(),$message);
                break;
            case self::ERROR_LEVEL_TRACE:
                $output = sprintf("[%d][DEBUG] : %s\n",time(),$message);
                break;

        }

        // check if we are logging to console
        if(self::$_diagnosticFlags & self::DIAGNOSTICS_LOG_CONSOLE){
            fprintf(STDOUT, $output);
        }

        // check if we are logging to a file
        if(self::$_diagnosticFlags & self::DIAGNOSTICS_LOG_FILE){
            file_put_contents(self::$_logFile, $output, FILE_APPEND|LOCK_EX);
        }
    }
}
?>