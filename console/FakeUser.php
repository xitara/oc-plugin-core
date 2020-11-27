<?php namespace Xitara\Core\Console;

use Auth;
use Db;
use Faker;
use Illuminate\Console\Command;
use Str;
use Symfony\Component\Console\Input\InputOption;

class FakeUser extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'xitara:fakeuser';

    /**
     * @var string The console command description.
     */
    protected $description = 'No description provided yet...';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        /**
         * init user
         *
         * @var integer
         */
        $userCount = (int) $this->option('user');

        /**
         * init progressbar
         */
        $bar = $this->output->createProgressBar($userCount);
        $this->output->writeln('Create ' . $userCount . ' Users');
        $faker = Faker\Factory::create('de_DE');

        for ($i = 1; $i <= $userCount; $i++) {
            $password = substr($faker->password() . $faker->password(), 0, 20);

            /**
             * create user
             */
            $user = Auth::register([
                'name' => $faker->firstName($gender = null),
                'surname' => $faker->lastName(),
                'email' => $faker->safeEmail(),
                'username' => $faker->userName(),
                'password' => $password,
                'password_confirmation' => $password,
            ], true);

            $file = new \System\Models\File;
            // $user->avatar = $file->fromUrl('http://mod.andoria/System/fakebilder/' . $faker->numberBetween($min = 1, $max = 20) . '.jpg');
            $user->avatar = $file->fromUrl('https://picsum.photos/800/600?image=' . $i, 'avatar_' . $i . '.jpg');

            // $user->avatar = $file->fromUrl('https://mghm.kuse.de/storage/app/media/faker/' . $faker->numberBetween($min = 1, $max = 20) . '.jpg');
            // $user->avatar = $file->fromUrl($faker->imageUrl($width = 640, $height = 480));
            $user->save();

            $success = Db::table('users_groups')
                ->insert([
                    'user_id' => $user->id,
                    'user_group_id' => 2,
                ]);

            $bar->advance();
        }

        $bar->finish();
        $this->output->writeln('');
        $this->output->writeln('finished');
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['user', 'u', InputOption::VALUE_OPTIONAL, 'Number of user to create', 1],
        ];
    }
}
