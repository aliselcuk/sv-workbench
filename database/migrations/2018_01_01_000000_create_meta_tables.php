<?php

use SuperV\Platform\Domains\Database\Migrations\Migration;
use SuperV\Platform\Domains\Database\Schema\Blueprint;
use SuperV\Platform\Domains\Database\Schema\Schema;
use SuperV\Platform\Domains\Resource\ResourceConfig;

class CreateMetaTables extends Migration
{
    public function up()
    {
        Schema::create('sv_meta', function (Blueprint $table, ResourceConfig $resource) {
            $table->increments('id');

            if ($table instanceof Blueprint) {
                $resource->label('Meta');
//            $resource->model(MetaModel::class);
                $table->hasMany('sv_meta_items', 'items', 'meta_id');
            }

            $table->nullableMorphs('owner');
            $table->string('label')->nullable();
            $table->uuid('uuid')->nullable();

            $table->timestamps();
        });

        Schema::create('sv_meta_items', function (Blueprint $table, ResourceConfig $resource) {
            $table->increments('id');

            if ($table instanceof Blueprint) {
                $resource->label('Meta Items');

                $table->nullableBelongsTo('sv_meta', 'meta');
                $table->nullableBelongsTo('sv_meta_items', 'parent_item');
                $table->hasMany('sv_meta_items', 'items', 'parent_item_id');
            } else {
                $table->unsignedInteger('meta_id')->nullable();
                $table->unsignedInteger('parent_item_id')->nullable();
            }

            $table->string('key');
            $table->text('value')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sv_meta');
        Schema::dropIfExists('sv_meta_items');
    }
}
