<section class="py-5" style="background-color: var(--agro-light-bg);">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="section-badge">{{ $currentLocale === 'hi' ? 'सामान्य प्रश्न' : 'FREQUENTLY ASKED QUESTIONS' }}</span>
            <h2 class="section-title">{{ $section->title ?? ($currentLocale === 'hi' ? 'अक्सर पूछे जाने वाले प्रश्न' : 'Frequently Asked Questions') }}</h2>
            @if($section->subtitle)
                <p class="section-subtitle">{{ $section->subtitle }}</p>
            @endif
        </div>

        @php
            $faqs = $section->data;
            if (empty($faqs)) {
                $faqs = [
                    ['q' => 'What warranty is offered on Chauhan Agro tractor trollies & trailers?', 'a' => 'All Chauhan Agro tractor trollies, tipping trailers, and implements carry a 24-month comprehensive structural warranty on heavy channel chassis, hydraulic cylinders, and hub-axle assemblies.'],
                    ['q' => 'Are genuine replacement parts and hydraulic seals readily available?', 'a' => 'Yes, through our 1,800+ authorized dealer network and direct regional hubs, genuine hydraulic seals, leaf springs, pins, and hubs are dispatched within 24 to 48 hours.'],
                    ['q' => 'What tractor HP is required for Chauhan Agro Tractor Trollies?', 'a' => 'Our tractor trollies and tipping trailers operate efficiently with tractors ranging from 35 HP up to 75+ HP depending on the tonnage model (5 Ton, 8 Ton, 10 Ton, or 12 Ton payload).'],
                ];
            }
        @endphp

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion shadow-sm rounded-4 overflow-hidden" id="faqAccordion{{ $section->id }}">
                    @foreach($faqs as $idx => $f)
                        @php
                            $cleanQ = str_ireplace('AgroVision', 'Chauhan Agro', $f['q'] ?? '');
                            $cleanA = str_ireplace('AgroVision', 'Chauhan Agro', $f['a'] ?? '');
                        @endphp
                        <div class="accordion-item border-0 border-bottom">
                            <h2 class="accordion-header" id="heading{{ $section->id }}_{{ $idx }}">
                                <button class="accordion-button {{ $idx === 0 ? '' : 'collapsed' }} fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $section->id }}_{{ $idx }}" aria-expanded="{{ $idx === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $section->id }}_{{ $idx }}">
                                    {{ $cleanQ }}
                                </button>
                            </h2>
                            <div id="collapse{{ $section->id }}_{{ $idx }}" class="accordion-collapse collapse {{ $idx === 0 ? 'show' : '' }}" aria-labelledby="heading{{ $section->id }}_{{ $idx }}" data-bs-parent="#faqAccordion{{ $section->id }}">
                                <div class="accordion-body text-muted" style="line-height: 1.7;">
                                    {{ $cleanA }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
