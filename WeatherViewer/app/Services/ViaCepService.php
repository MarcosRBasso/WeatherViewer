<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ViaCepService
{
    public function getAddressByCep(string $cep): ?array
    {
        $cep = preg_replace('/\D/', '', $cep);

        if (strlen($cep) !== 8) {
            return null;
        }

        try {
            $response = Http::withoutVerifying()
                ->get("https://viacep.com.br/ws/{$cep}/json/");

            if ($response->failed()) {
                return null;
            }

            $json = $response->json();

            if (isset($json['erro']) && $json['erro'] === true) {
                return null;
            }

            return $json;
        } catch (\Throwable $e) {
            logger()->error('Erro ao consultar ViaCEP', ['exception' => $e->getMessage()]);
            return null;
        }
    }
}
