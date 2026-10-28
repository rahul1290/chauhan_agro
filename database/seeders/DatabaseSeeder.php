<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\BannerTranslation;
use App\Models\Enquiry;
use App\Models\EnquiryNote;
use App\Models\Language;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\MenuItemTranslation;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\PageTranslation;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductCategoryTranslation;
use App\Models\ProductImage;
use App\Models\ProductSpecification;
use App\Models\ProductSpecificationTranslation;
use App\Models\ProductTranslation;
use App\Models\Role;
use App\Models\SectionTranslation;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        // 1. Roles
        $roles = [
            [
                'name' => 'Super Administrator',
                'slug' => 'super-admin',
                'description' => 'Full control over all administrative modules, settings, users, and CMS.',
                'permissions' => ['*'],
            ],
            [
                'name' => 'Content Manager',
                'slug' => 'content-manager',
                'description' => 'Manage CMS pages, dynamic sections, hero banners, menus, media, and translations.',
                'permissions' => ['pages', 'sections', 'banners', 'menus', 'media', 'translations'],
            ],
            [
                'name' => 'Product Manager',
                'slug' => 'product-manager',
                'description' => 'Manage product categories, machinery catalogue, technical specs, and brochures.',
                'permissions' => ['products', 'categories', 'specifications', 'media'],
            ],
            [
                'name' => 'Enquiry Manager',
                'slug' => 'enquiry-manager',
                'description' => 'View customer enquiries, update follow-up statuses, and add internal notes.',
                'permissions' => ['enquiries', 'notes', 'export'],
            ],
        ];

        foreach ($roles as $r) {
            Role::updateOrCreate(['slug' => $r['slug']], $r);
        }

        $superAdminRole = Role::where('slug', 'super-admin')->first();
        $contentRole = Role::where('slug', 'content-manager')->first();
        $productRole = Role::where('slug', 'product-manager')->first();
        $enquiryRole = Role::where('slug', 'enquiry-manager')->first();

        // 2. Users
        $users = [
            [
                'name' => 'Chief Executive / Super Admin',
                'email' => 'admin@agrovision.com',
                'password' => Hash::make('password123'),
                'role_id' => $superAdminRole->id,
                'phone' => '+91 98765 00001',
                'is_active' => true,
            ],
            [
                'name' => 'Editorial Content Manager',
                'email' => 'content@agrovision.com',
                'password' => Hash::make('password123'),
                'role_id' => $contentRole->id,
                'phone' => '+91 98765 00002',
                'is_active' => true,
            ],
            [
                'name' => 'Technical Product Manager',
                'email' => 'product@agrovision.com',
                'password' => Hash::make('password123'),
                'role_id' => $productRole->id,
                'phone' => '+91 98765 00003',
                'is_active' => true,
            ],
            [
                'name' => 'Customer Relations Officer',
                'email' => 'enquiry@agrovision.com',
                'password' => Hash::make('password123'),
                'role_id' => $enquiryRole->id,
                'phone' => '+91 98765 00004',
                'is_active' => true,
            ],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(['email' => $u['email']], $u);
        }

        // 3. Languages
        $languages = [
            [
                'code' => 'en',
                'name' => 'English',
                'native_name' => 'English',
                'flag' => '🇬🇧',
                'is_default' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'code' => 'hi',
                'name' => 'Hindi',
                'native_name' => 'हिन्दी',
                'flag' => '🇮🇳',
                'is_default' => false,
                'is_active' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($languages as $l) {
            Language::updateOrCreate(['code' => $l['code']], $l);
        }

        // 4. Site Settings
        $settings = [
            ['key' => 'site_name', 'value' => 'Chauhan Agro Industries', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Pioneering Heavy Duty Agricultural Machinery & Implements Worldwide', 'group' => 'general'],
            ['key' => 'contact_email', 'value' => 'contact@chauhanagroindustries.com', 'group' => 'contact'],
            ['key' => 'sales_email', 'value' => 'sales@chauhanagroindustries.com', 'group' => 'contact'],
            ['key' => 'toll_free_number', 'value' => '1800-212-9000', 'group' => 'contact'],
            ['key' => 'primary_phone', 'value' => '+91 98765 43210', 'group' => 'contact'],
            ['key' => 'whatsapp_number', 'value' => '+91 98765 43210', 'group' => 'contact'],
            ['key' => 'headquarters_address', 'value' => "Chauhan Agro Industries Complex, NH-8 Highway Corridor, Phase II, Precision Engineering Zone, Gujarat, India - 382010", 'group' => 'contact'],
            ['key' => 'working_hours', 'value' => 'Monday - Saturday: 9:00 AM - 6:30 PM IST', 'group' => 'contact'],
            ['key' => 'google_map_embed', 'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d117506.97341393664!2d72.49397669466551!3d23.020497771783387!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e848aba5bd449%3A0x4fcedd11614f6516!2sAhmedabad%2C%20Gujarat!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin', 'group' => 'contact'],
            ['key' => 'social_facebook', 'value' => 'https://www.facebook.com/share/1GpUiRYvWb/', 'group' => 'social'],
            ['key' => 'social_youtube', 'value' => 'https://youtube.com/@chauhangroupindia?si=UnQCzy4DrxsMjZdc', 'group' => 'social'],
            ['key' => 'social_instagram', 'value' => 'https://www.instagram.com/chauhangroup_india?utm_source=qr&exln=dnZxdXdkYjc1Z2Vs', 'group' => 'social'],
            ['key' => 'meta_title_default', 'value' => 'Chauhan Agro Industries | Heavy-Duty Agricultural Machinery, Rotary Tillers & Implements', 'group' => 'seo'],
            ['key' => 'meta_description_default', 'value' => 'Discover industry-leading agricultural machinery from Chauhan Agro Industries (chauhanagroindustries.com): Multi-Speed Rotary Tillers, Heavy-Duty Disc Harrows, Laser Land Levellers, Seed Drills, and MB Ploughs engineered for maximum farm productivity.', 'group' => 'seo'],
            ['key' => 'footer_about_text', 'value' => 'Chauhan Agro Industries is a premier global manufacturer of advanced farm machinery, agricultural implements, and tillage equipment engineered with robotic precision and high-tensile boron steel.', 'group' => 'general'],
        ];

        foreach ($settings as $s) {
            Setting::updateOrCreate(['key' => $s['key']], $s);
        }

        // 5. Product Categories
        $categoriesData = [
            [
                'slug' => 'rotary-tillers',
                'image' => '/assets/images/categories/rotary-tiller.jpg',
                'icon' => 'bi-gear-wide-connected',
                'order' => 1,
                'is_featured' => true,
                'en' => [
                    'name' => 'Rotary Tillers (Rotavators)',
                    'slug' => 'rotary-tillers',
                    'description' => 'Heavy-duty multi-speed and regular rotary tillers engineered for superior seedbed preparation across all soil conditions with high-grade boron steel blades.',
                    'meta_title' => 'Rotary Tillers & Rotavators | AgroVision Farm Machinery',
                    'meta_description' => 'Explore heavy-duty agricultural rotary tillers and rotavators engineered with multi-speed gearbox and boron steel blades.',
                ],
                'hi' => [
                    'name' => 'रोटरी टिलर (रोटावेटर)',
                    'slug' => 'rotary-tillers-hi',
                    'description' => 'सभी प्रकार की मिट्टी में बेहतरीन जुताई और बीज की क्यारी तैयार करने के लिए बोरॉन स्टील ब्लेड से निर्मित मल्टी-स्पीड रोटावेटर।',
                    'meta_title' => 'रोटरी टिलर और रोटावेटर | एग्रोविज़न कृषि मशीनरी',
                    'meta_description' => 'उच्च गुणवत्ता वाले बोरॉन स्टील ब्लेड और मल्टी-स्पीड गियरबॉक्स से सुसज्जित रोटावेटर।',
                ],
            ],
            [
                'slug' => 'disc-harrows',
                'image' => '/assets/images/categories/disc-harrow.jpg',
                'icon' => 'bi-circle-half',
                'order' => 2,
                'is_featured' => true,
                'en' => [
                    'name' => 'Disc Harrows & Ploughs',
                    'slug' => 'disc-harrows',
                    'description' => 'Hydraulic mounted and trailed offset disc harrows designed to pulverize hard soil, eradicate roots, and optimize soil moisture retention.',
                    'meta_title' => 'Disc Harrows & Hydraulic Ploughs | AgroVision',
                    'meta_description' => 'Heavy-duty mounted offset disc harrows and hydraulic reversible mould board ploughs.',
                ],
                'hi' => [
                    'name' => 'डिस्क हैरो और हल',
                    'slug' => 'disc-harrows-hi',
                    'description' => 'कठोर मिट्टी को भुरभुरा करने और खरपतवार की जड़ों को नष्ट करने के लिए हेवी ड्यूटी हाइड्रोलिक डिस्क हैरो और मोल्ड बोर्ड हल।',
                    'meta_title' => 'डिस्क हैरो और हाइड्रोलिक हल | एग्रोविज़न',
                    'meta_description' => 'मजबूत हाइड्रोलिक डिस्क हैरो और रिवर्सिबल एमबी हल।',
                ],
            ],
            [
                'slug' => 'cultivators-tillers',
                'image' => '/assets/images/categories/cultivator.jpg',
                'icon' => 'bi-grid-3x3',
                'order' => 3,
                'is_featured' => true,
                'en' => [
                    'name' => 'Cultivators & Tillers',
                    'slug' => 'cultivators-tillers',
                    'description' => 'Spring-loaded and rigid tine cultivators engineered for secondary tillage, aerating root zones, and heavy stubble eradication.',
                    'meta_title' => 'Agricultural Cultivators & Tiller Implements | AgroVision',
                    'meta_description' => 'High-strength spring-loaded 9 & 11 tyne cultivators built for extreme durability.',
                ],
                'hi' => [
                    'name' => 'कल्टीवेटर और टिलर',
                    'slug' => 'cultivators-tillers-hi',
                    'description' => 'मिट्टी में हवा का प्रवाह बढ़ाने और कठोर जुताई के लिए स्प्रिंग-लोडेड 9 और 11 टाइन कल्टीवेटर।',
                    'meta_title' => 'कृषि कल्टीवेटर और टिलर उपकरण | एग्रोविज़न',
                    'meta_description' => 'उच्च शक्ति वाले स्प्रिंग लोडेड कल्टीवेटर।',
                ],
            ],
            [
                'slug' => 'laser-land-levellers',
                'image' => '/assets/images/categories/laser-leveller.jpg',
                'icon' => 'bi-broadcast',
                'order' => 4,
                'is_featured' => true,
                'en' => [
                    'name' => 'Laser Land Levellers',
                    'slug' => 'laser-land-levellers',
                    'description' => 'High-precision dual-slope laser levellers equipped with hydraulic masts and electronic control systems, saving up to 35% irrigation water.',
                    'meta_title' => 'Precision Laser Land Levellers | AgroVision',
                    'meta_description' => 'Precision dual-slope agricultural laser land levellers saving irrigation water and boosting crop yield.',
                ],
                'hi' => [
                    'name' => 'लेजर लैंड लेवलर',
                    'slug' => 'laser-land-levellers-hi',
                    'description' => 'हाई-प्रिसिजन लेजर लैंड लेवलर, जो 35% तक सिंचाई के पानी की बचत करता है और खेत को एक समान समतल बनाता है।',
                    'meta_title' => 'सटीक लेजर लैंड लेवलर | एग्रोविज़न',
                    'meta_description' => 'उच्च परिशुद्धता लेजर लैंड लेवलर सिस्टम।',
                ],
            ],
            [
                'slug' => 'seeding-planting',
                'image' => '/assets/images/categories/seed-drill.jpg',
                'icon' => 'bi-flower1',
                'order' => 5,
                'is_featured' => true,
                'en' => [
                    'name' => 'Seeding & Planting Equipment',
                    'slug' => 'seeding-planting',
                    'description' => 'Zero-till multi-crop seed cum fertilizer drills and pneumatic planters designed for accurate seed spacing and enhanced germination.',
                    'meta_title' => 'Zero-Till Seed Cum Fertilizer Drills | AgroVision',
                    'meta_description' => 'Multi-crop zero-till seed drills and precision planters for wheat, paddy, corn, and pulses.',
                ],
                'hi' => [
                    'name' => 'बुवाई और रोपाई उपकरण',
                    'slug' => 'seeding-planting-hi',
                    'description' => 'सटीक बीज दूरी और अधिकतम अंकुरण के लिए जीरो-टिल मल्टी-क्रॉप सीड कम फर्टिलाइजर ड्रिल मशीन।',
                    'meta_title' => 'जीरो-टिल सीड कम फर्टिलाइजर ड्रिल | एग्रोविज़न',
                    'meta_description' => 'गेहूं, धान और दलहन के लिए सटीक बुवाई उपकरण।',
                ],
            ],
            [
                'slug' => 'harvest-straw-management',
                'image' => '/assets/images/categories/straw-reaper.jpg',
                'icon' => 'bi-scissors',
                'order' => 6,
                'is_featured' => true,
                'en' => [
                    'name' => 'Harvesting & Straw Management',
                    'slug' => 'harvest-straw-management',
                    'description' => 'Straw reapers, combine harvesters, and balers that recycle crop residue into valuable cattle fodder while preventing field burning.',
                    'meta_title' => 'Straw Reapers & Post-Harvest Machinery | AgroVision',
                    'meta_description' => 'Tractor-driven heavy duty straw reapers and residue management machinery.',
                ],
                'hi' => [
                    'name' => 'कटाई एवं पराली प्रबंधन',
                    'slug' => 'harvest-straw-management-hi',
                    'description' => 'पराली को पौष्टिक पशु चारे में बदलने और खेत की सफाई के लिए शक्तिशाली स्ट्रॉ रीपर और हार्वेस्टर।',
                    'meta_title' => 'स्ट्रॉ रीपर और पराली प्रबंधन मशीनरी | एग्रोविज़न',
                    'meta_description' => 'ट्रैक्टर चालित स्ट्रॉ रीपर और अवशेष प्रबंधन मशीन।',
                ],
            ],
        ];

        $createdCategories = [];
        foreach ($categoriesData as $cData) {
            $cat = ProductCategory::updateOrCreate(
                ['slug' => $cData['slug']],
                [
                    'image' => $cData['image'],
                    'icon' => $cData['icon'],
                    'order' => $cData['order'],
                    'is_active' => true,
                    'is_featured' => $cData['is_featured'],
                ]
            );

            // EN Translation
            ProductCategoryTranslation::updateOrCreate(
                ['category_id' => $cat->id, 'locale' => 'en'],
                $cData['en']
            );

            // HI Translation
            ProductCategoryTranslation::updateOrCreate(
                ['category_id' => $cat->id, 'locale' => 'hi'],
                $cData['hi']
            );

            $createdCategories[$cData['slug']] = $cat;
        }

        // 6. Products Data
        $productsData = [
            [
                'category_slug' => 'rotary-tillers',
                'sku' => 'AGV-ROT-GLD7',
                'slug' => 'agrovision-gold-rotary-tiller-rotavator',
                'featured_image' => '/assets/images/products/gold-rotary-tiller.jpg',
                'brochure_pdf' => '/assets/brochures/agrovision-gold-rotary-tiller.pdf',
                'tractor_hp_min' => 45,
                'tractor_hp_max' => 65,
                'working_width' => '2.0 Meters (7 Feet)',
                'weight_approx' => '485 kg',
                'blades_count' => 48,
                'is_featured' => true,
                'order' => 1,
                'en' => [
                    'name' => 'AgroVision Gold Multi-Speed Rotary Tiller (Rotavator)',
                    'slug' => 'agrovision-gold-rotary-tiller-rotavator',
                    'short_description' => 'Engineered with a heavy-duty multi-speed gearbox and specially forged boron steel L/C blades, the AgroVision Gold series provides unmatched pulverization in both wet and dry soil conditions.',
                    'description' => '<p>The <strong>AgroVision Gold Series Rotary Tiller</strong> is the pinnacle of agricultural engineering for modern soil preparation. Designed for medium to high-horsepower tractors (45 - 65 HP), it combines heavy-duty chassis construction, an oil-bath side gear drive, and ergonomic depth control skids.</p><p>Its 4-speed multi-gearbox enables operators to adjust rotor speed dynamically to achieve the ideal seedbed texture regardless of clay, sandy, or stony terrain. With precision laser-balanced rotor shafts, vibration is reduced by 40%, safeguarding your tractor PTO shaft and drastically cutting fuel consumption.</p>',
                    'features' => [
                        '4-Speed Multi-Gearbox for customizable rotor speeds and optimal fuel efficiency.',
                        'Boron Steel L-shaped & C-shaped heat-treated blades for 3x longer operational life.',
                        'Heavy-duty side gear drive in continuous oil bath for zero maintenance downtime.',
                        'Heavy spring-loaded trailing board with safety guard ensuring a clump-free leveled finish.',
                        'Mechanical shear bolt / slip clutch PTO drive protection.',
                        'Adjustable depth skids with wear-resistant steel shoes.',
                    ],
                    'meta_title' => 'AgroVision Gold Multi-Speed Rotavator | 45-65 HP Tractor Tiller',
                    'meta_description' => 'Buy the AgroVision Gold Multi-Speed Rotary Tiller. Features boron steel blades, 4-speed gearbox, and heavy-duty side gear drive.',
                ],
                'hi' => [
                    'name' => 'एग्रोविज़न गोल्ड मल्टी-स्पीड रोटरी टिलर (रोटावेटर)',
                    'slug' => 'agrovision-gold-rotary-tiller-rotavator-hi',
                    'short_description' => 'मजबूत मल्टी-स्पीड गियरबॉक्स और विशेष रूप से तैयार किए गए बोरॉन स्टील ब्लेड्स से युक्त, एग्रोविज़न गोल्ड रोटावेटर गीली और सूखी दोनों मिट्टी में शानदार जुताई प्रदान करता है।',
                    'description' => '<p><strong>एग्रोविज़न गोल्ड सीरीज रोटावेटर</strong> आधुनिक कृषि के लिए इंजीनियरिंग का उत्कृष्ट उदाहरण है। 45 से 65 एचपी ट्रैक्टरों के लिए विशेष रूप से डिज़ाइन किया गया यह रोटावेटर मजबूत चेसिस, ऑयल-बाथ साइड गियर ड्राइव और सटीक गहराई समायोजन के साथ आता है।</p><p>इसका 4-स्पीड मल्टी-गियरबॉक्स आपको मिट्टी के प्रकार के अनुसार रोटर की गति समायोजित करने की स्वतंत्रता देता है। लेजर-संतुलित रोटर शाफ्ट के कारण कंपन 40% तक कम होता है, जिससे ट्रैक्टर के पुर्जों की सुरक्षा होती है और डीजल की बचत होती है।</p>',
                    'features' => [
                        'विभिन्न मिट्टी की स्थितियों के लिए 4-स्पीड मल्टी-गियरबॉक्स तकनीक।',
                        '3 गुना अधिक चलने वाले उच्च गुणवत्ता वाले बोरॉन स्टील एल/सी ब्लेड।',
                        'ऑयल-बाथ में डूबा हुआ मजबूत साइड गियर ड्राइव सिस्टम।',
                        'खेत को समतल और ढेलारहित बनाने वाला स्प्रिंग-लोडेड बैक बोर्ड।',
                        'ट्रैक्टर पीटीओ की सुरक्षा के लिए स्लिप क्लच / शीयर बोल्ट सिस्टम।',
                        'आसानी से समायोजित होने वाले डेप्थ कंट्रोल स्किड्स।',
                    ],
                    'meta_title' => 'एग्रोविज़न गोल्ड मल्टी-स्पीड रोटावेटर | 45-65 एचपी कृषि टिलर',
                    'meta_description' => 'एग्रोविज़न गोल्ड मल्टी-स्पीड रोटावेटर की जानकारी प्राप्त करें। बोरॉन स्टील ब्लेड और कम डीजल खपत।',
                ],
                'specs' => [
                    ['en_key' => 'Tractor Power Requirement', 'en_val' => '45 - 65 HP', 'hi_key' => 'ट्रैक्टर पावर आवश्यकता', 'hi_val' => '45 - 65 एचपी'],
                    ['en_key' => 'Working Width', 'en_val' => '2000 mm (7 Feet)', 'hi_key' => 'कार्य चौड़ाई', 'hi_val' => '2000 मिमी (7 फीट)'],
                    ['en_key' => 'Gearbox Type', 'en_val' => 'Multi-Speed 4-Gear Heavy Duty', 'hi_key' => 'गियरबॉक्स का प्रकार', 'hi_val' => 'मल्टी-स्पीड 4-गियर हेवी ड्यूटी'],
                    ['en_key' => 'Side Transmission', 'en_val' => 'Precision Gear Drive in Oil Bath', 'hi_key' => 'साइड ट्रांसमिशन', 'hi_val' => 'ऑयल बाथ में साइड गियर ड्राइव'],
                    ['en_key' => 'Number of Blades', 'en_val' => '48 Blades (6 Blades per flange)', 'hi_key' => 'ब्लेडों की संख्या', 'hi_val' => '48 ब्लेड (6 ब्लेड प्रति फ्लैंज)'],
                    ['en_key' => 'Blade Material', 'en_val' => 'Heat-Treated High-Grade Boron Steel', 'hi_key' => 'ब्लेड सामग्री', 'hi_val' => 'हीट-ट्रीटेड हाई-ग्रेड बोरॉन स्टील'],
                    ['en_key' => 'Weight (Approx.)', 'en_val' => '485 kg', 'hi_key' => 'कुल वजन (लगभग)', 'hi_val' => '485 किग्रा'],
                    ['en_key' => 'PTO RPM', 'en_val' => '540 RPM Standard', 'hi_key' => 'पीटीओ आरपीएम', 'hi_val' => '540 आरपीएम मानक'],
                ],
            ],
            [
                'category_slug' => 'disc-harrows',
                'sku' => 'AGV-DSH-HYD20',
                'slug' => 'hydraulic-mounted-offset-disc-harrow',
                'featured_image' => '/assets/images/products/hydraulic-disc-harrow.jpg',
                'brochure_pdf' => '/assets/brochures/hydraulic-offset-disc-harrow.pdf',
                'tractor_hp_min' => 55,
                'tractor_hp_max' => 80,
                'working_width' => '2.3 Meters (20 Discs)',
                'weight_approx' => '650 kg',
                'blades_count' => 20,
                'is_featured' => true,
                'order' => 2,
                'en' => [
                    'name' => 'Heavy Duty Hydraulic Mounted Offset Disc Harrow',
                    'slug' => 'hydraulic-mounted-offset-disc-harrow',
                    'short_description' => 'Built with high-carbon boron steel notched discs and a seamless hydraulic gang angle adjustment, the AgroVision Disc Harrow breaks the toughest clods and root systems with minimal tractor draft.',
                    'description' => '<p>The <strong>AgroVision Hydraulic Offset Disc Harrow</strong> is built for deep primary tillage and aggressive stubble incorporation. Fabricated using high-strength seamless tubular steel beams, it resists torsion even in rock-strewn soils.</p><p>Equipped with dual double-tapered roller bearing spools sealed with mechanical floating seals, grease contamination is prevented, delivering uninterrupted service life. Hydraulic cylinder controls allow the tractor driver to vary the gang angle directly from the cabin.</p>',
                    'features' => [
                        'Hydraulic cylinder for instant on-the-go gang angle adjustment from the tractor cabin.',
                        '22-inch high-carbon boron steel discs with tempered notched leading edges.',
                        'Heavy-duty cast iron spools with dual oil-immersed taper roller bearings.',
                        'Adjustable mud scrapers on each disc preventing clogging in wet clay soils.',
                        'Cat-II three-point linkage hitch with reinforced clevis pins.',
                    ],
                    'meta_title' => 'Hydraulic Mounted Offset Disc Harrow | AgroVision 20-Disc Harrow',
                    'meta_description' => 'Heavy duty hydraulic offset disc harrow with 20 boron steel notched discs for tractors 55-80 HP.',
                ],
                'hi' => [
                    'name' => 'हेवी ड्यूटी हाइड्रोलिक माउंटेड ऑफसेट डिस्क हैरो',
                    'slug' => 'hydraulic-mounted-offset-disc-harrow-hi',
                    'short_description' => 'उच्च कार्बन बोरॉन स्टील नॉच डिस्क और हाइड्रोलिक गैंग एंगल एडजस्टमेंट से लैस, यह डिस्क हैरो कठोर मिट्टी और खरपतवार की जड़ों को आसानी से काटता है।',
                    'description' => '<p><strong>एग्रोविज़न हाइड्रोलिक डिस्क हैरो</strong> गहरी जुताई और पराली को मिट्टी में दबाने के लिए सबसे विश्वसनीय उपकरण है। उच्च शक्ति वाले ट्यूबलर फ्रेम से निर्मित यह हैरो पथरीली और सख्त भूमि में भी बिना किसी मोड़ के काम करता है।</p><p>इसके ऑयल-इमर्स्ड टेपर रोलर बेयरिंग धूल और पानी से पूरी तरह सुरक्षित रहते हैं, जिससे रखरखाव का खर्च नगण्य हो जाता है। हाइड्रोलिक सिलेंडर की मदद से ऑपरेटर सीट पर बैठे-बैठे ही कोण बदल सकता है।</p>',
                    'features' => [
                        'ट्रैक्टर केबिन से आसानी से गैंग एंगल बदलने के लिए हाइड्रोलिक सिलेंडर।',
                        '22-इंच की उच्च कार्बन बोरॉन स्टील नॉच डिस्क जो कठोर मिट्टी को चीर देती है।',
                        'डबल टेपर रोलर बेयरिंग युक्त हेवी ड्यूटी कास्ट आयरन स्पूल्स।',
                        'गीली मिट्टी में जाम होने से बचाने के लिए प्रत्येक डिस्क पर स्क्रैपर।',
                        'मजबूत कैट-II थ्री-पॉइंट लिंकेज।',
                    ],
                    'meta_title' => 'हाइड्रोलिक माउंटेड ऑफसेट डिस्क हैरो | एग्रोविज़न 20 डिस्क',
                    'meta_description' => '55 से 80 एचपी ट्रैक्टर के लिए 20 डिस्क वाला हेवी ड्यूटी हाइड्रोलिक डिस्क हैरो।',
                ],
                'specs' => [
                    ['en_key' => 'Tractor Power Requirement', 'en_val' => '55 - 80 HP', 'hi_key' => 'ट्रैक्टर पावर आवश्यकता', 'hi_val' => '55 - 80 एचपी'],
                    ['en_key' => 'Number of Discs', 'en_val' => '20 Discs (10 Front Notched + 10 Rear Plain)', 'hi_key' => 'डिस्क की संख्या', 'hi_val' => '20 डिस्क (10 आगे नॉच + 10 पीछे प्लेन)'],
                    ['en_key' => 'Disc Diameter', 'en_val' => '560 mm (22 Inches)', 'hi_key' => 'डिस्क का व्यास', 'hi_val' => '560 मिमी (22 इंच)'],
                    ['en_key' => 'Working Width', 'en_val' => '2300 mm (7.5 Feet)', 'hi_key' => 'कार्य चौड़ाई', 'hi_val' => '2300 मिमी (7.5 फीट)'],
                    ['en_key' => 'Bearing System', 'en_val' => 'Heavy Duty Taper Roller Bearing Spools', 'hi_key' => 'बेयरिंग सिस्टम', 'hi_val' => 'हेवी ड्यूटी टेपर रोलर बेयरिंग'],
                    ['en_key' => 'Weight (Approx.)', 'en_val' => '650 kg', 'hi_key' => 'कुल वजन (लगभग)', 'hi_val' => '650 किग्रा'],
                    ['en_key' => 'Hitch Type', 'en_val' => '3-Point Cat II Linkage', 'hi_key' => 'हिच का प्रकार', 'hi_val' => '3-पॉइंट कैट II लिंकेज'],
                ],
            ],
            [
                'category_slug' => 'laser-land-levellers',
                'sku' => 'AGV-LSR-PR9',
                'slug' => 'precision-dual-slope-laser-land-leveller',
                'featured_image' => '/assets/images/products/laser-land-leveller.jpg',
                'brochure_pdf' => '/assets/brochures/precision-laser-land-leveller.pdf',
                'tractor_hp_min' => 50,
                'tractor_hp_max' => 75,
                'working_width' => '2.5 Meters (8.2 Feet Bucket)',
                'weight_approx' => '820 kg',
                'blades_count' => null,
                'is_featured' => true,
                'order' => 3,
                'en' => [
                    'name' => 'AgroVision Precision Dual-Slope Laser Land Leveller',
                    'slug' => 'precision-dual-slope-laser-land-leveller',
                    'short_description' => 'Featuring military-grade laser transmitter optics, 360-degree wireless sensor receiver, and proportional hydraulic control valve, delivering millimeter-level leveling precision.',
                    'description' => '<p>The <strong>AgroVision Precision Laser Land Leveller</strong> represents state-of-the-art agricultural conservation. By achieving an immaculate level surface across farm plots, it reduces water consumption by up to 35%, prevents nutrient runoff, and accelerates crop maturity uniformity.</p><p>The heavy-duty laser bucket is fabricated with robotic continuous welding, featuring reversible high-wear cutting edges. The system includes an ultra-bright in-cabin touch screen display providing real-time elevation readouts and automatic grade elevation compensation.</p>',
                    'features' => [
                        'Dual-slope laser transmitter with up to 800-meter operating diameter range.',
                        'High-speed proportional hydraulic solenoid valve for instant bucket response.',
                        'In-cabin operator control box with manual override and digital grade display.',
                        'Laser receiver with 360-degree panoramic detection window.',
                        'Reinforced high-capacity bucket with dual reversible Hardox cutting blades.',
                        'Heavy-duty tandem axle with wide flotation tires for zero rutting.',
                    ],
                    'meta_title' => 'Dual-Slope Laser Land Leveller | AgroVision Agricultural Leveller',
                    'meta_description' => 'Level farm fields to millimeter accuracy with the AgroVision Dual-Slope Laser Land Leveller. Cut irrigation water by 35%.',
                ],
                'hi' => [
                    'name' => 'एग्रोविज़न प्रिसिजन डुअल-स्लोप लेजर लैंड लेवलर',
                    'slug' => 'precision-dual-slope-laser-land-leveller-hi',
                    'short_description' => 'सैन्य-ग्रेड लेजर ट्रांसमीटर, 360-डिग्री वायरलेस रिसीवर और प्रोपोर्शनल हाइड्रोलिक कंट्रोल वॉल्व से युक्त, जो खेत को मिलीमीटर की सटीकता से समतल करता है।',
                    'description' => '<p><strong>एग्रोविज़न प्रिसिजन लेजर लैंड लेवलर</strong> आधुनिक कृषि का एक क्रांतिकारी उपकरण है। खेत को पूरी तरह समतल करके यह सिंचाई के पानी में 35% तक की बचत करता है, खाद के बहाव को रोकता है और फसल के एक समान उत्पादन को सुनिश्चित करता है।</p><p>इसकी बकेट रोबोटिक वेल्डिंग से तैयार की गई है और इसमें हार्डेक्स कटिंग ब्लेड लगे हैं। ट्रैक्टर केबिन में लगा डिजिटल डिस्प्ले वास्तविक समय में जमीन के उतार-चढ़ाव की सटीक जानकारी देता है।</p>',
                    'features' => [
                        '800 मीटर तक की रेंज वाला डुअल-स्लोप लेजर ट्रांसमीटर।',
                        'तुरंत प्रतिक्रिया देने वाला हाई-स्पीड प्रोपोर्शनल हाइड्रोलिक वॉल्व।',
                        'ट्रैक्टर केबिन के लिए डिजिटल कंट्रोल बॉक्स और टच डिस्प्ले।',
                        '360-डिग्री डिटेक्शन वाला मजबूत लेजर रिसीवर।',
                        'हार्डेक्स रिवर्सिबल कटिंग ब्लेड के साथ हेवी ड्यूटी बकेट।',
                        'जमीन में धंसने से बचाने वाले चौड़े फ्लोटेशन टायर्स।',
                    ],
                    'meta_title' => 'डुअल-स्लोप लेजर लैंड लेवलर | एग्रोविज़न लेजर लेवलर',
                    'meta_description' => 'एग्रोविज़न लेजर लैंड लेवलर के साथ खेत को बनाएं पूरी तरह समतल। पानी और समय दोनों की बचत।',
                ],
                'specs' => [
                    ['en_key' => 'Tractor Power Requirement', 'en_val' => '50 - 75 HP', 'hi_key' => 'ट्रैक्टर पावर आवश्यकता', 'hi_val' => '50 - 75 एचपी'],
                    ['en_key' => 'Bucket Working Width', 'en_val' => '2500 mm (8.2 Feet)', 'hi_key' => 'बकेट की चौड़ाई', 'hi_val' => '2500 मिमी (8.2 फीट)'],
                    ['en_key' => 'Laser Range (Diameter)', 'en_val' => 'Up to 800 Meters', 'hi_key' => 'लेजर रेंज (व्यास)', 'hi_val' => '800 मीटर तक'],
                    ['en_key' => 'Levelling Accuracy', 'en_val' => '± 2.5 mm per 30 Meters', 'hi_key' => 'लेवलिंग सटीकता', 'hi_val' => '± 2.5 मिमी प्रति 30 मीटर'],
                    ['en_key' => 'Hydraulic Valve Type', 'en_val' => 'High-Speed Proportional Solenoid', 'hi_key' => 'हाइड्रोलिक वॉल्व', 'hi_val' => 'हाई-स्पीड प्रोपोर्शनल सोलेनोइड'],
                    ['en_key' => 'Bucket Capacity', 'en_val' => '1.8 Cubic Meters', 'hi_key' => 'बकेट क्षमता', 'hi_val' => '1.8 क्यूबिक मीटर'],
                    ['en_key' => 'Weight (Approx.)', 'en_val' => '820 kg', 'hi_key' => 'कुल वजन (लगभग)', 'hi_val' => '820 किग्रा'],
                ],
            ],
            [
                'category_slug' => 'seeding-planting',
                'sku' => 'AGV-SED-ZT11',
                'slug' => 'zero-till-multi-crop-seed-cum-fertilizer-drill',
                'featured_image' => '/assets/images/products/seed-cum-fertilizer-drill.jpg',
                'brochure_pdf' => '/assets/brochures/zero-till-seed-drill.pdf',
                'tractor_hp_min' => 40,
                'tractor_hp_max' => 60,
                'working_width' => '2.1 Meters (11 Rows)',
                'weight_approx' => '420 kg',
                'blades_count' => null,
                'is_featured' => true,
                'order' => 4,
                'en' => [
                    'name' => 'AgroVision Zero Till Multi-Crop Seed Cum Fertilizer Drill',
                    'slug' => 'zero-till-multi-crop-seed-cum-fertilizer-drill',
                    'short_description' => 'Designed for zero-tillage direct sowing of wheat, barley, pulses, and mustard right into unplowed paddy stubble, preserving soil moisture and saving critical sowing days.',
                    'description' => '<p>The <strong>AgroVision Zero Till Multi-Crop Seed Drill</strong> eliminates the cost and time of multiple pre-sowing plowings. With inverted T-type tungsten carbide tynes, it creates a narrow furrow, drops fertilizer below the seed band, and deposits seed at uniform depth.</p><p>Dual separate hoppers for fertilizer and seeds feature fluted roller metering mechanisms that eliminate seed breakage and allow precise calibration from 5 kg to 150 kg per hectare.</p>',
                    'features' => [
                        'Heavy tungsten carbide inverted T-type furrow openers for stubble penetration.',
                        'Precision fluted roller metering mechanism preventing grain damage.',
                        'Dual separate powder-coated stainless steel hoppers for fertilizer and seed.',
                        'Transparent heavy-duty seed delivery tubes for clear operator visibility.',
                        'Adjustable row-to-row spacing from 15 cm to 25 cm.',
                    ],
                    'meta_title' => 'Zero Till Seed Cum Fertilizer Drill | AgroVision 11-Row Drill',
                    'meta_description' => 'Direct seeding drill for wheat and paddy stubble. Dual hopper, tungsten carbide tynes, for 40-60 HP tractors.',
                ],
                'hi' => [
                    'name' => 'एग्रोविज़न जीरो टिल मल्टी-क्रॉप सीड कम फर्टिलाइजर ड्रिल',
                    'slug' => 'zero-till-multi-crop-seed-cum-fertilizer-drill-hi',
                    'short_description' => 'धान की पराली वाले खेत में बिना जुताई के सीधे गेहूं, सरसों और दलहन की बुवाई के लिए विशेष रूप से डिज़ाइन की गई आधुनिक ड्रिल मशीन।',
                    'description' => '<p><strong>एग्रोविज़न जीरो टिल सीड ड्रिल</strong> बार-बार जुताई के खर्च और समय को समाप्त करती है। इसके टंगस्टन कार्बाइड इनवर्टेड टी-टाइप फाले मिट्टी में बारीक नाली बनाकर खाद को बीज के नीचे सही गहराई पर डालते हैं।</p><p>बीज और खाद के लिए अलग-अलग मजबूत हॉपर दिए गए हैं, जिनमें फ्लूटेड रोलर सिस्टम है जो बीजों को टूटने से बचाता है और प्रति हेक्टेयर 5 से 150 किग्रा तक सटीक मात्रा सुनिश्चित करता है।</p>',
                    'features' => [
                        'पराली को चीरकर बुवाई करने वाले मजबूत टंगस्टन कार्बाइड इनवर्टेड टी-फाले।',
                        'बीज को टूटने से बचाने वाला सटीक फ्लूटेड रोलर मीटरिंग सिस्टम।',
                        'जंग-रोधी पाउडर कोटेड खाद और बीज के दो अलग-अलग हॉपर।',
                        'बीज के बहाव को आसानी से देखने के लिए पारदर्शी डिलीवरी पाइप।',
                        'पंक्ति से पंक्ति की दूरी 15 से 25 सेमी तक समायोजित करने की सुविधा।',
                    ],
                    'meta_title' => 'जीरो टिल सीड कम फर्टिलाइजर ड्रिल | एग्रोविज़न 11-रो मशीन',
                    'meta_description' => 'बिना जुताई गेहूं की बुवाई के लिए एग्रोविज़न जीरो टिल ड्रिल। 40 से 60 एचपी ट्रैक्टर के अनुकूल।',
                ],
                'specs' => [
                    ['en_key' => 'Tractor Power Requirement', 'en_val' => '40 - 60 HP', 'hi_key' => 'ट्रैक्टर पावर आवश्यकता', 'hi_val' => '40 - 60 एचपी'],
                    ['en_key' => 'Number of Rows', 'en_val' => '11 Rows (Configurable to 9 or 13)', 'hi_key' => 'पंक्तियों की संख्या', 'hi_val' => '11 पंक्तियां (9 या 13 में समायोज्य)'],
                    ['en_key' => 'Row Spacing', 'en_val' => '175 mm to 225 mm Adjustable', 'hi_key' => 'पंक्ति की दूरी', 'hi_val' => '175 से 225 मिमी समायोज्य'],
                    ['en_key' => 'Furrow Opener Type', 'en_val' => 'Inverted T-Type Carbide Tipped', 'hi_key' => 'फाले का प्रकार', 'hi_val' => 'इनवर्टेड टी-टाइप कार्बाइड टिप्ड'],
                    ['en_key' => 'Seed Metering', 'en_val' => 'Fluted Roller Aluminium Alloy', 'hi_key' => 'सीड मीटरिंग', 'hi_val' => 'फ्लूटेड रोलर एल्युमिनियम मिश्र धातु'],
                    ['en_key' => 'Hopper Capacity', 'en_val' => 'Seed: 65 kg | Fertilizer: 70 kg', 'hi_key' => 'हॉपर क्षमता', 'hi_val' => 'बीज: 65 किग्रा | खाद: 70 किग्रा'],
                    ['en_key' => 'Weight (Approx.)', 'en_val' => '420 kg', 'hi_key' => 'कुल वजन (लगभग)', 'hi_val' => '420 किग्रा'],
                ],
            ],
            [
                'category_slug' => 'disc-harrows',
                'sku' => 'AGV-PLG-HYD3',
                'slug' => 'hydraulic-reversible-mould-board-plough',
                'featured_image' => '/assets/images/products/mould-board-plough.jpg',
                'brochure_pdf' => '/assets/brochures/hydraulic-mb-plough.pdf',
                'tractor_hp_min' => 60,
                'tractor_hp_max' => 90,
                'working_width' => '1.1 Meters (3-Bottom Reversible)',
                'weight_approx' => '510 kg',
                'blades_count' => null,
                'is_featured' => true,
                'order' => 5,
                'en' => [
                    'name' => 'Hydraulic Reversible Mould Board Plough (3-Bottom)',
                    'slug' => 'hydraulic-reversible-mould-board-plough',
                    'short_description' => 'Equipped with heavy-duty turnover hydraulic cylinder and special alloy boron steel mould boards, turning the soil 180 degrees to bury weeds and pests deep underground.',
                    'description' => '<p>The <strong>AgroVision 3-Bottom Hydraulic Reversible MB Plough</strong> delivers flawless complete soil inversion. Designed for tough, dry soils and green manuring fields, it turns crop residue beneath the topsoil, enhancing natural humus content.</p><p>Its automatic hydraulic rollover mechanism allows continuous back-and-forth plowing without dead furrows, reducing empty turnaround time at field headlands by 50%.</p>',
                    'features' => [
                        'Heavy double-acting hydraulic rollover cylinder for smooth turnover without shocks.',
                        'Boron steel shares, points, and mould boards hardened to 52 HRC.',
                        'Shear bolt safety protection preventing frame damage in hidden boulder terrain.',
                        'Adjustable furrow width and depth control gauge wheel.',
                        'Solid hot-rolled steel main mast beam ensuring zero deflection.',
                    ],
                    'meta_title' => 'Hydraulic Reversible MB Plough | AgroVision 3-Bottom Plough',
                    'meta_description' => 'Heavy duty 3-bottom hydraulic reversible mould board plough for 60-90 HP tractors. Complete soil inversion.',
                ],
                'hi' => [
                    'name' => 'हाइड्रोलिक रिवर्सिबल मोल्ड बोर्ड हल (3-बॉटम)',
                    'slug' => 'hydraulic-reversible-mould-board-plough-hi',
                    'short_description' => 'हेवी-ड्यूटी हाइड्रोलिक सिलेंडर और बोरॉन स्टील मोल्ड बोर्ड से सुसज्जित, जो मिट्टी को 180 डिग्री पलटकर खरपतवार और कीड़ों को गहराई में दबा देता है।',
                    'description' => '<p><strong>एग्रोविज़न 3-बॉटम हाइड्रोलिक एमबी हल</strong> मिट्टी को पूरी तरह पलटने के लिए सबसे उन्नत उपकरण है। सख्त मिट्टी और हरी खाद वाले खेतों के लिए निर्मित यह हल फसल के अवशेषों को मिट्टी के नीचे दबाकर प्राकृतिक खाद का निर्माण करता है।</p><p>इसका स्वचालित हाइड्रोलिक रिवर्स सिस्टम खेत में खाली चक्कर लगाने से बचाता है और ट्रैक्टर का समय व डीजल 50% तक बचाता है।</p>',
                    'features' => [
                        'झटकों से मुक्त 180 डिग्री रिवर्सिबल डबल-एक्टिंग हाइड्रोलिक सिलेंडर।',
                        '52 एचआरसी तक कठोर किए गए बोरॉन स्टील मोल्ड बोर्ड और शेयर पॉइंट्स।',
                        'पत्थर टकराने पर सुरक्षा के लिए शीयर बोल्ट सेफ्टी मैकेनिज्म।',
                        'सटीक गहराई नियंत्रण के लिए गेज व्हील।',
                        'मजबूत हॉट-रोल्ड स्टील बीम जो कभी मुड़ती नहीं है।',
                    ],
                    'meta_title' => 'हाइड्रोलिक रिवर्सिबल एमबी हल | एग्रोविज़न 3-बॉटम हल',
                    'meta_description' => '60 से 90 एचपी ट्रैक्टर के लिए 3 बॉटम हाइड्रोलिक रिवर्सिबल हल की पूरी जानकारी।',
                ],
                'specs' => [
                    ['en_key' => 'Tractor Power Requirement', 'en_val' => '60 - 90 HP', 'hi_key' => 'ट्रैक्टर पावर आवश्यकता', 'hi_val' => '60 - 90 एचपी'],
                    ['en_key' => 'Number of Bottoms', 'en_val' => '3 Bottoms Reversible (6 Mouldboards)', 'hi_key' => 'बॉटम्स की संख्या', 'hi_val' => '3 बॉटम रिवर्सिबल (6 मोल्ड बोर्ड)'],
                    ['en_key' => 'Ploughing Depth', 'en_val' => '250 mm to 350 mm (10-14 Inches)', 'hi_key' => 'जुताई गहराई', 'hi_val' => '250 से 350 मिमी (10-14 इंच)'],
                    ['en_key' => 'Mould Board Steel', 'en_val' => 'Wear-Resistant Boron Alloy Steel', 'hi_key' => 'मोल्ड बोर्ड सामग्री', 'hi_val' => 'घिसाव-रोधी बोरॉन मिश्र धातु'],
                    ['en_key' => 'Turnover Mechanism', 'en_val' => 'Hydraulic Cylinder from Tractor Valve', 'hi_key' => 'टर्नओवर मैकेनिज्म', 'hi_val' => 'ट्रैक्टर वॉल्व से हाइड्रोलिक सिलेंडर'],
                    ['en_key' => 'Weight (Approx.)', 'en_val' => '510 kg', 'hi_key' => 'कुल वजन (लगभग)', 'hi_val' => '510 किग्रा'],
                ],
            ],
            [
                'category_slug' => 'harvest-straw-management',
                'sku' => 'AGV-STR-RP50',
                'slug' => 'heavy-duty-tractor-straw-reaper',
                'featured_image' => '/assets/images/products/straw-reaper.jpg',
                'brochure_pdf' => '/assets/brochures/tractor-straw-reaper.pdf',
                'tractor_hp_min' => 50,
                'tractor_hp_max' => 75,
                'working_width' => '2.1 Meters (7 Feet Cutter Bar)',
                'weight_approx' => '1950 kg',
                'blades_count' => null,
                'is_featured' => true,
                'order' => 6,
                'en' => [
                    'name' => 'AgroVision Heavy Duty Tractor Straw Reaper with Hydraulic Hopper',
                    'slug' => 'heavy-duty-tractor-straw-reaper',
                    'short_description' => 'Cuts, threshes, and cleans wheat and paddy straw left behind by combine harvesters, transforming field residue into fine, dust-free cattle fodder stored in a hydraulic tipping trolley.',
                    'description' => '<p>The <strong>AgroVision Straw Reaper</strong> is the farmer\'s most profitable post-harvest investment. It clears remaining crop stubble in a single pass while producing superior-quality finely chopped straw (bhusa).</p><p>Featuring a double-blower cleaning mechanism, heavy balanced threshing cylinder with hardened concave beaters, and an integrated hydraulic unloading hopper, it cleans up to 2 acres per hour while avoiding field residue burning penalties.</p>',
                    'features' => [
                        'Heavy balanced threshing cylinder with 28 heat-treated steel beaters.',
                        'Dual high-output blower fans removing dust and foreign particles completely.',
                        'Heavy hydraulic tipping trolley pipe for direct discharge into trailer wagons.',
                        'Grain recovery tray collecting up to 1.5 - 2 quintals of lost grain per acre.',
                        'High clearance chassis with heavy-duty stub axle and balanced flywheels.',
                    ],
                    'meta_title' => 'Tractor Straw Reaper with Hydraulic Hopper | AgroVision',
                    'meta_description' => 'Heavy duty wheat and paddy straw reaper. Transforms harvest stubble into cattle fodder. For 50-75 HP tractors.',
                ],
                'hi' => [
                    'name' => 'एग्रोविज़न हेवी ड्यूटी ट्रैक्टर स्ट्रॉ रीपर (भूसा मशीन)',
                    'slug' => 'heavy-duty-tractor-straw-reaper-hi',
                    'short_description' => 'कंबाइन हार्वेस्टर के बाद बचे हुए गेहूं और धान के डंठलों को काटकर बारीक, धूल-रहित पशु चारा (भूसा) तैयार करने वाला शक्तिशाली स्ट्रॉ रीपर।',
                    'description' => '<p><strong>एग्रोविज़न स्ट्रॉ रीपर</strong> कटाई के बाद किसानों के लिए सबसे अधिक कमाई कराने वाली मशीन है। यह एक ही चक्कर में खेत के सभी अवशेषों को साफ करके बेहतरीन गुणवत्ता का बारीक भूसा तैयार करती है।</p><p>डबल ब्लोअर क्लीनिंग सिस्टम, संतुलित थ्रेशिंग ड्रम और हाइड्रोलिक अनलोडिंग पाइप के साथ यह मशीन प्रति घंटे 2 एकड़ तक खेत साफ कर देती है और खेत में आग लगाने से रोकती है।</p>',
                    'features' => [
                        '28 हीट-ट्रीटेड बीटर्स के साथ पूरी तरह संतुलित हेवी थ्रेशिंग ड्रम।',
                        'धूल और मिट्टी को पूरी तरह अलग करने वाले दो शक्तिशाली ब्लोअर पंखे।',
                        'ट्रॉली में सीधे भूसा भरने के लिए हाइड्रोलिक डिलीवरी पाइप।',
                        'प्रति एकड़ 1.5 से 2 क्विंटल तक छूटे हुए अनाज को बचाने वाला ग्रेन रिकवरी ट्रे।',
                        'संतुलित फ्लाईव्हील के साथ मजबूत और टिकाऊ चेसिस।',
                    ],
                    'meta_title' => 'ट्रैक्टर स्ट्रॉ रीपर भूसा मशीन | एग्रोविज़न',
                    'meta_description' => '50 से 75 एचपी ट्रैक्टर के लिए एग्रोविज़न हेवी ड्यूटी स्ट्रॉ रीपर की जानकारी।',
                ],
                'specs' => [
                    ['en_key' => 'Tractor Power Requirement', 'en_val' => '50 - 75 HP (Dual Clutch Recommended)', 'hi_key' => 'ट्रैक्टर पावर आवश्यकता', 'hi_val' => '50 - 75 एचपी (ड्यूल क्लच अनुशंसित)'],
                    ['en_key' => 'Cutter Bar Width', 'en_val' => '2100 mm (7 Feet)', 'hi_key' => 'कटर बार चौड़ाई', 'hi_val' => '2100 मिमी (7 फीट)'],
                    ['en_key' => 'Capacity (Output)', 'en_val' => '1.5 to 2.0 Acres / Hour', 'hi_key' => 'कार्य क्षमता', 'hi_val' => '1.5 से 2.0 एकड़ / घंटा'],
                    ['en_key' => 'Blower System', 'en_val' => 'Twin High-Velocity Centrifugal Blowers', 'hi_key' => 'ब्लोअर सिस्टम', 'hi_val' => 'ट्विन हाई-वेलोसिटी सेंट्रीफ्यूगल ब्लोअर'],
                    ['en_key' => 'Grain Recovery', 'en_val' => 'Included with Filter Sieve', 'hi_key' => 'ग्रेन रिकवरी', 'hi_val' => 'फिल्टर छलनी के साथ शामिल'],
                    ['en_key' => 'Weight (Approx.)', 'en_val' => '1950 kg', 'hi_key' => 'कुल वजन (लगभग)', 'hi_val' => '1950 किग्रा'],
                    ['en_key' => 'PTO RPM', 'en_val' => '540 RPM', 'hi_key' => 'पीटीओ आरपीएम', 'hi_val' => '540 आरपीएम'],
                ],
            ],
        ];

        $createdProducts = [];
        foreach ($productsData as $pData) {
            $cat = $createdCategories[$pData['category_slug']];
            $prod = Product::updateOrCreate(
                ['slug' => $pData['slug']],
                [
                    'category_id' => $cat->id,
                    'sku' => $pData['sku'],
                    'featured_image' => $pData['featured_image'],
                    'brochure_pdf' => $pData['brochure_pdf'],
                    'tractor_hp_min' => $pData['tractor_hp_min'],
                    'tractor_hp_max' => $pData['tractor_hp_max'],
                    'working_width' => $pData['working_width'],
                    'weight_approx' => $pData['weight_approx'],
                    'blades_count' => $pData['blades_count'],
                    'is_featured' => $pData['is_featured'],
                    'is_active' => true,
                    'order' => $pData['order'],
                ]
            );

            // EN Translation
            ProductTranslation::updateOrCreate(
                ['product_id' => $prod->id, 'locale' => 'en'],
                [
                    'name' => $pData['en']['name'],
                    'slug' => $pData['en']['slug'],
                    'short_description' => $pData['en']['short_description'],
                    'description' => $pData['en']['description'],
                    'features_json' => $pData['en']['features'],
                    'meta_title' => $pData['en']['meta_title'],
                    'meta_description' => $pData['en']['meta_description'],
                ]
            );

            // HI Translation
            ProductTranslation::updateOrCreate(
                ['product_id' => $prod->id, 'locale' => 'hi'],
                [
                    'name' => $pData['hi']['name'],
                    'slug' => $pData['hi']['slug'],
                    'short_description' => $pData['hi']['short_description'],
                    'description' => $pData['hi']['description'],
                    'features_json' => $pData['hi']['features'],
                    'meta_title' => $pData['hi']['meta_title'],
                    'meta_description' => $pData['hi']['meta_description'],
                ]
            );

            // Specifications
            ProductSpecification::where('product_id', $prod->id)->delete();
            $specOrder = 1;
            foreach ($pData['specs'] as $specItem) {
                $spec = ProductSpecification::create([
                    'product_id' => $prod->id,
                    'order' => $specOrder++,
                ]);

                ProductSpecificationTranslation::create([
                    'specification_id' => $spec->id,
                    'locale' => 'en',
                    'spec_key' => $specItem['en_key'],
                    'spec_value' => $specItem['en_val'],
                ]);

                ProductSpecificationTranslation::create([
                    'specification_id' => $spec->id,
                    'locale' => 'hi',
                    'spec_key' => $specItem['hi_key'],
                    'spec_value' => $specItem['hi_val'],
                ]);
            }

            // Gallery images
            ProductImage::where('product_id', $prod->id)->delete();
            ProductImage::create([
                'product_id' => $prod->id,
                'image_path' => $pData['featured_image'],
                'caption' => $pData['en']['name'] . ' - Front View',
                'order' => 1,
            ]);
            ProductImage::create([
                'product_id' => $prod->id,
                'image_path' => str_replace('.jpg', '-side.jpg', $pData['featured_image']),
                'caption' => $pData['en']['name'] . ' - Side & Gearbox Detail',
                'order' => 2,
            ]);

            $createdProducts[] = $prod;
        }

        // Link related products
        if (count($createdProducts) >= 3) {
            $createdProducts[0]->relatedProducts()->sync([$createdProducts[1]->id, $createdProducts[2]->id]);
            $createdProducts[1]->relatedProducts()->sync([$createdProducts[0]->id, $createdProducts[4]->id]);
            $createdProducts[2]->relatedProducts()->sync([$createdProducts[0]->id, $createdProducts[1]->id]);
            $createdProducts[3]->relatedProducts()->sync([$createdProducts[0]->id, $createdProducts[2]->id]);
        }

        // 7. Banners
        Banner::truncate();
        BannerTranslation::truncate();

        $banners = [
            [
                'page_slug' => 'home',
                'image_path' => '/assets/images/banners/hero-rotavator.jpg',
                'mobile_image_path' => '/assets/images/banners/hero-rotavator-mobile.jpg',
                'button_url' => '/products/agrovision-gold-rotary-tiller-rotavator',
                'secondary_button_url' => '/contact',
                'order' => 1,
                'is_active' => true,
                'en' => [
                    'badge_text' => 'Next-Gen Farming Technology',
                    'title' => 'Engineered For Superior Soil Pulverization',
                    'subtitle' => 'High-tensile boron steel blades and heavy-duty 4-speed multi-gearboxes built to dominate the toughest farmlands across 50+ countries.',
                    'button_text' => 'Explore Rotary Tillers',
                    'secondary_button_text' => 'Request Quotation',
                ],
                'hi' => [
                    'badge_text' => 'अगली पीढ़ी की कृषि तकनीक',
                    'title' => 'उत्कृष्ट जुताई और मिट्टी तैयारी के लिए निर्मित',
                    'subtitle' => 'कठोरतम मिट्टी को जीतने के लिए हाई-टेंसिल बोरॉन स्टील ब्लेड और हेवी-ड्यूटी 4-स्पीड मल्टी-गियरबॉक्स तकनीक।',
                    'button_text' => 'रोटावेटर देखें',
                    'secondary_button_text' => 'कोटेशन प्राप्त करें',
                ],
            ],
            [
                'page_slug' => 'home',
                'image_path' => '/assets/images/banners/hero-laser-leveller.jpg',
                'mobile_image_path' => '/assets/images/banners/hero-laser-leveller-mobile.jpg',
                'button_url' => '/products/precision-dual-slope-laser-land-leveller',
                'secondary_button_url' => '/products',
                'order' => 2,
                'is_active' => true,
                'en' => [
                    'badge_text' => 'Water Conservation & Yield Maximization',
                    'title' => 'Precision Laser Land Levelling Systems',
                    'subtitle' => 'Cut farm irrigation water requirements by up to 35% with our sub-centimeter dual-slope automatic electronic laser levellers.',
                    'button_text' => 'Discover Laser Levellers',
                    'secondary_button_text' => 'View Full Catalogue',
                ],
                'hi' => [
                    'badge_text' => 'जल संरक्षण एवं अधिकतम पैदावार',
                    'title' => 'सटीक लेजर लैंड लेवलिंग सिस्टम',
                    'subtitle' => 'हमारे सब-सेंटीमीटर डुअल-स्लोप इलेक्ट्रॉनिक लेजर लेवलर के साथ सिंचाई के पानी में 35% तक की बचत करें।',
                    'button_text' => 'लेजर लेवलर देखें',
                    'secondary_button_text' => 'सभी उत्पाद देखें',
                ],
            ],
            [
                'page_slug' => 'home',
                'image_path' => '/assets/images/banners/hero-reaper.jpg',
                'mobile_image_path' => '/assets/images/banners/hero-reaper-mobile.jpg',
                'button_url' => '/products/heavy-duty-tractor-straw-reaper',
                'secondary_button_url' => '/contact',
                'order' => 3,
                'is_active' => true,
                'en' => [
                    'badge_text' => 'Zero Residue Burning Solutions',
                    'title' => 'Eco-Friendly Straw & Stubble Management',
                    'subtitle' => 'Convert field residue into valuable cattle fodder in a single pass with our tractor-driven heavy-duty straw reapers.',
                    'button_text' => 'View Straw Reaper',
                    'secondary_button_text' => 'Locate a Dealer',
                ],
                'hi' => [
                    'badge_text' => 'पराली प्रबंधन और पर्यावरण सुरक्षा',
                    'title' => 'पर्यावरण-अनुकूल पराली व भूसा प्रबंधन',
                    'subtitle' => 'हमारे शक्तिशाली ट्रैक्टर स्ट्रॉ रीपर से खेत की पराली को एक ही बार में पौष्टिक पशु चारे में बदलें।',
                    'button_text' => 'स्ट्रॉ रीपर देखें',
                    'secondary_button_text' => 'डीलर खोजें',
                ],
            ],
        ];

        foreach ($banners as $bData) {
            $banner = Banner::create([
                'page_slug' => $bData['page_slug'],
                'image_path' => $bData['image_path'],
                'mobile_image_path' => $bData['mobile_image_path'],
                'button_url' => $bData['button_url'],
                'secondary_button_url' => $bData['secondary_button_url'],
                'order' => $bData['order'],
                'is_active' => $bData['is_active'],
            ]);

            BannerTranslation::create(array_merge(['banner_id' => $banner->id, 'locale' => 'en'], $bData['en']));
            BannerTranslation::create(array_merge(['banner_id' => $banner->id, 'locale' => 'hi'], $bData['hi']));
        }

        // 8. CMS Pages & Reusable Sections
        $pagesData = [
            [
                'slug' => 'home',
                'template' => 'home',
                'is_published' => true,
                'order' => 1,
                'show_in_nav' => true,
                'en' => [
                    'title' => 'Home',
                    'slug' => 'home',
                    'subtitle' => 'Empowering Farmers With Industrial-Grade Agricultural Implements',
                    'content' => null,
                    'excerpt' => 'Global agricultural machinery manufacturer specializing in rotary tillers, disc harrows, laser levellers, and seeding solutions.',
                    'meta_title' => 'AgroVision Heavy Industries | High-Performance Agricultural Machinery',
                    'meta_description' => 'Official website of AgroVision Heavy Industries. Discover heavy duty rotary tillers, disc harrows, laser land levellers, and seed drills.',
                ],
                'hi' => [
                    'title' => 'होम',
                    'slug' => 'mukhya-prishth',
                    'subtitle' => 'उद्योग-स्तरीय कृषि उपकरणों के साथ किसानों का सशक्तिकरण',
                    'content' => null,
                    'excerpt' => 'रोटावेटर, डिस्क हैरो, लेजर लेवलर और बुवाई उपकरणों के अग्रणी वैश्विक निर्माता।',
                    'meta_title' => 'एग्रोविज़न हेवी इंडस्ट्रीज | उच्च प्रदर्शन कृषि मशीनरी',
                    'meta_description' => 'एग्रोविज़न हेवी इंडस्ट्रीज की आधिकारिक वेबसाइट। रोटावेटर, डिस्क हैरो, लेजर लेवलर और कृषि उपकरणों की जानकारी।',
                ],
                'sections' => [
                    [
                        'type' => 'hero_slider',
                        'order' => 1,
                        'is_active' => true,
                        'settings' => ['autoplay' => true, 'interval' => 6000],
                        'en' => ['title' => 'Hero Banner Slider', 'subtitle' => 'Agricultural Excellence'],
                        'hi' => ['title' => 'हीरो स्लाइडर', 'subtitle' => 'कृषि उत्कृष्टता'],
                    ],
                    [
                        'type' => 'stats_counter',
                        'order' => 2,
                        'is_active' => true,
                        'settings' => ['background' => 'dark'],
                        'en' => [
                            'title' => 'AgroVision At A Glance',
                            'subtitle' => 'Built on decades of precision industrial engineering and farmer trust.',
                            'data_json' => [
                                ['count' => '35+', 'label' => 'Years of Engineering Heritage', 'icon' => 'bi-shield-check'],
                                ['count' => '500,000+', 'label' => 'Implements Working Globally', 'icon' => 'bi-tractor'],
                                ['count' => '52+', 'label' => 'Countries Exported Worldwide', 'icon' => 'bi-globe-americas'],
                                ['count' => '1,800+', 'label' => 'Authorized Dealer Service Points', 'icon' => 'bi-geo-alt'],
                            ],
                        ],
                        'hi' => [
                            'title' => 'एग्रोविज़न एक नज़र में',
                            'subtitle' => 'दशकों की सटीक इंजीनियरिंग और किसानों के अटूट विश्वास पर आधारित।',
                            'data_json' => [
                                ['count' => '35+', 'label' => 'वर्षों की इंजीनियरिंग विरासत', 'icon' => 'bi-shield-check'],
                                ['count' => '5,00,000+', 'label' => 'खेतों में काम करती मशीनें', 'icon' => 'bi-tractor'],
                                ['count' => '52+', 'label' => 'देशों में वैश्विक निर्यात', 'icon' => 'bi-globe-americas'],
                                ['count' => '1,800+', 'label' => 'अधिकृत डीलर सेवा केंद्र', 'icon' => 'bi-geo-alt'],
                            ],
                        ],
                    ],
                    [
                        'type' => 'category_grid',
                        'order' => 3,
                        'is_active' => true,
                        'settings' => ['columns' => 3],
                        'en' => [
                            'title' => 'Explore Product Lines',
                            'subtitle' => 'Heavy-duty agricultural equipment tailored for every phase of crop production.',
                            'button_text' => 'View All Equipment',
                            'button_url' => '/products',
                        ],
                        'hi' => [
                            'title' => 'उत्पाद श्रेणियां देखें',
                            'subtitle' => 'फसल उत्पादन के हर चरण के लिए विशेष रूप से तैयार किए गए हेवी-ड्यूटी कृषि उपकरण।',
                            'button_text' => 'सभी उपकरण देखें',
                            'button_url' => '/products',
                        ],
                    ],
                    [
                        'type' => 'product_grid',
                        'order' => 4,
                        'is_active' => true,
                        'settings' => ['limit' => 6, 'filter' => 'featured'],
                        'en' => [
                            'title' => 'Featured Machinery',
                            'subtitle' => 'Discover our most sought-after machinery trusted by progressive farming enterprises worldwide.',
                            'button_text' => 'Browse Full Machinery Catalogue',
                            'button_url' => '/products',
                        ],
                        'hi' => [
                            'title' => 'प्रमुख कृषि मशीनरी',
                            'subtitle' => 'दुनिया भर के प्रगतिशील किसानों द्वारा पसंद की जाने वाली हमारी प्रमुख मशीनें।',
                            'button_text' => 'पूरी सूची देखें',
                            'button_url' => '/products',
                        ],
                    ],
                    [
                        'type' => 'feature_cards',
                        'order' => 5,
                        'is_active' => true,
                        'settings' => ['layout' => 'cards_grid'],
                        'en' => [
                            'title' => 'Why Choose AgroVision',
                            'subtitle' => 'Industrial precision, ultra-durable metallurgy, and world-class customer support.',
                            'data_json' => [
                                ['icon' => 'bi-cpu', 'title' => 'Robotic Laser Precision', 'description' => 'Computerized robotic cutting and welding ensures structural tolerance of less than 0.1 mm, preventing frame fatigue.'],
                                ['icon' => 'bi-gem', 'title' => 'High-Grade Boron Steel', 'description' => 'Our blades and wearing parts are forged with genuine European boron steel, achieving up to 3x longer operational life.'],
                                ['icon' => 'bi-gear-wide', 'title' => 'In-House Multi-Speed Gearboxes', 'description' => 'Precision induction-hardened gears machined to zero-backlash standards for whisper-quiet and cool-running operation.'],
                                ['icon' => 'bi-headset', 'title' => '24/7 Field & Spare Support', 'description' => 'Extensive dealer inventory network guaranteeing 48-hour delivery of critical genuine spare parts anywhere.'],
                            ],
                        ],
                        'hi' => [
                            'title' => 'एग्रोविज़न ही क्यों चुनें?',
                            'subtitle' => 'औद्योगिक सटीकता, उच्च श्रेणी की धातु और विश्वस्तरीय ग्राहक सेवा।',
                            'data_json' => [
                                ['icon' => 'bi-cpu', 'title' => 'रोबोटिक लेजर सटीकता', 'description' => 'कंप्यूटरीकृत रोबोटिक वेल्डिंग 0.1 मिमी से कम टॉलरेंस सुनिश्चित करती है, जिससे फ्रेम कभी टूटता नहीं।'],
                                ['icon' => 'bi-gem', 'title' => 'उच्च श्रेणी बोरॉन स्टील', 'description' => 'हमारे सभी ब्लेड्स और घिसने वाले पुर्जे असली बोरॉन स्टील से निर्मित हैं, जो 3 गुना अधिक चलते हैं।'],
                                ['icon' => 'bi-gear-wide', 'title' => 'स्वयं निर्मित गियरबॉक्स', 'description' => 'इंडक्शन-हार्डन्ड गियर्स जो बिना किसी शोर और गर्मी के लगातार भारी काम करने में सक्षम हैं।'],
                                ['icon' => 'bi-headset', 'title' => '24/7 फील्ड और स्पेयर सपोर्ट', 'description' => 'विशाल डीलर नेटवर्क जो 48 घंटों में असली स्पेयर पार्ट्स की उपलब्धता सुनिश्चित करता है।'],
                            ],
                        ],
                    ],
                    [
                        'type' => 'testimonials',
                        'order' => 6,
                        'is_active' => true,
                        'settings' => ['slider' => true],
                        'en' => [
                            'title' => 'Voices From The Field',
                            'subtitle' => 'Hear from progressive agricultural producers and tractor fleet operators.',
                            'data_json' => [
                                [
                                    'quote' => 'We operate 4 AgroVision Gold Rotavators across 600 acres of paddy and wheat. Even in heavy black cotton soil, the diesel consumption dropped by nearly 1.5 liters per acre compared to our previous equipment.',
                                    'author' => 'Sukhwinder Singh Gill',
                                    'role' => 'Commercial Farm Owner, Punjab',
                                    'avatar' => '/assets/images/testimonials/avatar1.jpg',
                                    'rating' => 5,
                                ],
                                [
                                    'quote' => 'The AgroVision Dual-Slope Laser Leveller paid for itself in a single cropping season. Water savings exceeded 30%, and germination across our plots is 100% uniform.',
                                    'author' => 'Ramesh Patel',
                                    'role' => 'Agri-Contractor & Progressive Farmer, Gujarat',
                                    'avatar' => '/assets/images/testimonials/avatar2.jpg',
                                    'rating' => 5,
                                ],
                                [
                                    'quote' => 'Extremely impressed with the hydraulic reversible MB plough. Perfect soil inversion with zero clumping, and the shear-bolt protection saved our tractor PTO several times in rocky soil.',
                                    'author' => 'Devendra Sharma',
                                    'role' => 'Farm Manager, Madhya Pradesh',
                                    'avatar' => '/assets/images/testimonials/avatar3.jpg',
                                    'rating' => 5,
                                ],
                            ],
                        ],
                        'hi' => [
                            'title' => 'किसानों की जुबानी',
                            'subtitle' => 'प्रगतिशील किसानों और ट्रैक्टर ऑपरेटरों के वास्तविक अनुभव।',
                            'data_json' => [
                                [
                                    'quote' => 'हम 600 एकड़ धान और गेहूं के खेत में 4 एग्रोविज़न गोल्ड रोटावेटर चला रहे हैं। भारी काली मिट्टी में भी पिछली मशीनों के मुकाबले प्रति एकड़ लगभग 1.5 लीटर डीजल कम लगता है।',
                                    'author' => 'सुखविंदर सिंह गिल',
                                    'role' => 'व्यावसायिक कृषि संचालक, पंजाब',
                                    'avatar' => '/assets/images/testimonials/avatar1.jpg',
                                    'rating' => 5,
                                ],
                                [
                                    'quote' => 'एग्रोविज़न लेजर लेवलर ने एक ही सीजन में अपनी पूरी लागत वसूल कर दी। पानी की 30% से अधिक बचत हुई और फसल का अंकुरण पूरे खेत में एक समान है।',
                                    'author' => 'रमेश पटेल',
                                    'role' => 'प्रगतिशील किसान एवं कांट्रैक्टर, गुजरात',
                                    'avatar' => '/assets/images/testimonials/avatar2.jpg',
                                    'rating' => 5,
                                ],
                                [
                                    'quote' => 'हाइड्रोलिक रिवर्सिबल एमबी हल के काम से मैं बहुत प्रभावित हूं। मिट्टी पूरी तरह पलट जाती है और पथरीली जमीन में भी इसके सेफ्टी सिस्टम ने ट्रैक्टर को सुरक्षित रखा।',
                                    'author' => 'देवेंद्र शर्मा',
                                    'role' => 'फार्म मैनेजर, मध्य प्रदेश',
                                    'avatar' => '/assets/images/testimonials/avatar3.jpg',
                                    'rating' => 5,
                                ],
                            ],
                        ],
                    ],
                    [
                        'type' => 'cta_banner',
                        'order' => 7,
                        'is_active' => true,
                        'settings' => ['theme' => 'primary'],
                        'en' => [
                            'title' => 'Ready To Transform Your Agricultural Productivity?',
                            'subtitle' => 'Connect with our technical product specialists for customized implement recommendations and competitive factory-direct quotations.',
                            'button_text' => 'Request Direct Factory Quote',
                            'button_url' => '/contact',
                        ],
                        'hi' => [
                            'title' => 'क्या आप अपनी कृषि पैदावार को नई ऊंचाई देने के लिए तैयार हैं?',
                            'subtitle' => 'अपनी जमीन और ट्रैक्टर के अनुकूल सही उपकरण चुनने और फैक्टरी कोटेशन पाने के लिए हमारे विशेषज्ञों से संपर्क करें।',
                            'button_text' => 'फैक्टरी कोटेशन प्राप्त करें',
                            'button_url' => '/contact',
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'about-us',
                'template' => 'about',
                'is_published' => true,
                'order' => 2,
                'show_in_nav' => true,
                'en' => [
                    'title' => 'About Us',
                    'slug' => 'about-us',
                    'subtitle' => 'Engineering Excellence in Agricultural Machinery Since 1989',
                    'excerpt' => 'Learn more about AgroVision Heavy Industries, our state-of-the-art robotic manufacturing plant, and our mission to modernize farming.',
                    'content' => '<h3>Our Legacy & Heritage</h3><p>Founded with a vision to democratize modern mechanized agriculture, <strong>AgroVision Heavy Industries</strong> has grown into a globally respected manufacturer of heavy-duty agricultural implements. Operating out of an expansive 65-acre manufacturing facility, our integrated plant houses high-power fiber optic CNC laser cutting lines, automated robotic welding cells, computer-controlled induction heat treatment furnaces, and electrostatic powder coating plants.</p><h3>Mission & Vision</h3><p><strong>Our Mission:</strong> To empower farming communities worldwide by engineering resilient, fuel-efficient, and technologically superior agricultural machinery that maximizes crop yields while preserving natural resources.</p><p><strong>Our Vision:</strong> To be the most trusted global brand in agricultural implements, recognized for continuous innovation, uncompromising metallurgical quality, and farmer-first support.</p><h3>Global Footprint & Standards</h3><p>Accredited with ISO 9001:2015, CE European Conformity, and certified under leading international agricultural testing bodies, AgroVision machinery is exported to over 52 countries across North America, Europe, Africa, Australia, and Asia.</p>',
                    'meta_title' => 'About AgroVision Heavy Industries | Agricultural Machinery Pioneer',
                    'meta_description' => 'Discover the heritage, robotic manufacturing facilities, mission, and global dealership network of AgroVision Heavy Industries.',
                ],
                'hi' => [
                    'title' => 'हमारे बारे में',
                    'slug' => 'hamare-bare-mein',
                    'subtitle' => '1989 से कृषि मशीनरी में उत्कृष्ट इंजीनियरिंग का प्रतीक',
                    'excerpt' => 'एग्रोविज़न हेवी इंडस्ट्रीज, हमारे रोबोटिक निर्माण संयंत्र और खेती को आधुनिक बनाने के हमारे संकल्प के बारे में जानें।',
                    'content' => '<h3>हमारी विरासत और अनुभव</h3><p>खेती के आधुनिकीकरण और मशीनीकरण के उद्देश्य से स्थापित, <strong>एग्रोविज़न हेवी इंडस्ट्रीज</strong> आज कृषि उपकरणों के क्षेत्र में एक अग्रणी वैश्विक नाम है। हमारे 65 एकड़ में फैले विशाल संयंत्र में अत्याधुनिक सीएनसी फाइबर लेजर कटिंग, स्वचालित रोबोटिक वेल्डिंग, कंप्यूटर चालित हीट ट्रीटमेंट फर्नेस और इलेक्ट्रोस्टैटिक पाउडर कोटिंग की सुविधाएं उपलब्ध हैं।</p><h3>हमारा मिशन और दृष्टिकोण</h3><p><strong>हमारा मिशन:</strong> टिकाऊ, डीजल की बचत करने वाली और उच्च तकनीक वाली कृषि मशीनरी का निर्माण करके किसानों को सशक्त बनाना, जिससे कम लागत में अधिक पैदावार प्राप्त हो सके।</p><p><strong>हमारा दृष्टिकोण:</strong> नवाचार, बेहतर धातु गुणवत्ता और किसान-हितैषी सेवाओं के दम पर दुनिया का सबसे भरोसेमंद कृषि उपकरण ब्रांड बनना।</p><h3>वैश्विक उपस्थिति और गुणवत्ता मानक</h3><p>आईएसओ 9001:2015 और यूरोपीय सीई मानकों से प्रमाणित, एग्रोविज़न की मशीनें आज उत्तरी अमेरिका, यूरोप, अफ्रीका, ऑस्ट्रेलिया और एशिया के 52 से अधिक देशों में सफलता के साथ काम कर रही हैं।</p>',
                    'meta_title' => 'एग्रोविज़न हेवी इंडस्ट्रीज के बारे में | कृषि उपकरण निर्माता',
                    'meta_description' => 'एग्रोविज़न हेवी इंडस्ट्रीज का इतिहास, रोबोटिक मैन्युफैक्चरिंग प्लांट, विज़न और वैश्विक नेटवर्क।',
                ],
                'sections' => [],
            ],
            [
                'slug' => 'contact',
                'template' => 'contact',
                'is_published' => true,
                'order' => 3,
                'show_in_nav' => true,
                'en' => [
                    'title' => 'Contact Us',
                    'slug' => 'contact',
                    'subtitle' => 'We Are Here To Assist Your Agricultural Machinery Needs',
                    'excerpt' => 'Get in touch with AgroVision factory headquarters, sales advisors, or technical customer support.',
                    'content' => '<p>Whether you require machinery specifications, customized implement sizing, export dealership inquiries, or spare parts dispatch, our responsive corporate team is standing by to serve you.</p>',
                    'meta_title' => 'Contact AgroVision | Sales, Dealership & Support Inquiries',
                    'meta_description' => 'Contact AgroVision Heavy Industries. Corporate headquarters, phone, email, Google Maps directions, and online quotation form.',
                ],
                'hi' => [
                    'title' => 'संपर्क करें',
                    'slug' => 'sampark',
                    'subtitle' => 'कृषि मशीनरी संबंधी सहायता के लिए हम सदैव तत्पर हैं',
                    'excerpt' => 'एग्रोविज़न फैक्टरी मुख्यालय, बिक्री सलाहकारों या तकनीकी सहायता टीम से संपर्क करें।',
                    'content' => '<p>चाहे आपको मशीनरी के विनिर्देशों की जानकारी चाहिए, डीलरशिप पूछताछ हो या स्पेयर पार्ट्स की आवश्यकता, हमारी समर्पित टीम आपकी सहायता के लिए तैयार है।</p>',
                    'meta_title' => 'एग्रोविज़न से संपर्क करें | बिक्री, डीलरशिप और सहायता',
                    'meta_description' => 'एग्रोविज़न हेवी इंडस्ट्रीज का पता, फोन, ईमेल, गूगल मैप्स और ऑनलाइन पूछताछ फॉर्म।',
                ],
                'sections' => [],
            ],
            [
                'slug' => 'dealer-network',
                'template' => 'custom',
                'is_published' => true,
                'order' => 4,
                'show_in_nav' => true,
                'en' => [
                    'title' => 'Authorized Dealer Network',
                    'slug' => 'dealer-network',
                    'subtitle' => 'Locate An AgroVision Showroom & Service Center Near You',
                    'excerpt' => 'Find authorized dealers, test-drive implements, and access certified service technicians.',
                    'content' => '<p>With over 1,800 authorized retail and service partners across major agricultural belts, you are never far from certified AgroVision technicians, original boron wear spares, and genuine warranty support.</p><p>Interested in joining our prestigious international dealership network? Submit your profile through our contact page or speak directly with our Dealer Relations Directorate.</p>',
                    'meta_title' => 'Dealer Network | Locate AgroVision Dealerships & Service Centers',
                    'meta_description' => 'Find authorized AgroVision farm machinery dealerships, service centers, and genuine parts retailers near you.',
                ],
                'hi' => [
                    'title' => 'अधिकृत डीलर नेटवर्क',
                    'slug' => 'dealer-network-hi',
                    'subtitle' => 'अपने नजदीकी एग्रोविज़न शोरूम और सर्विस सेंटर की जानकारी पाएं',
                    'excerpt' => 'अधिकृत डीलरों से संपर्क करें, मशीनों का प्रदर्शन देखें और प्रमाणित सर्विस प्राप्त करें।',
                    'content' => '<p>प्रमुख कृषि क्षेत्रों में 1,800 से अधिक अधिकृत डीलरों के साथ, एग्रोविज़न की सेवाएं और असली स्पेयर पार्ट्स हमेशा आपके नजदीक उपलब्ध हैं।</p><p>क्या आप हमारे डीलरशिप नेटवर्क का हिस्सा बनना चाहते हैं? हमारे संपर्क पेज के माध्यम से अपना आवेदन भेजें।</p>',
                    'meta_title' => 'डीलर नेटवर्क | एग्रोविज़न शोरूम और सर्विस सेंटर',
                    'meta_description' => 'अपने निकटतम एग्रोविज़न डीलरशिप और सर्विस सेंटर खोजें।',
                ],
                'sections' => [],
            ],
            [
                'slug' => 'warranty-policy',
                'template' => 'custom',
                'is_published' => true,
                'order' => 5,
                'show_in_nav' => false,
                'en' => [
                    'title' => 'Warranty Policy',
                    'slug' => 'warranty-policy',
                    'subtitle' => 'Uncompromised Quality Guaranteed With Standard 1-Year Comprehensive Warranty',
                    'excerpt' => 'Review AgroVision implement warranty terms, coverage, gearbox guarantee, and claims procedures.',
                    'content' => '<p>Every genuine AgroVision agricultural implement is protected by our standard <strong>12-Month Comprehensive Manufacturer Warranty</strong> against defects in material and workmanship from the date of authorized purchase.</p><h4>Gearbox Extended Protection</h4><p>Heavy-duty multi-speed gearboxes and main drive assemblies carry an extended 24-month structural warranty when maintained with genuine recommended gear lubricants.</p><h4>Warranty Service Claim</h4><p>Warranty service is honored at any authorized dealership worldwide upon presenting your purchase invoice and serial plate number.</p>',
                    'meta_title' => 'Warranty Policy & Terms | AgroVision Heavy Industries',
                    'meta_description' => 'Comprehensive 1-year and 2-year warranty terms on AgroVision farm implements and heavy duty gearboxes.',
                ],
                'hi' => [
                    'title' => 'वारंटी नीति',
                    'slug' => 'warranty-niti',
                    'subtitle' => '1 वर्ष की व्यापक निर्माता वारंटी के साथ गुणवत्ता की पूरी गारंटी',
                    'excerpt' => 'एग्रोविज़न वारंटी शर्तें, गियरबॉक्स सुरक्षा और क्लेम प्रक्रिया की जानकारी।',
                    'content' => '<p>प्रत्येक असली एग्रोविज़न कृषि उपकरण खरीद की तारीख से <strong>12 महीने की व्यापक निर्माता वारंटी</strong> द्वारा सुरक्षित है।</p><h4>गियरबॉक्स विशेष सुरक्षा</h4><p>मल्टी-स्पीड गियरबॉक्स और मुख्य ट्रांसमिशन पर 24 महीने की विशेष संरचनात्मक वारंटी दी जाती है।</p><h4>वारंटी क्लेम प्रक्रिया</h4><p>वारंटी सेवा प्राप्त करने के लिए अपने नजदीकी अधिकृत डीलर से संपर्क करें और बिल व सीरियल नंबर प्रस्तुत करें।</p>',
                    'meta_title' => 'वारंटी नीति एवं नियम | एग्रोविज़न',
                    'meta_description' => 'एग्रोविज़न कृषि उपकरणों और गियरबॉक्स पर मिलने वाली वारंटी के नियम व शर्तें।',
                ],
                'sections' => [],
            ],
            [
                'slug' => 'privacy-policy',
                'template' => 'custom',
                'is_published' => true,
                'order' => 6,
                'show_in_nav' => false,
                'en' => [
                    'title' => 'Privacy Policy',
                    'slug' => 'privacy-policy',
                    'subtitle' => 'How We Protect And Respect Your Information',
                    'excerpt' => 'Our privacy guidelines regarding customer enquiry data, quotation requests, and website cookies.',
                    'content' => '<p>At AgroVision Heavy Industries, customer trust is paramount. This Privacy Policy details how we collect, use, and protect the contact details and specifications you submit through our enquiry and quotation forms.</p><ul><li>We never sell or distribute your contact details to unauthorized third parties.</li><li>Information submitted via quotation requests is solely used by our internal sales team and local authorized dealers to fulfill your machinery needs.</li><li>We enforce industry-standard SSL encryption and strict database security controls.</li></ul>',
                    'meta_title' => 'Privacy Policy | AgroVision Heavy Industries',
                    'meta_description' => 'Read how AgroVision protects and secures your customer information and quotation requests.',
                ],
                'hi' => [
                    'title' => 'गोपनीयता नीति (Privacy Policy)',
                    'slug' => 'gopniyata-niti',
                    'subtitle' => 'हम आपकी व्यक्तिगत जानकारी की सुरक्षा के प्रति पूरी तरह प्रतिबद्ध हैं',
                    'excerpt' => 'ग्राहक पूछताछ डेटा, कोटेशन अनुरोध और वेबसाइट कुकीज़ संबंधी हमारी नीति।',
                    'content' => '<p>एग्रोविज़न हेवी इंडस्ट्रीज में हम ग्राहकों के विश्वास का सम्मान करते हैं। हमारे पूछताछ फॉर्म द्वारा दी गई आपकी जानकारी पूरी तरह सुरक्षित रखी जाती है और इसका उपयोग केवल आपको मशीनरी कोटेशन व सेवा प्रदान करने के लिए किया जाता है।</p>',
                    'meta_title' => 'गोपनीयता नीति | एग्रोविज़न',
                    'meta_description' => 'एग्रोविज़न की गोपनीयता नीति और डेटा सुरक्षा नियम।',
                ],
                'sections' => [],
            ],
            [
                'slug' => 'terms-conditions',
                'template' => 'custom',
                'is_published' => true,
                'order' => 7,
                'show_in_nav' => false,
                'en' => [
                    'title' => 'Terms & Conditions',
                    'slug' => 'terms-conditions',
                    'subtitle' => 'General Website and Purchase Terms',
                    'excerpt' => 'Terms governing the use of this website, product specifications, and quotation inquiries.',
                    'content' => '<p>By accessing the AgroVision website or submitting quotation requests, you agree to comply with standard legal terms. Product specifications, weights, dimensions, and photographs are indicative and subject to ongoing engineering improvements without prior notice.</p>',
                    'meta_title' => 'Terms & Conditions | AgroVision Heavy Industries',
                    'meta_description' => 'Terms of service and legal conditions for AgroVision website visitors and equipment purchasers.',
                ],
                'hi' => [
                    'title' => 'नियम एवं शर्तें (Terms & Conditions)',
                    'slug' => 'niyam-aur-shartein',
                    'subtitle' => 'वेबसाइट उपयोग और सामान्य खरीद शर्तें',
                    'excerpt' => 'वेबसाइट उपयोग, मशीनरी विवरण और कोटेशन पूछताछ संबंधी नियम।',
                    'content' => '<p>एग्रोविज़न वेबसाइट का उपयोग करते समय आप सामान्य कानूनी शर्तों का पालन करने के लिए सहमत होते हैं। तकनीकी सुधार के तहत विनिर्देशों में परिवर्तन संभव है।</p>',
                    'meta_title' => 'नियम एवं शर्तें | एग्रोविज़न',
                    'meta_description' => 'एग्रोविज़न हेवी इंडस्ट्रीज के कानूनी नियम और उपयोग की शर्तें।',
                ],
                'sections' => [],
            ],
        ];

        foreach ($pagesData as $pData) {
            $page = Page::updateOrCreate(
                ['slug' => $pData['slug']],
                [
                    'template' => $pData['template'],
                    'is_published' => $pData['is_published'],
                    'order' => $pData['order'],
                    'show_in_nav' => $pData['show_in_nav'],
                ]
            );

            // EN Translation
            PageTranslation::updateOrCreate(
                ['page_id' => $page->id, 'locale' => 'en'],
                $pData['en']
            );

            // HI Translation
            PageTranslation::updateOrCreate(
                ['page_id' => $page->id, 'locale' => 'hi'],
                $pData['hi']
            );

            // Sections
            if (!empty($pData['sections'])) {
                PageSection::where('page_id', $page->id)->delete();
                foreach ($pData['sections'] as $sec) {
                    $section = PageSection::create([
                        'page_id' => $page->id,
                        'type' => $sec['type'],
                        'order' => $sec['order'],
                        'is_active' => $sec['is_active'],
                        'settings_json' => $sec['settings'] ?? null,
                    ]);

                    SectionTranslation::create([
                        'section_id' => $section->id,
                        'locale' => 'en',
                        'title' => $sec['en']['title'] ?? null,
                        'subtitle' => $sec['en']['subtitle'] ?? null,
                        'content' => $sec['en']['content'] ?? null,
                        'button_text' => $sec['en']['button_text'] ?? null,
                        'button_url' => $sec['en']['button_url'] ?? null,
                        'data_json' => $sec['en']['data_json'] ?? null,
                    ]);

                    SectionTranslation::create([
                        'section_id' => $section->id,
                        'locale' => 'hi',
                        'title' => $sec['hi']['title'] ?? null,
                        'subtitle' => $sec['hi']['subtitle'] ?? null,
                        'content' => $sec['hi']['content'] ?? null,
                        'button_text' => $sec['hi']['button_text'] ?? null,
                        'button_url' => $sec['hi']['button_url'] ?? null,
                        'data_json' => $sec['hi']['data_json'] ?? null,
                    ]);
                }
            }
        }

        // 9. Menus
        $menus = [
            [
                'location' => 'header',
                'name' => 'Main Navigation Menu',
                'items' => [
                    ['type' => 'custom', 'url' => '/', 'en' => 'Home', 'hi' => 'होम', 'order' => 1],
                    ['type' => 'custom', 'url' => '/about-us', 'en' => 'About Us', 'hi' => 'हमारे बारे में', 'order' => 2],
                    [
                        'type' => 'custom',
                        'url' => '/products',
                        'en' => 'Machinery & Implements',
                        'hi' => 'कृषि मशीनरी',
                        'order' => 3,
                        'children' => [
                            ['type' => 'custom', 'url' => '/products?category=rotary-tillers', 'en' => 'Rotary Tillers (Rotavators)', 'hi' => 'रोटावेटर', 'order' => 1],
                            ['type' => 'custom', 'url' => '/products?category=disc-harrows', 'en' => 'Disc Harrows & Ploughs', 'hi' => 'डिस्क हैरो एवं हल', 'order' => 2],
                            ['type' => 'custom', 'url' => '/products?category=cultivators-tillers', 'en' => 'Cultivators & Tillers', 'hi' => 'कल्टीवेटर', 'order' => 3],
                            ['type' => 'custom', 'url' => '/products?category=laser-land-levellers', 'en' => 'Laser Land Levellers', 'hi' => 'लेजर लैंड लेवलर', 'order' => 4],
                            ['type' => 'custom', 'url' => '/products?category=seeding-planting', 'en' => 'Seed Drills & Planters', 'hi' => 'सीड ड्रिल', 'order' => 5],
                            ['type' => 'custom', 'url' => '/products?category=harvest-straw-management', 'en' => 'Straw Reapers & Harvesters', 'hi' => 'स्ट्रॉ रीपर', 'order' => 6],
                        ],
                    ],
                    ['type' => 'custom', 'url' => '/dealer-network', 'en' => 'Dealer Network', 'hi' => 'डीलर नेटवर्क', 'order' => 4],
                    ['type' => 'custom', 'url' => '/contact', 'en' => 'Contact Us', 'hi' => 'संपर्क करें', 'order' => 5],
                ],
            ],
            [
                'location' => 'footer_quick',
                'name' => 'Footer Quick Links',
                'items' => [
                    ['type' => 'custom', 'url' => '/about-us', 'en' => 'Company Profile', 'hi' => 'कंपनी परिचय', 'order' => 1],
                    ['type' => 'custom', 'url' => '/dealer-network', 'en' => 'Locate a Dealer', 'hi' => 'डीलर खोजें', 'order' => 2],
                    ['type' => 'custom', 'url' => '/warranty-policy', 'en' => 'Warranty Coverage', 'hi' => 'वारंटी विवरण', 'order' => 3],
                    ['type' => 'custom', 'url' => '/contact', 'en' => 'Factory Headquarters', 'hi' => 'मुख्यालय संपर्क', 'order' => 4],
                ],
            ],
            [
                'location' => 'footer_products',
                'name' => 'Footer Machinery Links',
                'items' => [
                    ['type' => 'custom', 'url' => '/products/agrovision-gold-rotary-tiller-rotavator', 'en' => 'Gold Rotary Tillers', 'hi' => 'गोल्ड रोटावेटर', 'order' => 1],
                    ['type' => 'custom', 'url' => '/products/hydraulic-mounted-offset-disc-harrow', 'en' => 'Hydraulic Disc Harrows', 'hi' => 'हाइड्रोलिक डिस्क हैरो', 'order' => 2],
                    ['type' => 'custom', 'url' => '/products/precision-dual-slope-laser-land-leveller', 'en' => 'Dual-Slope Laser Levellers', 'hi' => 'लेजर लैंड लेवलर', 'order' => 3],
                    ['type' => 'custom', 'url' => '/products/zero-till-multi-crop-seed-cum-fertilizer-drill', 'en' => 'Zero-Till Seed Drills', 'hi' => 'जीरो-टिल सीड ड्रिल', 'order' => 4],
                    ['type' => 'custom', 'url' => '/products/hydraulic-reversible-mould-board-plough', 'en' => 'Reversible MB Ploughs', 'hi' => 'रिवर्सिबल एमबी हल', 'order' => 5],
                    ['type' => 'custom', 'url' => '/products/heavy-duty-tractor-straw-reaper', 'en' => 'Tractor Straw Reapers', 'hi' => 'स्ट्रॉ रीपर भूसा मशीन', 'order' => 6],
                ],
            ],
            [
                'location' => 'footer_legal',
                'name' => 'Footer Legal Links',
                'items' => [
                    ['type' => 'custom', 'url' => '/privacy-policy', 'en' => 'Privacy Policy', 'hi' => 'गोपनीयता नीति', 'order' => 1],
                    ['type' => 'custom', 'url' => '/terms-conditions', 'en' => 'Terms of Service', 'hi' => 'नियम एवं शर्तें', 'order' => 2],
                    ['type' => 'custom', 'url' => '/warranty-policy', 'en' => 'Warranty Policy', 'hi' => 'वारंटी नीति', 'order' => 3],
                ],
            ],
        ];

        foreach ($menus as $m) {
            $menu = Menu::updateOrCreate(['location' => $m['location']], [
                'name' => $m['name'],
                'is_active' => true,
            ]);

            MenuItem::where('menu_id', $menu->id)->delete();

            foreach ($m['items'] as $itemData) {
                $item = MenuItem::create([
                    'menu_id' => $menu->id,
                    'parent_id' => null,
                    'type' => $itemData['type'],
                    'target_url' => $itemData['url'],
                    'order' => $itemData['order'],
                    'is_active' => true,
                ]);

                MenuItemTranslation::create([
                    'menu_item_id' => $item->id,
                    'locale' => 'en',
                    'title' => $itemData['en'],
                    'url' => $itemData['url'],
                ]);

                MenuItemTranslation::create([
                    'menu_item_id' => $item->id,
                    'locale' => 'hi',
                    'title' => $itemData['hi'],
                    'url' => $itemData['url'],
                ]);

                if (!empty($itemData['children'])) {
                    foreach ($itemData['children'] as $childData) {
                        $child = MenuItem::create([
                            'menu_id' => $menu->id,
                            'parent_id' => $item->id,
                            'type' => $childData['type'],
                            'target_url' => $childData['url'],
                            'order' => $childData['order'],
                            'is_active' => true,
                        ]);

                        MenuItemTranslation::create([
                            'menu_item_id' => $child->id,
                            'locale' => 'en',
                            'title' => $childData['en'],
                            'url' => $childData['url'],
                        ]);

                        MenuItemTranslation::create([
                            'menu_item_id' => $child->id,
                            'locale' => 'hi',
                            'title' => $childData['hi'],
                            'url' => $childData['url'],
                        ]);
                    }
                }
            }
        }

        // 10. Sample Enquiries
        Enquiry::truncate();
        EnquiryNote::truncate();

        $firstProduct = Product::first();
        $sampleEnquiries = [
            [
                'product_id' => $firstProduct ? $firstProduct->id : null,
                'name' => 'Karanvir Singh Bajwa',
                'email' => 'karanvir.bajwa@example.com',
                'phone' => '+91 98140 12345',
                'company' => 'Bajwa Agro Farms & Contracting',
                'country' => 'India',
                'city' => 'Ludhiana, Punjab',
                'subject' => 'Quotation request for 5 units of 7ft Gold Rotavator',
                'message' => 'We are updating our contracting fleet for the upcoming wheat season. We operate 55 HP John Deere tractors. Please provide dealer pricing for 5 units of the AgroVision Gold 7-foot rotary tiller with boron blades.',
                'locale' => 'en',
                'status' => 'unread',
            ],
            [
                'product_id' => null,
                'name' => 'Mukesh Bhai Patel',
                'email' => 'patel.farms@example.com',
                'phone' => '+91 98250 54321',
                'company' => 'Patel Agricultural Services',
                'country' => 'India',
                'city' => 'Rajkot, Gujarat',
                'subject' => 'लेजर लैंड लेवलर की कीमत और सब्सिडी की जानकारी',
                'message' => 'नमस्ते, हमारे पास 60 एचपी का स्वराज ट्रैक्टर है। हमें डुअल स्लोप लेजर लैंड लेवलर खरीदना है। क्या इस पर सरकारी सब्सिडी उपलब्ध है और राजकोट में आपके डीलर का संपर्क नंबर दें।',
                'locale' => 'hi',
                'status' => 'read',
            ],
            [
                'product_id' => count($createdProducts) >= 2 ? $createdProducts[1]->id : null,
                'name' => 'Carlos Mendoza',
                'email' => 'cmendoza@agroimport.mx',
                'phone' => '+52 55 1234 5678',
                'company' => 'Mendoza Importaciones Agricolas S.A.',
                'country' => 'Mexico',
                'city' => 'Guadalajara',
                'subject' => 'Container Export Inquiries for Hydraulic Disc Harrows',
                'message' => 'We are interested in importing a 40-foot container of your 20-disc hydraulic offset harrows to Mexico. Please share your FOB Nhava Sheva price list and export catalogue.',
                'locale' => 'en',
                'status' => 'contacted',
            ],
        ];

        foreach ($sampleEnquiries as $enqData) {
            $enquiry = Enquiry::create($enqData);

            if ($enquiry->status === 'contacted') {
                EnquiryNote::create([
                    'enquiry_id' => $enquiry->id,
                    'user_id' => $users[3] ? User::where('email', 'enquiry@agrovision.com')->first()->id : null,
                    'note' => 'Sent export FOB price list and technical specification sheets via email. Scheduled follow-up call for next Tuesday with regional sales director.',
                ]);
            }
        }

        Schema::enableForeignKeyConstraints();
    }
}
