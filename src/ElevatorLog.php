<?php

namespace AustinW\Elevator;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class ElevatorLog
{
    protected $logger;

    protected $logLevel;

    public function __construct()
    {
        $this->logLevel = Logger::DEBUG;
    }

    public function setTerminalOutput()
    {
        $output = "[%datetime%] %channel%.%level_name%: %message%\n";
        $formatter = new LineFormatter($output);

        $streamHandler = new StreamHandler('php://stdout', $this->logLevel);
        $streamHandler->setFormatter($formatter);

        $this->logger = new Logger('ElevatorLogger');
        $this->logger->pushHandler($streamHandler);
    }

    public function setFileOutput($file)
    {
        $output = "[%datetime%] %channel%.%level_name%: %message%\n";
        $formatter = new LineFormatter($output);

        $streamHandler = new StreamHandler(__DIR__. '/../' . $file, $this->logLevel);
        $streamHandler->setFormatter($formatter);

        $this->logger = new Logger('ElevatorLogger');
        $this->logger->pushHandler($streamHandler);
    }

    public function setNoLog()
    {
        $this->logger = new Logger('ElevatorLogger');
        $this->logger->pushHandler(new NullHandler());
    }

    /**
     * @return mixed
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return int
     */
    public function getLogLevel()
    {
        return $this->logLevel;
    }

    /**
     * @param  int   $logLevel
     * @return $this
     */
    public function setLogLevel($logLevel)
    {
        $this->logLevel = $logLevel;
        return $this;
    }
}
