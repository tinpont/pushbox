<?php

namespace Tinpont\Pushbox;


class Message extends Options {

    /**
     * Message text to push.
     *
     * @var string
     */
    protected $text;

    /**
     * Constructor
     *
     * @param string $text
     * @param array $options
     */
    public function __construct($text, $options = []) {
        parent::__construct($options);

        $this->setText($text);
    }

    /**
     * Set message text.
     *
     * @param string $text
     */
    public function setText($text) {
        $this->text = $text;
    }

    /**
     * Get message text.
     *
     * @return string
     */
    public function getText() {
        return $this->text;
    }

}