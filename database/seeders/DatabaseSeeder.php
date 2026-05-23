<?php

namespace Database\Seeders;

use App\Models\CmsContent;
use App\Models\Product;
use App\Models\Machine;
use App\Models\Client;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 0. Create Admin User
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@csa.com'],
            [
                'name' => 'Administrator',
                'password' => \Illuminate\Support\Facades\Hash::make('password')
            ]
        );

        // 1. Hero & Stats Section
        CmsContent::updateOrCreate(
            ['key' => 'hero_badge'],
            ['section' => 'hero', 'value' => 'IATF 16949 & ISO 9001:2015 Certified']
        );
        CmsContent::updateOrCreate(
            ['key' => 'hero_title'],
            ['section' => 'hero', 'value' => 'PRECISION METAL STAMPING']
        );
        CmsContent::updateOrCreate(
            ['key' => 'hero_tagline'],
            ['section' => 'hero', 'value' => 'SOLUTIONS FOR INDUSTRY']
        );
        CmsContent::updateOrCreate(
            ['key' => 'hero_desc'],
            ['section' => 'hero', 'value' => 'PT. Cahaya Sentosa Abadi adalah produsen komponen stamping logam presisi tinggi yang melayani industri otomotif, elektronik, dan manufaktur sejak tahun 1998. Berlokasi di Kawasan Industri MM2100, Cikarang, Jawa Barat.']
        );
        CmsContent::updateOrCreate(
            ['key' => 'hero_stats'],
            [
                'section' => 'hero',
                'value' => json_encode([
                    ['num' => '26', 'unit' => '+', 'label' => 'Tahun Pengalaman'],
                    ['num' => '80', 'unit' => '+', 'label' => 'Unit Press Machine'],
                    ['num' => '500', 'unit' => 'T', 'label' => 'Kapasitas Maks.'],
                    ['num' => '0.1', 'unit' => '%', 'label' => 'Defect Rate'],
                    ['num' => '200', 'unit' => '+', 'label' => 'Klien Aktif'],
                    ['num' => '±0.05', 'unit' => 'mm', 'label' => 'Toleransi Presisi']
                ])
            ]
        );
        CmsContent::updateOrCreate(
            ['key' => 'hero_certs'],
            [
                'section' => 'hero',
                'value' => json_encode(['ISO 9001:2015', 'IATF 16949', 'ISO 14001', '5S & Kaizen'])
            ]
        );

        // 2. Ticker Ribbon
        CmsContent::updateOrCreate(
            ['key' => 'ticker_items'],
            [
                'section' => 'ticker',
                'value' => json_encode([
                    'Progressive Die Stamping',
                    'Transfer Die Stamping',
                    'Deep Drawing',
                    'Fine Blanking',
                    'Tool & Die Making',
                    'Roll Forming',
                    'Compound Die Stamping',
                    'Surface Treatment'
                ])
            ]
        );

        // 3. About Section
        CmsContent::updateOrCreate(
            ['key' => 'about_title'],
            ['section' => 'about', 'value' => 'Pengalaman & Kepercayaan Sejak 1998']
        );
        CmsContent::updateOrCreate(
            ['key' => 'about_desc_1'],
            ['section' => 'about', 'value' => 'PT. Cahaya Sentosa Abadi didirikan pada tahun 1998 sebagai perusahaan manufaktur komponen stamping logam di kawasan industri Cikarang, Jawa Barat. Selama lebih dari dua dekade, kami telah berkembang menjadi salah satu mitra stamping terpercaya bagi perusahaan-perusahaan multinasional di Indonesia.']
        );
        CmsContent::updateOrCreate(
            ['key' => 'about_desc_2'],
            ['section' => 'about', 'value' => 'Dengan lebih dari 500 karyawan terlatih dan fasilitas produksi seluas 35.000 m², kami mampu memenuhi kebutuhan komponen presisi dalam volume besar dengan konsistensi kualitas tinggi dan tepat waktu.']
        );
        CmsContent::updateOrCreate(
            ['key' => 'about_highlights'],
            [
                'section' => 'about',
                'value' => json_encode([
                    ['title' => 'Lokasi Strategis', 'desc' => 'MM2100 Industrial Town, Cikarang Barat, Bekasi', 'icon' => 'ti-map-pin'],
                    ['title' => '500+ Karyawan Terlatih', 'desc' => 'Teknisi, engineer, dan QC berpengalaman', 'icon' => 'ti-users'],
                    ['title' => 'Fasilitas 35.000 m²', 'desc' => 'Area produksi, tool room, gudang, dan QC lab', 'icon' => 'ti-building-factory-2']
                ])
            ]
        );
        CmsContent::updateOrCreate(
            ['key' => 'about_visi'],
            ['section' => 'about', 'value' => 'Menjadi perusahaan stamping logam terkemuka di Asia Tenggara yang dikenal atas presisi, keandalan, dan inovasi dalam setiap komponen yang kami produksi.']
        );
        CmsContent::updateOrCreate(
            ['key' => 'about_misi'],
            [
                'section' => 'about',
                'value' => json_encode([
                    'Menghasilkan komponen stamping berkualitas tinggi dengan toleransi presisi sesuai standar industri',
                    'Menerapkan sistem manajemen mutu IATF 16949 secara konsisten',
                    'Mengembangkan sumber daya manusia yang kompeten dan berkarakter',
                    'Memberikan layanan terbaik kepada pelanggan dengan pengiriman tepat waktu',
                    'Menjalankan operasional yang ramah lingkungan sesuai ISO 14001'
                ])
            ]
        );

        // 4. Products Capabilities Header CMS
        CmsContent::updateOrCreate(
            ['key' => 'products_eyebrow'],
            ['section' => 'products', 'value' => 'LAYANAN & PRODUK']
        );
        CmsContent::updateOrCreate(
            ['key' => 'products_title'],
            ['section' => 'products', 'value' => 'Kemampuan Stamping Kami']
        );
        CmsContent::updateOrCreate(
            ['key' => 'products_sub'],
            ['section' => 'products', 'value' => 'Kami menyediakan solusi stamping lengkap mulai dari desain die hingga produksi massal.']
        );

        // Seed 4b. PRODUCTS Relational Table
        Product::truncate();
        Product::create([
            'tag' => 'HIGH VOLUME',
            'name' => 'Progressive Stamping',
            'desc' => 'Proses stamping berkelanjutan dengan beberapa stasiun die untuk produksi volume tinggi secara efisien.',
            'specs' => ['±0.05mm toleransi', 'Vol. tinggi', 'Otomatis'],
            'icon' => 'ti-adjustments-horizontal',
            'tolerance' => '±0.05 mm (High Precision)',
            'capacity' => '45 Ton — 300 Ton',
            'speed' => '45 — 120 strokes / m',
            'volume' => 'High Volume Mass',
            'auxiliary' => 'Peralatan decoiler loops otomatis, roller straightener leveling loops, pelumas otomatis mikro spray.',
            'safety' => 'Tirai cahaya optoelektronik keselamatan kerja, sensor deteksi penumpukan pelat ganda, rem kopling pneumatik darurat.',
            'typical' => 'Terminal kelistrikan kuningan, klip pegas motor, braket mesin mobil, EMI shielding electronics housing.'
        ]);
        Product::create([
            'tag' => 'COMPLEX SHAPE',
            'name' => 'Deep Drawing',
            'desc' => 'Proses pembentukan lembaran logam menjadi komponen berbentuk cup, silinder, atau shell tanpa sambungan.',
            'specs' => ['Tanpa sambungan', 'Ketebalan seragam'],
            'icon' => 'ti-arrow-down-circle',
            'tolerance' => '±0.10 mm (Deep drawing flow)',
            'capacity' => '100 Ton — 500 Ton',
            'speed' => '15 — 35 strokes / m',
            'volume' => 'Medium to High Parts',
            'auxiliary' => 'Bantalan hidrolik kontrol tekanan, pengontrol ketebalan lembaran, sistem pembentuk tanpa sambungan khusus.',
            'safety' => 'Tirai cahaya optoelektronik keselamatan kerja, sensor deteksi penumpukan pelat ganda, rem kopling pneumatik darurat.',
            'typical' => 'Baterai EV shell cups, filter oli silinder tanpa sambungan, tabung pemadam api, tutup tangki BBM.'
        ]);
        Product::create([
            'tag' => 'PRECISION CUT',
            'name' => 'Fine Blanking',
            'desc' => 'Pemotongan presisi dengan permukaan shear yang halus dan rata tanpa memerlukan proses finishing tambahan.',
            'specs' => ['Permukaan halus', 'No finishing'],
            'icon' => 'ti-scissors',
            'tolerance' => '±0.01 mm (Micro fine clearance)',
            'capacity' => '80 Ton — 250 Ton',
            'speed' => '60 — 180 strokes / m',
            'volume' => 'Safety-Critical Precision',
            'auxiliary' => 'Penjepit pilot mikro hidrolik presisi tinggi, fine blanking punch clearances, die ejector pad assemblies.',
            'safety' => 'Tirai cahaya optoelektronik keselamatan kerja, sensor deteksi penumpukan pelat ganda, rem kopling pneumatik darurat.',
            'typical' => 'Roda gigi transmisi presisi halus, kopling cakram, pelat katup pengereman safety, spacer mesin mikro.'
        ]);
        Product::create([
            'tag' => 'MULTI-STEP',
            'name' => 'Transfer Stamping',
            'desc' => 'Proses multi-tahap dengan transfer otomatis antar stasiun, ideal untuk komponen berukuran besar dan kompleks.',
            'specs' => ['Komponen besar', 'Transfer otomatis'],
            'icon' => 'ti-repeat',
            'tolerance' => '±0.15 mm (Transfer layout spec)',
            'capacity' => '200 Ton — 500 Ton',
            'speed' => '12 — 30 strokes / m',
            'volume' => 'Large Frame Structural',
            'auxiliary' => 'Robot manipulator lengan transfer otomatis, stasiun pengubah die cepat, pilot indexing pins.',
            'safety' => 'Tirai cahaya optoelektronik keselamatan kerja, sensor deteksi penumpukan pelat ganda, rem kopling pneumatik darurat.',
            'typical' => 'Braket penguat sasis otomotif ukuran besar, panel pintu struktural, penutup kompresor pendingin udara.'
        ]);
        Product::create([
            'tag' => 'IN-HOUSE',
            'name' => 'Tool & Die Making',
            'desc' => 'Desain dan fabrikasi die in-house menggunakan CAD/CAM dan mesin CNC presisi tinggi untuk semua jenis tooling.',
            'specs' => ['CAD/CAM', 'CNC Machining'],
            'icon' => 'ti-tool',
            'tolerance' => '±0.002 mm (CNC Tooling precision)',
            'capacity' => 'CNC travel clearance',
            'speed' => 'Up to 24,000 RPM Spindle',
            'volume' => 'Custom Prototype & Die',
            'auxiliary' => 'Sistem CAD/CAM die design modeling, wire cut EDM generator sub-micron, multi-axis CNC stage fixtures.',
            'safety' => 'Pintu kabin tertutup sensor interlock laser, sensor pendeteksi getaran poros spindel spindel, sensor overload motor CNC.',
            'typical' => 'Pembuatan progressive dies set, stage tooling dies, transfer die arm fixtures, CNC custom molds presisi.'
        ]);
        Product::create([
            'tag' => 'FINISHING',
            'name' => 'Surface Treatment',
            'desc' => 'Layanan electroplating, powder coating, dan phosphating untuk meningkatkan ketahanan korosi dan estetika produk.',
            'specs' => ['Electroplating', 'Powder Coating'],
            'icon' => 'ti-droplets',
            'tolerance' => 'AS9100 / ASTM coating rating',
            'capacity' => 'Electroplating conveyorized',
            'speed' => 'Realtime Continuous Line',
            'volume' => 'Outbound Finished Coating',
            'auxiliary' => 'Bak pelapis electroplating kimia otomatis, oven conveyorized powder coating, spray pretreatment loops.',
            'safety' => 'Pembersih udara filter hisap uap kimia asam, pengaman katup pelepas gas otomatis, sensor kebocoran kimia.',
            'typical' => 'Pelapisan anti-karat komponen sasis, powder coating casing elektronik, zinc plating terminal tembaga.'
        ]);

        // 5. Production Facility CMS Header
        CmsContent::updateOrCreate(
            ['key' => 'machines_eyebrow'],
            ['section' => 'machines', 'value' => 'FASILITAS PRODUKSI']
        );
        CmsContent::updateOrCreate(
            ['key' => 'machines_title'],
            ['section' => 'machines', 'value' => 'Mesin & Peralatan']
        );
        CmsContent::updateOrCreate(
            ['key' => 'machines_sub'],
            ['section' => 'machines', 'value' => 'Dilengkapi mesin press modern dengan berbagai kapasitas untuk memenuhi kebutuhan produksi Anda.']
        );
        CmsContent::updateOrCreate(
            ['key' => 'machines_facilities'],
            [
                'section' => 'machines',
                'value' => json_encode([
                    ['name' => 'CMM Room', 'desc' => 'Coordinate measuring machine', 'icon' => 'ti-microscope'],
                    ['name' => '3D Scanner', 'desc' => 'Reverse engineering & inspection', 'icon' => 'ti-scan'],
                    ['name' => 'Heat Treatment', 'desc' => 'Hardening & tempering', 'icon' => 'ti-temperature'],
                    ['name' => 'Automated WH', 'desc' => 'Automated warehouse system', 'icon' => 'ti-package']
                ])
            ]
        );

        // Seed 5b. MACHINES Relational Table
        Machine::truncate();
        Machine::create([
            'name' => 'Mechanical Press',
            'spec' => 'Kapasitas 30T — 300T',
            'qty' => 24,
            'icon' => 'ti-settings-2',
            'longDesc' => 'Armada mesin press mekanikal kami merupakan tulang punggung produksi massal dengan kecepatan ketukan tinggi. Dilengkapi dengan unit otomatisasi rol koil pengumpan (feeder lines) untuk mendukung efisiensi pengerjaan tinggi.',
            'origin' => 'Jepang / Taiwan (AIDA, Seyi)',
            'precision' => 'JIS B 6402 Grade 1 Accuracy',
            'safety' => 'Dual-valve pneumatic clutch & optoelectronic light curtains keselamatan kerja otomatis, interlock overload sensors.',
            'application' => 'Braket otomotif, terminal kuningan kelistrikan, klip logam otomotif, EMI shielding electronics housing.'
        ]);
        Machine::create([
            'name' => 'Hydraulic Press',
            'spec' => 'Kapasitas 100T — 500T',
            'qty' => 12,
            'icon' => 'ti-droplet-half-2',
            'longDesc' => 'Didesain untuk proses pengerjaan penarikan dalam (deep drawing) yang membutuhkan tekanan stabil sepanjang stroke. Mampu menangani lembaran logam tebal menjadi bentuk silinder atau cup tanpa sambungan.',
            'origin' => 'Jepang / Taiwan (AMADA, Seyi)',
            'precision' => 'JIS B 6403 Hydraulic Standard',
            'safety' => 'Hydraulic overload protector valves, emergency physical interlock gate sensor, rem kopling darurat pneumatik.',
            'application' => 'EV battery case shells, filter oli silinder, tabung pemadam api tanpa sambungan, tutup tangki bahan bakar.'
        ]);
        Machine::create([
            'name' => 'Servo Press',
            'spec' => 'Kapasitas 80T — 200T',
            'qty' => 8,
            'icon' => 'ti-cpu',
            'longDesc' => 'Menggunakan teknologi motor AC servo dengan kurva ketukan yang dapat diprogram penuh. Memberikan akurasi penekanan mikro-milimeter guna menghindari keretakan material (springback control) pada material berkekuatan tarik tinggi.',
            'origin' => 'Jepang (AIDA, AMADA)',
            'precision' => 'Micron-level control (±0.005mm)',
            'safety' => 'Direct torque motor feedback sensor, emergency brake switch, sensor pendeteksi ketebalan plat ganda.',
            'application' => 'Komponen safety-critical otomotif, roda gigi transmisi mikro presisi halus, kopling cakram, spacer mesin.'
        ]);
        Machine::create([
            'name' => 'Transfer Press',
            'spec' => 'Kapasitas 200T — 500T',
            'qty' => 6,
            'icon' => 'ti-box',
            'longDesc' => 'Menghubungkan beberapa proses dies (blanking, bending, piercing) dalam satu rangkaian stasiun otomatis dengan robot lengan transfer. Ideal untuk memproduksi komponen struktural berukuran menengah hingga besar secara massal.',
            'origin' => 'Jepang (Komatsu)',
            'precision' => 'Automated stational alignment accuracy',
            'safety' => 'Pneumatic transfer feedback lines, physical perimeter fencing, tirai cahaya optoelektronik keselamatan kerja.',
            'application' => 'Sasis struktural otomotif ukuran besar, kompresor AC shells, panel pintu struktural, komponen frame.'
        ]);
        Machine::create([
            'name' => 'CNC Machining Center',
            'spec' => 'Tool room & die making',
            'qty' => 14,
            'icon' => 'ti-sparkles',
            'longDesc' => 'Fasilitas in-house tooling kami dilengkapi dengan CNC millings berkecepatan tinggi untuk membuat, memodifikasi, dan merawat cetakan dies logam secara presisi, menjamin kesiapan cetakan tanpa bergantung vendor luar.',
            'origin' => 'Jepang / Taiwan (Matsuura, YCM)',
            'precision' => 'Sub-micron tooling tolerance (±0.002mm)',
            'safety' => 'Enclosed cabin interlock door, automated spindle overload monitor, dust filtration suction loops.',
            'application' => 'Pembuatan progressive dies set, stage tooling dies, transfer die arm fixtures, CNC custom molds presisi.'
        ]);
        Machine::create([
            'name' => 'Welding Robot',
            'spec' => 'Spot & MIG Welding',
            'qty' => 10,
            'icon' => 'ti-robot',
            'longDesc' => 'Lengan robot multi-axis otomatis untuk pengelasan titik (spot welding) dan pengelasan gas metal (MIG). Menghasilkan pengelasan yang rapi, penetrasi kuat, dan bebas dari cacat las manusia pada komponen rakitan.',
            'origin' => 'Jepang (OTC Daihen, Yaskawa)',
            'precision' => 'High repeatability path accuracy (±0.08mm)',
            'safety' => 'Flash arc safety curtain, area laser safety scanners, physical fencing perimeter interlock.',
            'application' => 'Sub-assembly welding bracket otomotif, reinforcement panel sasis, pengelasan presisi sasis.'
        ]);

        // 6. Quality Assurance Section
        CmsContent::updateOrCreate(
            ['key' => 'quality_eyebrow'],
            ['section' => 'quality', 'value' => 'KUALITAS']
        );
        CmsContent::updateOrCreate(
            ['key' => 'quality_title'],
            ['section' => 'quality', 'value' => 'Standar Mutu Tertinggi']
        );
        CmsContent::updateOrCreate(
            ['key' => 'quality_sub'],
            ['section' => 'quality', 'value' => 'Kami berkomitmen pada kualitas yang terukur dan konsisten di setiap tahap produksi.']
        );
        CmsContent::updateOrCreate(
            ['key' => 'quality_certs'],
            [
                'section' => 'quality',
                'value' => json_encode([
                    ['name' => 'ISO 9001:2015', 'desc' => 'Sistem manajemen kualitas untuk konsistensi produk dan kepuasan pelanggan', 'icon' => 'ti-certificate'],
                    ['name' => 'IATF 16949', 'desc' => 'Standar kualitas internasional khusus industri otomotif', 'icon' => 'ti-car'],
                    ['name' => 'ISO 14001', 'desc' => 'Sistem manajemen lingkungan untuk operasional yang berkelanjutan', 'icon' => 'ti-leaf'],
                    ['name' => '5S & Kaizen', 'desc' => 'Penerapan lean manufacturing untuk efisiensi dan perbaikan berkelanjutan', 'icon' => 'ti-award']
                ])
            ]
        );
        CmsContent::updateOrCreate(
            ['key' => 'quality_steps'],
            [
                'section' => 'quality',
                'value' => json_encode([
                    ['num' => '01', 'title' => 'Incoming Material Inspection', 'desc' => 'Inspeksi material bahan baku menggunakan spectrometer dan hardness tester sebelum masuk produksi.'],
                    ['num' => '02', 'title' => 'In-Process Quality Check', 'desc' => 'Pemeriksaan dimensi secara berkala selama proses produksi berlangsung menggunakan SPC.'],
                    ['num' => '03', 'title' => 'Final Inspection', 'desc' => 'Inspeksi akhir menggunakan CMM dan 3D scanner untuk memastikan setiap komponen sesuai drawing.'],
                    ['num' => '04', 'title' => 'Traceability & Documentation', 'desc' => 'Setiap lot produksi terdokumentasi lengkap dengan nomor batch dan hasil inspeksi untuk traceability penuh.']
                ])
            ]
        );

        // 7. Clients & Partners CMS Header
        CmsContent::updateOrCreate(
            ['key' => 'clients_eyebrow'],
            ['section' => 'clients', 'value' => 'KLIEN & MITRA']
        );
        CmsContent::updateOrCreate(
            ['key' => 'clients_title'],
            ['section' => 'clients', 'value' => 'Dipercaya Oleh Perusahaan Terkemuka']
        );
        CmsContent::updateOrCreate(
            ['key' => 'clients_intro'],
            ['section' => 'clients', 'value' => 'Selama lebih dari 26 tahun, kami telah menjadi mitra terpercaya bagi perusahaan-perusahaan multinasional di berbagai sektor industri di Indonesia dan kawasan Asia Tenggara.']
        );
        CmsContent::updateOrCreate(
            ['key' => 'testimonial_quote'],
            ['section' => 'clients', 'value' => 'PT. Cahaya Sentosa Abadi telah menjadi mitra stamping kami selama lebih dari 10 tahun. Kualitas komponen yang konsisten, pengiriman tepat waktu, dan responsivitas tim mereka terhadap perubahan spesifikasi menjadikan mereka mitra yang sangat kami andalkan.']
        );
        CmsContent::updateOrCreate(
            ['key' => 'testimonial_author'],
            ['section' => 'clients', 'value' => 'Budi Santoso']
        );
        CmsContent::updateOrCreate(
            ['key' => 'testimonial_role'],
            ['section' => 'clients', 'value' => 'Procurement Manager, PT Astra Honda Motor']
        );

        // Seed 7b. CLIENTS Relational Table
        Client::truncate();
        Client::create(['name' => 'Toyota', 'industry' => 'Otomotif']);
        Client::create(['name' => 'Astra Honda', 'industry' => 'Otomotif']);
        Client::create(['name' => 'Denso', 'industry' => 'Komponen Otomotif']);
        Client::create(['name' => 'Yamaha', 'industry' => 'Otomotif']);
        Client::create(['name' => 'Panasonic', 'industry' => 'Elektronik']);
        Client::create(['name' => 'Samsung SDI', 'industry' => 'Baterai EV']);
        Client::create(['name' => 'Schneider', 'industry' => 'Elektrikal']);
        Client::create(['name' => 'Mitsubishi', 'industry' => 'Otomotif & Elektrik']);

        // 8. Contact Details
        CmsContent::updateOrCreate(
            ['key' => 'contact_eyebrow'],
            ['section' => 'contact', 'value' => 'KONTAK']
        );
        CmsContent::updateOrCreate(
            ['key' => 'contact_title'],
            ['section' => 'contact', 'value' => 'Hubungi Kami']
        );
        CmsContent::updateOrCreate(
            ['key' => 'contact_sub'],
            ['section' => 'contact', 'value' => 'Tim kami siap membantu kebutuhan stamping Anda. Kirimkan pertanyaan atau request quotation.']
        );
        CmsContent::updateOrCreate(
            ['key' => 'contact_alamat'],
            ['section' => 'contact', 'value' => 'Kawasan Industri MM2100']
        );
        CmsContent::updateOrCreate(
            ['key' => 'contact_alamat_sub'],
            ['section' => 'contact', 'value' => 'Jl. Irian Blok PP-1, Cikarang Barat, Bekasi, Jawa Barat 17520']
        );
        CmsContent::updateOrCreate(
            ['key' => 'contact_telp'],
            ['section' => 'contact', 'value' => '+62 21 8990 1234']
        );
        CmsContent::updateOrCreate(
            ['key' => 'contact_fax'],
            ['section' => 'contact', 'value' => '+62 21 8990 1235 (Fax)']
        );
        CmsContent::updateOrCreate(
            ['key' => 'contact_email_1'],
            ['section' => 'contact', 'value' => 'marketing@cahayasentosa.co.id']
        );
        CmsContent::updateOrCreate(
            ['key' => 'contact_email_2'],
            ['section' => 'contact', 'value' => 'info@cahayasentosa.co.id']
        );
        CmsContent::updateOrCreate(
            ['key' => 'contact_jam_1'],
            ['section' => 'contact', 'value' => 'Senin — Jumat: 08.00 — 17.00']
        );
        CmsContent::updateOrCreate(
            ['key' => 'contact_jam_2'],
            ['section' => 'contact', 'value' => 'Sabtu: 08.00 — 13.00 WIB']
        );

        // Delete old unused JSON keys from cms_contents if they are in database to avoid confusion
        CmsContent::whereIn('key', ['products_list', 'machines_list', 'clients_list'])->delete();
    }
}
