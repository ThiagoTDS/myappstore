<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AdminProductController extends Controller
{
    public function index(){
        $products = Product::all(); 
        return view('admin.products', compact('products'));
    }

    //Envia para pagina de edição
    public function edit(Product $product){
        return view('admin.product_edit', compact('product'));
        
    }

     // Recebe requisição para dar update PUT
    public function update(Request $request, Product $product){
        $input = $request->validate([
            'name' => 'string|required',
            'price'=> 'string|required',
            'stock'=> 'integer|nullable',
            'cover'=> 'file|nullable',
            'description'=> 'string|nullable',
        ]);
         // Gera o slug a partir do nome do produto
        $input['slug'] = Str::slug($input['name']);

        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            if (!empty($product->cover) && File::exists(public_path($product->cover))) {
                File::delete(public_path($product->cover));
            }
            $file = $request->file('cover');
            $fileName = time().'_'.$file->getClientOriginalName(); 
            $file->move(public_path('products'), $fileName);
            $input['cover'] = 'products/'.$fileName;
        }
        $product->update($input);
        return Redirect::route('admin.products')->with('success','Produto atualizado com sucesso!');
    }

    //Envia para pagina de criar produto
    public function create(){
        return view('admin.product_create');
    }

    //Recebe a requisição de criar
    public function store(Request $request){
        $input = $request->validate([
            'name' => 'string|required',
            'price'=> 'string|required',
            'stock'=> 'integer|nullable',
            'cover'=> 'file|nullable',
            'description'=> 'string|nullable'
        ]);
    
        $input['slug'] = Str::slug($input['name']);
    
        // Verifica se há um arquivo válido e faz o upload
        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $file = $request->file('cover');
            $path = $file->move(public_path('products'),$file->getClientOriginalName());
            $input['cover'] = 'products/'.$file->getClientOriginalName();
        }
    
        Product::create($input);
    
        return Redirect::route('admin.products')->with('success', 'Produto criado com sucesso!');
    }

    public function destroy(Product $product){
        if(!empty($product->cover) && File::exists(public_path($product->cover))){
            File::delete(public_path($product->cover));
        };
        $product->delete();
        return Redirect::route('admin.products')->with('success', 'Produto deletado com sucesso');
    }
}

