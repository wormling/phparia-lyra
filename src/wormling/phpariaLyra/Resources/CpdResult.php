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

namespace phpariaLyra\Resources;

use phparia\Resources\Response;

/**
 * Lyra AMD CPD-Result
 *
 * @author Brian Smith <wormling@gmail.com>
 */
class CpdResult extends Response
{
    // Post-connect CPD results
    const CPD_RESULT_UNKNOWN = 'Unknown';
    const CPD_RESULT_REJECT = 'Reject';
    const CPD_RESULT_ANSWERING_MACHINE = 'Answering Machine';
    const CPD_RESULT_VOICE = 'Voice';
    const CPD_RESULT_FAX = 'Fax';
    const CPD_RESULT_ERROR = '???';

    /**
     * @var string Such as "system,all"
     */
    private $privilege;

    /**
     * @var string Such as "SIP"
     */
    private $channelDriver;

    /**
     * @var string Such as "SIP/vitelity-out-00000010"
     */
    private $channel;

    /**
     * @var string|false (optional) - Caller ID name.
     */
    private $callerIdName;

    /**
     * @var string ID of the call from the dial command.
     */
    private $uniqueId;

    /**
     * @var string CPD Result
     */
    private $result;

    /**
     * @return string
     */
    public function getPrivilege()
    {
        return $this->privilege;
    }

    /**
     * @return string
     */
    public function getChannelDriver()
    {
        return $this->channelDriver;
    }

    /**
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @return false|string
     */
    public function getCallerIdName()
    {
        return $this->callerIdName;
    }

    /**
     * @return string
     */
    public function getUniqueid()
    {
        return $this->uniqueId;
    }

    /**
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param string $response
     */
    public function __construct($response)
    {
        parent::__construct($response);

        $this->privilege = $this->response->Privilege;
        $this->channelDriver = $this->response->ChannelDriver;
        $this->channel = $this->response->Channel;
        $this->callerIdName = $this->response->CallerIDName;
        $this->uniqueId = $this->response->Uniqueid;
        $this->result = $this->response->Result;
    }
}
