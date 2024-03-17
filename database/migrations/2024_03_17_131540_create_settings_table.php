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
            $table->string('importantUpdates')->nullable();
            $table->string('selectImageSection')->nullable();
            $table->string('imageFirstTitle')->nullable();
            $table->integer('imageSection_id')->nullable();
            $table->string('imageSecondTitle')->nullable();
            $table->string('selectImgWithTextSection')->nullable();
            $table->text('description')->nullable();
            $table->string('selectVideoSection')->nullable();
            $table->string('videoLink')->nullable();
            $table->string('videoFirstTitle')->nullable();
            $table->string('videoSecondTitle')->nullable();
            $table->string('facebook')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('pinterest')->nullable();
            $table->string('map')->nullable();
            $table->string('officeHours')->nullable();
            $table->string('address')->nullable();
            $table->integer('footerLogo_id')->nullable();
            $table->string('email')->nullable();
            $table->string('contact')->nullable();
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
            'selectImageSection' => 'Default Image Section',
            'imageFirstTitle' => 'Default Image First Title',
            'imageSection_id' => 1, 
            'imageSecondTitle' => 'Default Image Second Title',
            'selectImgWithTextSection' => 'Default Img With Text Section',
            'description' => 'Default Description',
            'selectVideoSection' => 'Default Video Section',
            'videoLink' => 'Default Video Link',
            'videoFirstTitle' => 'Default Video First Title',
            'videoSecondTitle' => 'Default Video Second Title',
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















