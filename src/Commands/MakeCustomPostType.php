<?php

namespace Presspack\Framework\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeCustomPostType extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:posttype';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new wordpress custom post type';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Custom Post Type';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (false === parent::handle() && !$this->option('force')) {
            return false;
        }
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/../Support/stubs/postType.stub';
    }
}
