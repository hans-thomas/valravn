<?php

	use Hans\Tests\Valravn\Core\Models\Category;
	use Hans\Tests\Valravn\Core\Models\Post;
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
			Schema::create( 'category_post', function( Blueprint $table ) {
				$table->foreignIdFor( Category::class )->constrained();
				$table->foreignIdFor( Post::class )->constrained();

				$table->primary( [ Category::foreignKey(), Post::foreignKey() ] );
			} );
		}

		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down() {
			Schema::dropIfExists( 'category_post' );
		}
	};
