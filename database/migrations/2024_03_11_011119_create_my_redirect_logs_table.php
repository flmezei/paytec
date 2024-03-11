<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMyRedirectLogsTable extends Migration
{
    public function up()
    {
        Schema::create('my_redirect_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('redirect_id'); // Usar unsignedBigInteger para chaves estrangeiras
            $table->foreign('redirect_id')->references('id')->on('my_redirects')->onDelete('cascade'); // Corrigir a referência para 'my_redirects'
            $table->string('ip');
            $table->text('user_agent')->nullable();
            $table->text('referer')->nullable();
            $table->json('query_params')->nullable();
            $table->timestamp('access_time')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('my_redirect_logs');
    }
}
