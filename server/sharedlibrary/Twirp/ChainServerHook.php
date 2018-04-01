<?php

namespace Twirp;

final class ChainServerHook implements ServerHook
{
    /**
     * @var ServerHook[]
     */
    private $hooks = [];

    /**
     * @param ServerHook[] ...$hooks
     */
    public function __construct(ServerHook ...$hooks)
    {
        $this->hooks = $hooks;
    }

    /**
     * {@inheritdoc}
     */
    public function requestReceived(array $ctx)
    {
        foreach ($this->hooks as $hook) {
            $ctx = $hook->requestReceived($ctx);
        }

        return $ctx;
    }

    /**
     * {@inheritdoc}
     */
    public function requestRouted(array $ctx)
    {
        foreach ($this->hooks as $hook) {
            $ctx = $hook->requestRouted($ctx);
        }

        return $ctx;
    }

    /**
     * {@inheritdoc}
     */
    public function responsePrepared(array $ctx)
    {
        foreach ($this->hooks as $hook) {
            $ctx = $hook->responsePrepared($ctx);
        }

        return $ctx;
    }

    /**
     * {@inheritdoc}
     */
    public function responseSent(array $ctx)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function error(array $ctx, Error $error)
    {
        foreach ($this->hooks as $hook) {
            $ctx = $hook->error($ctx, $error);
        }

        return $ctx;
    }
}
