<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Daily Sales Report
     */
    public function daily(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        
        $transactions = Transaction::with('transactionDetails.product', 'user')
            ->whereDate('transaction_date', $date)
            ->where('status', 'completed')
            ->orderBy('transaction_date', 'desc')
            ->get();
        
        $totalSales = $transactions->sum('total_amount');
        $totalTransactions = $transactions->count();
        $totalPayment = $transactions->sum('payment_amount');
        
        return view('reports.daily', compact(
            'transactions',
            'totalSales',
            'totalTransactions',
            'totalPayment',
            'date'
        ));
    }

    /**
     * Monthly Sales Report
     */
    public function monthly(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        
        $transactions = Transaction::with('transactionDetails.product', 'user')
            ->whereBetween('transaction_date', [
                Carbon::createFromFormat('Y-m', $month)->startOfMonth(),
                Carbon::createFromFormat('Y-m', $month)->endOfMonth()
            ])
            ->where('status', 'completed')
            ->orderBy('transaction_date', 'desc')
            ->get();
        
        $totalSales = $transactions->sum('total_amount');
        $totalTransactions = $transactions->count();
        $totalPayment = $transactions->sum('payment_amount');
        
        return view('reports.monthly', compact(
            'transactions',
            'totalSales',
            'totalTransactions',
            'totalPayment',
            'month'
        ));
    }

    /**
     * Product Stock Report
     */
    public function products(Request $request)
    {
        $sort = $request->input('sort', 'stock');
        
        $query = Product::with('category');
        
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($sort === 'name') {
            $query->orderBy('name', 'asc');
        } elseif ($sort === 'price') {
            $query->orderBy('price', 'desc');
        } else {
            $query->orderBy('stock', 'asc');
        }
        
        $products = $query->paginate(20);
        
        $categories = Category::all();
        $lowStock = Product::where('stock', '<', 10)->where('stock', '>', 0)->count();
        $outOfStock = Product::where('stock', 0)->count();
        $totalProducts = Product::count();
        
        return view('reports.products', compact(
            'products',
            'categories',
            'lowStock',
            'outOfStock',
            'totalProducts',
            'sort'
        ));
    }

    /**
     * Stock Report (Same as products but different view)
     */
    public function stock()
    {
        $products = Product::with('category')
            ->orderBy('stock', 'asc')
            ->get();
        
        $lowStock = $products->where('stock', '<', 10)->where('stock', '>', 0)->count();
        $outOfStock = $products->where('stock', 0)->count();
        $totalProducts = $products->count();
        
        return view('reports.stock', compact(
            'products',
            'lowStock',
            'outOfStock',
            'totalProducts'
        ));
    }

    /**
     * Transaction History with Filters
     */
    public function transactions(Request $request)
    {
        $query = Transaction::with('transactionDetails.product', 'user');
        
        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }
        
        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by payment method
        if ($request->filled('payment_method') && $request->payment_method !== 'all') {
            $query->where('payment_method', $request->payment_method);
        }
        
        // Search by transaction code
        if ($request->filled('search')) {
            $query->where('transaction_code', 'LIKE', '%' . $request->search . '%');
        }
        
        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(20);
        
        // Calculate summary
        $summaryQuery = Transaction::query();
        
        if ($request->filled('start_date')) {
            $summaryQuery->whereDate('transaction_date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $summaryQuery->whereDate('transaction_date', '<=', $request->end_date);
        }
        
        if ($request->filled('status') && $request->status !== 'all') {
            $summaryQuery->where('status', $request->status);
        }
        
        $summary = [
            'total_transactions' => $summaryQuery->count(),
            'total_amount' => $summaryQuery->sum('total_amount'),
            'total_today' => Transaction::whereDate('transaction_date', today())
                ->where('status', 'completed')
                ->sum('total_amount'),
        ];
        
        return view('reports.transactions', compact('transactions', 'summary'));
    }

    /**
     * Export to Excel (XLSX) - Proper Format
     */
    public function exportExcel(Request $request)
    {
        $query = Transaction::with('transactionDetails.product', 'user');
        
        // Apply filters
        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }
        
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('payment_method') && $request->payment_method !== 'all') {
            $query->where('payment_method', $request->payment_method);
        }
        
        $transactions = $query->orderBy('transaction_date', 'desc')->get();
        
        // Import PhpSpreadsheet classes
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('POS System')
            ->setTitle('Laporan Transaksi')
            ->setSubject('Laporan Transaksi')
            ->setDescription('Laporan transaksi penjualan');
        
        // Title
        $sheet->setCellValue('A1', 'LAPORAN TRANSAKSI PENJUALAN');
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        // Info
        $row = 2;
        $sheet->setCellValue('A' . $row, 'Tanggal Export:');
        $sheet->setCellValue('B' . $row, now()->format('d/m/Y H:i'));
        $row++;
        
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $sheet->setCellValue('A' . $row, 'Periode:');
            $sheet->setCellValue('B' . $row, date('d/m/Y', strtotime($request->start_date)) . ' - ' . date('d/m/Y', strtotime($request->end_date)));
            $row++;
        }
        
        $row++; // Empty row
        
        // Header
        $headerRow = $row;
        $headers = ['No', 'No Invoice', 'Tanggal', 'Waktu', 'Kasir', 'Total Belanja', 'Metode Pembayaran', 'Jumlah Bayar', 'Kembalian', 'Status'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $headerRow, $header);
            $sheet->getStyle($col . $headerRow)->getFont()->setBold(true);
            $sheet->getStyle($col . $headerRow)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('4472C4');
            $sheet->getStyle($col . $headerRow)->getFont()->getColor()->setRGB('FFFFFF');
            $col++;
        }
        
        // Data
        $row++;
        $no = 1;
        foreach ($transactions as $transaction) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $transaction->transaction_code);
            $sheet->setCellValue('C' . $row, $transaction->transaction_date->format('d/m/Y'));
            $sheet->setCellValue('D' . $row, $transaction->transaction_date->format('H:i:s'));
            $sheet->setCellValue('E' . $row, $transaction->user->name);
            $sheet->setCellValue('F' . $row, $transaction->total_amount);
            $sheet->setCellValue('G' . $row, ucfirst($transaction->payment_method));
            $sheet->setCellValue('H' . $row, $transaction->payment_amount);
            $sheet->setCellValue('I' . $row, $transaction->change_amount);
            $sheet->setCellValue('J' . $row, ucfirst($transaction->status));
            
            // Format currency
            $sheet->getStyle('F' . $row)->getNumberFormat()
                ->setFormatCode('#,##0');
            $sheet->getStyle('H' . $row)->getNumberFormat()
                ->setFormatCode('#,##0');
            $sheet->getStyle('I' . $row)->getNumberFormat()
                ->setFormatCode('#,##0');
            
            $row++;
        }
        
        // Summary
        $row++;
        $sheet->setCellValue('A' . $row, 'TOTAL');
        $sheet->mergeCells('A' . $row . ':E' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $sheet->setCellValue('F' . $row, $transactions->sum('total_amount'));
        $sheet->setCellValue('H' . $row, $transactions->sum('payment_amount'));
        $sheet->setCellValue('I' . $row, $transactions->sum('change_amount'));
        $sheet->getStyle('F' . $row)->getFont()->setBold(true);
        $sheet->getStyle('H' . $row)->getFont()->setBold(true);
        $sheet->getStyle('I' . $row)->getFont()->setBold(true);
        
        // Format currency for summary
        $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('H' . $row)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('I' . $row)->getNumberFormat()->setFormatCode('#,##0');
        
        $row++;
        $sheet->setCellValue('A' . $row, 'JUMLAH TRANSAKSI');
        $sheet->mergeCells('A' . $row . ':E' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $sheet->setCellValue('F' . $row, $transactions->count() . ' transaksi');
        
        // Auto-size columns
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Borders
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];
        $sheet->getStyle('A' . $headerRow . ':J' . ($row - 2))->applyFromArray($styleArray);
        
        // Generate filename
        $filename = 'laporan_transaksi_' . date('Y-m-d_His') . '.xlsx';
        
        // Download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Export to PDF
     */
    public function exportPDF(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        
        $transactions = Transaction::with('transactionDetails.product', 'user')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->orderBy('transaction_date', 'desc')
            ->get();
        
        $totalSales = $transactions->sum('total_amount');
        $totalTransactions = $transactions->count();
        
        return view('reports.pdf', compact(
            'transactions',
            'totalSales',
            'totalTransactions',
            'startDate',
            'endDate'
        ));
    }
}