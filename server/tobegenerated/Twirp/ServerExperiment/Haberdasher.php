<?php

namespace Twirp\ServerExperiment;

use Twirphp\Server_experiment\Hat;
use Twirphp\Server_experiment\Size;

interface Haberdasher
{
    /**
     * @param array $ctx
     * @param Size  $size
     * @return Hat
     */
    public function makeHat(array $ctx, Size $size);
}
