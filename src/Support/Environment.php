<?php

namespace Presspack\Framework\Support;

class Environment
{
    public static function set(string $key, string $value = null, string $dir = './.env')
    {
        try {
            list($key, $value) = self::getKeyValue($key, $value);
        } catch (\InvalidArgumentException $e) {
            return self::error($e->getMessage());
        }
        $envFilePath = "{$dir}";
        $contents = file_get_contents($envFilePath);
        if ($oldValue = self::getOldValue($contents, $key)) {
            $contents = str_replace("{$key}={$oldValue}", "{$key}={$value}", $contents);
            self::writeFile($envFilePath, $contents);

            return "Environment variable with key '{$key}' has been changed from '{$oldValue}' to '{$value}'";
        }
        $contents = $contents."\n{$key}={$value}\n";
        self::writeFile($envFilePath, $contents);

        return "A new environment variable with key '{$key}' has been set to '{$value}'";
    }

    /**
     * Overwrite the contents of a file.
     */
    protected static function writeFile(string $path, string $contents): bool
    {
        $file = fopen($path, 'w');
        fwrite($file, $contents);

        return fclose($file);
    }

    /**
     * Get the old value of a given key from an environment file.
     */
    protected static function getOldValue(string $envFile, string $key): string
    {
        // Match the given key at the beginning of a line
        preg_match("/^{$key}=[^\r\n]*/m", $envFile, $matches);
        if (\count($matches)) {
            return substr($matches[0], \strlen($key) + 1);
        }

        return '';
    }

    /**
     * Determine what the supplied key and value is from the current command.
     */
    protected static function getKeyValue(string $key, string $value): array
    {
        if (!$value) {
            $parts = explode('=', $key, 2);
            if (2 !== \count($parts)) {
                throw new InvalidArgumentException('No value was set');
            }
            $key = $parts[0];
            $value = $parts[1];
        }
        if (!self::isValidKey($key)) {
            throw new InvalidArgumentException('Invalid argument key');
        }
        if (!\is_bool(strpos($value, ' '))) {
            $value = '"'.$value.'"';
        }

        return [strtoupper($key), $value];
    }

    /**
     * Check if a given string is valid as an environment variable key.
     */
    protected static function isValidKey(string $key): bool
    {
        if (str_contains($key, '=')) {
            throw new InvalidArgumentException("Environment key should not contain '='");
        }
        if (!preg_match('/^[a-zA-Z_]+$/', $key)) {
            throw new InvalidArgumentException('Invalid environment key. Only use letters and underscores');
        }

        return true;
    }
}
