<?php

namespace Twirp\ServerExperiment;

use Twitch\Twirp\Example\Haberdasher;
use Twitch\Twirp\Example\Hat;
use Twitch\Twirp\Example\Size;

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
