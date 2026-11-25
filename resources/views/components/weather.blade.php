<div class="card shadow-sm mb-4 border-0">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h5 class="mb-1">
                    <i class="bi bi-cloud-sun me-2"></i>
                    Campus Weather
                </h5>
                <small class="text-muted">
                    SPAMAST Main Campus — Malita, Davao Occidental
                </small>
            </div>

            <span class="badge bg-primary">Current Weather</span>
        </div>

        @if (!empty($weather))
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <div class="p-3 bg-light rounded">
                        <small class="text-muted">Temperature</small>
                        <h3 class="mb-0">
                            {{ $weather['temperature_2m'] ?? '--' }}°C
                        </h3>
                        <small>{{ $weather['condition'] ?? 'Unavailable' }}</small>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="p-3 bg-light rounded">
                        <small class="text-muted">Feels Like</small>
                        <h3 class="mb-0">
                            {{ $weather['apparent_temperature'] ?? '--' }}°C
                        </h3>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="p-3 bg-light rounded">
                        <small class="text-muted">Humidity</small>
                        <h3 class="mb-0">
                            {{ $weather['relative_humidity_2m'] ?? '--' }}%
                        </h3>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="p-3 bg-light rounded">
                        <small class="text-muted">Wind Speed</small>
                        <h3 class="mb-0">
                            {{ $weather['wind_speed_10m'] ?? '--' }} km/h
                        </h3>
                    </div>
                </div>
            </div>

            <div class="mt-3 d-flex justify-content-between flex-wrap gap-2">
                <small class="text-muted">
                    Weather data provided by
                    <a href="https://open-meteo.com/" target="_blank" rel="noopener noreferrer">
                        Open-Meteo
                    </a>
                </small>

                <small class="text-muted">
                    Updated: {{ $weather['time'] ?? 'Time unavailable' }}
                </small>
            </div>
        @else
            <div class="alert alert-warning mb-0">
                <i class="bi bi-cloud-slash me-2"></i>
                Weather information is temporarily unavailable.
                Please try again later.
            </div>
        @endif
    </div>
</div>
