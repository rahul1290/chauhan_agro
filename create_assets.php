<?php

$dirs = [
    __DIR__ . '/public/assets/images/banners',
    __DIR__ . '/public/assets/images/categories',
    __DIR__ . '/public/assets/images/products',
    __DIR__ . '/public/assets/images/testimonials',
    __DIR__ . '/public/assets/images/branding',
    __DIR__ . '/public/assets/brochures',
];

foreach ($dirs as $d) {
    if (!is_dir($d)) {
        mkdir($d, 0777, true);
    }
}

function generateSvgCard($title, $subtitle, $color1, $color2, $iconText, $badge = 'AGROVISION HEAVY DUTY') {
    return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800" width="100%" height="100%">
  <defs>
    <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:{$color1};stop-opacity:1" />
      <stop offset="100%" style="stop-color:{$color2};stop-opacity:1" />
    </linearGradient>
    <linearGradient id="accent" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" style="stop-color:#FF6B00;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#FFA800;stop-opacity:1" />
    </linearGradient>
    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
      <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/>
    </pattern>
  </defs>

  <!-- Background -->
  <rect width="1200" height="800" fill="url(#grad)" />
  <rect width="1200" height="800" fill="url(#grid)" />

  <!-- Geometric Industrial Elements -->
  <circle cx="950" cy="400" r="280" fill="none" stroke="rgba(255,255,255,0.06)" stroke-width="3" />
  <circle cx="950" cy="400" r="200" fill="none" stroke="rgba(255,107,0,0.15)" stroke-width="2" stroke-dasharray="8 8" />
  <polygon points="1200,0 850,0 1050,800 1200,800" fill="rgba(0,0,0,0.18)" />

  <!-- Tractor Silhouette Graphic representation -->
  <g transform="translate(760, 260) scale(1.1)">
    <!-- Gear/Tiller wheel -->
    <circle cx="160" cy="180" r="70" fill="#111827" stroke="#FF6B00" stroke-width="8"/>
    <circle cx="160" cy="180" r="30" fill="#374151" stroke="#F59E0B" stroke-width="4"/>
    <circle cx="340" cy="190" r="50" fill="#111827" stroke="#FF6B00" stroke-width="6"/>
    <!-- Machine Chassis Frame -->
    <path d="M 120 180 L 150 100 L 260 100 L 310 130 L 370 140 L 370 190 Z" fill="#047857" opacity="0.9"/>
    <!-- Cab Glass -->
    <polygon points="190,105 255,105 255,145 180,145" fill="#38BDF8" opacity="0.6"/>
    <!-- Exhaust pipe -->
    <rect x="270" y="70" width="8" height="35" fill="#D1D5DB"/>
  </g>

  <!-- Badge Pill -->
  <rect x="80" y="160" width="340" height="42" rx="21" fill="url(#accent)" />
  <text x="100" y="187" font-family="'Segoe UI', Roboto, sans-serif" font-weight="800" font-size="14" fill="#FFFFFF" letter-spacing="2">{$badge}</text>

  <!-- Title & Subtitle -->
  <text x="80" y="270" font-family="'Segoe UI', Roboto, sans-serif" font-weight="900" font-size="52" fill="#FFFFFF">{$title}</text>
  <text x="80" y="330" font-family="'Segoe UI', Roboto, sans-serif" font-weight="400" font-size="24" fill="#E2E8F0">{$subtitle}</text>

  <!-- Feature Highlights -->
  <g transform="translate(80, 420)">
    <rect x="0" y="0" width="180" height="75" rx="10" fill="rgba(0,0,0,0.35)" stroke="rgba(255,255,255,0.1)" />
    <text x="20" y="32" font-family="'Segoe UI', sans-serif" font-size="12" fill="#94A3B8">METALLURGY</text>
    <text x="20" y="58" font-family="'Segoe UI', sans-serif" font-weight="700" font-size="18" fill="#F8FAFC">Boron Steel 52 HRC</text>

    <rect x="200" y="0" width="180" height="75" rx="10" fill="rgba(0,0,0,0.35)" stroke="rgba(255,255,255,0.1)" />
    <text x="220" y="32" font-family="'Segoe UI', sans-serif" font-size="12" fill="#94A3B8">STANDARDS</text>
    <text x="220" y="58" font-family="'Segoe UI', sans-serif" font-weight="700" font-size="18" fill="#F8FAFC">ISO 9001:2015</text>

    <rect x="400" y="0" width="180" height="75" rx="10" fill="rgba(0,0,0,0.35)" stroke="rgba(255,255,255,0.1)" />
    <text x="420" y="32" font-family="'Segoe UI', sans-serif" font-size="12" fill="#94A3B8">WARRANTY</text>
    <text x="420" y="58" font-family="'Segoe UI', sans-serif" font-weight="700" font-size="18" fill="#F8FAFC">12-24 Months</text>
  </g>

  <!-- Bottom Accent Stripe -->
  <rect x="0" y="788" width="1200" height="12" fill="url(#accent)" />
</svg>
SVG;
}

