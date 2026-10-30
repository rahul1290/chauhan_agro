<section class="py-5" style="background-color: var(--agro-light-bg);" @if(!empty($section->settings_json['animation_effect'])) data-section-anim="{{ $section->settings_json['animation_effect'] }}" @endif @if(!empty($section->settings_json['animation_duration'])) data-section-duration="{{ $section->settings_json['animation_duration'] }}" @endif>
    <div class="container py-4">
        <div class="text-center mb-5 agro-reveal">
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
                        'quote' => 'We operate 4 Chauhan Agro Heavy-Duty Hydraulic Tractor Trollies across 600 acres of paddy and sugarcane. Outstanding chassis strength and zero maintenance required.',
                        'author' => 'Sukhwinder Singh Gill',
                        'role' => 'Commercial Farm Owner, Punjab',
                        'avatar' => '/assets/images/testimonials/avatar1.jpg',
                        'rating' => 5,
                    ],
                    [
                        'quote' => 'The Chauhan Agro Commercial 4-Wheel Heavy Trailer paid for itself in a single haulage season. Leaf spring suspension delivers extreme stability with full 12-ton payload.',
                        'author' => 'Ramesh Patel',
                        'role' => 'Agri-Contractor, Gujarat',
                        'avatar' => '/assets/images/testimonials/avatar2.jpg',
                        'rating' => 5,
                    ],
                    [
                        'quote' => 'Extremely impressed with the Chauhan Agro heavy-duty sugarcane trolley. Heavy channel chassis and high-tensile steel provide unmatched endurance during harvest.',
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
                @php
                    $cleanQuote = str_ireplace('AgroVision', 'Chauhan Agro', $rev['quote'] ?? '');
                @endphp
                <div class="col-lg-4 col-md-6 agro-reveal stagger-{{ ($loop->index % 3) + 1 }}">
                    <div class="testimonial-card">
                        <div class="testimonial-stars">
                            @for($i = 0; $i < ($rev['rating'] ?? 5); $i++)
                                <i class="bi bi-star-fill"></i>
                            @endfor
                        </div>
                        <p class="testimonial-quote">"{{ $cleanQuote }}"</p>
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
