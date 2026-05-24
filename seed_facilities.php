<?php

use App\Models\Facility;

$baseFacilities = [
    [
        'name' => 'Area Produksi',
        'short_desc' => 'Area stamping dan assembling utama dengan tata letak optimal.',
        'long_desc' => 'Area produksi berstandar industri 5S dengan pembagian zona kerja yang jelas untuk memaksimalkan keselamatan dan efisiensi alur material.',
        'spec' => "Kapasitas Layout: Terintegrasi\nLuas Area: Terukur",
        'icon' => 'ti-settings-2'
    ],
    [
        'name' => 'Tool Room',
        'short_desc' => 'Perawatan, perbaikan, dan modifikasi dies (cetakan) secara mandiri.',
        'long_desc' => 'Dilengkapi fasilitas CNC Machining Center mandiri untuk menjamin kecepatan perbaikan dan perawatan cetakan dies tanpa pihak ketiga.',
        'spec' => "Standard: JIS/ISO\nEquipment: CNC, EDM",
        'icon' => 'ti-tool'
    ],
    [
        'name' => 'Gudang',
        'short_desc' => 'Penyimpanan bahan baku dan barang jadi dengan manajemen inventory yang baik.',
        'long_desc' => 'Sistem penyimpanan dan manajemen inventaris berbasis rak terstruktur dengan sistem FIFO (First In First Out) untuk menjamin kualitas material.',
        'spec' => "Sistem: Terkomputerisasi\nAlur: FIFO",
        'icon' => 'ti-package'
    ],
    [
        'name' => 'QC Lab',
        'short_desc' => 'Laboratorium jaminan mutu dengan alat ukur presisi tinggi (CMM).',
        'long_desc' => 'Laboratorium isolasi bersuhu terkontrol untuk proses pengukuran mikroskopis menggunakan alat CMM (Coordinate Measuring Machine) dan Hardness Tester.',
        'spec' => "Suhu Terkontrol: Ya\nAlat: CMM, Profile Projector",
        'icon' => 'ti-microscope'
    ]
];

foreach ($baseFacilities as $data) {
    Facility::updateOrCreate(['name' => $data['name']], $data);
}

echo "Facilities seeded successfully.\n";
