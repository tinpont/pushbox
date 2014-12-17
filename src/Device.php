<?php

namespace Tinpont\Pushbox;


class Device extends Options {

    /**
     * Identifier for device. Push token, sms number etc.
     *
     * @var string
     */
    protected $token;

    /**
     * Constructor
     *
     * @param string $token
     * @param array $options
     */
    public function __construct($token, array $options = []) {
        parent::__construct($options);

        $this->setToken($token);
    }

    /**
     * Set device token.
     *
     * @param string $token
     */
    public function setToken($token) {
        $this->token = $token;
    }

    /**
     * Get device token.
     *
     * @return string
     */
    public function getToken() {
        return $this->token;
    }

}