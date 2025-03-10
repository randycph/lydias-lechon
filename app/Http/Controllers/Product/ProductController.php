<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Helpers\ListingHelper;
use App\Http\Requests\ProductRequest;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Models\ProductVariation;
use App\Models\ProductCategory;
use App\Models\ProductPhoto;
use App\Models\ProductTag;
use App\Models\Product;
use App\Models\Page;
use Illuminate\Support\Facades\Input;
class ProductController extends Controller
{
    private $searchFields = ['name'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        Permission::module_init($this, 'product');
    }

    public function index()
    {
        $customConditions = [
            [
                'field' => 'status',
                'operator' => '!=',
                'value' => 'UNEDITABLE',
                'apply_to_deleted_data' => true
            ]
        ];

        $listing = new ListingHelper( 'desc', 10, 'updated_at', $customConditions);

        $products = $listing->simple_search(Product::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.products.index',compact('products', 'filter', 'searchType'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ProductCategory::get();

        return view('admin.products.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        //dd($request);
        $is_group = ($request->total_sizes > 1) ? 1 : 0;
        for($x = 1; $x <= $request->total_sizes; $x++){
            $slug = Page::convert_to_slug($request->name);

            $product = Product::create([
                'code' => $request->code,
                'category_id' => empty($request->size) ? '0' : $request->category,
                'name' => $request->name,
                'slug' => $slug,
                'short_description' => $request->short_description,
                'description' => $request->long_description,
                'currency' => 'PHP',
                'price' => Input::get('price'.$x.''),
                'size' => $request->size,
                'weight' => Input::get('weight'.$x.''),
                'no_of_pax' => Input::get('pax'.$x.''),
                'paella_price' => empty($request->paella_price) ? '0' : $request->paella_price,
                'is_group' => $is_group,
                'for_sale' => $request->has('for_sale'),
                'status' => ($request->has('status') ? 'PUBLISHED' : 'PRIVATE'),
                'is_featured' => $request->has('is_featured'),
                'uom' => 'PC',
                'for_sale_web' => $request->has('for_sale_web'),
                'for_sale_kiosk' => $request->has('for_sale_kiosk'),
                'is_misc' => $request->has('is_misc'),
                'production_item' => $request->has('production_item'),
                'meta_title' => $request->seo_title,
                'meta_keyword' => $request->seo_keywords,
                'meta_description' => $request->seo_description,
                'created_by' => Auth::id(),
            ]);

            $this->tags($product->id, $request->tags);

            $newPhotos = $this->set_order(request('photos'));
            $productPhotos = $this->move_product_to_official_folder($product->id, $newPhotos);



            foreach ($productPhotos as $key => $photo) {
                ProductPhoto::create([
                    'product_id' => $product->id,
                    'name' => (empty($photo['name']) ? '' : $photo['name']),
                    'description' => '',
                    'path' => $photo['image_path'],
                    'status' => 'PUBLISHED',
                    'is_primary' => ($key == $request->is_primary) ? 1 : 0,
                    'created_by' => Auth::id()
                ]);
            }
        }
        $this->delete_temporary_product_folder();
        return redirect()->route('products.index')->with('success', __('standard.products.product.create_success'));

        //return $request;
    }

    public function tags($id,$tags)
    {
        foreach(explode(',',$tags) as $tag)
        {
            ProductTag::create([
                'product_id' => $id,
                'tag' => $tag,
                'created_by' => Auth::id()
            ]);
        }
    }

//    public function variations($id,$colors,$sizes)
//    {
//        foreach(explode(',',$colors) as $color)
//        {
//            foreach(explode(',',$sizes) as $size)
//            {
//                ProductVariation::create([
//                    'product_id' => $id,
//                    'color' => $color,
//                    'size' => $size
//                ]);
//            }
//        }
//    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = ProductCategory::get();

        return view('admin.products.edit',compact('product','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {

        $product = Product::findOrFail($id);

//        $colors = ProductVariation::colors($id);
//        $sizes  = ProductVariation::sizes($id);

        if($product->name == $request->name){
            $slug = $product->slug;
        }
        else{
            $slug = Page::convert_to_slug($request->name);
        }

        $product->update([
            'code' => $request->code,
            'category_id' => $request->category,
            'name' => $request->name,
            'slug' => $slug,
            'short_description' => $request->short_description,
            'description' => $request->long_description,
            'currency' => $request->currency,
            'price' => $request->price,
            'paella_price' => $request->paella_price,
            'size' => $request->size,
            'weight' => $request->weight,
            'no_of_pax' => $request->no_of_pax,
            'for_sale' => $request->has('for_sale'),
            'status' => ($request->has('status') ? 'PUBLISHED' : 'PRIVATE'),
            'is_featured' => $request->has('is_featured'),
            'uom' => '',
            'for_sale_web' => $request->has('for_sale_web'),
            'for_sale_kiosk' => $request->has('for_sale_kiosk'),
            'is_misc' => $request->has('is_misc'),
            'production_item' => $request->has('production_item'),
            'meta_title' => $request->seo_title,
            'meta_keyword' => $request->seo_keywords,
            'meta_description' => $request->seo_description,
            'created_by' => Auth::id()
        ]);

//        if($colors <> $request->colors || $sizes <> $request->sizes){
//            $this->update_tags($product->id,$request->tags);
//            $this->update_variations($product->id,$request->colors,$request->sizes);
//        };

        $this->update_tags($product->id,$request->tags);

        $photos = $this->set_order(request('photos'));

        $this->update_photos($this->get_product_photos($photos));

        $this->remove_photos_from_product(request('remove_photos'));

        $newPhotos = $this->get_new_photos($photos);

        $newPhotos = $this->move_product_to_official_folder($product->id, $newPhotos);

        foreach ($newPhotos as $key => $photo) {
            ProductPhoto::create([
                'product_id' => $product->id,
                'name' => (empty($photo['name']) ? '' : $photo['name']),
                'description' => '',
                'path' => $photo['image_path'],
                'status' => 'PUBLISHED',
                'is_primary' => ($key == $request->is_primary) ? 1 : 0,
                'created_by' => Auth::id()
            ]);
        }

        return redirect()->route('products.index')->with('success', __('standard.products.product.update_success'));
    }

    public function update_photos($photos)
    {
        foreach ($photos as $photo) {
            if ($photo) {
                $photo['name'] = ($photo['name']) ? $photo['name'] : '';
                ProductPhoto::find($photo['id'])->update($photo);
            }
        }
    }

    public function update_tags($id,$tags)
    {
        $delete = ProductTag::where('product_id',$id)->forceDelete();

        if($delete){
            foreach(explode(',',$tags) as $tag){
                ProductTag::create([
                    'product_id' => $id,
                    'tag' => $tag,
                    'created_by' => Auth::id()
                ]);
            }
        }

    }

//    public function update_variations($id,$colors,$sizes)
//    {
//        $delete = ProductVariation::where('product_id',$id)->forceDelete();
//
//        if($delete){
//            foreach(explode(',',$colors) as $color)
//            {
//                foreach(explode(',',$sizes) as $size)
//                {
//                    $data = $color.' - '.$size;
//                    ProductVariation::create([
//                        'product_id' => $id,
//                        'color' => $color,
//                        'size' => $size
//                    ]);
//                }
//            }
//        }
//
//    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function get_slug(Request $request)
    {
        return Page::convert_to_slug($request->url, $request->parentPage);
    }

    public function restore($id)
    {
        Product::withTrashed()->find($id)->update(['created_by' => Auth::id() ]);
        Product::whereId($id)->restore();

        return back()->with('success', __('standard.products.product.restore_product_success'));
    }

    public function change_status($id,$status)
    {
        Product::where('id',$id)->update([
            'status' => $status,
            'created_by' => Auth::id()
        ]);

        return back()->with('success', __('standard.products.product.update_status_success', ['STATUS' => $status]));
    }

    public function single_delete(Request $request)
    {
        $product = Product::findOrFail($request->products);
        $product->update([ 'created_by' => Auth::id() ]);
        $product->delete();

        return back()->with('success', __('standard.products.product.single_delete_success'));

    }

    public function multiple_change_status(Request $request)
    {
        $products = explode("|", $request->products);

        foreach ($products as $product) {
            $publish = Product::where('status', '!=', $request->status)->whereId($product)->update([
                'status'  => $request->status,
                'created_by' => Auth::id()
            ]);
        }

        return back()->with('success',  __('standard.products.product.change_status_success', ['STATUS' => $request->status]));
    }

    public function multiple_delete(Request $request)
    {
        $products = explode("|",$request->products);

        foreach($products as $product){
            Product::whereId($product)->update(['created_by' => Auth::id() ]);
            Product::whereId($product)->delete();
        }

        return back()->with('success', __('standard.products.product.multiple_delete_success'));
    }

// save files to temporary folder
    public function upload(Request $request)
    {
        if ($request->hasFile('banner')) {

            $newFile = $this->upload_file_to_temporary_storage($request->file('banner'));

            return response()->json([
                'status' => 'success',
                'image_url' => $newFile['url'],
                'image_name' => $newFile['name'],
                'image_path' => $newFile['path'],
            ]);
        }

        return response()->json([
            'status' => 'failed',
            'image_url' => '',
            'image_name' => ''
        ]);
    }

    public function get_product_photos($photos)
    {
        return array_filter($photos, function ($photo) {
            return isset($photo['id']);
        });
    }

    public function get_new_photos($photos)
    {
        return array_filter($photos, function ($photo) {
            return !isset($photo['id']);
        });
    }

    public function remove_photos_from_product($photos)
    {
        ProductPhoto::find($photos ?? [])->each(function ($photo, $key) {
            $imagePath = $this->get_banner_path_in_storage($photo->image_path);
            Storage::disk('public')->delete($imagePath);
            $photo->update(['user_id' => auth()->id()]);
            $photo->delete();

        });
    }

    public function upload_file_to_temporary_storage($file)
    {
        $temporaryFolder = 'temporary_products'.auth()->id();
        $fileName = $file->getClientOriginalName();
        if (Storage::disk('public')->exists($temporaryFolder.'/'.$fileName)) {
            $fileName = $this->make_unique_file_name($temporaryFolder, $fileName);
        }

        $path = Storage::disk('public')->putFileAs($temporaryFolder, $file, $fileName);
        $url = Storage::disk('public')->url($path);

        return [
            'path' => $temporaryFolder.'/'.$fileName,
            'name' => $fileName,
            'url' => $url
        ];
    }
//

// move uploaded product files to official product folder
    public function delete_temporary_product_folder()
    {
        $temporaryFolder = 'temporary_products'.auth()->id();
        Storage::disk('public')->deleteDirectory($temporaryFolder);
    }

    public function set_order($products = [])
    {
        $products = $products ?? [];

        $count = 1;
        foreach($products as $key => $product) {
            $products[$key]['order'] = $count;
            $count += 1;
        }

        return $products;
    }

    public function move_product_to_official_folder($productId, $products)
    {
        foreach ($products as $key => $product) {
            $temporaryPath = $this->get_banner_path_in_storage($products[$key]['image_path']);

            $fileName = $this->get_banner_file_name($products[$key]['image_path']);
            $bannerFolder = '';

            $products[$key]['image_path'] = $this->move_to_products_folder($productId, $temporaryPath, $bannerFolder.$fileName);
        }

        return $products;
    }

    public function get_banner_path_in_storage($path)
    {
        $paths = explode('storage/', $path);

        if (count($paths) == 1) {
            return '';
        }
        //return $path;
        return explode('storage/', $path)[1];
    }

    public function get_banner_file_name($path)
    {
        $temporaryFolder = 'temporary_products'.auth()->id();
        return explode($temporaryFolder, $path)[1];
    }

    public function move_to_products_folder($id,$temporaryPath, $fileName)
    {
        $folder = 'products/'.$id;
        if (Storage::disk('public')->exists($folder.$fileName)) {
            $fileName = $this->make_unique_file_name($folder, $fileName);
        }

        $newPath = $folder.$fileName;

        Storage::disk('public')->copy($temporaryPath, $newPath);
        return $id.$fileName;
    }

    public function make_unique_file_name($folder, $fileName)
    {
        $fileNames = explode(".", $fileName);
        $count = 2;
        $newFilename = $fileNames[0].' ('.$count.').'.$fileNames[1];
        while(Storage::disk('public')->exists($folder.'/'.$newFilename)) {
            $count += 1;
            $newFilename = $fileNames[0].' ('.$count.').'.$fileNames[1];
        }

        return $newFilename;
    }
//

}
