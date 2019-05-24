<?php

use Illuminate\Database\Seeder;
use App\Contract;
use App\Invoice;
use App\Item;
use App\Payment;
use App\Opportunity;
use Faker\Factory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        foreach (range(1, 100) as $i) {
            Contract::create([
                'name' => $faker->name
            ]);
        }

        foreach (range(1, 100) as $i) {
            Item::create([
                'name' => 'Product' . $i
            ]);
        }

        foreach (range(1, 100) as $i) {
            Invoice::create([
                'issue_date' => '2019-05-' . mt_rand(1, 30),
                'due_date' => '2019-06-' . mt_rand(1, 30),
                'status' => $faker->randomElement(['sent', 'paid'])
            ]);
        }

        foreach (range(1, 100) as $i) {
            Payment::create([
                'payment_date' => '2019-05-' . mt_rand(1, 30),
                'status' => $faker->randomElement(['undeposited', 'deposited'])
            ]);
        }

        foreach (range(1, 100) as $i) {
            Opportunity::create([
                'status' => $faker->randomElement(['new', 'lost', 'won'])
            ]);
        }

    }
}
