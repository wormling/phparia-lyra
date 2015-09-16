<?php

/*
 * Copyright 2015 Brian Smith <wormling@gmail.com>.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace phpariaLyra\Examples;

use phparia\Client\Phparia;
use phparia\Events\StasisStart;
use phparia\Exception\ServerException;
use phpariaLyra\Client\PhpariaLyra;
use phpariaLyra\Resources\CpdResult;
use Symfony\Component\Yaml\Yaml;
use Zend\Log;

// Make sure composer dependencies have been installed
require __DIR__.'/../../../../vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('xdebug.var_display_max_depth', 4);

/**
 * @author Brian Smith <wormling@gmail.com>
 */
class AmdExample
{
    /**
     * Example of creating a stasis app which also supports AMI events.
     *
     * @var Phparia
     */
    public $client;

    public function __construct()
    {
        $configFile = __DIR__.'/../config.yml';
        $value = Yaml::parse(file_get_contents($configFile));

        $ariAddress = $value['examples']['client']['ari_address'];
        $amiAddress = $value['examples']['client']['ami_address'];
        $dialString = $value['examples']['dial_example']['dial_string'];

        $logger = new Log\Logger();
        $logWriter = new Log\Writer\Stream("php://output");
        $logger->addWriter($logWriter);
        //$filter = new Log\Filter\SuppressFilter(true);
        $filter = new Log\Filter\Priority(Log\Logger::NOTICE);
        $logWriter->addFilter($filter);

        // Connect to the ARI server
        $this->client = new Phparia($logger);
        $this->client->connect($ariAddress, $amiAddress);
        $phpariaLyra = new PhpariaLyra($this->client);

        $this->client->getAriClient()->onConnect(function () use ($dialString) {
            try {
                $this->client->channels()->createChannel($dialString, null, null, null, null,
                    $this->client->getStasisApplicationName(), 'dialed', '8185551212', 30, null, null,
                    array('MYVARIABLE' => 'value'));
            } catch (ServerException $e) {
                $this->log($e->getMessage());
            }
        });

        // Listen for the stasis start
        $this->client->onStasisStart(function (StasisStart $event) use ($dialString) {
            if (count($event->getArgs()) > 0 && $event->getArgs()[0] === 'dialed') {
                $this->log('Detected outgoing call with variable MYVARIABLE='.$event->getChannel()->getVariable('MYVARIABLE')->getValue());

                // Put the new channel in a bridge
                $channel = $event->getChannel();
                $this->bridge = $this->client->bridges()->createBridge(uniqid(), 'dtmf_events, mixing',
                    'dial_example_bridge');
                $this->bridge->addChannel($channel->getId());
            }
        });

        // Log the AMD result
        $phpariaLyra->onCpdResult(function (CpdResult $cpdResult) {
            $this->log('AMD Result: '.$cpdResult->getResult());
        });

        $this->client->run();
    }

    /**
     * @param string $msg
     */
    protected function log($msg)
    {
        $logger = $this->client->getLogger();
        $logger->notice($msg);
    }

}

new AmdExample();
