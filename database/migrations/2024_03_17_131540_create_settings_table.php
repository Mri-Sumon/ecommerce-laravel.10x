<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->integer('icon_id')->nullable();
            $table->string('companyName')->nullable();
            $table->integer('logo_id')->nullable();
            $table->integer('adminPicture_id')->nullable();
            $table->text('importantUpdates')->nullable();
            $table->string('facebook')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('pinterest')->nullable();
            $table->longText('map')->nullable();
            $table->string('officeHours')->nullable();
            $table->string('address')->nullable();
            $table->integer('footerLogo_id')->nullable();
            $table->string('email')->nullable();
            $table->string('contact')->nullable();
            $table->string('copyright')->nullable();
            $table->timestamps();
        });



        // Insert default record
        DB::table('settings')->insert([
            'title' => 'Default Title',
            'icon_id' => 1, 
            'companyName' => 'Default Company',
            'logo_id' => 1, 
            'adminPicture_id' => 1, 
            'importantUpdates' => 'Default Updates',
            'facebook' => 'Default Facebook',
            'whatsapp' => 'Default Whatsapp',
            'twitter' => 'Default Twitter',
            'instagram' => 'Default Instagram',
            'linkedin' => 'Default LinkedIn',
            'pinterest' => 'Default Pinterest',
            'map' => 'Default Map',
            'officeHours' => 'Default Office Hours',
            'address' => 'Default Address',
            'footerLogo_id' => 1, 
            'email' => 'default@example.com',
            'contact' => 'Default Contact',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
}















