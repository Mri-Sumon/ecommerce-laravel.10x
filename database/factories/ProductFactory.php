<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $title = fake()->unique()->name();
        $slug = Str::slug($title);

        $subCategories = [2,4];
        $subCatRandKey = array_rand($subCategories);

        $brands = [1,2,3,4,5,6,7];
        $brandRandKey = array_rand($brands);

        return [
            'category_id' => 30,
            'sub_category_id' => $subCategories[$subCatRandKey],
            'brand_id' => $brands[$brandRandKey],
            'title' => $title,
            'slug' => $slug,
            'description' => fake()->sentence(),
            'price' => rand(10,10000),
            'compare_price' => rand(10,10000),
            'is_featured' => 'Yes',
            'is_top_selling' => 'Yes',
            'sku' => rand(1000,100000),
            'barcode' => rand(1000,100000),
            'track_qty' => 'Yes',
            'qty' => 100,
            'status' => 1,
            'sort' => rand(1000,100000),
            'created_by' => 1,
        ];

    }
}
