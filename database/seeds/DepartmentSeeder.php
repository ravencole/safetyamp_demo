<?php

use App\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $departments = [
      'shipping',
      'corporate',
      'manufacturing',
      'supply',
      'design'
    ];

    foreach($departments as $dept) {
      factory(Department::class)->create([
        'name' => $dept
      ]);
    }
  }
}
