<?php
/*---============== SANTHOSH =============---*/
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BusinessCategorySeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run()
  {
    // Insert a default category if it does not exist
    DB::table('business_categories')->updateOrInsert(
      ['id' => 1], // Ensure it's always ID 1
      [
        'business_category_id' => '1',
        'business_category_name' => 'Default Category',
        'business_category_logo_url' => 'https://example.com/default-logo.png', // Update with a real URL if needed
        'business_category_description' => 'This is the default category for businesses.',
        'business_category_slug' => 'default-category',
        'status' => 1, // Assuming 1 means active
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ]
    );
  }
}
/*---============== SANTHOSH =============---*/