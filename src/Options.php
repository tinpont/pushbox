<?php

namespace Tinpont\Pushbox;


abstract class Options {

    /**
     * Options.
     *
     * @var array
     */
    protected $options;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = []) {
        $this->setOptions($options);
    }

    /**
     * Set options.
     *
     * @param array $options
     */
    public function setOptions(array $options) {
        $this->options = $options;
    }

    /**
     * Get options.
     *
     * @return array
     */
    public function getOptions() {
        return $this->options;
    }

    /**
     * Get option by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getOption($key, $default = null) {
        if (isset($this->options[$key])) {
            return $this->options[$key];
        }

        return $default;
    }

    /**
     * Set option by key.
     *
     * @param string $key
     * @param mixed $value
     */
    public function setOption($key, $value) {
        $this->options[$key] = $value;
    }

}