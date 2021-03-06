<?php namespace Deploy\Payload;

use Deploy\Contracts\FactoryContract;

class PayloadFactory implements FactoryContract {

    /**
     * Make new Payload instance depending on payload.
     *
     * @param string $payload
     * @return \Deploy\Contracts\PayloadContract
     */
    public function make($payload)
    {
        $payload = container($payload);

        switch(true)
        {
            case $payload->has('canon_url'):
                return new BitbucketPayload($payload);
            case $payload->has('ref'):
                return new GithubPayload($payload);
            default:
                throw new \UnexpectedValueException('Can\'t detect payload: '.$payload);
        }
    }

    /**
     * Return new Payload Factory instance.
     *
     * @return static
     */
    public static function create()
    {
        return new static;
    }
}
