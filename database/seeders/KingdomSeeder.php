<?php

namespace Database\Seeders;

use App\Models\Kingdom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class KingdomSeeder extends Seeder
{

    //  'slug' => $value['slug'],
    // 'name' => $value['name'],
    // 'description' => $value['description'],
    // 'thumbnail' => $appUrl.$value['thumbnail'],
    // 'image' => $appUrl.$value['image']

    private function throwError(string $error, string $itemString)
    {
        throw new \Exception($error . ": " . $itemString);
    }

    private function validateAnyFieldMissing($item): bool
    {
        $json = json_encode($item, JSON_PRETTY_PRINT);
        if (!isset($item->slug)) $this->throwError("Missing slug", $json);
        if (!isset($item?->name)) $this->throwError("Missing name", $json);
        if (!isset($item?->description)) $this->throwError("Missing description", $json);
        if (!isset($item?->image)) $this->throwError("Missing image", $json);
        if (!isset($item?->thumbnail)) $this->throwError("Missing thumbnail", $json);

        return true;
    }


    private function validateTypeOfItem($item): bool
    {
        $json = json_encode($item, JSON_PRETTY_PRINT);
        if (gettype($item?->slug) !== "string") $this->throwError("Invalid type of slug, it musts be string", $json);
        if (gettype($item?->name) !== "string") $this->throwError("Invalid type of name, it musts be string", $json);
        if (gettype($item?->description) !== "string") $this->throwError("Invalid type of description, it musts be string", $json);
        if (gettype($item?->image) !== "string") $this->throwError("Invalid type of image, it musts be string", $json);
        if (gettype($item?->thumbnail) !== "string") $this->throwError("Invalid type of thumbnail, it musts be string", $json);
        return true;
    }

    private function validateDataFormat($item): bool
    {
        $json = json_encode($item, JSON_PRETTY_PRINT);
        if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/i', $item->slug)) $this->throwError("Invalid slug, it must be a string with this pattern: /^[a-z0-9]+(?:-[a-z0-9]+)*$/i", $json);
        if (strlen($item?->slug) < 1 || strlen($item->slug) > 100) $this->throwError("Invalid slug, it must be between 1 and 100 characters", $json);
        if (strlen($item?->name) < 1 || strlen($item->name) > 100) $this->throwError("Invalid name, it must be between 1 and 100 characters", $json);
        if (strlen($item?->description) < 1 || strlen($item->description) > 800) $this->throwError("Invalid description, it must be between 1 and 800 characters", $json);
        if (strlen($item?->image) < 1 || strlen($item->image) > 100) $this->throwError("Invalid image, it must be between 1 and 100 characters", $json);
       

        if (strlen($item?->thumbnail) < 1 || strlen($item->thumbnail) > 100) $this->throwError("Invalid thumbnail, it must be between 1 and 100 characters", $json);
        return true;
    }


    public function validateEntry($item): bool
    {
        $this->validateAnyFieldMissing($item);
        $this->validateTypeOfItem($item);
        $this->validateDataFormat($item);
        return true;
    }


    public function run(): void
    {

        $file = File::get('database/data/kingdoms.json');
        $kingdoms = json_decode($file);
        $appUrl = env('APP_URL');

        foreach ($kingdoms as $kingdom => $value) {
            $this->validateEntry($value);
            Kingdom::create([
                'slug' => $value->slug,
                'name' => $value->name,
                'description' => $value->description,
                'thumbnail' => $value->thumbnail,
                'image' => $value->image
            ]);
        }
    }
}