// Banner Images
file_put_contents(__DIR__ . '/public/assets/images/banners/hero-rotavator.jpg', generateSvgCard('AgroVision Gold Rotavators', 'Multi-Speed Gearbox & Boron Steel L/C Blades', '#064E3B', '#022C22', 'Rotary Tiller'));
file_put_contents(__DIR__ . '/public/assets/images/banners/hero-rotavator-mobile.jpg', generateSvgCard('AgroVision Gold', 'Heavy Duty Rotavators', '#064E3B', '#022C22', 'Rotavator'));
file_put_contents(__DIR__ . '/public/assets/images/banners/hero-laser-leveller.jpg', generateSvgCard('Dual-Slope Laser Levellers', 'Sub-Centimeter Automatic Hydraulic Control', '#1E3A8A', '#0F172A', 'Laser Leveller'));
file_put_contents(__DIR__ . '/public/assets/images/banners/hero-laser-leveller-mobile.jpg', generateSvgCard('Dual-Slope Laser', 'High Precision Grade', '#1E3A8A', '#0F172A', 'Laser'));
file_put_contents(__DIR__ . '/public/assets/images/banners/hero-reaper.jpg', generateSvgCard('Tractor Straw Reapers', 'Clean Stubble Management & Pure Fodder', '#78350F', '#451A03', 'Straw Reaper'));
file_put_contents(__DIR__ . '/public/assets/images/banners/hero-reaper-mobile.jpg', generateSvgCard('Straw Reaper', 'Residue Management', '#78350F', '#451A03', 'Reaper'));

// Category Images
$categories = [
    'rotary-tiller' => ['Rotary Tillers', 'Multi-Speed Seedbed Pulverizers', '#064E3B', '#0F766E'],
    'disc-harrow' => ['Disc Harrows', 'Hydraulic Mounted Offset Harrows', '#1E293B', '#334155'],
    'cultivator' => ['Cultivators & Tillers', 'Spring-Loaded 9 & 11 Tynes', '#0F172A', '#1E3A8A'],
    'laser-leveller' => ['Laser Land Levellers', 'Dual-Slope Electronic Grade Transmitters', '#1E1B4B', '#312E81'],
    'seed-drill' => ['Seed Drills & Planters', 'Zero-Till Multi-Crop Sowing Drills', '#14532D', '#166534'],
    'straw-reaper' => ['Harvest & Straw Reapers', 'Crop Stubble & Cattle Fodder Reapers', '#713F12', '#854D0E'],
];

foreach ($categories as $filename => $info) {
    file_put_contents(__DIR__ . "/public/assets/images/categories/{$filename}.jpg", generateSvgCard($info[0], $info[1], $info[2], $info[3], 'Category'));
}

// Product Images
$products = [
    'gold-rotary-tiller' => ['AgroVision Gold Rotavator', '45 - 65 HP Tractor Mounted', '#064E3B', '#047857'],
    'hydraulic-disc-harrow' => ['Hydraulic Offset Disc Harrow', '20 Notched High-Carbon Discs', '#1E293B', '#475569'],
    'laser-land-leveller' => ['Precision Laser Land Leveller', '8.2 Ft High-Strength Levelling Bucket', '#1E1B4B', '#3730A3'],
    'seed-cum-fertilizer-drill' => ['Zero Till Multi-Crop Seed Drill', '11 Rows Tungsten Carbide Furrows', '#14532D', '#15803D'],
    'mould-board-plough' => ['Hydraulic Reversible MB Plough', '3-Bottom Heavy Duty Inversion', '#831843', '#9D174D'],
    'straw-reaper' => ['Heavy Duty Tractor Straw Reaper', '28 Balanced Beaters with Tipping Hopper', '#713F12', '#A16207'],
];

