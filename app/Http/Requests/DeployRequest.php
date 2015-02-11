<?php namespace Deploy\Http\Requests;


class DeployRequest extends Request {

    /**
     * Validation rules for Payload request
     *
     * @return array
     */
    public function rules()
    {
        return ['payload' => 'required'];
    }
}
