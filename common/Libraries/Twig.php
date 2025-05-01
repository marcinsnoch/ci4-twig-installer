<?php

namespace App\Libraries;

use Config\Services;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Extra\Intl\IntlExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class Twig
{
    private array $paths = [];
    private array $config = [];

    private array $functions_asis = ['base_url', 'site_url'];
    private array $functions_safe = [
        'csrf_field', 'csrf_token', 'csrf_hash', 'csrf_meta',
        'form_open', 'form_close', 'form_error', 'form_hidden', 'set_value',
        'form_open_multipart', 'form_upload', 'form_submit', 'form_dropdown',
        'set_radio', 'set_select', 'set_checkbox',
    ];

    private bool $functions_added = false;

    private ?Environment $twig = null;
    private ?FilesystemLoader $loader = null;

    public function __construct(array $params = [])
    {
        $this->mergeFunctions($params);
        $this->initializePaths($params);
        $this->initializeConfig($params);
    }

    public function display(string $view, array $params = []): void
    {
        echo $this->render($view, $params);
    }

    public function render(string $view, array $params = []): string
    {
        $this->initializeTwig();
        $this->addFunctions();

        return $this->twig->render($view . '.twig', $params);
    }

    public function addGlobal(string $name, mixed $value): void
    {
        $this->initializeTwig();
        $this->twig->addGlobal($name, $value);
    }

    public function validation_list_errors(): string
    {
        return Services::validation()->listErrors();
    }

    public function getTwig(): Environment
    {
        $this->initializeTwig();

        return $this->twig;
    }

    protected function resetTwig(): void
    {
        $this->twig = null;
        $this->initializeTwig();
    }

    protected function setLoader(FilesystemLoader $loader): void
    {
        $this->loader = $loader;
    }

    // ========== Prywatne metody pomocnicze ==========

    private function mergeFunctions(array &$params): void
    {
        if (isset($params['functions'])) {
            $this->functions_asis = array_unique(array_merge($this->functions_asis, $params['functions']));
            unset($params['functions']);
        }

        if (isset($params['functions_safe'])) {
            $this->functions_safe = array_unique(array_merge($this->functions_safe, $params['functions_safe']));
            unset($params['functions_safe']);
        }
    }

    private function initializePaths(array &$params): void
    {
        if (isset($params['paths'])) {
            $this->paths = is_array($params['paths']) ? $params['paths'] : [$params['paths']];
            unset($params['paths']);
        } else {
            $this->paths = [APPPATH . 'Views/'];
        }
    }

    private function initializeConfig(array $params): void
    {
        $defaults = [
            'cache' => WRITEPATH . 'cache/twig',
            'debug' => ENVIRONMENT !== 'production',
            'autoescape' => 'html',
        ];

        $this->config = array_merge($defaults, $params);
    }

    private function initializeTwig(): void
    {
        if ($this->twig !== null) {
            return;
        }

        if ($this->loader === null) {
            $this->loader = new FilesystemLoader($this->paths);
        }

        $this->twig = new Environment($this->loader, $this->config);

        // Debug
        if ($this->config['debug']) {
            $this->twig->addExtension(new DebugExtension());
        }

        // Intl
        $this->twig->addExtension(new IntlExtension());
    }

    private function addFunctions(): void
    {
        if ($this->functions_added) {
            return;
        }

        foreach ($this->functions_asis as $function) {
            if (function_exists($function)) {
                $this->twig->addFunction(new TwigFunction($function, $function));
            }
        }

        foreach ($this->functions_safe as $function) {
            if (function_exists($function)) {
                $this->twig->addFunction(new TwigFunction($function, $function, ['is_safe' => ['html']]));
            }
        }

        if (function_exists('anchor')) {
            $this->twig->addFunction(new TwigFunction('anchor', [$this, 'safe_anchor'], ['is_safe' => ['html']]));
        }

        $this->twig->addFunction(new TwigFunction('validation_list_errors', [$this, 'validation_list_errors'], ['is_safe' => ['html']]));

        $this->functions_added = true;
    }
}

