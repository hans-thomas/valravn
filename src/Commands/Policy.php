<?php

	namespace Hans\Valravn\Commands;

	use Illuminate\Console\Command;
	use Illuminate\Contracts\Filesystem\Filesystem;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Str;
	use League\Flysystem\Visibility;
	use Throwable;

	class Policy extends Command {
		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = '
		valravn:policy 
		{namespace: Group of the entity}
		{name: Name of the entity}
		';

		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Generate policy class.';

		private Filesystem $fs;

		public function __construct() {
			parent::__construct();
			$this->fs = Storage::createLocalDriver( [
				'root'       => app_path(),
				'visibility' => Visibility::PUBLIC
			] );
		}

		/**
		 * Execute the console command.
		 *
		 * @return void
		 * @throws Throwable
		 */
		public function handle() {
			$singular  = ucfirst( Str::singular( $this->argument( 'name' ) ) );
			$namespace = ucfirst( $this->argument( 'namespace' ) );

			$policyStub = file_get_contents( __DIR__ . '/stubs/policies/policy.stub' );
			$policyStub = Str::replace( "{{POLICY::NAMESPACE}}", $namespace, $policyStub );
			$policyStub = Str::replace( "{{POLICY::MODEL}}", $singular, $policyStub );
			$this->fs->write( "Policies/$namespace/{$singular}Policy.php", $policyStub );

			$this->info( "policy class successfully created!" );
		}

	}
