@php
    $promoPopup = \App\Models\PromotionalPopup::getActivePopup();
@endphp

@if($promoPopup && $promoPopup->image_path)
    <div id="agroPromoModal" class="agro-popup-backdrop" style="display: none;" role="dialog" aria-modal="true" aria-label="{{ $promoPopup->title }}">
        <div class="agro-popup-wrapper">
            <div class="agro-popup-dialog" style="border: 3.5px solid {{ $promoPopup->border_color ?? '#16a34a' }};">
                <button type="button" class="agro-popup-close" id="agroPopupCloseBtn" aria-label="Close exhibition announcement">
                    <i class="bi bi-x-lg"></i>
                </button>

                <div class="agro-popup-content">
                    @if($promoPopup->link_url)
                        <a href="{{ $promoPopup->link_url }}" {{ $promoPopup->target_blank ? 'target="_blank" rel="noopener noreferrer"' : '' }} class="agro-popup-link" id="agroPopupImgLink">
                            <img src="{{ $promoPopup->image_url }}" alt="{{ $promoPopup->title }}" class="agro-popup-image" loading="eager">
                        </a>
                    @else
                        <img src="{{ $promoPopup->image_url }}" alt="{{ $promoPopup->title }}" class="agro-popup-image" loading="eager">
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
    /* =========================================================
       Agro Promotional Exhibition Popup (Fieldking Style)
       ========================================================= */
    .agro-popup-backdrop {
        position: fixed;
        inset: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.72);
        backdrop-filter: blur(3px);
        -webkit-backdrop-filter: blur(3px);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.25rem;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .agro-popup-backdrop.show {
        opacity: 1;
        visibility: visible;
    }

    .agro-popup-wrapper {
        position: relative;
        max-width: 680px;
        width: 100%;
        margin: auto;
    }

    .agro-popup-dialog {
        position: relative;
        background-color: #ffffff;
        border-radius: 14px;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.55), 0 0 20px rgba(0, 0, 0, 0.25);
        overflow: visible;
        transform: scale(0.92);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .agro-popup-backdrop.show .agro-popup-dialog {
        transform: scale(1);
    }

    .agro-popup-content {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        background: #ffffff;
    }

    .agro-popup-link {
        display: block;
        text-decoration: none;
        cursor: pointer;
    }

    .agro-popup-image {
        display: block;
        width: 100%;
        height: auto;
        border-radius: 10px;
        vertical-align: middle;
        object-fit: cover;
    }

    /* Circular Overlapping White Close Button with X */
    .agro-popup-close {
        position: absolute;
        top: -16px;
        right: -16px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #ffffff;
        border: none;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.38);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 100001;
        color: #1f2937;
        font-size: 17px;
        line-height: 1;
        font-weight: 700;
        transition: transform 0.18s ease, background-color 0.18s ease, color 0.18s ease;
        padding: 0;
    }

    .agro-popup-close:hover,
    .agro-popup-close:focus {
        transform: scale(1.15) rotate(90deg);
        background-color: #f3f4f6;
        color: #000000;
        outline: none;
    }

    .agro-popup-close:active {
        transform: scale(0.95);
    }

    @media (max-width: 576px) {
        .agro-popup-backdrop {
            padding: 0.85rem;
        }
        .agro-popup-wrapper {
            max-width: 95vw;
        }
        .agro-popup-close {
            top: -12px;
            right: -10px;
            width: 34px;
            height: 34px;
            font-size: 15px;
        }
    }
    </style>

    <script>
    (function () {
        const STORAGE_KEY_SESSION = 'agro_promo_popup_viewed_session';
        const STORAGE_KEY_DAILY = 'agro_promo_popup_viewed_daily';
        const frequency = '{{ $promoPopup->display_frequency ?? "session" }}';
        const delaySeconds = {{ (int) ($promoPopup->display_delay ?? 1) }};

        function shouldShowPopup() {
            if (frequency === 'always') {
                return true;
            }

            if (frequency === 'session') {
                return !sessionStorage.getItem(STORAGE_KEY_SESSION);
            }

            if (frequency === 'daily') {
                const lastShown = localStorage.getItem(STORAGE_KEY_DAILY);
                if (!lastShown) return true;
                const now = new Date().getTime();
                const oneDayMs = 24 * 60 * 60 * 1000;
                return (now - parseInt(lastShown, 10)) > oneDayMs;
            }

            return !sessionStorage.getItem(STORAGE_KEY_SESSION);
        }

        function recordPopupShown() {
            try {
                sessionStorage.setItem(STORAGE_KEY_SESSION, '1');
                localStorage.setItem(STORAGE_KEY_DAILY, new Date().getTime().toString());
            } catch (e) {
                // Ignore storage quota/permission exceptions
            }
        }

        function showPopup() {
            const modal = document.getElementById('agroPromoModal');
            if (!modal) return;

            modal.style.display = 'flex';
            // Force browser reflow for CSS transition
            void modal.offsetWidth;
            modal.classList.add('show');
            document.body.classList.add('agro-popup-open');
        }

        function closePopup() {
            const modal = document.getElementById('agroPromoModal');
            if (!modal) return;

            modal.classList.remove('show');
            document.body.classList.remove('agro-popup-open');
            recordPopupShown();

            setTimeout(function () {
                modal.style.display = 'none';
            }, 300);
        }

        // Initialize on DOM load
        window.addEventListener('DOMContentLoaded', function () {
            if (!shouldShowPopup()) {
                return;
            }

            setTimeout(showPopup, Math.max(0, delaySeconds * 1000));

            const closeBtn = document.getElementById('agroPopupCloseBtn');
            if (closeBtn) {
                closeBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    closePopup();
                });
            }

            const modal = document.getElementById('agroPromoModal');
            if (modal) {
                modal.addEventListener('click', function (e) {
                    if (e.target === modal || e.target.classList.contains('agro-popup-wrapper')) {
                        closePopup();
                    }
                });
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal && modal.classList.contains('show')) {
                    closePopup();
                }
            });

            const link = document.getElementById('agroPopupImgLink');
            if (link) {
                link.addEventListener('click', function () {
                    recordPopupShown();
                });
            }
        });
    })();
    </script>
@endif
