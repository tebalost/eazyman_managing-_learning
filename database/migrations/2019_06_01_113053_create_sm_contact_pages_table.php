<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmContactPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_contact_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->string('address')->nullable();
            $table->string('address_text')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_text')->nullable();
            $table->string('email')->nullable();
            $table->string('email_text')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('google_map_address')->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->timestamps();

            $table->integer('created_by')->nullable()->default(1)->unsigned();

            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
        });
        DB::table('sm_contact_pages')->insert([
            [
                'title' => 'Contact Us',
                'description' => 'Have any questions? We’d love to hear from you! Here’s how to get in touch with us.',
                'image' => 'public/uploads/contactPage/contact.jpg',
                'button_text' => 'Learn More About Us',
                'button_url' => 'about',
                'address' => '136, 2nd Street, Randjespark, Midrand, South Africa',
                'address_text' => 'Santa monica bullevard',
                'phone' => '+27822072730',
                'phone_text' => 'Mon to Fri 7am to 6 pm',
                'email' => 'info@serumula.com',
                'email_text' => 'Send us your query anytime!',
                'latitude' => '-25.9992',
                'longitude' => '28.1263',
                'google_map_address' => 'Midrand, South Africa',
                'school_id' => null,
            ],
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_contact_pages');
    }
}
