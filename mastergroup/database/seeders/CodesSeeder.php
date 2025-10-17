<?php

namespace Database\Seeders;

use App\Models\Code;
use Illuminate\Database\Seeder;

class CodesSeeder extends Seeder
{
    public function run(): void
    {
        // Создаём только "new" коды, без activated_by_user_id и activated_at
        Code::factory()->count(100)->create();
    }
}
