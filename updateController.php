<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Registers;
use App\Order;
use App\OrderItems;
use DB;
use Redirect;
use App\Staffs;

class DisplayItemsController extends Controller
{
    public function showStages()
    {
        $a = Product::where('cat_id', 2)->get();
        return $a;
    }

    public function showOrders(){
        $o = Order::where('placed',1)->where('status',0)->orderBy('created_at')->get();
        return view('users.admin.admin_orders_view')->with(compact('o'));
    }
    public function showPendingOrders(){
        $o = Order::where('placed',1)->where('status',1)->orderBy('created_at')->get();
        return view('users.admin.admin_pending_orders')->with(compact('o'));
    }

    public function showCancelledOrders(){
        $o = Order::where('placed',2)->where('status',1)->orderBy('created_at')->get();
        return view('users.admin.admin_cancelled_orders')->with(compact('o'));
    }

    public function viewOrder(){
        $oid = $_POST['oid'];
        $items = DB::select(DB::raw("select * from products, order_items where order_items.oid = $oid and order_items.pid = products.pid order by products.cat_id asc"));

        return $items;
        //view('users.admin.order')->with('items' ,$items);
        //redirect('/order');
    }

    public function apprOrder(){
        $oid = $_POST['oid'];
        DB::table('orders')
            ->where('oid', $oid)
            ->update(['status' => 1]);

    }

    public function decOrder(){
        $oid = $_POST['oid'];
        Order::where('oid', $oid)->delete();
        OrderItems::where('oid', $oid)->delete();
    }

    public function editItems(){
        $items = Product::orderBy('cat_id', 'ASC')->get();
        return view('users.admin.edit_items')->with('items', $items);
    }

    public function editProduct(Request $request){
        $pid = $request -> get('pid');
        $a = Product::where('pid', $pid)->first();
        return view('users.admin.admin_edit_product')->with('product', $a);
    }

    public function updateProduct(Request $request){
        $pid = $request -> get('id');
        $name = $request -> get('name');
        $description = $request -> get('description');
        $image = $request -> file('image');
        $price = $request -> get('price');

        $a = Product::where('pid', $pid)->first();
        $a -> name = $name;
        $a -> description = $description;
        if($image != NULL){
            $name_image = time().'_'.$image->getClientOriginalName();
        $image -> move(public_path().'/images/', $name_image);
            $a -> image = $name_image;
        }


        $a -> price = $request -> get('price');
        $a -> save();
        $b = Product::where('pid', $pid)->first();
        return view('users.admin.admin_edit_product')->with('product', $a);


    }


    public function showCars(){
        return $a = Product::where('cat_id',3)->get();
    }

    public function searchCK(Request $request){
        $x = $request -> get('x');
        return $s_result = DB::select(DB::raw('SELECT name from auditoria as type where name LIKE "%'.$x.'%" UNION SELECT name from products as type where name LIKE "%'.$x.'%"'))  ;
    }

    public function searchCKFood(Request $request){
        $x = $request -> get('x');
        return $s_result = DB::select(DB::raw('SELECT pid, cat_name, name from products where name LIKE "%'.$x.'%"'))  ;
    }

    public function updateQty(Request $r){
         $oid = session('oid');

         $pid = $r -> get('id');
        $n = $r -> get('n');
        DB::table('order_items')
            ->where('oid', $oid)
            ->where('pid', $pid)
            ->update(['qty' => $n]);

    }

    public function updateQtyAll(Request $r){
        $qty = $r -> get('qty');
        $oid = $r -> get('oid');
        // DB::table('order_items')->where('oid', $oid)
        //                         ->update(['qty' => $qty]);

        $a = OrderItems::where('oid', $oid)->get();
        foreach($a as $b){
            $pid = $b -> pid;
            $c = Product::where('pid', $pid)->first();

            if(($c -> cat_id) != 2){
                $b -> qty = $qty;
            }
            $b -> save();
        }
        DB::table('orders')->where('oid', $oid)
                                ->update(['quantity' => $qty]);
    }


    public function RegShowOrder(){
        $uid = session('id');
        $o = Order::where('uid', $uid)->where('placed', 1)->orWhere('placed', 2)->get();
        return view('users.reg_users.reg_users_orders')->with('order',$o);
    }

    public function callStaff(Request $r){

        $num_staff = $r-> get('num_staff');
        $oid = $r -> get('oid');
        DB::table('orders')->where('oid', $oid)
                                ->update(['staff' => $num_staff]);
                                return redirect('/admin_pending_orders');

    }

    public function oidStaff(){
        return $a = Order::where('status', 1)->get();

    }
    public function delOrder($oid){
        DB::table('orders')->where('oid', $oid)
                                ->update(['placed' => 2]);
        redirect()->back();
    }

    public function showServStaff(){
        $a = Registers::where('ulevel', 3)->get();
        return view('users.admin.admin_staff')->with('a', $a);

    }

    public function blockStaff(Request $r){
        $sid = $r ->get('sid');
        DB::table('logins')->where('uid', $sid)
                                ->update(['ublock' => 0]);
                                return redirect('/admin_serv_staff');

    }

    public function unblockStaff(Request $r){
        $sid = $r ->get('sid');
        DB::table('logins')->where('uid', $sid)
                                ->update(['ublock' => 1]);
                                return redirect('/admin_serv_staff');

    }

    public function jobOpenings(){
        $a = Order::where('staff', '>', 0)->get();
        return view('users.staff.openings')->with('a', $a);
    }
    public function upcomingJobs(){
        $a = Order::where('staff', '>', 0)->get();
        return view('users.staff.upcoming')->with('a', $a);
    }
    public function applyJob(Request $r){
        $uid = session('id');
        $oid = $r ->get('oid');
        $a = new Staffs([
            'uid' => $uid,
            'oid' => $oid,
        ]);
        $a -> save();
    }

    public function cancelJob(Request $r){
        $uid = session('id');
        $oid = $r ->get('oid');
        $a = Staffs::where('uid', $uid)->where('oid', $oid)->first();
        $a -> status = 0;
        $a -> save();
    }


}
