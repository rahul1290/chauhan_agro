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
                            @if(($item['icon'] ?? '') === 'bi-tractor')
                                <i class="bi bi-tractor">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 640 512" style="vertical-align: -0.125em; display: inline-block;"><path d="M528 256c53 0 96 43 96 96s-43 96-96 96c-48.4 0-88.3-35.8-95-82.3l-59.5 29.8c-1.8 17.5-9.1 33.6-20.7 46.5H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H320c-17.7 0-32-14.3-32-32s14.3-32 32-32h14.2c2.9-7.2 6.9-13.8 11.8-19.6l-50.5-25.2c-15.6 19.3-39.3 31.7-65.6 31.7c-47.5 0-86.2-38-87.9-85.3l-45.7 18.3c-2.3 8.3-9.9 14.3-18.7 14.3H48c-11 0-20-9-20-20s9-20 20-20h23.1l60.2-24.1c1.5-8.5 4.8-16.5 9.7-23.4L81.6 280.9C73.8 281.6 65 288 64 288c-17.7 0-32-14.3-32-32s14.3-32 32-32h41.4l52.1-52.1C164.2 165.2 173 160 182.2 160H288V64c0-17.7 14.3-32 32-32s32 14.3 32 32v96h96c17.7 0 32 14.3 32 32v64h48zm-160 96c0-35.3 28.7-64 64-64s64 28.7 64 64s-28.7 64-64 64s-64-28.7-64-64zm-192 48c26.5 0 48-21.5 48-48s-21.5-48-48-48s-48 21.5-48 48s21.5 48 48 48z"/></svg>
                                </i>
                            @else
                                <i class="bi {{ $item['icon'] ?? 'bi-gear-fill' }}"></i>
                            @endif
                        </div>
                        <div class="stat-number">{{ $item['count'] ?? '' }}</div>
                        <div class="stat-label">{{ $item['label'] ?? '' }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
