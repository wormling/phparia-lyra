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

namespace phpariaLyra\Client;

use phparia\Client\Phparia;
use phpariaLyra\Resources\CpdResult;

class PhpariaLyra
{
    /**
     * @var Phparia
     */
    protected $phparia;

    /**
     * @var callable
     */
    protected $cpdResultCallback = null;

    /**
     * @param Phparia $phparia
     */
    public function __construct(Phparia $phparia)
    {
        $this->phparia = $phparia;
    }

    /**
     * Monitor CPD Results (AMD)
     *
     * @param callable $callback
     */
    public function onCpdResult(callable $callback)
    {
        $this->cpdResultCallback = function ($event) use ($callback) {
            $cpdResult = new CpdResult($event);
            $callback($cpdResult);
        };

        $this->phparia->getWsClient()->on('CPD-Result', $this->cpdResultCallback);
    }

    /**
     * Monitor CPD Results (AMD)
     *
     * @param callable $callback
     */
    public function onceCpdResult(callable $callback)
    {
        $this->cpdResultCallback = function ($event) use ($callback) {
            $cpdResult = new CpdResult($event);
            $callback($cpdResult);
        };

        $this->phparia->getWsClient()->once('CPD-Result', $this->cpdResultCallback);
    }

    /**
     * Remove CPD Result listener
     */
    public function removeCpdResult()
    {
        $this->phparia->getWsClient()->removeListener('CPD-Result', $this->cpdResultCallback);
    }
}