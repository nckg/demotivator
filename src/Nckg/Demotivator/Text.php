<?php


namespace Nckg\Demotivator;


class Text
{
    /**
     * @var string
     */
    protected $text;

    /**
     * Text constructor.
     * @param $text
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * @param $name
     * @param $arguments
     * @return $this
     */
    function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return $this->$name($arguments);
        }

        return $this;
    }

    /**
     * Convert text to uppercase
     *
     * @return $this
     */
    public function upper()
    {
        $this->text = strtoupper($this->text);
        return $this;
    }

    /**
     * @return mixed
     */
    function __toString()
    {
        return $this->text;
    }
}