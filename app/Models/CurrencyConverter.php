<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class CurrencyConverter extends Model
{
    use HasFactory;

    protected $client;
    protected $accessKey;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => Config::get('fixer.base_uri'),
        ]);
        $this->accessKey = Config::get('fixer.access_key');
    }

    public function convert($amount, $fromCurrency, $toCurrency)
    {
        $response = $this->client->request('GET', 'convert', [
            'query' => [
                'access_key' => $this->accessKey,
                'from' => $fromCurrency,
                'to' => $toCurrency,
                'amount' => $amount,
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        // Process the response and extract the converted amount
        $convertedAmount = $data['result'];

        return $convertedAmount;
    }
    function convertCurrency($amount, $from, $to)
    {
        $url = "http://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
        $data = file_get_contents($url);
        preg_match("/<span class=bld>(.*)<\/span>/",$data, $converted);
        return $converted[1];
    }
}
