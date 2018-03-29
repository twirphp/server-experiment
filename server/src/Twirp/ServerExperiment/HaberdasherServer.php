<?php

namespace Twirp\ServerExperiment;

use Psr\Http\Message\ServerRequestInterface;

final class HaberdasherServer
{
    /**
     * Handle the request and return a response.
     */
    public function handle(ServerRequestInterface $request)
    {
        $contentTypeHeader = $request->getHeaderLine('Content-Type');
        $i = strpos($contentTypeHeader, ';');

        if ($i === false) {
            $i = strlen($contentTypeHeader);
        }

        switch (trim(strtolower(substr($contentTypeHeader, 0, $i)))) {
            case 'application/json':
                return $this->handleJson($request);

            case 'application/protobuf':
                return $this->handleProtobuf($request);

            default:
                throw new InvalidArgumentException('invalid content type');
        }
    }

    private function handleJson(ServerRequestInterface $request)
    {
        $size = new \Twirphp\Server_experiment\Size();

        $size->mergeFromJsonString((string) $request->getBody());

        $hat = new \Twirphp\Server_experiment\Hat();
        $hat->setSize($size->getInches());
        $hat->setColor('golden');
        $hat->setName('crown');

        $data = $hat->serializeToJsonString();

        return new \GuzzleHttp\Psr7\Response(200, [], $data);
    }

    private function handleProtobuf(ServerRequestInterface $request)
    {
        $size = new \Twirphp\Server_experiment\Size();

        $size->mergeFromString((string) $request->getBody());

        $hat = new \Twirphp\Server_experiment\Hat();
        $hat->setSize($size->getInches());
        $hat->setColor('golden');
        $hat->setName('crown');

        $data = $hat->serializeToString();

        return new \GuzzleHttp\Psr7\Response(200, [], $data);
    }
}
