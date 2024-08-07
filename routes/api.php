<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\BranchMenuController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login'])->name('api.auth.login');

Route::group(['middleware' => ['auth:sanctum', 'active']], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    Route::get('profile', [ProfileController::class, 'index'])->name('api.profile.index');
    Route::post('profile', [ProfileController::class, 'update'])->name('api.profile.update');
    Route::post('profile/password', [ProfileController::class, 'passwordUpdate'])->name('api.profile.password.update');

    Route::get('branch-paginate', [BranchController::class, 'paginate'])->name('api.branches.paginate');
    Route::apiResource('branches', BranchController::class)->names('api.branches');

    Route::get('menu-paginate', [MenuController::class, 'paginate'])->name('api.menus.paginate');
    Route::apiResource('menus', MenuController::class)->names('api.menus');

    Route::get('branch-menu-paginate', [BranchMenuController::class, 'paginate'])->name('api.branch_menus.paginate');
    Route::apiResource('branch_menus', BranchMenuController::class)->names('api.branch_menus');

    Route::get('user-paginate', [UserController::class, 'paginate'])->name('api.users.paginate');
    Route::apiResource('users', UserController::class)->names('api.users');

    Route::get('cart-paginate', [CartController::class, 'paginate'])->name('api.carts.paginate');
    Route::apiResource('carts', CartController::class)->names('api.carts');

    Route::get('order-paginate', [OrderController::class, 'paginate'])->name('api.orders.paginate');
    Route::apiResource('orders', OrderController::class)->names('api.orders');
});
