<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CurrencyRate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CurrencyRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $id
     * @param  Request $request
     * @return JsonResponse|Response
     */
    public function index(int $id, Request $request)
    {
        $validator = Validator::make($request->only(...array_keys($date_rules = [
            'date_from' => 'date',
            'date_to' => 'date|after:date_from',
        ])), $date_rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 403);
        }

        $page_size = $request->input('page_size', 10);
        $date_from = $request->input('date_from', false);
        $date_to = $request->input('date_to', false);

        return CurrencyRate::where('currency_id', $id)
            ->where($date_from ? [['date', '>=', $date_from]] : null)
            ->where($date_to ? [['date', '<=', $date_to]] : null)
            ->orderBy('date', 'desc')
            ->paginate($page_size);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $currencyId
     * @param  int  $currencyRateId
     * @return \Illuminate\Http\Response
     */
    public function show(int $currencyId, int $currencyRateId)
    {
        return CurrencyRate::where(['currency_id' => $currencyId])
            ->findOrFail($currencyRateId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $currencyId
     * @param  int  $currencyRateId
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function destroy(int $currencyId, int $currencyRateId)
    {
        $currencyRate = CurrencyRate::where(['currency_id' => $currencyId])
            ->findOrFail($currencyRateId);

        if (!$currencyRate->delete()) {
            return response()->json(['error' => 'Currency Rate cannot be deleted'], 400);
        }

        return response()->json(['message' => 'Currency Rate has been deleted']);
    }
}
