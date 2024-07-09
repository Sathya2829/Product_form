<?php
namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProductCreated;

class ProductController extends Controller
{
    public function create()
    {
        return view('product.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sku' => 'required|string|max:255|unique:products',
            'description' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/images');
                $images[] = $path;
            }
        }

        $product = new Product([
            'name' => $request->get('name'),
            'price' => $request->get('price'),
            'sku' => $request->get('sku'),
            'description' => $request->get('description'),
            'images' => json_encode($images),
        ]);

        $product->save();
// Send email to admin
        Mail::to('sakthivel.p12@infosys.com')->send(new ProductCreated($product));

        return redirect()->route('product.index');
    }

    public function index()
    {
        $products = Product::all();
        return view('product.index', compact('products'));
    }

    public function export()
{
    $products = Product::all();
    $filename = "products_" . date('Ymd') . ".csv";
    $handle = fopen($filename, 'w+');
    fputcsv($handle, ['Name', 'Price', 'SKU', 'Description', 'Images']);
 
    foreach ($products as $product) {
        $images = implode(",", json_decode($product->images, true));
        fputcsv($handle, [$product->name, $product->price, $product->sku, $product->description, $images]);
    }
 
    fclose($handle);
 
    $headers = [
        'Content-Type' => 'text/csv',
    ];
 
    return response()->download($filename, $filename, $headers)->deleteFileAfterSend(true);
}

public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt',
        ]);
 
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
 
        $path = $request->file('file')->getRealPath();
        $file = fopen($path, 'r');
        $header = fgetcsv($file);
 
        while ($columns = fgetcsv($file)) {
            $data = array_combine($header, $columns);
 
            Product::updateOrCreate(
                ['sku' => $data['SKU']],
                [
                    'name' => $data['Name'],
                    'price' => $data['Price'],
                    'description' => $data['Description'],
                    'images' => json_encode(explode(',', $data['Images'])),
                ]
            );
        }
 
        fclose($file);
 
        return redirect()->route('product.index')->with('success', 'Products imported successfully.');
    }
}
