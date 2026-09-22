<?php

namespace Database\Seeders;

use App\Models\AiClassMapping;
use App\Models\EducationContent;
use App\Models\ImpactFactor;
use App\Models\Partner;
use App\Models\User;
use App\Models\WasteCategory;
use App\Models\WastePrice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ────────────────────────────────────────────────────────────

        $admin = User::updateOrCreate(
            ['email' => 'admin@ecocash.test'],
            [
                'name'              => 'Admin ECOCASH',
                'password'          => Hash::make('password'),
                'role'              => 'admin',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@ecocash.test'],
            [
                'name'              => 'Demo Pengguna',
                'password'          => Hash::make('password'),
                'role'              => 'user',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'partner@ecocash.test'],
            [
                'name'              => 'Demo Mitra',
                'password'          => Hash::make('password'),
                'role'              => 'bank_partner',
                'email_verified_at' => now(),
            ]
        );

        // ── Partners ─────────────────────────────────────────────────────────

        $partner1 = Partner::updateOrCreate(
            ['name' => 'Bank Sampah Hijau'],
            [
                'address'   => 'Jakarta Selatan',
                'latitude'  => -6.2615,
                'longitude' => 106.8106,
            ]
        );

        $partner2 = Partner::updateOrCreate(
            ['name' => 'Bank Sampah Mandiri'],
            [
                'address'   => 'Jakarta Timur',
                'latitude'  => -6.2297,
                'longitude' => 106.9001,
            ]
        );

        // ── Waste categories ─────────────────────────────────────────────────

        $categories = [
            [
                'name'                => 'Plastik (PET)',
                'material'            => 'PET',
                'sorting_instruction' => 'Kosongkan isi, bilas bersih, keringkan, dan pisahkan tutupnya sebelum disetor.',
                'processing_route'    => 'Daur ulang mekanis',
                'impact_factor'       => 0.35,
                'price'               => 4000,
                'co2e_per_kg'         => 0.35,
            ],
            [
                'name'                => 'Kertas (Campuran)',
                'material'            => 'Kertas',
                'sorting_instruction' => 'Pastikan kertas kering dan bebas dari sisa makanan atau minyak.',
                'processing_route'    => 'Daur ulang pulp',
                'impact_factor'       => 0.55,
                'price'               => 2500,
                'co2e_per_kg'         => 0.55,
            ],
            [
                'name'                => 'Logam/Kaleng (Aluminium)',
                'material'            => 'Aluminium',
                'sorting_instruction' => 'Bersihkan dari isi, pipihkan kaleng jika memungkinkan.',
                'processing_route'    => 'Peleburan logam',
                'impact_factor'       => 1.20,
                'price'               => 8000,
                'co2e_per_kg'         => 1.20,
            ],
            [
                'name'                => 'Kaca (Glass)',
                'material'            => 'Kaca',
                'sorting_instruction' => 'Bersihkan dari sisa isi, pisahkan dari benda tajam lain.',
                'processing_route'    => 'Daur ulang kaca',
                'impact_factor'       => 0.31,
                'price'               => 500,
                'co2e_per_kg'         => 0.31,
            ],
            [
                'name'                => 'Elektronik (E-waste)',
                'material'            => 'Elektronik',
                'sorting_instruction' => 'Jangan rusak baterai. Setor ke mitra yang memiliki fasilitas e-waste.',
                'processing_route'    => 'Fasilitas e-waste bersertifikat',
                'impact_factor'       => 2.10,
                'price'               => 0,
                'co2e_per_kg'         => 2.10,
            ],
        ];

        $savedCategories = [];
        foreach ($categories as $catData) {
            $category = WasteCategory::updateOrCreate(
                ['name' => $catData['name']],
                [
                    'material'            => $catData['material'],
                    'sorting_instruction' => $catData['sorting_instruction'],
                    'processing_route'    => $catData['processing_route'],
                    'impact_factor'       => $catData['impact_factor'],
                ]
            );
            $savedCategories[] = ['model' => $category, 'price' => $catData['price'], 'co2e_per_kg' => $catData['co2e_per_kg']];
        }

        // ── AI class mappings ─────────────────────────────────────────────────

        $plasticCategory = $savedCategories[0]['model'];
        $paperCategory   = $savedCategories[1]['model'];
        $metalCategory   = $savedCategories[2]['model'];

        $aiMappings = [
            ['class_id' => 0, 'class_name' => 'plastic_bottle', 'category' => $plasticCategory],
            ['class_id' => 1, 'class_name' => 'paper',          'category' => $paperCategory],
            ['class_id' => 2, 'class_name' => 'metal_can',      'category' => $metalCategory],
        ];

        foreach ($aiMappings as $mapping) {
            AiClassMapping::updateOrCreate(
                ['class_id' => $mapping['class_id'], 'class_name' => $mapping['class_name']],
                ['waste_category_id' => $mapping['category']->id]
            );
        }

        // ── Prices & impact factors for each category ──────────────────────────

        foreach ($savedCategories as $item) {
            $category = $item['model'];

            // Price for partner 1
            WastePrice::updateOrCreate(
                [
                    'waste_category_id' => $category->id,
                    'partner_id'        => $partner1->id,
                    'effective_from'    => '2026-01-01',
                ],
                [
                    'price_per_kg'    => $item['price'],
                    'effective_until' => null,
                    'is_active'       => true,
                ]
            );

            // Price for partner 2
            WastePrice::updateOrCreate(
                [
                    'waste_category_id' => $category->id,
                    'partner_id'        => $partner2->id,
                    'effective_from'    => '2026-01-01',
                ],
                [
                    'price_per_kg'    => $item['price'],
                    'effective_until' => null,
                    'is_active'       => true,
                ]
            );

            // Impact factor
            ImpactFactor::updateOrCreate(
                ['waste_category_id' => $category->id],
                [
                    'co2e_per_kg' => $item['co2e_per_kg'],
                    'source'      => 'KLHK / ECOCASH Internal Estimate 2026',
                    'is_active'   => true,
                ]
            );
        }

        // ── Partner–category links ─────────────────────────────────────────────

        foreach ([$partner1, $partner2] as $partner) {
            foreach ($savedCategories as $item) {
                DB::table('partner_waste_categories')->updateOrInsert(
                    [
                        'partner_id'        => $partner->id,
                        'waste_category_id' => $item['model']->id,
                    ]
                );
            }
        }

        // ── Education content ─────────────────────────────────────────────────

        $educationArticles = [
            [
                'title'        => 'Cara Memilah Sampah Plastik di Rumah',
                'category'     => 'sorting_guide',
                'slug'         => 'cara-memilah-sampah-plastik-di-rumah',
                'summary'      => 'Panduan langkah demi langkah untuk memilah sampah plastik jenis PET, HDPE, dan PP agar siap disetor ke bank sampah.',
                'body'         => '<p>Memilah sampah plastik di rumah tidak harus rumit. Mulai dari mengenali kode segitiga di bawah kemasan hingga cara membilas dan mengeringkan botol sebelum disetor.</p><h3>Langkah-langkah:</h3><ol><li>Cek kode plastik di bagian bawah kemasan.</li><li>Kosongkan dan bilas bersih.</li><li>Keringkan sebelum dikumpulkan.</li><li>Pisahkan tutup botol dari badan botol.</li></ol>',
                'is_published' => true,
            ],
            [
                'title'        => 'Mengapa E-waste Berbahaya jika Dibuang Sembarangan?',
                'category'     => 'waste_info',
                'slug'         => 'mengapa-e-waste-berbahaya-jika-dibuang-sembarangan',
                'summary'      => 'Sampah elektronik mengandung timbal, merkuri, dan kadmium yang dapat mencemari tanah dan air jika tidak ditangani dengan benar.',
                'body'         => '<p>E-waste atau sampah elektronik adalah salah satu kategori sampah yang paling berbahaya. Ponsel, laptop, dan baterai mengandung logam berat yang bisa meresap ke tanah dan sumber air.</p><p>Setor e-waste Anda ke mitra ECOCASH yang memiliki fasilitas penanganan khusus.</p>',
                'is_published' => true,
            ],
            [
                'title'        => '5 Tips Kurangi Sampah Sehari-hari Tanpa Harus Jadi Zero-Waste Ekstrem',
                'category'     => 'tips',
                'slug'         => '5-tips-kurangi-sampah-sehari-hari',
                'summary'      => 'Tidak perlu drastis. Lima langkah kecil ini sudah cukup untuk mengurangi jejak sampah harian Anda secara signifikan.',
                'body'         => '<p>Mengurangi sampah tidak harus mulai dari ekstrem. Berikut lima langkah kecil yang bisa langsung diterapkan:</p><ol><li>Bawa tas belanja sendiri.</li><li>Tolak sedotan plastik sekali pakai.</li><li>Pilih produk dengan kemasan minimal.</li><li>Kompos sisa sayuran dan buah.</li><li>Setor sampah daur ulang ke bank sampah terdekat.</li></ol>',
                'is_published' => true,
            ],
        ];

        foreach ($educationArticles as $article) {
            EducationContent::updateOrCreate(
                ['slug' => $article['slug']],
                array_merge($article, ['author_id' => $admin->id])
            );
        }

        // ── Levels ────────────────────────────────────────────────────────────

        $levels = [
            ['name' => 'Pemilah Pemula',     'description' => 'Memulai langkah awal memilah sampah.', 'minimum_xp' => 0,    'badge_icon' => 'bi-flower1', 'level_order' => 1],
            ['name' => 'Pemilah Aktif',      'description' => 'Mulai rutin menyortir dan menyetor.',   'minimum_xp' => 200,  'badge_icon' => 'bi-recycle',  'level_order' => 2],
            ['name' => 'Penjaga Lingkungan', 'description' => 'Konsisten menjaga kebersihan bumi.',   'minimum_xp' => 600,  'badge_icon' => 'bi-shield-check', 'level_order' => 3],
            ['name' => 'Pejuang Daur Ulang', 'description' => 'Menggerakkan dampak nyata daur ulang.','minimum_xp' => 1500, 'badge_icon' => 'bi-award',   'level_order' => 4],
            ['name' => 'Sahabat Lingkungan', 'description' => 'Pahlawan sirkular dan inspirasi.',    'minimum_xp' => 3500, 'badge_icon' => 'bi-trophy',  'level_order' => 5],
        ];

        foreach ($levels as $lvl) {
            \App\Models\Level::updateOrCreate(
                ['level_order' => $lvl['level_order']],
                $lvl
            );
        }

        // ── Withdrawal Methods ─────────────────────────────────────────────────

        $withdrawalMethods = [
            ['name' => 'BCA (Bank Central Asia)', 'type' => 'bank',    'code' => 'BCA',     'min_amount' => 10000, 'fee' => 0],
            ['name' => 'Bank Mandiri',            'type' => 'bank',    'code' => 'MANDIRI', 'min_amount' => 10000, 'fee' => 0],
            ['name' => 'Bank BRI',                'type' => 'bank',    'code' => 'BRI',     'min_amount' => 10000, 'fee' => 0],
            ['name' => 'GoPay',                   'type' => 'ewallet', 'code' => 'GOPAY',   'min_amount' => 10000, 'fee' => 0],
            ['name' => 'DANA',                    'type' => 'ewallet', 'code' => 'DANA',    'min_amount' => 10000, 'fee' => 0],
            ['name' => 'OVO',                     'type' => 'ewallet', 'code' => 'OVO',     'min_amount' => 10000, 'fee' => 0],
        ];

        foreach ($withdrawalMethods as $wm) {
            \App\Models\WithdrawalMethod::updateOrCreate(
                ['code' => $wm['code']],
                $wm
            );
        }

        // ── Missions ───────────────────────────────────────────────────────────

        $missions = [
            [
                'title'           => 'Scan Sampah Pertamamu',
                'description'     => 'Ambil foto sampah di sekitarmu dengan AI Scanner untuk mendeteksi jenisnya.',
                'type'            => 'daily',
                'action_type'     => 'scan_count',
                'target_count'    => 1,
                'reward_xp'       => 50,
                'reward_ecopoint' => 5,
            ],
            [
                'title'           => 'Setor 1 Kali Minggu Ini',
                'description'     => 'Kunjungi mitra bank sampah terdekat dan lakukan 1 kali setoran sampah terpilah.',
                'type'            => 'weekly',
                'action_type'     => 'deposit_count',
                'target_count'    => 1,
                'reward_xp'       => 150,
                'reward_ecopoint' => 15,
            ],
            [
                'title'           => 'Misi Berburu Sampah Botol PET',
                'description'     => 'Kumpulkan 5 botol plastik bersih dari rumah atau lingkungan sekitar yang aman.',
                'type'            => 'hunting',
                'action_type'     => 'scan_count',
                'target_count'    => 5,
                'reward_xp'       => 100,
                'reward_ecopoint' => 10,
            ],
            [
                'title'           => 'Pelajari Panduan Pilah',
                'description'     => 'Baca 1 artikel edukasi lingkungan untuk memahami proses daur ulang yang tepat.',
                'type'            => 'daily',
                'action_type'     => 'education_read',
                'target_count'    => 1,
                'reward_xp'       => 30,
                'reward_ecopoint' => 0,
            ],
        ];

        foreach ($missions as $m) {
            \App\Models\Mission::updateOrCreate(
                ['title' => $m['title']],
                $m
            );
        }

        // ── Achievements ───────────────────────────────────────────────────────

        $achievements = [
            [
                'title'           => 'Langkah Pertama',
                'description'     => 'Menyelesaikan setoran sampah terverifikasi pertama kali.',
                'icon'            => 'bi-flag-fill',
                'criterion_type'  => 'first_deposit',
                'criterion_value' => 1,
                'reward_xp'       => 100,
            ],
            [
                'title'           => 'Pemilah Tangguh (5 Setoran)',
                'description'     => 'Telah berhasil menyelesaikan 5 kali setoran sampah terverifikasi.',
                'icon'            => 'bi-recycle',
                'criterion_type'  => 'total_deposits',
                'criterion_value' => 5,
                'reward_xp'       => 250,
            ],
            [
                'title'           => '10 Kilogram Pertama',
                'description'     => 'Menyalurkan akumulasi 10 kg sampah terpilah ke bank sampah.',
                'icon'            => 'bi-box-seam-fill',
                'criterion_type'  => 'total_weight',
                'criterion_value' => 10,
                'reward_xp'       => 300,
            ],
            [
                'title'           => 'Pahlawan Lingkungan (50 Kg)',
                'description'     => 'Menyelamatkan lingkungan dengan menyalurkan lebih dari 50 kg sampah terdaur ulang.',
                'icon'            => 'bi-globe-americas',
                'criterion_type'  => 'total_weight',
                'criterion_value' => 50,
                'reward_xp'       => 1000,
            ],
        ];

        foreach ($achievements as $ach) {
            \App\Models\Achievement::updateOrCreate(
                ['title' => $ach['title']],
                $ach
            );
        }

        // ── Pastikan User Demo memiliki akun dompet awal ────────────────────────
        $demoUser = User::where('email', 'user@ecocash.test')->first();
        if ($demoUser) {
            \App\Models\WalletAccount::firstOrCreate(
                ['user_id' => $demoUser->id],
                ['balance' => 0, 'pending_balance' => 0]
            );
        }
    }
}
