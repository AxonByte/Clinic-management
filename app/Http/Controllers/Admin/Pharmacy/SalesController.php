<?php

namespace App\Http\Controllers\Admin\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\Sales;
use DataTables;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Sales::with('items')->latest();
            return DataTables::of($data)
                ->addColumn('items', fn($row) => $row->items->pluck('item_name')->join(', '))
                ->addColumn('created_at', fn($row) => \Carbon\Carbon::parse($row->created_at)->format('d M Y'))
                ->addColumn('action', function ($row) {
                    return '
                     <button data-id="'.$row->id.'" class="deleteBtn btn btn-sm btn-danger">Delete</button>
                     <button data-id="'.$row->id.'" class="editBtn btn btn-sm btn-primary">Edit</button>'
                    ;
                    // return view('admin.pharmacy.sales.actions', compact('row'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.pharmacy.sales.index');
    }


    public function create()
    {
        $medicines = Medicine::all();
        return view('admin.pharmacy.sales.create', compact('medicines'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required',
            'items.*.item_name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer|min:1',
            'subtotal' => 'required|numeric',
            'total' => 'required|numeric',
        ]);
//  dd($request->all());
        $sale = Sales::create([
            'subtotal' => $request->subtotal,
            'discount' => $request->discount ?? 0,
            'total' => $request->total,
        ]);

        foreach ($request->items as $item) {
            $sale->items()->create([
                'sale_id'     => $sale->id,
                'medicine_id' => $item['item_id'],
                'item_name'   => $item['item_name'],
                'price'       => $item['price'],
                'quantity'    => $item['quantity'],
            ]);
        }

        return redirect()->route('admin.pharmacy.sales.index')->with('success', 'Sale recorded.');
    }

//     public function edit($id)
//     {
//         $sale = Sales::with('items')->findOrFail($id);
// //    dd($sale)
// ;        $saleItems = $sale->items->mapWithKeys(function ($item) {
//             return [
//                 $item->medicine_id => [
//                     'quantity' => $item->quantity,
//                     'price' => $item->price,
//                 ]
//             ];
//         });

//         $medicines = Medicine::all();
//         return view('admin.pharmacy.sales.create', compact('sale', 'medicines', 'saleItems'));
//     }

    public function edit($id)
    {
        $sale = Sales::with('items')->findOrFail($id);
        $medicines = Medicine::all();
        // Convert sale items into format for JavaScript
        $saleItems = $sale->items->mapWithKeys(function ($item) {
            return [$item->item_id => [
                'quantity' => $item->quantity,
            ]];
        });

        return view('admin.pharmacy.sales.create', [
            'sale' => $sale,
            'saleItems' => $saleItems,
            'medicines' => $medicines,
        ]);
    }

    public function update(Request $request, Sales $sale)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.stock' => 'required|integer',
            'subtotal' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        $sale->update([
            'subtotal' => $request->subtotal,
            'discount' => $request->discount ?? 0,
            'total' => $request->total,
        ]);

        $sale->items()->delete();
        foreach ($request->items as $item) {
            $sale->items()->create($item);
        }

        return redirect()->route('pharmacy.sales.index')->with('success', 'Sale updated.');
    }

    public function destroy(Sales $sale)
    {
        $sale->delete();
        return response()->json(['success' => true]);
    }
}
