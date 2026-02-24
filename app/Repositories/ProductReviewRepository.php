<?php

namespace App\Repositories;

use App\Interfaces\ProductReviewRepositoryInterface;
use App\Models\ProductReview;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductReviewRepository implements ProductReviewRepositoryInterface
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $productReview = new ProductReview;
            $productReview->transaction_id = $data['transaction_id'];
            $productReview->product_id = $data['product_id'];
            $productReview->rating = $data['rating'];
            $productReview->review = $data['review'];
            $productReview->save();

            DB::commit();

            return $productReview;
        } catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }
    }
}
