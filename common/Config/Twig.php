<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Twig extends BaseConfig
{
    public array $config = [];

    private array $functions_asis = [
        'current_url',
    ];

    private array $functions_safe = [
        'csrf_meta',
        'url_is',
        'lang',
    ];

    private array $debug_functions = [
        'd',
        'dd',
        'print_r',
        'var_dump',
    ];

    public function __construct()
    {
        parent::__construct();

        if (ENVIRONMENT == 'development') {
            $this->functions_safe = array_merge($this->functions_safe, $this->debug_functions);
        }

        $this->config = [
            'functions_asis' => $this->functions_asis,
            'functions_safe' => $this->functions_safe,
        ];
    }
}
