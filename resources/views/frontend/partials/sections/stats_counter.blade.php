<section class="stats-section">
    <div class="container">
        @if($section->title)
            <div class="text-center mb-5">
                <span class="text-warning fw-bold text-uppercase" style="font-size: 0.82rem; letter-spacing: 0.1em;">{{ $section->subtitle ?? 'GLOBAL REACH' }}</span>
                <h2 class="text-white fw-bold mb-2">{{ $section->title }}</h2>
            </div>
        @endif

        <div class="row g-4 justify-content-center">
            @php
                $statsData = $section->data;
                if (empty($statsData)) {
                    $statsData = [
                        ['count' => '35+', 'label' => 'Years of Heritage', 'icon' => 'bi-shield-check'],
                        ['count' => '500k+', 'label' => 'Implements Deployed', 'icon' => 'bi-tractor'],
                        ['count' => '52+', 'label' => 'Countries Exported', 'icon' => 'bi-globe-americas'],
                        ['count' => '1,800+', 'label' => 'Service Points', 'icon' => 'bi-geo-alt'],
                    ];
                }
            @endphp

            @foreach($statsData as $item)
                <div class="col-lg-3 col-md-6 col-6">
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="bi {{ $item['icon'] ?? 'bi-gear-fill' }}"></i>
                        </div>
                        <div class="stat-number">{{ $item['count'] ?? '' }}</div>
                        <div class="stat-label">{{ $item['label'] ?? '' }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
