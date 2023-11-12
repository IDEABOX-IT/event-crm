<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $account = Company::create(['name' => 'IDEABOX IT']);

        User::factory()->create([
            'company_id' => $account->id,
            'first_name' => 'Ed',
            'last_name' => 'Suguimoto',
            'email' => 'rijoedi@gmail.com',
            'password' => 'secret',
            'owner' => true,
        ]);

        User::factory(1)->create(['company_id' => $account->id]);

        $events = Event::factory(1)
            ->create(['company_id' => $account->id]);

        Contact::factory(1)
            ->create(['company_id' => $account->id])
            ->each(function ($contact) use ($events) {
                $contact->update(['event_id' => $events->random()->id]);
            });
    }
}
