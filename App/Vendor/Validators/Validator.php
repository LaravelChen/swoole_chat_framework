<?php

namespace App\Vendor\Validators;

use Illuminate\Validation\Factory;

class Validator extends Factory
{
    public static function getInstance()
    {
        static $validator = null;
        if ($validator == null) {
            $test_translation_path = __DIR__ . '/lang';
            $test_translation_locale = 'en';
            $translation_file_loader = new \Illuminate\Translation\FileLoader(new \Illuminate\Filesystem\Filesystem, $test_translation_path);
            $translator = new \Illuminate\Translation\Translator($translation_file_loader, $test_translation_locale);
            $validator = new \Illuminate\Validation\Factory($translator);
        }
        return $validator;
    }
}