<?php

class DatabaseSeeder extends Seeder {

    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        Eloquent::unguard();
        
        $this->call('HaikPagesTableSeeder');
        $this->call('HaikSitesTableSeeder');
        $this->call('HaikUsersTableSeeder');
    }

}