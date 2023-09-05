<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Product\CartRequest;
use App\Http\Resources\CartResource;
use App\Http\Resources\ProductResource;
use App\Http\Utils\ResponseBuilder;
use App\UseCases\CartCase;
use App\UseCases\ProductCase;

class ProductController extends Controller
{
    private CartCase $cartCase;
    private ProductCase $productCase;
    private ResponseBuilder $responseBuilder;

    public function __construct(CartCase $cartCase,
                                ProductCase $productCase,
                                ResponseBuilder $responseBuilder)
    {
        $this->middleware('auth:api', ['except' => ['getAll', 'getById']]);
        $this->cartCase = $cartCase;
        $this->productCase = $productCase;
        $this->responseBuilder = $responseBuilder;
    }
    public function getAll()
    {
        $products = $this->productCase->with(['category'])->getCollection();

        return ProductResource::collection($products);
    }

    public function getById($productId)
    {
        $product = $this->productCase->with(['category'])
            ->where('id', $productId)
            ->first();
        if ($product == null) {
            return $this->responseBuilder->apiError(404, 'Продукт не найден', 404);
        }

        return ProductResource::make($product);
    }

    public function addToCart($productId, CartRequest $request)
    {
        $product = $this->productCase->with(['category'])
            ->where('id', $productId)
            ->first();
        if ($product == null) {
            return $this->responseBuilder->apiError(404, 'Продукт не найден', 404);
        }

        if (!$product->is_available) {
            return $this->responseBuilder->apiError(400, 'Продукт нет в наличии', 400);
        }

        $data = [
            'user_id' => auth()->user()->id,
            'product_id' => $product->id,
            'quantity' => $request->get('quantity'),
            'amount' => $request->get('quantity') * $product->price
        ];
        $this->cartCase->store($data);

        return $this->responseBuilder->apiSuccess(['success' => true]);
    }

    public function myCart()
    {
        $carts = $this->cartCase
            ->with(['product'])
            ->where('user_id', auth()->user()->id)
            ->getCollection();

        return CartResource::collection($carts);
    }

    public function deleteCart($cartId)
    {
        $cart = $this->cartCase
            ->with(['product'])
            ->where('user_id', auth()->user()->id)
            ->where('id', $cartId)
            ->first();

        if ($cart == null) {
            return $this->responseBuilder->apiError(400, 'В корзине не найдено', 400);
        }
        $cart->delete();

        return $this->responseBuilder->apiSuccess(['success' => true]);
    }
}
