<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\Category;
use App\Models\Dashboard;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class DashboardService extends BaseController
{
    public function __construct(
        private Category $category,
        private Product $product,
        private User $user,
        private Sale $sale,
        private Dashboard $dashboard,

    ) {
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function index($request)
    {
        $total_user = $this->user->whereNull('deleted_at')->count();
        $total_product = $this->product->whereNull('deleted_at')->count();
        $total_sale = $this->sale->whereNull('deleted_at')->count();
        $in_progress = 0;
        $schedule_for_delivery = 0;
        $delivered_today =0;
        $in_progress = $this->sale->where('status', 1)->whereNull('deleted_at')->count();
        $schedule_for_delivery = $total_sale - $in_progress;
        $delivered_today = $this->sale->where('status', 2)->whereNull('deleted_at')->count();

        $topTenUsers = $this->dashboard
                        ->where('user_id','!=', null)
                        ->orderBy('count','desc')
                        ->limit(10)
                        ->with([
                            'user' => function ($q) {
                                $q->select('id', 'name','username');
                            }])
                        ->get();
        $topThreeProducts = $this->dashboard
                            ->where('product_id','!=', null)
                            ->orderBy('count', 'desc')
                            ->limit(3)
                            ->with([
                                'product' => function ($q) {
                                    $q->select('id', 'name', 'price')
                                        ->with(['product_image' => function($query){
                                            $query->select('id','images','product_id');
                                        }]);
                                    },
                                ])
                            ->get();


        $data = [
            'total_user' => $total_user,
            'total_sale' => $total_sale,
            'total_product' => $total_product,
            'topTenUsers' => $topTenUsers,
            'topThreeProducts' => $topThreeProducts,
            'order_received'=>$total_sale,
            'in_progress'=>$in_progress,
            'schedule_for_delivery' =>$schedule_for_delivery,
            'delivered_today' => $delivered_today

        ];
        return $this->sendResponse('Caregory Index Success', $data);
    }


}
