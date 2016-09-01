<?php


namespace Nckg\Demotivator\Quotes;


class Quote
{
    protected $items;

    /**
     * Quote constructor.
     */
    public function __construct()
    {
        $this->items = json_decode(file_get_contents(realpath(__DIR__ . "/quotes.json")));
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return mixed
     */
    public function random()
    {
        $key = array_rand($this->items);
        return $this->items[$key];
    }
}