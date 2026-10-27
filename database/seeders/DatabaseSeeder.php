<?php

namespace Database\Seeders;

use App\Models\Authorizations\Role;
use App\Models\Contribution\InvoiceJournal;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\MasterData\Member;
use App\Models\MenuPost;
use App\Models\Post;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use Pest\Plugins\Tia\Storage;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::beginTransaction();

        $this->init();
        $this->contrib();
        $this->postProfile();
        $this->postStruktur();
        $this->galleries();

        DB::commit();
    }

    private function init()
    {
        $roleids = [];

        foreach ([
            ['admin', 'admin'],
            ['member', 'member'],
        ] as $idx => $data) {
            $role = new Role;
            $role->id = ++$idx;
            $role->name = $data[0];
            $role->role_grouping = $data[1];
            $role->save();
            $roleids[] = $role->id;
        }

        $admin = new User;
        $admin->id = 1;
        $admin->name = 'admin';
        $admin->email = 'test@example.test';
        $admin->email_verified_at = date('Y-m-d H:i:s');
        $admin->password = Hash::make('password');
        $admin->save();
        $admin->roles()->attach([1]);
        $admin->save();

        $adminMember = new Member;
        $adminMember->id = 1;
        $adminMember->user()->associate($admin);
        $adminMember->member_code = 'ID0001';
        $adminMember->first_name = 'Admin';
        $adminMember->last_name = 'Admin';
        $adminMember->gender = 'M';
        $adminMember->birth_date = '2009-03-21';
        $adminMember->nik = '4364893101771821';
        $adminMember->plate_number = 'D19181AC';
        $adminMember->phone_number = '081222335342';
        $adminMember->save();

        Member::factory(50)->create();
        $posts = Post::factory()->count(50)->for(User::query()->find(1))->create();
        foreach ($posts as $post) {
            Event::factory()->for($post)->create();
            $post->reviewed_at = date('Y-m-d H:i:s');
            $post->published_at = $post->reviewed_at;
            $post->save();
        }

    }

    private function contrib()
    {
        $journal = new InvoiceJournal;
        $journal->name = 'Fee Members';
        $journal->code = 'FEE-';
        $journal->default_amount = 50000;
        $journal->save();

        $this->call(ContributionSeeder::class);
    }

    /**
    *
    *  Fungsi membuat postingan dan menu profile
    *
    */
    private function postProfile()
    {
        $post = new Post();
        $post->title = 'Profile';
        $post->description = 'District Tiger Cikarang PLUS adalah organisasi club kendaraan bermotor yang bertempat di kabupaten Bekasi. Seperti yang ada tertera pada Namanya, club ini menjadikan motor tiger sebagai tunggangan utamanya. DTC PLUS memiliki basecamp yang terletak di ruko kuning taman Sentosa dan kantor secretariat yang berada di Kompas tambun. Club ini tergabung dalam wadah Honda Tiger Club Indonesia sejak oktober 2004.

DTC PLUS didirikan dengan tujuan untuk mengumpulkan dan menyatukan anggota serta menyalurkan hobi otomotif, terkhusus para penggemar motor Honda Tiger series. DTC PLUS mengutamakan rasa kekeluargaan, kesetiakawanan, tertib dan disiplin berlalu lintas, serta mematuhi segala macam peraturan dan taat tertib yang berlaku.';
        $post->contents = '<p class="ql-align-justify"><strong>District Tiger Cikarang PLUS</strong> adalah organisasi club kendaraan bermotor yang bertempat di kabupaten Bekasi. Seperti yang ada tertera pada Namanya, club ini menjadikan motor tiger sebagai tunggangan utamanya. DTC PLUS memiliki basecamp yang terletak di ruko kuning taman Sentosa dan kantor secretariat yang berada di Kompas tambun. Club ini tergabung dalam wadah Honda Tiger Club Indonesia sejak oktober 2004.</p><p class="ql-align-justify"><br></p><p class="ql-align-justify"><strong>DTC PLUS</strong> didirikan dengan tujuan untuk mengumpulkan dan menyatukan anggota serta menyalurkan hobi otomotif, terkhusus para penggemar motor Honda Tiger series. DTC PLUS mengutamakan rasa kekeluargaan, kesetiakawanan, tertib dan disiplin berlalu lintas, serta mematuhi segala macam peraturan dan taat tertib yang berlaku.</p><p class="ql-align-justify"><br></p><h3 class="ql-align-justify"><strong>A. Sejarah singkat berdirinya DTC PLUS</strong></h3><p class="ql-align-justify"><br></p><p class="ql-align-justify">Berawal dari keinginan untuk bergabung dengan klub motor tiger, maka bergabunglah 5 (lima) orang dari Cikarang ke klub tiger yang telah lebih dulu berdiri yaitu <strong>STICK</strong> (Sanggabuan Club Karawang) yang berada di Karawang.Ke 5 (lima) orang tersebut adalah : </p><ol><li data-list="bullet" class="ql-align-justify"><span class="ql-ui" contenteditable="false"></span>Sudiro</li><li data-list="bullet" class="ql-align-justify"><span class="ql-ui" contenteditable="false"></span>Ahmad</li><li data-list="bullet" class="ql-align-justify"><span class="ql-ui" contenteditable="false"></span>Tamsid</li><li data-list="bullet" class="ql-align-justify"><span class="ql-ui" contenteditable="false"></span>Bambang</li><li data-list="bullet" class="ql-align-justify"><span class="ql-ui" contenteditable="false"></span>Junaedi</li></ol><p class="ql-align-justify"><br></p><p class="ql-align-justify">Seiring waktu berlalu maka mulai punya keinginan untuk membuat klub tiger sendiri di Kota Cikarang. Setelah diadakan musyawarah dengan pengurus STICK dan mendapat izin, maka dibentuklah klub pertama di Cikarang dengan nama Cikarang Lippo Tiger Club (CLTC) yang terbentuk pada tgl 4 Mei 2004 dengan basecamp di ruko kuning, dimotori oleh ke 5 (lima) orang tersebut diatas dengan Junaedi sebagai ketua. Dan kemudian digantikan oleh Sudiro. Pada tahun yang sama yaitu 2004 CLTC terdaftar dalam induk Honda Tiger Club Indonesia (HTCI), dan masuk dalam wilayah 4 Pengda Jawa Barat, satu wilayah dengan Bekasi Tiger Club (BKTC) Kota Bekasi, Sanggabuana Tiger Club Karawang (STICK) Karawang, Tiger Organization Purwakarta (TOPU) Purwakarta, Subang Tiger Owners Club (STOC) Subang. </p><p class="ql-align-justify"><br></p><p class="ql-align-justify">Semakin lama anggota CLTC semakin banyak dan semakin berkembang, namanya pun mulai terdengar di luar Kota Cikarang. Atas dasar itulah maka Pak Camat Cikarang Utara yang juga sebagai penasehat yaitu Bpk. Muhtar, beliau menyarankan agar nama CLTC dirubah menjadi District Tiger Cikarang, dengan alasan DTC lebih mencerminkan Kota Cikarang. Dari dasar itulah maka nama Cikarang Lippo Tiger Club (CLTC) dirubah menjadi District Tiger Cikarang (DTC) dengan ketua Sudiro. </p><p class="ql-align-justify"><br></p><p class="ql-align-justify">Dengan bergantinya nama CLTC menjadi DTC ternyata menimbulkan pro dan kontra diantara anggota sehingga DTC terpecah menjadi 2 kubu yaitu District Tiger Cikarang dan terbentuklah klub tiger baru yaitu Citoz (Cikarang Tiger Organization). Hubungan 2 klub tiger tersebut sangat tidak harmonis, bahkan sempat hampir terjadi kontak fisik. Pada Tahun 2006 ke 2 klub yaitu DTC dan Citoz sepakat mengadakan Musyawarah bersama (Mubes) di Kecamatan Cikarang Utara didampingi oleh Camat Cikarang Utara selaku pelindung DTC yaitu Bpk. Muhtar. Dari Mubes tersebut disepakati ke 2 klub bergabung menjadi satu walaupun harus dilalui dengan debat yang sulit dan alot dengan nama “District Tiger Cikarang Plus” (DTC Plus), dan terpilih sebagai ketua umum yaitu Pak Rusbandi.</p><p class="ql-align-justify"><br></p><h3 class="ql-align-justify"><strong>B. Visi &amp; misi club</strong></h3><p class="ql-align-justify"><br></p><h3 class="ql-align-justify"><strong>VISI</strong></h3><p class="ql-align-justify">Menjadi club yang maju, solid, tangguh, dapat bekerjasama, dan ikut menjaga ketertiban dan ketentraman kota Cikarang.</p><p class="ql-align-justify"><br></p><h3 class="ql-align-justify"><strong>MISI </strong></h3><p class="ql-align-justify">a. Membentuk anggota yang solid dan bertaqwa Kepada Tuhan YME </p><p class="ql-align-justify">b. Menyalurkan hobby berkendara sepeda motor </p><p class="ql-align-justify">c. Membentuk anggota agar selalu tertib berkendara </p><p class="ql-align-justify">d. Membantu masyarakat khususnya wilayah Cikarang dan sekitarnya</p><p class="ql-align-justify"><br></p><h3 class="ql-align-justify"><strong>MOTTO</strong> </h3><p class="ql-align-justify">Membangun jiwa Brotherhood / persaudaraan tanpa batas</p><p class="ql-align-justify"><br></p><h3 class="ql-align-justify"><strong>JARGON</strong></h3><p class="ql-align-justify">The Real Tiger At Industrial Park</p><p class="ql-align-justify"><br></p><p class="ql-align-justify"><br></p>';
        $post->review();
        $post->publish();
        $post->user_id = 1;

        $post->save();

        $menupost = new MenuPost([
            'post_id' => $post->id,
            'slug' => 'profile',
            'default' => 100,
            'menu_title' => 'Profile',
            'icons' => null,
        ]);
        $menupost->save();
    }

    private function postStruktur()
    {
        $post = new Post();
        $post->title = 'Struktur Kepungurusan';
        $post->description = 'District Tiger Cikarang PLUS memiliki anggota kepungurusan yang masing-masing bertugas untuk membantu setiap anggota dan menjaga lingkungan serta kondisi club diantara anggota.';
        $post->path_featured_image = 'galleries/struktur-anggota.png';
        $post->review();
        $post->publish();
        $post->user_id = 1;

        $post->save();

        $menupost = new MenuPost([
            'post_id' => $post->id,
            'slug' => 'struktur',
            'default' => 200,
            'menu_title' => 'Struktur',
            'icons' => null,
        ]);
        $menupost->save();
    }

    private array $tags = [
        'event',
        'document',
        'other'
    ];

    private function galleries()
    {
        $files = FacadesStorage::disk('public')->allFiles('galleries');
        foreach($files as $file) {
            $g = new Gallery();
            $g->title = fake()->text(32);
            $g->description = fake()->text(250);
            $g->path_image = $file;
            $g->tags = [
                $this->tags[rand(0,2)]
            ];
            $g->save();
        }
    }
}
