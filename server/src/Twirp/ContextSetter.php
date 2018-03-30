<?php

namespace Twirp;

final class ContextSetter
{
    public static function withMethodName(array $ctx, $name)
    {
        $ctx[ContextKey::METHOD_NAME] = $name;

        return $ctx;
    }

    public static function withServiceName(array $ctx, $name)
    {
        $ctx[ContextKey::SERVICE_NAME] = $name;

        return $ctx;
    }

    public static function withPackageName(array $ctx, $name)
    {
        $ctx[ContextKey::PACKAGE_NAME] = $name;

        return $ctx;
    }

    public static function withStatusCode(array $ctx, $code)
    {
        $ctx[ContextKey::STATUS_CODE] = $code;

        return $ctx;
    }
}
