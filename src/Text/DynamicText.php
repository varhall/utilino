<?php

namespace Varhall\Utilino\Text;


class DynamicText
{
    protected $text = '';

    public function __construct($text)
    {
        $this->text = $text;
    }

    public static function create($text)
    {
        return new static($text);
    }

    public function variables()
    {
        return array_unique(array_map(function($item) {
            return $item->variable;
        }, $this->parse()));
    }

    public function execute(array $variables)
    {
        $variables = array_change_key_case($variables);
        $result = $this->text;

        foreach ($this->parse() as $placeholder) {
            $variable = strtolower($placeholder->variable);
            $result = preg_replace("#\{+ *{$variable}[^}]*\}+#i", $placeholder->execute($variables[$variable]), $result, 1);
        }

        return $result;
    }

    protected function parse()
    {
        //if (!preg_match_all('\'#\{+ *([A-Za-z0-9]+)(\ *| *(.+))* *\}+#\'', $this->text, $matches))
        if (!preg_match_all('#\{+ *(.+?) *\}+#i', $this->text, $matches))
            return [];

        return array_map(function($match) {
            return new Placeholder($match);
        }, $matches[1]);
    }
}
