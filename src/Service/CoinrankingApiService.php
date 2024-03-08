<?php

namespace App\Service;

class CoinrankingApiService
{
    public function getCoinData(): ?array
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://coinranking1.p.rapidapi.com/coins?referenceCurrencyUuid=yhjMzLPhuIDl&timePeriod=24h&tiers%5B0%5D=1&orderBy=marketCap&orderDirection=desc&limit=220&offset=0",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: coinranking1.p.rapidapi.com",
                "X-RapidAPI-Key: e4572342c9msh6144f178c7823b4p1aa0f9jsn6284d6bdc09b"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return null; // Gestion de l'erreur de requête cURL
        } else {
            return json_decode($response, true)['data']['coins'];
        }
    }

    public function getCoinHistory(array $cryptoUUIDs): ?array
{
    $historyData = [];

    foreach ($cryptoUUIDs as $cryptoName => $uuid) {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://coinranking1.p.rapidapi.com/coin/{$uuid}/history?referenceCurrencyUuid=yhjMzLPhuIDl&timePeriod=30d",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: coinranking1.p.rapidapi.com",
                "X-RapidAPI-Key: e4572342c9msh6144f178c7823b4p1aa0f9jsn6284d6bdc09b"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if (!$err) {
            $decodedResponse = json_decode($response, true);

            if (isset($decodedResponse['data']['history'])) {
                $historyData[$cryptoName] = $decodedResponse['data']['history'];
            }
        }
    }

    return $historyData;
}
}