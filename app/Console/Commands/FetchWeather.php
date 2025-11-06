<?php

namespace App\Console\Commands;

use App\Models\WeatherReport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchWeather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-weather';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apikey = env('OPENWEATHERMAP_API_KEY');
        $airports = \App\Models\Airport::get();

        foreach ($airports as $airport) {
            $url = "https://api.openweathermap.org/data/2.5/weather?lat={$airport->latitude}&lon={$airport->longitude}&appid={$apikey}&units=metric&lang=id";

            $response = file_get_contents($url);
            $data = json_decode($response, true);

            $response = Http::get($url);

            if ($response->successful()) {
                $data = $response->json();

                WeatherReport::updateOrCreate(
                    ['airport_id' => $airport->id],
                    [
                        'temperature' => $data['main']['temp'] ?? null,
                        'humidity' => $data['main']['humidity'] ?? null,
                        'wind_speed' => $data['wind']['speed'] ?? null,
                        'weather' => $data['weather'][0]['description'] ?? null,
                        'icon' => $data['weather'][0]['icon'] ?? null,
                    ]
                );

                $this->info("Weather data fetched successfully for airport ID {$airport->id}");
            } else {
                WeatherReport::updateOrCreate(
                    ['airport_id' => $airport->id],
                    [
                        'temperature' => null,
                        'humidity' => null,
                        'wind_speed' => null,
                        'weather' => null,
                        'icon' => null,
                    ]
                );
                $this->error("Failed to fetch weather data for airport ID {$airport->id}");
            }
        }
    }
}
