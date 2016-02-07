<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BaseTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("dimensions", function(Blueprint $table) {
            $table->increments("id");
            $table->string("label");
            $table->text("description")->nullable();
            $table->integer("minimum");
            $table->integer("maximum");
            $table->integer("increment")->default(1);
            $table->boolean("active")->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create("ratings", function(Blueprint $table) {
            $table->increments("id");
            $table->integer("dimension_id")->unsigned();
            $table->string("parent_type");
            $table->integer("parent_id")->unsigned();
            $table->datetime("recorded_time");
            $table->string("recorder_type")->nullable();
            $table->integer("recorder_id")->unsigned();
            $table->integer("score");
            $table->foreign("dimension_id", "fk_rating_dimension")->references("id")->on("dimensions")->onUpdate("CASCADE")->onDelete("CASCADE");
            $table->index(["parent_type", "parent_id"], "i_rating_audit");
            $table->index("dimension_id", "i_rating_dimension");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("ratings");
        Schema::drop("dimensions");
    }
}
