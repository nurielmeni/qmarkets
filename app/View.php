<?php

class View {
    /**
     *
     * @param $name   - PHP file that acts as a template.
     * @param $args   - Associative array of variables to pass to the template file.
     * @return string - Output of the template file.
     */

    private $path;
    public function __construct($path = __DIR__)
    {
        $this->path = $path;
    }

    public function render($name, $args = [])
    {
        $file = $this->path . '/views/' . $name . '.php';
        // ensure the file exists
        if (!file_exists($file)) {
            return '';
        }

        // Make values in the associative array easier to access by extracting them
        if (is_array($args)) {
            extract($args);
        }

        // buffer the output (including the file is "output")
        ob_start();
        include($file);
        return ob_get_clean();
    }
}