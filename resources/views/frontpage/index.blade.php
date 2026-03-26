@extends('layouts.frontpage')

@section('content')
<div class="fids-board container-fluid px-3 py-3 py-md-4">
    <div class="fids-header">
        <img class="fids-header-logo" src="{{ asset('logo/logo_kemenhub.PNG') }}" alt="Logo Kemenhub">
        <h1 class="fids-header-title">Bandar Udara Muara Bungo</h1>
        <img class="fids-header-logo" src="{{ asset('logo/logo_muarabungo.PNG') }}" alt="Logo Muara Bungo">
    </div>
    
    <section class="fids-section mt-4">
        <div class="fids-section-head">
            <div class="fids-section-icon rotate-45">✈</div>
            <h2>Arrivals</h2>
        </div>
        <div class="table-responsive">
            <table class="fids-table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Airline</th>
                        <th>Flight Number</th>
                        <th>Origin</th>
                        <th>Schedule</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < 3; $i++)
                    @php
                    $flight = $arrivals->get($i);
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            @if ($flight)
                            <div class="fids-airline-wrap">
                                @if (!empty($flight->user?->logo))
                                <img class="fids-airline-logo"
                                src="{{ asset('uploads/' . $flight->user->logo) }}"
                                alt="{{ $flight->user?->name ?? 'Airline' }}">
                                @else
                                <span>{{ $flight->user?->name ?? '-' }}</span>
                                @endif
                            </div>
                            @else
                            -
                            @endif
                        </td>
                        <td>{{ $flight?->flight_number ?? '-' }}</td>
                        <td>{{ $flight?->origin?->name ?? '-' }}</td>
                        <td>
                            {{ $flight?->scheduled_time ? \Illuminate\Support\Carbon::parse($flight->scheduled_time)->format('H:i') : '-' }}
                        </td>
                        <td>
                            @if ($flight)
                            {{ $flight->status }}
                            {{ $flight->delayed_until ? ' Until ' . \Illuminate\Support\Carbon::parse($flight->delayed_until)->format('H:i') : '' }}
                            @else
                            -
                            @endif
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </section>
    
    <section class="fids-section mt-4">
        <div class="fids-section-head">
            <div class="fids-section-icon rotate--45">✈</div>
            <h2>Departures</h2>
        </div>
        <div class="table-responsive">
            <table class="fids-table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Airline</th>
                        <th>Flight Number</th>
                        <th>Destination</th>
                        <th>Schedule</th>
                        <th>Gate</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < 3; $i++)
                    @php
                    $flight = $departures->get($i);
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            @if ($flight)
                            <div class="fids-airline-wrap">
                                @if (!empty($flight->user?->logo))
                                <img class="fids-airline-logo"
                                src="{{ asset('uploads/' . $flight->user->logo) }}"
                                alt="{{ $flight->user?->name ?? 'Airline' }}">
                                @else
                                <span>{{ $flight->user?->name ?? '-' }}</span>
                                @endif
                            </div>
                            @else
                            -
                            @endif
                        </td>
                        <td>{{ $flight?->flight_number ?? '-' }}</td>
                        <td>{{ $flight?->destination?->name ?? '-' }}</td>
                        <td>
                            {{ $flight?->scheduled_time ? \Illuminate\Support\Carbon::parse($flight->scheduled_time)->format('H:i') : '-' }}
                        </td>
                        <td>
                            {{ $flight?->gate }}
                        </td>
                        <td>
                            @if ($flight)
                            {{ $flight->status }}
                            {{ $flight->delayed_until ? ' Until ' . \Illuminate\Support\Carbon::parse($flight->delayed_until)->format('H:i') : '' }}
                            @else
                            -
                            @endif
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </section>
    
    <div class="fids-footer mt-4">
        <div class="fids-running-text">
            <div id="running-text-track" class="fids-running-track" aria-label="Running text information board">
                <span id="running-text-content" class="fids-running-content"></span>
            </div>
        </div>
        <div class="fids-clock-box">
            <div id="current-time" class="fids-time"></div>
            <div id="current-date" class="fids-date"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let lastArrivalChecksum = null;
        let lastDepartureChecksum = null;
        let lastRunningTextChecksum = null;
        let refreshRunningText = null;
        
        const fallbackMessages = [
            'Selamat datang di Bandar Udara Muara Bungo.',
            'Mohon perhatikan informasi penerbangan pada layar ini.',
            'Datang lebih awal dan siapkan dokumen perjalanan Anda.'
        ];
        
        function initRunningText() {
            const trackEl = document.getElementById('running-text-track');
            const contentEl = document.getElementById('running-text-content');
            
            if (!trackEl || !contentEl) return;
            
            let messages = @json(
                ($runningTexts ?? collect())->filter()->values()->all()
            );

            if (messages.length === 0) {
                messages = [...fallbackMessages];
            }
            
            let activeIndex = 0;
            let contentWidth = 0;
            let currentX = 0;
            let lastTime = null;
            const speed = 95; // pixel per second

            function applyMessages(newMessages) {
                const nextMessages = Array.isArray(newMessages) && newMessages.length > 0
                    ? newMessages
                    : fallbackMessages;

                messages = [...nextMessages];
                activeIndex = 0;
                lastTime = null;
                setMessage(activeIndex);
            }
            
            function setMessage(index) {
                contentEl.textContent = messages[index];
                currentX = trackEl.clientWidth;
                contentEl.style.transform = `translate3d(${currentX}px, -50%, 0)`;
                contentWidth = contentEl.offsetWidth;
            }
            
            function animate(timestamp) {
                if (lastTime === null) {
                    lastTime = timestamp;
                }
                
                const delta = (timestamp - lastTime) / 1000;
                lastTime = timestamp;
                currentX -= speed * delta;
                
                if (currentX < -contentWidth) {
                    activeIndex = (activeIndex + 1) % messages.length;
                    setMessage(activeIndex);
                }
                
                contentEl.style.transform = `translate3d(${currentX}px, -50%, 0)`;
                window.requestAnimationFrame(animate);
            }
            
            setMessage(activeIndex);
            window.requestAnimationFrame(animate);
            
            window.addEventListener('resize', () => {
                setMessage(activeIndex);
            });

            refreshRunningText = applyMessages;
        }

        function fetchRunningTexts() {
            return fetch('/api/running-texts')
                .then(response => response.json())
                .then(data => {
                    if (refreshRunningText) {
                        refreshRunningText(data.messages || []);
                    }

                    if (data.checksum) {
                        lastRunningTextChecksum = data.checksum;
                    }
                })
                .catch(error => console.error('Error loading running texts:', error));
        }
        
        function updateDateTime() {
            const now = new Date();
            
            const dateFormatter = new Intl.DateTimeFormat('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
            
            const dateEl = document.getElementById('current-date');
            const timeEl = document.getElementById('current-time');
            
            if (dateEl) dateEl.textContent = dateFormatter.format(now);
            
            if (timeEl) {
                timeEl.textContent = now.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true,
                });
            }
        }
        
        function checkForUpdates() {
            Promise.all([
            fetch('/api/flights-updates?type=arrival').then(response => response.json()),
            fetch('/api/flights-updates?type=departure').then(response => response.json()),
            ])
            .then(([arrivalData, departureData]) => {
                if (lastArrivalChecksum === null || lastDepartureChecksum === null) {
                    lastArrivalChecksum = arrivalData.checksum;
                    lastDepartureChecksum = departureData.checksum;
                    return;
                }
                
                if (arrivalData.checksum !== lastArrivalChecksum || departureData.checksum !== lastDepartureChecksum) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error checking updates:', error));
        }

        function checkRunningTextUpdates() {
            fetch('/api/running-text-updates')
                .then(response => response.json())
                .then(data => {
                    if (lastRunningTextChecksum === null) {
                        lastRunningTextChecksum = data.checksum;
                        return;
                    }

                    if (data.checksum !== lastRunningTextChecksum) {
                        fetchRunningTexts();
                    }
                })
                .catch(error => console.error('Error checking running text updates:', error));
        }
        
        initRunningText();
        fetchRunningTexts();
        updateDateTime();
        checkForUpdates();
        checkRunningTextUpdates();
        setInterval(updateDateTime, 1000);
        setInterval(checkForUpdates, 5000);
        setInterval(checkRunningTextUpdates, 5000);
    });
</script>
@endsection
