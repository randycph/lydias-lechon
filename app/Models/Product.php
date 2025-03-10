<?php

namespace App\Models;

use App\EcommerceModel\Wishlist;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\EcommerceModel\Member;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use SoftDeletes;

    public $table = 'products';
    protected $fillable = [ 'code', 'category_id', 'name', 'slug', 'short_description', 'description', 'currency', 'price', 'size', 'weight', 'no_of_pax', 'for_sale', 'status', 'is_featured', 'uom', 'created_by', 'meta_title', 'meta_keyword', 'meta_description','is_group','paella_price','for_sale_web','for_sale_kiosk','is_misc','production_item' ];

    public function get_url()
    {
        return env('APP_URL')."/products/".$this->slug;
    }

    public function getPriceWithCurrencyAttribute()
    {
    	return " ".number_format($this->price,2);
    }

    public function getInstallationFeeWithCurrencyAttribute()
    {
        return ucfirst(strtolower($this->currency))." ".number_format($this->installation_fee,2);
    }

    public function tags(){
        return $this->hasMany(ProductTag::class);
    }

    public function category(){
        return $this->belongsTo(ProductCategory::class);
    }

    public static function colors($value){

        $colors = DB::table('products_variations')->select('color')->distinct()->where('product_id',$value)->get();
        return $colors;

    }

    public static function sizes($value){

        $sizes = DB::table('products_variations')->select('size')->distinct()->where('product_id',$value)->get();
        return $sizes;

    }

    public function photos()
    {
        return $this->hasMany(ProductPhoto::class);
    }

    public function getPhotoPrimaryAttribute()
    {
        $photo = $this->photos()->where('is_primary', 1)->first();
        if(!$photo){
            return '0/no_image_available.PNG';
        }
        else{
            return $photo->path;
        }
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function getRatingAttribute()
    {
        return $this->reviews->avg('rating');
    }

    public function getRatingStarAttribute(){
        $star = 5 - (integer) $this->rating;
        $front = '';
        for($x = 1; $x<=$this->rating; $x++){
            $front.='<span class="fa fa-star checked"></span>';
        }

        for($x = 1; $x<=$star; $x++){
            $front.='<span class="fa fa-star"></span>';
        }

        return $front;
    }

    public static function related_products($id){

        $products = Product::whereStatus('PUBLISHED')->where('id','<>',$id)->take(3)->get();


        $data = '';

        foreach($products as $product){
            $data .= '
                <div class="col-md-4 col-sm-6 item">
                    <div class="product-link">
                        <div class="product-card">
                            <a href="'.route("product.front.show",$product->slug).'">
                                <div class="product-img">
                                    <img src="'.asset("storage/products/".$product->photoPrimary).'" alt="" />
                                </div>
                                <div class="gap-30"></div>
                                <p class="product-title">'.$product->name.'</p>
                            </a>
                            <div class="rating small">
                                '.$product->ratingStar.'
                            </div>
                            <h3 class="product-price">'.$product->priceWithCurrency.'</h3>
                        </div>
                    </div>
                </div>
            ';
        }

        return $data;

    }

    public static function totalProduct()
    {
        $total = Product::withTrashed()->get()->count();

        return $total;
    }

    public function is_editable()
    {
        return $this->status != 'UNEDITABLE';
    }

    public static function info($p){

        $pd = Product::where('name','=',$p)->where('status','PUBLISHED')->first();
        //if(!$pd) { logger($p); }
        return $pd;
    }

    public static function detail($p){

        $pd = Product::where('name',$p)->where('status','PUBLISHED')->get();

        return $pd;
    }

    public static function misc(){

        $pd = Product::where('is_misc',1)->where('status','PUBLISHED')->orderBy('name')->get();

        return $pd;
    }
    
    
    public static function menu_products($categoryId)
    {
        $ids = [$categoryId];
        $category = ProductCategory::find($categoryId);
        $subCategories = $category->child_categories;
        if ($subCategories && $subCategories->count()) {
            foreach($subCategories as $category){
                if (!in_array($category->id, $ids)){
                    array_push($ids, $category->id);
                }
            }
        }

        $products = Product::whereStatus('PUBLISHED')->whereIn('category_id', $ids)->distinct()->get(['name']);

        return $products;
    }


    // Need to change every model
    static $oldModel;
    static $tableTitle = 'product';
    static $name = 'name';
    // END Need to change every model

    public static function boot()
    {
        parent::boot();

        self::created(function($model) {
            $name = $model[self::$name];
            ActivityLog::create([
                'created_by' => auth()->id(),
                'activity_type' => 'insert',
                'dashboard_activity' => 'created a new '. self::$tableTitle,
                'activity_desc' => 'created the '. self::$tableTitle .' '. $name,
                'activity_date' => date("Y-m-d H:i:s"),
                'db_table' => $model->getTable(),
                'old_value' => '',
                'new_value' => $name,
                'reference' => $model->id
            ]);
        });

        self::updating(function($model) {
            self::$oldModel = $model->fresh();
        });

        self::updated(function($model) {
            $name = $model[self::$name];
            $unrelatedFields = ['id', 'created_at', 'updated_at', 'deleted_at'];
            $oldModel = self::$oldModel->toArray();
            foreach ($oldModel as $fieldName => $value) {
                if (in_array($fieldName, $unrelatedFields)) {
                    continue;
                }

                $oldValue = $model[$fieldName];
                if ($oldValue != $value) {
                    $fieldNames = implode(' ', explode('_', $fieldName));
                    ActivityLog::create([
                        'created_by' => auth()->id(),
                        'activity_type' => 'update',
                        'dashboard_activity' => 'updated the '. self::$tableTitle .' '. $fieldNames,
                        'activity_desc' => 'updated the '. self::$tableTitle .' '. $fieldNames .'of '. $name .' from '. $oldValue .' to '. $value,
                        'activity_date' => date("Y-m-d H:i:s"),
                        'db_table' => $model->getTable(),
                        'old_value' => $oldValue,
                        'new_value' => $value,
                        'reference' => $model->id
                    ]);
                }
            }
        });

        self::deleted(function($model){
            $name = $model[self::$name];
            ActivityLog::create([
                'created_by' => auth()->id(),
                'activity_type' => 'delete',
                'dashboard_activity' => 'deleted a '. self::$tableTitle,
                'activity_desc' => 'deleted the '. self::$tableTitle .' '. $name,
                'activity_date' => date("Y-m-d H:i:s"),
                'db_table' => $model->getTable(),
                'old_value' => $name,
                'new_value' => '',
                'reference' => $model->id
            ]);
        });
    }
}
