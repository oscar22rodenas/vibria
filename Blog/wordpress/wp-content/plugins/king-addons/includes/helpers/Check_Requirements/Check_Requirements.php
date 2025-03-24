<?php

namespace King_Addons;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

final class Check_Requirements
{
    public static function check_import_requirements(): array
    {
        $requirements = [
            'memory' => (int)ini_get('memory_limit') >= 128, // Check if memory is at least 128MB
//            'zip' => class_exists('ZipArchive'),
            // Add more checks if necessary
        ];

        $errors = [];

        if (!$requirements['zip']) {
            $errors[] = 'ZIP module is not enabled on this server.';
        }

        if (!$requirements['memory']) {
            $errors[] = 'Memory limit is less than 128MB.';
        }

        return $errors;
    }
}