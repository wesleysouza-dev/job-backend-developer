<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use Carbon\Carbon;

class ProductCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:import {--id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products into your database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $queryStringID = $this->option('id');
        $response = Http::get(env('APP_URL_API_PRODUCTS') . '/' . $this->option('id'));

        if ($response->ok()) {
            $products = $response->object();

            if (gettype($products) === 'object')
                $products = [0 => $products];

            $products = collect($products);

            if ($products->count() > 0) {
                $products->transform(function($item, $key) {
                    return [
                        'name' => $item->title,
                        'price' => $item->price,
                        'description' => $item->description,
                        'category' => $item->category,
                        'image_url' => $item->image,
                    ];
                });


                $listProductToInsert = $products->count() > 1 ? $products->toArray() : $products[0];

                $URL_MY_BASE_API = env('APP_URL_API');

                $result = Http::acceptJson()->post($URL_MY_BASE_API . '/products', $products->toArray());

                /* SEM USAR POST API
                * Outra maneira de fazer

                    $productInsert = new ProductController();
                    $result = $productInsert->store(new StoreProductRequest ($listProductToInsert));
                    $result = Product::create($listProductToInsert);

                */

                //
                if ($result->successful())
                    return $this->info('Product(s) inserted successfully!');

                $this->error('Errors found: ' . $result->body());

            } else {
                return $this->warn('Product not found!');
            }
        }

        return $this->error('Product not inserted in the database.');;
    }
}
