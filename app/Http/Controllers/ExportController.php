<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Ingredient;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesExport;
use App\Exports\InventoryExport;

class ExportController extends Controller
{
    // SALES EXPORT
    public function exportSales()
    {
        return Excel::download(new SalesExport, 'sales.xlsx');
    }

    // INVENTORY EXPORT
    public function exportInventory()
    {
        return Excel::download(new InventoryExport, 'inventory.xlsx');
    }
}
