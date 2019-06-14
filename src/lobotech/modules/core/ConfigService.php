<?php

namespace lobotech\modules\core;

class ConfigService
{

    private $configs = [];

    public const        LOGGER = 'logger';
    public const        DEBUG = true; // set to false in production
    public static        $LOGGER_FILE = __DIR__.'/logger.log';
    public  static       $LOGGER_SECURITY_FILE =  __DIR__.'/logger-sec.log';
    const UNKNOWN_ERROR = 16405;
    const DUPLICATED_ROW = 23000;
    const PDO_DUPLICATED_ROW = 23000;
    const INVALID_PARAMS = 15000;

    const INVALID_LOGIN = 13000;
    const INVALID_PASSWORD = 13001;
    //
    const PARAM_NOT_ATTRIBUTE = 101000;
    const PARAM_EMPTY = 102000;
    //PERSIST
    const MAX_UPDATE_QTD = 10001;
    const MAX_DELETE_QTD = 10002;
    const NOT_FOUND = 10003;





    public function __construct($configs)
    {
        $this->configs = $configs;
    }

    public function get($key): string
    {

        if (array_key_exists($key, $this->configs)) {
            return $this->configs[$key];
        } else {

            throw new \Exception('KEY: [' . $key . '] NOT FOUND');
        }

    }
}