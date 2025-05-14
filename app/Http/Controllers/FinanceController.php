<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', today()->toDateString());
        $startDate = $request->input('start_date', today()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', today()->endOfMonth()->toDateString());

        // Daily stats
        $dailySales = Sale::whereDate('sale_date', $selectedDate)->get();
        $dailyRevenue = $dailySales->sum('total_price');
        $dailyProfit = $dailySales->sum(function ($sale) {
            return ($sale->product->selling_price - $sale->product->base_price) * $sale->quantity;
        });

        // Monthly stats
        $monthlySales = Sale::whereBetween('sale_date', [$startDate, $endDate])->get();
        $monthlyRevenue = $monthlySales->sum('total_price');
        $monthlyProfit = $monthlySales->sum(function ($sale) {
            return ($sale->product->selling_price - $sale->product->base_price) * $sale->quantity;
        });

        // Check for high revenue notification
        if ($dailyRevenue > 10000000) {
            Notification::firstOrCreate([
                'type' => 'high_revenue',
                'message' => "Kamu berhasil mendapatkan Rp " . number_format($dailyRevenue, 0, ',', '.') . " di hari ini!"
            ]);
        }

        // Chart data
        $chartData = $this->getChartData($startDate, $endDate);

        return view('finance.index', compact(
            'dailySales',
            'dailyRevenue',
            'dailyProfit',
            'monthlyRevenue',
            'monthlyProfit',
            'selectedDate',
            'startDate',
            'endDate',
            'chartData'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date', today()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', today()->endOfMonth()->toDateString());

        return Excel::download(new SalesExport($startDate, $endDate), 'sales_report.xlsx');
    }

    private function getChartData($startDate, $endDate)
    {
        $sales = Sale::whereBetween('sale_date', [$startDate, $endDate])
            ->selectRaw('DATE(sale_date) as date, SUM(total_price) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $data = [];

        $currentDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        while ($currentDate <= $endDate) {
            $dateString = $currentDate->toDateString();
            $labels[] = $currentDate->format('d M');

            $sale = $sales->firstWhere('date', $dateString);
            $data[] = $sale ? $sale->revenue : 0;

            $currentDate->addDay();
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}
