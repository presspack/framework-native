<?php

namespace Presspack\Framework\Commands;

use Illuminate\Console\Command;
use Presspack\Framework\Support\Environment;
use RandomLib\Factory;
use SecurityLib\Strength;

class SaltsGenerate extends Command
{
    protected $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_[]{}<>~`+=,.;:/?|';

    protected $keys = [
        'AUTH_KEY',
        'SECURE_AUTH_KEY',
        'LOGGED_IN_KEY',
        'NONCE_KEY',
        'AUTH_SALT',
        'SECURE_AUTH_SALT',
        'LOGGED_IN_SALT',
        'NONCE_SALT',
    ];

    protected $signature = 'salts:generate';

    protected $description = 'Generates Wordpress Salts and writes them to .env';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        foreach ($this->keys as $key) {
            Environment::set($key, "\"{$this->generateSalt()}\"");
        }
        $this->info('Salts stored!');
    }

    public function generateSalt($length = 64)
    {
        $factory = new Factory();
        $generator = $factory->getGenerator(new Strength(Strength::MEDIUM));

        return $generator->generateString($length, $this->chars);
    }
}
