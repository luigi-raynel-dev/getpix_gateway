<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: pix.proto

namespace Pix;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>pix.PixKeyResponse</code>
 */
class PixKeyResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string status = 1;</code>
     */
    protected $status = '';
    /**
     * Generated from protobuf field <code>string message = 2;</code>
     */
    protected $message = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $status
     *     @type string $message
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Pix::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string status = 1;</code>
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Generated from protobuf field <code>string status = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setStatus($var)
    {
        GPBUtil::checkString($var, True);
        $this->status = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string message = 2;</code>
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Generated from protobuf field <code>string message = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setMessage($var)
    {
        GPBUtil::checkString($var, True);
        $this->message = $var;

        return $this;
    }

}

