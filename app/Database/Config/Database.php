<?php

namespace Config;

use CodeIgniter\Database\Config;

/**
 * Database Configuration
 */
class Database extends Config
{
    /**
     * The directory that holds the Migrations and Seeds directories.
     */
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;

    /**
     * Lets you choose which connection group to use if no other is specified.
     */
    public string $defaultGroup = 'default';

    /**
     * The default database connection.
     */
    public array $default = [
        'DSN'          => '',
        'hostname'     => 'localhost',
        'username'     => 'root',
        'password'     => '',
        'database'     => 'do_list_db',
        'DBDriver'     => 'MySQLi',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'      => true,
        'charset'      => 'utf8',
        'DBCollat'     => 'utf8_general_ci',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'port'         => 3306,
        'numberNative' => false,
        'dateFormat'   => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

    // Development environment overrides
    public array $development = [
        'default' => [
            'DBDebug' => true,
        ],
    ];

    // Production environment overrides
    public array $production = [
        'default' => [
            'DBDebug' => false,
        ],
    ];

    public function __construct()
    {
        parent::__construct();

        // Set environment-specific settings
        if (ENVIRONMENT === 'production') {
            $this->default = array_merge($this->default, $this->production['default']);
        } elseif (ENVIRONMENT === 'development') {
            $this->default = array_merge($this->default, $this->development['default']);
        }
    }
}