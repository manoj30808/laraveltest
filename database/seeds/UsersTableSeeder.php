<?php
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;
use App\Customer;
use App\Vendor;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       	$vendors = [
        	[
	            'name' => 'Test Vendor1'
	        ],
	        [
	            'name' => 'Test Vendor2',
	        ],
	        [
	            'name' => 'Test Vendor3',
	        ]
        ];
        $vendor_id = array();
        foreach ($vendors as $key => $value) {
        	$vendor_id[] = Vendor::create($value)->id;
		}
		\DB::table('customers')->insert([
            'name' => 'Test Customer',
            'company_name' => 'Test Company',
            'vendor_id' => implode(',', $vendor_id)
        ]);
    }
}