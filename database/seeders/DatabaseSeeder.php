<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\User;
use Carbon\Carbon;
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
        User::create([
            'name' => 'Randi Yulio Fajri',
            'email' => 'randijulio13@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password')
        ]);

        $items = [
            ['name' => 'Design', 'type' => 'Service', 'price' => 230.00],
            ['name' => 'Development', 'type' => 'Service', 'price' => 330.00],
            ['name' => 'Meetings', 'type' => 'Service', 'price' => 60.00],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }

        Customer::factory(10)->create();

        $invoice = Invoice::create([
            'subject' => 'Spring Marketing Campaign',
            'customer_id' => 1,
            'issue_date' => Carbon::parse('06-05-2017'),
            'due_date' => Carbon::parse('06-05-2017'),
            'subtotal' => 28510.00,
            'tax' => 10,
            'total' => 31361.00
        ]);

        $items = Item::select('id as item_id')->get()->toArray();
        $items = array_map(function ($item) {
            $item['quantity'] = rand(1, 10);
            return $item;
        }, $items);
        $invoice->items()->createMany($items);
    }
}
