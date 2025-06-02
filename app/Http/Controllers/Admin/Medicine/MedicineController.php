<?php

namespace App\Http\Controllers\Admin\Medicine;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MedicineController extends Controller
{
   
    public function index(Request $request)
    {
        $pageTitle = 'Medicine List';
        if ($request->ajax()) {
            $data = Medicine::with('category')->select('medicines.*')->latest()->get(); 
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('category', function ($row) {
                    return $row->category->name ?? 'N/A';
                })
                ->addColumn('quantity', function ($row) {
                    $quantityText = $row->quantity == 0
                        ? '<span class="badge bg-danger">Out of Stock</span>'
                        : '<span>' . $row->quantity . '</span>';

                    return '
                        <div class="d-flex align-items-center gap-2">
                            ' . $quantityText . '
                            <button class="btn btn-sm btn-outline-success update-qty-btn"
                                    data-id="' . $row->id . '"
                                    data-qty="' . $row->quantity . '">
                                Add
                            </button>
                        </div>';
                })

                ->addColumn('action', function ($row) {
                    return '<a href="'.route('admin.medicine.edit', $row->id).'" class="btn btn-sm btn-primary">Edit</a>
                    <button data-id="'.$row->id.'" class="deleteBtn btn btn-sm btn-danger">Delete</button>';
                })
                ->rawColumns(['action','quantity'])
                ->make(true);
        }

        return view('admin.medicine.index', compact('pageTitle'));
    }

    public function create()
    {
       $pageTitle = 'Add Medicine';
       $category = MedicineCategory::get();
       return view('admin.medicine.create', compact('category','pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'generic_name' => 'required|string',
            'category_id' => 'required|exists:medicine_categories,id',
            'purchase_price' => 'required',
            'sale_price' => 'required',
            'quantity' => 'required',
            'expiry_date' => 'required',
        ]);

        try {
            Medicine::create($request->all());
            return redirect()->route('admin.medicine.index')
                            ->with('success', 'Medicine added successfully.');
        } catch (Exception $e) {
            \Log::error('Medicine Store Error: ' . $e->getMessage());
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Something went wrong while adding medicine.');
        }
    }

    public function updateQuantity(Request $request, $id)
    {
        $request->validate([
            'add_quantity' => 'required|numeric|min:1',
        ]);

        try {
            $medicine = Medicine::findOrFail($id);
            $medicine->quantity += $request->add_quantity;
            $medicine->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Update Quantity Error: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    public function edit($id)
    {
       $pageTitle = 'Update Medicine';
       $medicine = Medicine::findOrFail($id);
       $category = MedicineCategory::get();
       return view('admin.medicine.edit', compact('category','medicine','pageTitle'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'required|string|max:255',
            'category_id' => 'required|exists:medicine_categories,id',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'expiry_date' => 'required|date',
            'company' => 'nullable|string|max:255',
            'effects' => 'nullable|string|max:500',
            'store_box' => 'nullable|string|max:255',
        ]);

        try {
            $medicine = Medicine::findOrFail($id);

            $medicine->update($request->only([
                'name', 'generic_name', 'category_id', 'purchase_price', 'sale_price',
                'quantity', 'expiry_date', 'company', 'effects', 'store_box'
            ]));

            return redirect()->route('admin.medicine.index')
                            ->with('success', 'Medicine updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Medicine Update Error: ' . $e->getMessage());

            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Something went wrong while updating medicine.');
        }
    }

    public function destroy($id)
    {
        Medicine::find($id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Medicine deleted successfully.'
        ]);
    }


}
