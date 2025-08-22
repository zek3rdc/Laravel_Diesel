<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class CurrencyRateController extends Controller
{
    /**
     * Obtiene las tasas de cambio desde el BCV, utilizando caché.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRates()
    {
        try {
            $rates = Cache::remember('bcv_rates', now()->addHours(1), function () {
                return $this->scrapeBcvRates();
            });

            if (empty($rates)) {
                return response()->json(['error' => 'No se pudieron obtener las tasas de cambio.'], 500);
            }

            return response()->json([
                'rates' => $rates,
                'last_updated' => now()->toDateTimeString(),
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener tasas del BCV: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error interno al procesar las tasas de cambio.'], 500);
        }
    }

    /**
     * Realiza el scraping de la página del BCV para obtener las tasas.
     *
     * @return array|null
     */
    private function scrapeBcvRates()
    {
        try {
            $response = Http::withoutVerifying()->get('https://www.bcv.org.ve/');

            if (!$response->successful()) {
                Log::error('Error al conectar con el BCV: ' . $response->status());
                return null;
            }

            $crawler = new Crawler($response->body());

            $usdRate = $this->extractRate($crawler, 'div#dolar div.centrado strong');
            $eurRate = $this->extractRate($crawler, 'div#euro div.centrado strong');

            if (!$usdRate || !$eurRate) {
                Log::warning('No se encontraron los selectores de tasas en la página del BCV.');
                return null;
            }

            return [
                'USD' => $usdRate,
                'EUR' => $eurRate,
            ];

        } catch (\Exception $e) {
            Log::error('Error durante el scraping del BCV: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Extrae y formatea una tasa de un nodo del crawler.
     *
     * @param \Symfony\Component\DomCrawler\Crawler $crawler
     * @param string $selector
     * @return float|null
     */
    private function extractRate(Crawler $crawler, $selector)
    {
        $node = $crawler->filter($selector);
        if ($node->count() > 0) {
            $rateStr = $node->text();
            // Convertir el formato "123.456,78" a "123456.78"
            $rate = str_replace('.', '', $rateStr);
            $rate = str_replace(',', '.', $rate);
            return (float) $rate;
        }
        return null;
    }
}
