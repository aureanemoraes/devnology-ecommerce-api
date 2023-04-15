<?php

namespace App\Http\Rules;

use App\Http\Requests\Order\ResumeOrderRequest;
use Illuminate\Http\JsonResponse;
use Ramsey\Uuid\Uuid;
use App\Models\Supplier;
use Illuminate\Support\Facades\Http;
use App\Utils\FormatDataUtil;
use Illuminate\Support\Facades\Cache;

class GetOrderResume {
    public function resume(ResumeOrderRequest $request): JsonResponse
    {
        $inputs = $request->all();

        // check if products still exists/are available;
        $info = $this->getCurrentProductsInfo($inputs['items']);

        if (!$info['success']) return response()->error([ 'statusCode' => 404, 'message' => $info['message'], 'errors' => $info['data']['unavailableItems'] ]);

        $resumeId = (string) Uuid::uuid4();

        $resumeInfo = [ 'resume_id' => $resumeId , 'resume_info' => ['items' => $info['data']['availableItems'] ] ];

        // 5 minutes
        Cache::put($resumeId, $resumeInfo , 60 * 60 * 5);

        return response()->success([ 'data' => $resumeInfo ]);
    }

    private function getCurrentProductsInfo($items): Array
    {
        $unavailableItems = [];
        $availableItems = [];

        foreach($items as $item) {
            $supplier = Supplier::where('name', $item['supplier'])->first();

            $url = $supplier->api_base_url . '/' . $item['id'];

            $response = Http::get($url);

            if ($response->failed()) {
                $unavailableItems[] = [ 'id' => $item['id'], 'name' => $item['name'] ];
            }

            if ($response->successful()) {
                $product = (new FormatDataUtil())->format($response->json(), $supplier->name);
                $availableItems[] = [ ...$product, 'supplier_id' => $supplier->id ];
            }
        }

        if (count($unavailableItems) > 0) {
            $items = implode(', ', array_column($unavailableItems, 'name'));
            $message = 'Items: ' . $items . ' are current unavailable.';

            return [ 'success' => false, 'data' => [ 'unavailableItems' => $unavailableItems ], 'message' => $message ];
        } else if (count($items) === count($availableItems))
            return [ 'success' => true, 'data' => [ 'availableItems' => $availableItems ], 'message' => '' ];
        else
            return [ 'success' => false, 'data' => [], 'message' => "Something went wrong. Can't finish this order." ];
    }
}
