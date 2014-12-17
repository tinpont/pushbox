<?php

namespace Tinpont\Pushbox;


use Tinpont\Pushbox\Exception\AdapterException;

abstract class Adapter extends Options {

    /**
     * Push response.
     *
     * @var mixed
     */
    protected $response;

    /**
     * Devices ready to push.
     *
     * @var array
     */
    protected $devices = [];

    /**
     * Get response after pushed.
     *
     * @return mixed
     */
    public function getResponse() {
        return $this->response;
    }

    /**
     * Get the first response after pushed.
     *
     * @return mixed
     */
    public function firstResponse() {
        if (is_array($this->response)) {
            return count($this->response) ? reset($this->response) : null;
        }

        return $this->response;
    }

    /**
     * Set devices.
     *
     * @param string|array|Device $devices
     * @return Adapter
     */
    public function setDevices($devices) {
        $this->devices = [];

        if (!is_array($devices)) {
            $devices = [$devices];
        }

        foreach ($devices as $device) {
            $this->addDevice($device);
        }

        return $this;
    }

    /**
     * Add a new device.
     *
     * @param string|Device $device
     */
    public function addDevice($device) {
        if (!($device instanceof Device)) {
            $device = new Device($device);
        }

        $token = $device->getToken();
        if (!$this->isValidToken($token)) {
            throw new AdapterException('Adapter "' . get_class($this) . '" does not support device token "' . $token . '" .');
        }

        $this->devices[$device->getToken()] = $device;
    }

    /**
     * Get devices.
     *
     * @return array
     */
    public function getDevices() {
        return array_values($this->devices);
    }

    /**
     * Get device tokens.
     *
     * @return array
     */
    public function getDeviceTokens() {
        $tokens = [];
        foreach ($this->devices as $device) {
            $tokens[] = $device->getToken();
        }

        return $tokens;
    }

    /**
     * Short method for setDevices.
     *
     * @param string|array|Device $devices
     * @return Adapter
     */
    public function to($devices) {
        return $this->setDevices($devices);
    }

    /**
     * Pushing message.
     * Override this method to custom the way you push.
     *
     * @param mixed $message
     * @return Adapter
     */
    abstract public function push($message);

    /**
     * Validate the token.
     * Override this method to change validate way.
     *
     * @param string $token
     * @return bool
     */
    abstract protected function isValidToken($token);

    /**
     * Reformat message.
     *
     * @param mixed $message
     * @return Message
     */
    protected function getMessage($message) {
        if (!($message instanceof Message)) {
            return new Message($message);
        }

        return $message;
    }

}