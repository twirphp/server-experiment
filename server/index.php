<?php

require __DIR__.'/vendor/autoload.php';

$request = \GuzzleHttp\Psr7\ServerRequest::fromGlobals();

$size = new \Twirphp\Server_experiment\Size();

switch ($request->getHeaderLine('Content-Type')) {

	case 'application/json':
		$size->mergeFromJsonString((string) $request->getBody());

		break;

	case 'application/protobuf':
		$size->mergeFromString((string) $request->getBody());

		break;

	default:
		throw new InvalidArgumentException('invalid content type');
}

$hat = new \Twirphp\Server_experiment\Hat();
$hat->setSize($size->getInches());
$hat->setColor('golden');
$hat->setName('crown');

$data = null;

switch ($request->getHeaderLine('Content-Type')) {

	case 'application/json':
		$data = $hat->serializeToJsonString();

		break;

	case 'application/protobuf':
		$data = $hat->serializeToString();

		break;

	default:
		throw new InvalidArgumentException('invalid content type');
}

$response = new \GuzzleHttp\Psr7\Response(200, [], $data);

if (!headers_sent()) {
	// status
	header(sprintf('HTTP/%s %s %s', $response->getProtocolVersion(), $response->getStatusCode(), $response->getReasonPhrase()), true, $response->getStatusCode());
	// headers
	foreach ($response->getHeaders() as $header => $values) {
		foreach ($values as $value) {
			header($header.': '.$value, false, $response->getStatusCode());
		}
	}
}
echo $response->getBody();
