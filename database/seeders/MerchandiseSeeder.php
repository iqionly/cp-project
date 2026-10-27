<?php

namespace Database\Seeders;

use App\Models\Merchandise;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MerchandiseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan folder storage/app/public/uploads ada
        $publicUploadsDir = storage_path('app/public/uploads');
        if (! File::isDirectory($publicUploadsDir)) {
            File::makeDirectory($publicUploadsDir, 0755, true);
        }

        // Sumber file kemungkinan ada di storage/app/uploads atau storage/app/publics/uploads
        $potentialSources = [
            storage_path('app/uploads'),
            storage_path('app/publics/uploads'),
            $publicUploadsDir,
        ];

        // Salin file merch-* ke storage/app/public/uploads jika belum ada di sana
        foreach ($potentialSources as $sourceDir) {
            if (File::isDirectory($sourceDir) && $sourceDir !== $publicUploadsDir) {
                $sourceFiles = File::glob($sourceDir.'/merch-*');
                foreach ($sourceFiles as $sourceFile) {
                    $filename = basename($sourceFile);
                    $destPath = $publicUploadsDir.'/'.$filename;
                    if (! File::exists($destPath)) {
                        File::copy($sourceFile, $destPath);
                    }
                }
            }
        }

        // Ambil semua file merch-* yang ada di storage/app/public/uploads
        $merchFiles = File::glob($publicUploadsDir.'/merch-*');

        // Preset katalog merchandise yang informatif
        $catalogPresets = [
            'merch-1.jpg' => [
                'name' => 'Official Club T-Shirt Black Edition',
                'description' => 'Kaos resmi club berbahan cotton combed 24s premium. Nyaman, adem, dan cocok dipakai untuk kegiatan kopdar maupun harian.',
                'price' => 125000,
                'stock' => 50,
            ],
            'merch-1.png' => [
                'name' => 'Official Club T-Shirt Limited White',
                'description' => 'Kaos edisi khusus warna putih dengan sablon discharge logo club detail tinggi, bahan sejuk dan menyerap keringat.',
                'price' => 135000,
                'stock' => 30,
            ],
            'merch-3.jpg' => [
                'name' => 'Official Touring Riding Jacket',
                'description' => 'Jaket riding tahan angin (windproof) dengan protektor bahu dan siku, cocok untuk perjalanan touring jarak jauh dan cuaca dingin.',
                'price' => 450000,
                'stock' => 20,
            ],
            'merch-4.jpg' => [
                'name' => 'Official Club Snapback Cap',
                'description' => 'Topi snapback dengan bordir 3D logo club di bagian depan, material kanvas tebal dan ukuran dapat disesuaikan.',
                'price' => 85000,
                'stock' => 40,
            ],
            'merch-5.jpg' => [
                'name' => 'Official Zipper Hoodie Club',
                'description' => 'Hoodie zipper berbahan fleece tebal premium yang hangat, dilengkapi saku samping dan sablon berkualitas.',
                'price' => 275000,
                'stock' => 25,
            ],
            'merch-6.jpg' => [
                'name' => 'Official Club Metal Keychain & Sticker Pack',
                'description' => 'Gantungan kunci logam anti karat berlogo club grafir presisi, bonus paket stiker vinyl tahan air dan cuaca.',
                'price' => 45000,
                'stock' => 100,
            ],
        ];

        foreach ($merchFiles as $file) {
            $filename = basename($file);
            $preset = $catalogPresets[$filename] ?? null;

            $name = $preset['name'] ?? 'Official Merchandise '.Str::title(str_replace(['-', '.'], ' ', pathinfo($filename, PATHINFO_FILENAME)));
            $description = $preset['description'] ?? 'Official merchandise resmi club untuk anggota dan pecinta otomotif.';
            $price = $preset['price'] ?? 100000;
            $stock = $preset['stock'] ?? 25;

            Merchandise::updateOrCreate(
                ['path_image' => 'uploads/'.$filename],
                [
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'stock' => $stock,
                    'is_available' => true,
                ]
            );
        }

        $this->command?->info('Merchandise seeded successfully from image files in uploads/merch-*. Total: '.count($merchFiles).' items.');
    }
}
