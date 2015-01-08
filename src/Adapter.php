<?php

namespace Tinpont\Pushbox;


use Tinpont\Pushbox\Exception\AdapterException;

abstract class Adapter extends Options {

    /**
     * Success push response.
     *
     * @var mixed
     */
    protected $success;

    /**
     * Fail push response.
     *
     * @var mixed
     */
    protected $fails;

    /**
     * Devices ready to push.
     *
     * @var array
     */
    protected $devices = [];

    /**
     * Get success push response.
     *
     * @return mixed
     */
    public function success() {
        return $this->success;
    }

    /**
     * Get fail push response.
     *
     * @return mixed
     */
    public function fails() {
        return $this->fails;
    }

    /**
     * Set devices.
     *
     * @param string|array|Device $devices
     */
    public function setDevices($devices) {
        $this->devices = [];

        if (!is_array($devices)) {
            $devices = [$devices];
        }

        foreach ($devices as $device) {
            $this->addDevice($device);
        }
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
            throw new AdapterException(
                'Adapter "' . get_class($this) . '" does not support device token "' . $token . '" .'
            );
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
        $this->setDevices($devices);

        return $this;
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