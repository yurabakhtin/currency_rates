<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return LengthAwarePaginator|JsonResponse
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->only('date_from', 'date_to'), [
            'date_from' => 'date',
            'date_to' => 'date|after:date_from',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 403);
        }

        $page_size = $request->input('page_size', 10);
        $date_from = $request->input('date_from', date('Y-m-d'));
        $date_to = $request->input('date_to', date('Y-m-d'));

        return Currency::with(['rates' => function ($query) use ($date_from, $date_to) {
                $query->where($date_from <= $date_to ? [['date', '>=', $date_from]] : null)
                    ->where('date', '<=', $date_to)
                    ->orderBy('date', 'desc');
            }])
            ->paginate($page_size);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Builder|Builder[]|Collection|Model|JsonResponse|Response
     */
    public function show(int $id)
    {
        return Currency::with('rates')
            ->findOrFail($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function destroy(int $id)
    {
        $currency = Currency::with('rates')
            ->findOrFail($id);

        if (!$currency->delete()) {
            return response()->json(['error' => 'Currency cannot be deleted'], 400);
        }

        return response()->json(['message' => 'Currency has been deleted']);
    }
}
