<?php

	use Hans\Valravn\Tests\Core\Models\Comment;
	use Hans\Valravn\Tests\Core\Models\Post;
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	return new class extends Migration {
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up() {
			Schema::create( Comment::table(), function( Blueprint $table ) {
				$table->id();
				$table->foreignIdFor( Post::class )->constrained()->cascadeOnDelete();
				$table->text( 'content' );
				$table->timestamps();
			} );
		}

		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down() {
			Schema::dropIfExists( Comment::table() );
		}
	};
