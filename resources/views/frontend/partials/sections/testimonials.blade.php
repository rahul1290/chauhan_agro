<section class="py-5" style="background-color: var(--agro-light-bg);">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="section-badge">{{ $currentLocale === 'hi' ? 'किसानों का अनुभव' : 'CUSTOMER TESTIMONIALS' }}</span>
            <h2 class="section-title">{{ $section->title ?? ($currentLocale === 'hi' ? 'किसानों की जुबानी' : 'Voices From The Field') }}</h2>
            @if($section->subtitle)
                <p class="section-subtitle">{{ $section->subtitle }}</p>
            @endif
        </div>

        @php
            $reviews = $section->data;
            if (empty($reviews)) {
                $reviews = [
                    [
                        'quote' => 'We operate 4 AgroVision Gold Rotavators across 600 acres of paddy and wheat. Even in heavy black cotton soil, diesel consumption dropped significantly.',
                        'author' => 'Sukhwinder Singh Gill',
                        'role' => 'Commercial Farm Owner, Punjab',
                        'avatar' => '/assets/images/testimonials/avatar1.jpg',
                        'rating' => 5,
                    ],
                    [
                        'quote' => 'The AgroVision Dual-Slope Laser Leveller paid for itself in a single cropping season. Water savings exceeded 30%, and germination is 100% uniform.',
                        'author' => 'Ramesh Patel',
                        'role' => 'Agri-Contractor, Gujarat',
                        'avatar' => '/assets/images/testimonials/avatar2.jpg',
                        'rating' => 5,
                    ],
                    [
                        'quote' => 'Extremely impressed with the hydraulic reversible MB plough. Flawless inversion and the shear bolt protection saved our PTO in stony terrain.',
                        'author' => 'Devendra Sharma',
                        'role' => 'Farm Manager, Madhya Pradesh',
                        'avatar' => '/assets/images/testimonials/avatar3.jpg',
                        'rating' => 5,
                    ],
                ];
            }
        @endphp

        <div class="row g-4">
            @foreach($reviews as $rev)
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card">
                        <div class="testimonial-stars">
                            @for($i = 0; $i < ($rev['rating'] ?? 5); $i++)
                                <i class="bi bi-star-fill"></i>
                            @endfor
                        </div>
                        <p class="testimonial-quote">"{{ $rev['quote'] ?? '' }}"</p>
                        <div class="testimonial-author">
                            <img src="{{ asset($rev['avatar'] ?? 'assets/images/testimonials/avatar1.jpg') }}" alt="{{ $rev['author'] ?? '' }}" class="testimonial-avatar" loading="lazy">
                            <div>
                                <h5 class="testimonial-name">{{ $rev['author'] ?? '' }}</h5>
                                <p class="testimonial-role">{{ $rev['role'] ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