foreach ($products as $filename => $info) {
    file_put_contents(__DIR__ . "/public/assets/images/products/{$filename}.jpg", generateSvgCard($info[0], $info[1], $info[2], $info[3], 'Product'));
    file_put_contents(__DIR__ . "/public/assets/images/products/{$filename}-side.jpg", generateSvgCard($info[0] . ' (Detail)', 'Transmission & Structural View', $info[2], $info[3], 'Detail'));
}

// Testimonials Avatars
for ($i = 1; $i <= 3; $i++) {
    $avatarSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" width="100%" height="100%">
  <circle cx="100" cy="100" r="100" fill="#0F766E"/>
  <circle cx="100" cy="75" r="40" fill="#FFFFFF" opacity="0.9"/>
  <path d="M 30 180 C 45 130 155 130 170 180 Z" fill="#FFFFFF" opacity="0.9"/>
</svg>
SVG;
    file_put_contents(__DIR__ . "/public/assets/images/testimonials/avatar{$i}.jpg", $avatarSvg);
}

// Branding Logo SVG
$logoSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 420 90" width="100%" height="100%">
  <defs>
    <linearGradient id="logoOrange" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" style="stop-color:#FF6B00;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#F59E0B;stop-opacity:1" />
    </linearGradient>
    <linearGradient id="logoGreen" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" style="stop-color:#059669;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#10B981;stop-opacity:1" />
    </linearGradient>
  </defs>

  <!-- Emblem Icon: Stylized Gear & Wheat Sprout -->
  <g transform="translate(10, 10)">
    <circle cx="35" cy="35" r="32" fill="#0F172A" />
    <!-- Gear teeth -->
    <path d="M 35 7 L 35 15 M 35 55 L 35 63 M 7 35 L 15 35 M 55 35 L 63 35 M 15 15 L 21 21 M 49 49 L 55 55 M 15 55 L 21 49 M 49 21 L 55 15" stroke="#FF6B00" stroke-width="4" stroke-linecap="round"/>
    <!-- Central Sprout / Blade -->
    <path d="M 35 48 C 25 36 26 22 35 18 C 44 22 45 36 35 48 Z" fill="url(#logoGreen)" />
    <path d="M 35 48 L 35 22" stroke="#FFFFFF" stroke-width="2" />
  </g>

  <!-- Typography -->
  <text x="92" y="44" font-family="'Outfit', 'Segoe UI', sans-serif" font-weight="900" font-size="32" fill="#0F172A" letter-spacing="-0.5">AGRO<tspan fill="#FF6B00">VISION</tspan></text>
  <text x="94" y="64" font-family="'Segoe UI', sans-serif" font-weight="700" font-size="10.5" fill="#64748B" letter-spacing="3.5">HEAVY INDUSTRIES</text>
</svg>
SVG;
file_put_contents(__DIR__ . '/public/assets/images/branding/logo.svg', $logoSvg);

$logoWhiteSvg = str_replace('#0F172A', '#FFFFFF', $logoSvg);
$logoWhiteSvg = str_replace('fill="#0F172A"', 'fill="#FFFFFF"', $logoWhiteSvg);
$logoWhiteSvg = str_replace('fill="#64748B"', 'fill="#94A3B8"', $logoWhiteSvg);
file_put_contents(__DIR__ . '/public/assets/images/branding/logo-white.svg', $logoWhiteSvg);

// Mock Brochures
$samplePdfContent = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n3 0 obj<</Type/Page/MediaBox[0 0 612 792]/Parent 2 0 R/Resources<<>>>>endobj\nxref\n0 4\n0000000000 65535 f \n0000000010 00000 n \n0000000060 00000 n \n0000000118 00000 n \ntrailer<</Size 4/Root 1 0 R>>\nstartxref\n200\n%%EOF";
$brochures = [
    'agrovision-gold-rotary-tiller.pdf',
    'hydraulic-offset-disc-harrow.pdf',
    'precision-laser-land-leveller.pdf',
    'zero-till-seed-drill.pdf',
    'hydraulic-mb-plough.pdf',
    'tractor-straw-reaper.pdf',
];

foreach ($brochures as $bFile) {
    file_put_contents(__DIR__ . "/public/assets/brochures/{$bFile}", $samplePdfContent);
}

echo "All graphic assets, logos, and brochures created successfully!\n";
