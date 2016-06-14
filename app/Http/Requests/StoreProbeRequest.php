<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\User;

class StoreProbeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->validateApiKey();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'timestamp'        => 'required',
            'macAddress'       => 'required',
            'signalStrength'   => 'required',
            'ssid'             => 'required',
            'manufacturerName' => 'required',
        ];
    }

    protected function validateApiKey()
    {
        $headers = request()->header();

        if (isset($headers['key'][0])) {
            $providedKey = request()->header()['key'][0];
            return (bool) User::byApiKey($providedKey)->count();
        }

        return false;

    }
}
