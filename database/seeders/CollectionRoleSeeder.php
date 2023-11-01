<?php

namespace Database\Seeders;

use App\Models\CollectionRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CollectionRoleSeeder extends Seeder
{
    private $datas = [
        [
            'slug' => "photographer_people",
            'name' => "People Photographer",
            'description' => "A photographer who captures people is responsible for taking pictures of individuals, whether in controlled environments like photography studios or in outdoor locations. Their work involves directing the models, selecting the appropriate lighting, and composing the image to highlight the personality and aesthetics of the person being portrayed. They may also retouch the photos in post-production to enhance the quality and appearance of the images."
        ],
        [
            'slug' => "photographer_animals",
            'name' => "Animal Photographer",
            'description' => "A photographer who exclusively captures animals focuses on taking pictures of various creatures, often in their natural habitats or controlled settings like wildlife reserves or zoos. Their work involves patience and a keen eye for animal behavior, as they strive to capture the essence and beauty of the animal kingdom. They may use specialized equipment and techniques to get close-up shots of wildlife, and post-processing may be used to enhance the final images when needed."
        ],
        [
            'slug' => "photographer",
            'name' => "General Photographer",
            'description' => "A photographer who performs a wide range of photography work covers diverse subjects and settings. They capture a variety of subjects, including people, animals, landscapes, events, products, and more. Their work requires versatility in photography techniques, equipment, and post-processing to adapt to the specific requirements of each assignment. These photographers need to be skilled in composition, lighting, and post-production to deliver high-quality images regardless of the subject matter."
        ],
        [
            'slug' => "artist_monster",
            'name' => "Monster Design",
            'description' => "A digital artist specialized in monsters creates imaginative and terrifying beings using digital tools."
        ],
        [
            'slug' => "artist",
            'name' => "Artist",
            'description' => "An all-around artist works in various mediums, including drawing, painting, sculpture, and digital art, covering a wide range of creative expression."
        ],
    ];


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->datas as $data) {
            CollectionRole::firstOrCreate([
                    'slug' => $data['slug']
                ], [
                    'name' => $data['name'],
                    'description' => $data['description']
                ]
            );
        }
    }
}
