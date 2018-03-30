<?php

namespace Twirp;

final class TwirpError implements Error
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $msg;

    /**
     * @var array
     */
    private $meta = [];

    public function __construct($code, $msg)
    {
        $this->code = $code;
        $this->msg = $msg;
    }

    /**
     * {@inheritdoc}
     */
    public function code()
    {
        return $this->code;
    }

    /**
     * {@inheritdoc}
     */
    public function msg()
    {
        return $this->msg;
    }

    /**
     * {@inheritdoc}
     */
    public function withMeta($key, $val)
    {
        $this->meta[$key] = $val;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function meta($key)
    {
        if (isset($key, $this->meta)) {
            return $this->meta[$key];
        }

        return '';
    }


    /**
     * {@inheritdoc}
     */
    public function metaMap()
    {
        return $this->meta;
    }

    /**
     * Generic constructor for a {@see Twirp\TwirpError}. The error code must be
     * one of the valid predefined constants, otherwise it will be converted to an
     * error {type: Internal, msg: "invalid error type {{code}}"}. If you need to
     * add metadata, use withMeta(key, value) method after building the error.
     *
     * @param string $code
     * @param string $msg
     *
     * @return self
     */
    public static function newError($code, $msg)
    {
        if (ErrorCode::isValid($code)) {
            return new self($code, $msg);
        }

        return new self(ErrorCode::Internal, "invalid error type ".$code);
    }

    /**
     * Constructor for the common NotFound error.
     *
     * @param string $msg
     *
     * @return self
     */
    public static function notFound($msg)
    {
        return self::newError(ErrorCode::NotFound, $msg);
    }

    /**
     * Constructor for the common InvalidArgument error. Can be
     * used when an argument has invalid format, is a number out of range, is a bad
     * option, etc).
     *
     * @param string $argument
     * @param string $validationMsg
     *
     * @return self
     */
    public static function invalidArgument($argument, $validationMsg)
    {
        $e = self::newError(ErrorCode::InvalidArgument, $argument.' '.$validationMsg);

        $e->withMeta('argument', $argument);

        return $e;
    }

    /**
     * A more specific constructor for InvalidArgument
     * error. Should be used when the argument is required (expected to have a
     * non-zero value).
     *
     * @param string $argument
     *
     * @return self
     */
    public static function requiredArgument($argument)
    {
        return self::invalidArgument($argument, 'is required');
    }

    /**
     * Constructor for the common Internal error. Should be used to
     * specify that something bad or unexpected happened.
     *
     * @param string $msg
     *
     * @return self
     */
    public static function internalError($msg)
    {
        return self::newError(ErrorCode::Internal, $msg);
    }

    /**
     * Wrap another error. It adds the
     * underlying error's type as metadata with a key of "cause", which can be
     * useful for debugging. Should be used in the common case of an unexpected
     * error returned from another API, but sometimes it is better to build a more
     * specific error (like with self::newError(self::Unknown, $e->getMessage()), for example).
     *
     * @param \Exception $e
     *
     * @return self
     */
    public static function internalErrorWith(\Exception $e)
    {
        $e = new self(ErrorCode::Internal, $e->getMessage());

        $e->withMeta('cause', $e->getMessage());

        return $e;
    }
}
