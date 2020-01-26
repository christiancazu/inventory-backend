<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\Field;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('names', Field::MAX_NAME_USER);
            $table->string('surnames', Field::MAX_NAME_USER);
            $table->string('doc_num', Field::MAX_DOC_NUM)->unique();
            $table->string('email', Field::MAX_EMAIL)->unique();
            $table->string('password');
            $table->boolean('activated')->default(true);
            
            $table->tinyInteger('role_id')->unsigned()->index()->default(Field::ID_ROLE_GUESS);
            $table->foreign('role_id')->references('id')->on('roles');

            $table->smallInteger('modified_by_id')->unsigned()->index();
            $table->foreign('modified_by_id')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
