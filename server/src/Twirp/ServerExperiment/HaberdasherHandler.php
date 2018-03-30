<?php

namespace Twirp\ServerExperiment;

use Twirphp\Server_experiment\Hat;
use Twirphp\Server_experiment\Size;

final class HaberdasherHandler implements Haberdasher
{
    public function makeHat(array $ctx, Size $size)
    {
        $hat = new Hat();
        $hat->setSize($size->getInches());
        $hat->setColor('golden');
        $hat->setName('crown');

        return $hat;
    }
}
