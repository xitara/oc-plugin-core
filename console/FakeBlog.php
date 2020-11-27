<?php namespace Xitara\Core\Console;

use Illuminate\Console\Command;
use RainLab\Blog\Models\Category;
use RainLab\Blog\Models\Post;
use Str;
use Symfony\Component\Console\Input\InputOption;

class FakeBlog extends Command
{
    // protected $require = ['Rahman\Faker'];

    /**
     * @var string The console command name.
     */
    protected $name = 'xitara:fakeblog';

    /**
     * @var string The console command description.
     */
    protected $description = 'Generate fake content for RainLab:Blog plugin for testing';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        /**
         * init counter
         *
         * @var integer
         */
        $postsCount = (int) $this->option('posts');
        $categoriesCount = (int) $this->option('categories');

        // var_dump($this->option('category'));
        // exit;

        if (!empty($this->option('category'))) {
            $categoriesCount = count($this->option('category'));
            $categoriesList = $this->option('category');
        }

        /**
         * instantiate faker
         */
        $this->faker = \Faker\Factory::create('de_DE');

        $this->output->writeln('Create ' . $postsCount . ' posts in ' .
            $categoriesCount . ' categories');

        /**
         * generate categories
         */
        $progressBar = $this->output->createProgressBar($categoriesCount * $postsCount);
        for ($i = 0; $i < $categoriesCount; $i++) {
            // if (isset($categoriesList)) {
            $cat = $this->generateCategory($categoriesList[$i] ?? null);
            // } else {
            // $cat = $this->generateCategory();
            // }

            $progressBar->advance();

            /**
             * generate posts
             */
            $postsBar = $this->output->createProgressBar($postsCount);
            for ($ii = 1; $ii <= $postsCount; $ii++) {
                $this->generatepost($cat);
                $progressBar->advance();
            }
        }
        $progressBar->finish();

        $this->output->writeln('');
        $this->output->writeln($postsCount . ' posts in ' .
            $categoriesCount . ' categories successfully created');

    }

    private function generateCategory($name = null)
    {
        $category = new Category;
        $category->name = $name ?? $this->faker->text($maxNbChars = 20);
        $category->slug = Str::slug($category->name);
        $category->description = $this->faker->sentence($nbWords = 20, $variableNbWords = true);
        $category->save();

        return $category;
    }

    private function generatepost($cat)
    {
        $contents = $this->faker->paragraphs($nb = 10, $asText = false);
        $content = '<h2>' . $this->faker->text($maxNbChars = 20) . '</h2>';
        foreach ($contents as $content_) {
            $content .= '<p>' . $content_ . '</p>' . "\n";
        }

        $post = new Post;
        $post->title = $this->faker->sentence($nbWords = 6, $variableNbWords = true) . '_' .
        $this->faker->numberBetween($min = 1000, $max = 9000);
        $post->slug = Str::slug($post->title);
        $post->excerpt = $this->faker->sentence($nbWords = 20, $variableNbWords = true);
        $post->content = $post->content_html = $content;
        $post->categories = [$cat->id];
        $post->published = 1;
        $post->published_at = $this->faker->dateTimeBetween($startDate = '-3 months', $endDate = 'now', $timezone = null);
        $post->save();

        for ($i = 1; $i <= $this->faker->numberBetween($min = 1, $max = 5); $i++) {
            $random = $this->faker->numberBetween($min = 1, $max = 40);
            $file = new \System\Models\File;
            $file->fromUrl('https://picsum.photos/800/600?random=' . $random, 'image_' . $random . '.jpg');
            $file->save();
            $post->featured_images()->add($file);
        }

        $post->save();

    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['categories', 'c', InputOption::VALUE_OPTIONAL, 'Number of categories to create', 1],
            ['category', 'cat', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'List of categories to create. If given, categories wgere ignored', null],
            ['posts', 'p', InputOption::VALUE_OPTIONAL, 'Number of posts per category to create', 10],
        ];
    }
}
