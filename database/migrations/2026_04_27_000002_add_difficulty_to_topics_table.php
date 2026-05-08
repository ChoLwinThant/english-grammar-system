<?php

use App\Models\Topic;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->string('difficulty')->default(Topic::DIFFICULTY_BASIC)->after('name');
        });

        DB::table('topics')->where('name', 'Present Simple')->update(['difficulty' => Topic::DIFFICULTY_ELEMENTARY]);
        DB::table('topics')->where('name', 'Past Simple')->update(['difficulty' => Topic::DIFFICULTY_INTERMEDIATE]);
        DB::table('topics')->where('name', 'Future Simple')->update(['difficulty' => Topic::DIFFICULTY_INTERMEDIATE]);
        DB::table('topics')->where('name', 'Comparative Adjectives')->update(['difficulty' => Topic::DIFFICULTY_ELEMENTARY]);
        DB::table('topics')->where('name', 'Superlative Adjectives')->update(['difficulty' => Topic::DIFFICULTY_INTERMEDIATE]);
        DB::table('topics')->where('name', 'Modal Verbs')->update(['difficulty' => Topic::DIFFICULTY_ADVANCED]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropColumn('difficulty');
        });
    }
};
