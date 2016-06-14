<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Probe extends Model
{

	protected $fillable = [
		"timestamp",
		"macAddress",
		"signalStrength",
		"ssid",
		"manufacturerName",
		"userId",
	];

	protected $table = 'probes';

	public static function create(array $attributes = [])
	{
		$providedKey = request()->header()['key'][0];
		$attributes['userId'] = User::byApiKey($providedKey)[0]->id;
		return parent::create($attributes);
	}

}
