<?php
namespace cms\traits;

use Psr\Log\LoggerInterface;

trait LogTrait
{

    /**
     * 日志记录器
     *
     * @var unknown
     */
    protected $_logger;

    /**
     * 设置日志记录器
     *
     * @param unknown $logger            
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->_logger = $logger;
    }

    /**
     * 获取日志记录器
     *
     * @return LoggerInterface
     */
    protected function getLogger()
    {
        return $this->_logger;
    }

    /**
     * System is unusable
     *
     * @param string $message            
     * @param array $context            
     */
    protected function logEmergency($message, $context = [])
    {
        return $this->log('emergency', $message, $context);
    }

    /**
     * Action must be taken immediately
     *
     * @param string $message            
     * @param array $context            
     */
    protected function logAlert($message, $context = [])
    {
        return $this->log('alert', $message, $context);
    }

    /**
     * Critical conditions
     *
     * @param string $message            
     * @param array $context            
     */
    protected function logCritical($message, $context = [])
    {
        return $this->log('critical', $message, $context);
    }

    /**
     * Runtime errors
     *
     * @param string $message            
     * @param array $context            
     */
    protected function logError($message, $context = [])
    {
        return $this->log('error', $message, $context);
    }

    /**
     * Exceptional occurrences
     *
     * @param string $message            
     * @param array $context            
     */
    protected function logWarning($message, $context = [])
    {
        return $this->log('warning', $message, $context);
    }

    /**
     * Normal but significant events
     *
     * @param string $message            
     * @param array $context            
     */
    protected function logNotice($message, $context = [])
    {
        return $this->log('notice', $message, $context);
    }

    /**
     * Interesting events
     *
     * @param string $message            
     * @param array $context            
     */
    protected function logInfo($message, $context = [])
    {
        return $this->log('info', $message, $context);
    }

    /**
     * Detailed debug information
     *
     * @param string $message            
     * @param array $context            
     */
    protected function logDebug($message, $context = [])
    {
        return $this->log('debug', $message, $context);
    }

    /**
     * 记录日志
     *
     * @param string $level            
     * @param string $message            
     * @param array $context            
     */
    protected function log($level, $message, $context = [])
    {
        $logger = $this->getLogger();
        if ($logger) {
            if (method_exists($logger, $level)) {
                return $logger->$level($message, $context);
            } else {
                return $logger->log($level, $message, $context);
            }
        }
    }

}