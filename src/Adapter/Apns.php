<?php

namespace Tinpont\Pushbox\Adapter;


use Tinpont\Pushbox\Device;
use Tinpont\Pushbox\Options;
use Tinpont\Pushbox\Adapter;
use Tinpont\Pushbox\Exception\AdapterException;
use ZendService\Apple\Apns\Message as ZendMessage;
use ZendService\Apple\Apns\Message\Alert as ZendMessageAlert;
use ZendService\Apple\Apns\Client\Message as ZendClientMessage;
use ZendService\Apple\Apns\Client\Feedback as ZendClientFeedback;
use ZendService\Apple\Apns\Client\AbstractClient as ZendAbstractClient;
use ZendService\Apple\Apns\Response\Message as ZendResponseMessage;
use ZendService\Apple\Exception\RuntimeException as ZendRuntimeException;

class Apns extends Adapter {

    /**
     * Pushing message.
     *
     * @param mixed $message
     * @return Adapter
     */
    public function push($message) {
        $this->success = $this->fails = [];
        $zendClient = $this->openZendClient(new ZendClientMessage());

        foreach ($this->getDevices() as $device) {
            $zendMessage = $this->getZendMessage($device, $message);

            try {
                $response = $zendClient->send($zendMessage);
            } catch (ZendRuntimeException $e) {
                throw new AdapterException($e->getMessage());
            }

            if (ZendResponseMessage::RESULT_OK === $response->getCode()) {
                $this->success[] = $zendMessage;
            } else {
                $this->fails[] = $zendMessage;
            }
        }

        $zendClient->close();

        return $this;
    }

    /**
     * Apns feedback.
     *
     * @return array
     */
    public function feedback() {
        $zendClient = $this->openZendClient(new ZendClientFeedback());

        $zendResponses = $zendClient->feedback();
        $zendClient->close();

        $responses = [];
        foreach ($zendResponses as $zendResponse) {
            $responses[$zendResponse->getToken()] = $zendResponse->getTime();
        }

        return $responses;
    }

    /**
     * Validate the token.
     *
     * @param string $token
     * @return bool
     */
    protected function isValidToken($token) {
        return ctype_xdigit($token) && strlen($token) == 64;
    }

    /**
     * Get zend apns message.
     *
     * @param Device $device
     * @param mixed $message
     * @return ZendMessage
     */
    protected function getZendMessage(Device $device, $message) {
        $message = $this->getMessage($message);
        $options = new Options(array_merge($message->getOptions(), $device->getOptions()));

        // apns identifier
        $zendMessage = new ZendMessage();
        $zendMessage->setId(time());

        // apns alert options
        $zendMessage->setAlert(
            new ZendMessageAlert(
                $message->getText(),
                $options->getOption('actionLocKey'),
                $options->getOption('locKey'),
                $options->getOption('locArgs'),
                $options->getOption('launchImage')
            )
        );

        // apns badge number
        $badge = intval($device->getOption('badge')) + intval($message->getOption('badge'));
        $zendMessage->setBadge($badge);

        // others...
        $zendMessage->setToken($device->getToken());
        $zendMessage->setSound($options->getOption('sound', 'bingbong.aiff'));
        $zendMessage->setCustom($options->getOption('custom', []));

        return $zendMessage;
    }

    /**
     * Open zend client.
     *
     * @param ZendAbstractClient $client
     * @return ZendAbstractClient
     */
    protected function openZendClient(ZendAbstractClient $client) {
        try {
            $client->open(
                $this->getOption('prod') ? ZendAbstractClient::PRODUCTION_URI : ZendAbstractClient::SANDBOX_URI,
                $this->getOption('cert'),
                $this->getOption('pass')
            );
        } catch (\Exception $e) {
            throw new AdapterException($e->getMessage());
        }

        return $client;
    }

}