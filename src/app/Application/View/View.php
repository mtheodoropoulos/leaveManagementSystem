<?php

declare(strict_types = 1);

namespace App\Application\View;

class View
{
    protected $template;
    protected $variables = [];

    public function __construct($template, $variables = [])
    {
        $this->template  = $template;
        $this->variables = $variables;
    }

    public function render()
    {
        // Extract the variables to make them available in the template
        extract($this->variables);

        ob_start();

        include '/var/www/resources/views/' .$this->template;

        // Get the contents of the output buffer and clean it
        return ob_get_clean();
    }
}
