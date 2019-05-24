<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contract;
use App\Invoice;
use App\Item;
use App\Payment;
use App\Opportunity;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $cards = [
            [
                'title' => 'All Contracts',
                'type' => 'value',
                'value' => Contract::count()
            ],
            [
                'title' => 'All Item',
                'type' => 'value',
                'value' => Item::count()
            ],
            [
                'title' => 'UnPaid Invoice',
                'type' => 'value',
                'value' => Invoice::where('status', 'sent')->count()
            ],
            [
                'title' => 'Paid Invoice',
                'type' => 'chart',
                'color' => '#6be6c1',
                'value' => $this->getChart(Invoice::where('status', 'paid'), 'issue_date')
            ],
            [
                'title' => 'Opportunities Lost',
                'type' => 'chart',
                'color' => '#6be6c1',
                'value' => $this->getChart(Opportunity::where('status', 'lost'), 'created_at')
            ],
            [
                'title' => 'Opportunities Won',
                'type' => 'chart',
                'color' => '#96dee8',
                'value' => $this->getChart(Opportunity::where('status', 'won'), 'created_at')
            ],
            [
                'title' => 'New Opportunities',
                'type' => 'value',
                'value' => Opportunity::where('status', 'new')->count()
            ],
            [
                'title' => 'Deposited Payments',
                'type' => 'chart',
                'color' => '#6be6c1',
                'value' => $this->getChart(Payment::where('status', 'deposited'), 'payment_date')
            ],
            [
                'title' => 'Undeposited Funds',
                'type' => 'value',
                'value' => Payment::where('status', 'undeposited')->count()
            ]
        ];

        return response()
            ->json(['cards' => $cards]);
    }

    public function getChart($model, $column)
    {
        $valueFormat = DB::raw("DATE_FORMAT(".$column.", '%d') as value");
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        $dates = [];
        $run = $start->copy();

        while($run->lte($end)){
            $dates = array_add($dates, $run->copy()->format('d'), 0);
            $run->addDay(1);
        }

        $res = $model->groupBy($column)
            ->select(DB::raw('count(*) as total'), $valueFormat)
            ->pluck('total', 'value');

        $all = $res->toArray() + $dates;

        ksort($all);

        return collect(array_values($all))->map(function($item){
            return ['value' => $item];
        });
    }
}
