<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="section-badge">{{ $currentLocale === 'hi' ? 'हमारी विशेषताएं' : 'UNCOMPROMISING EXCELLENCE' }}</span>
            <h2 class="section-title">{{ $section->title ?? ($currentLocale === 'hi' ? 'चौहान एग्रो ही क्यों चुनें?' : 'Why Choose Chauhan Agro') }}</h2>
            @if($section->subtitle)
                <p class="section-subtitle">{{ $section->subtitle }}</p>
            @endif
        </div>

        @php
            $cards = $section->data;
            if (empty($cards)) {
                $cards = [
                    ['icon' => 'bi-cpu', 'title' => 'Robotic Laser Precision', 'description' => 'Computerized robotic cutting and welding ensures structural tolerance of less than 0.1 mm, preventing frame fatigue.'],
                    ['icon' => 'bi-gem', 'title' => 'High-Grade Boron Steel', 'description' => 'Our blades and wearing parts are forged with genuine European boron steel, achieving up to 3x longer operational life.'],
                    ['icon' => 'bi-gear-wide', 'title' => 'In-House Multi-Speed Gearboxes', 'description' => 'Precision induction-hardened gears machined to zero-backlash standards for whisper-quiet and cool-running operation.'],
                    ['icon' => 'bi-headset', 'title' => '24/7 Field & Spare Support', 'description' => 'Extensive dealer inventory network guaranteeing 48-hour delivery of critical genuine spare parts anywhere.'],
                ];
            }
        @endphp

        <div class="row g-4">
            @foreach($cards as $c)
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon-box">
                            <i class="bi {{ $c['icon'] ?? 'bi-star-fill' }}"></i>
                        </div>
                        <h4>{{ $c['title'] ?? '' }}</h4>
                        <p>{{ $c['description'] ?? '' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
