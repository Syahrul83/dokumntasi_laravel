<?php

// cara untuk membuat laravel di laragon
// 1. download laragon di https://laragon.org/download/
// 2. install laragon di C:\laragon
// 3. buak menu laragon dan pilih menu quick app > laravel
// 4. isi nama project laravel yang akan dibuat, misal laravel-1
// 5. laragon akan membuat folder project laravel di C:\laragon\www\laravel-1
// 6. buka folder project laravel di C:\laragon\www\laravel-1  di terminal dengan cara cd ke folder tersebut 
// 7. jalankan perintah code . untuk membuka project laravel di visual studio code
// 8. buka file .env dan ubah DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD sesuai dengan database yang akan digunakan
// 9. buka file database.php dan ubah DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD sesuai dengan database yang akan digunakan
// 10. jalankan perintah php artisan migrate untuk menjalankan migration

// jelaskan laravel mvc model view controller
// 1. Model: berfungsi untuk mengelola data, berinteraksi dengan database, dan menyediakan logika bisnis.
// 2. View: berfungsi untuk menampilkan data kepada pengguna, biasanya berupa HTML.
// 3. Controller: berfungsi untuk mengatur logika bisnis dan memanggil model dan view.
// 4. Route: berfungsi untuk mengatur rute aplikasi, menghubungkan URL dengan controller dan aksi yang sesuai.
// 6. Migration: berfungsi untuk mengelola struktur database, seperti membuat, mengubah, dan menghapus tabel.


// tutorial laravel
//  php artisan untuk menjalankan artisan command
//  php artisan serve untuk menjalankan server lokal
//  php artisan make:model ModelName -m untuk membuat model dan migration
//  php artisan migrate untuk menjalankan migration
//  php artisan make:controller ControllerName untuk membuat controller
//  php artisan migrate:refresh untuk menghapus dan menjalankan ulang migration
// php artisan migrate:refresh --path=/database/migrations/2023_10_01_000000_create_users_table.php untuk menghapus dan menjalankan ulang migration tertentu

// TUTORIAL LARAVEL CRUD
// 1. Buat model dan migration dengan perintah php artisan make:model Product -m
// 2. Buka file migration di database/migrations/2023_10_01_000000_create_products_table.php
// 3. Buat controller dengan perintah php artisan make:controller ProductController
// 4. Buat file view dengan perintah php artisan make:view products.index
// 5. Buka file routes/web.php dan tambahkan rute untuk controller
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController; 

Route::get('/products', [ProductController::class, 'index'])->name('products.index');   
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');  
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
// 6. Buka file ProductController.php di app/Http/Controllers/ProductController.php
// 7. Buka file view di resources/views/products/index.blade.php
// 8. Buka file view di resources/views/products/create.blade.php
// 9. Buka file view di resources/views/products/edit.blade.php
// 10. Buka file view di resources/views/products/show.blade.php
// 11. Buka file view di resources/views/layouts/app.blade.php
// 12. Buka file view di resources/views/layouts/header.blade.php

// CONTOH CRUD LARAVEL
// ProdukController.php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product; 

class ProductController extends Controller      
{      
    public function index()      
    {      
        $products = Product::all();      
        return view('products.index', compact('products'));      
    }
    public function create()      
    {      
        return view('products.create');      
    }
    public function store(Request $request)      
    {      
        $request->validate([      
            'name' => 'required',      
            'price' => 'required|numeric',      
        ]);      
        Product::create($request->all());      
        return redirect()->route('products.index')->with('success', 'Product created successfully.');      
    }
    public function edit($id)      
    {      
        $product = Product::findOrFail($id);      
        return view('products.edit', compact('product'));      
    }
    public function update(Request $request, $id)      
    {      
        $request->validate([      
            'name' => 'required',      
            'price' => 'required|numeric',      
        ]);      
        $product = Product::findOrFail($id);      
        $product->update($request->all());      
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');      
    }
    public function destroy($id)      
    {
        $product = Product::findOrFail($id);      
        $product->delete();      
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
    public function show($id)      
    {      
        $product = Product::findOrFail($id);      
        return view('products.show', compact('product'));      
    }
}
// Product.php (Model)
<?php       
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Product extends Model      
{      
    use HasFactory;      
    protected $fillable = ['name', 'price'];      
}
// 2023_10_01_000000_create_products_table.php (Migration)
<?php       
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateProductsTable extends Migration      
{      
    public function up()      
    {      
        Schema::create('products', function (Blueprint $table) {      
            $table->id();      
            $table->string('name');      
            $table->decimal('price', 8, 2);      
            $table->timestamps();      
        });      
    }
    public function down()      
    {      
        Schema::dropIfExists('products');      
    }      
}
// resources/views/products/index.blade.php (View)
@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Products</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary">Create Product</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-info">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection