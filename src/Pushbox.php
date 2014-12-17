<?php

namespace Tinpont\Pushbox;


use Tinpont\Pushbox\Exception\AdapterException;

class Pushbox {

    /**
     * Pre-defined adapters.
     *
     * @var array
     */
    static protected $adapters = [
        'apns' => 'Adapter\Apns.php',
        'gcm'  => 'Adapter\Gcm.php'
    ];


    /**
     * Build an adapter.
     *
     * @param string|Adapter $adapter
     * @param array $options
     * @return mixed
     */
    static public function adapter($adapter, $options = []) {
        if (is_string($adapter)) {
            $adapters = static::$adapters;

            if (isset($adapters[$adapter])) {
                $adapter = $adapters[$adapter];
            }

            if (class_exists($adapter) && is_subclass_of($adapter, 'Adapter')) {
                return new $adapter($options);
            }

            throw new AdapterException('Class "' . $adapter . '" not found or class not subclass of Adapter.');
        }

        if ($adapter instanceof Adapter) {
            return $adapter;
        }

        throw new AdapterException('Could not find the adapter.');
    }

}