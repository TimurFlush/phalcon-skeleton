<?php declare(strict_types = 1);

namespace app\libraries\volt;

class CallPhpFunctions
{
    private $_map = [
        'isset' => 'isset(%args%)',
        'isEmpty' => 'empty(%args%)'
    ];

    public function compileFunction($name, $arguments)
    {
        if (function_exists($name)) {
            return $name . '(' . $arguments . ')';
        }

        if (isset($this->_map[$name])) {
            return str_replace('%args%', $arguments, $this->_map[$name]);
        }
    }
}
