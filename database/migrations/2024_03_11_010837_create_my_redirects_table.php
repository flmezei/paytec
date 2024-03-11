<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMyRedirectsTable extends Migration
{
    public function up()
    {
        Schema::create('my_redirects', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->boolean('status')->default(true);
            $table->timestamp('last_access')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('my_redirects');
    }
}
