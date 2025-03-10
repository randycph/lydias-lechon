<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\ProductReview;

class ProductReviewController extends Controller
{    
    public function store(Request $request)
    {
        $product = ProductReview::create([
            'product_id' => $request->product_id,
            'review' => $request->review,
            'rating' => $request->rating,
            'parent_id' => 0,            
            'user_id' => Auth::id(),
            'is_anonymous' => $request->rating_display_name,
            'is_approved' => 0,
            'approver' => 0
        ]);
        return back();
        //return redirect()->route('products.index')->with('success', __('standard.products.product.create_success'));
    }
}
