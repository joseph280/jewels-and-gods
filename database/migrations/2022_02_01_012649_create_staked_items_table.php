<?php

use App\Models\Item;
use App\Models\User;
use App\Models\Token;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStakedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staked_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Item::class);
            $table->foreignIdFor(Token::class);
            $table->string('asset_id');
            $table->unsignedFloat('balance')->nullable();
            $table->timestamp('staked_at');
            $table->timestamp('claimed_at')->nullable();
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
        Schema::dropIfExists('staked_items');
    }
}
