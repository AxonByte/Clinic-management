<?php

namespace App\Http\Controllers\Admin\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Document;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

class DocumentCOntroller extends Controller
{
        public function index(Request $request)
        {
            if ($request->ajax()) {
                $data = Document::with('patient')->select('documents.*')->latest()->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('patient', fn($row) => $row->patient->name)
                    ->addColumn('date', fn($row) => $row->created_at ? $row->created_at->format('d-m-Y') : '')
                    ->addColumn('document', function($row) {
                        if (!$row->file_path) {
                            return '-';
                        }

                        $fileUrl = asset('storage/' . $row->file_path);
                        $extension = strtolower(pathinfo($row->file_path, PATHINFO_EXTENSION));
                        $imageExtensions = ['jpg', 'jpeg', 'png'];

                        if (in_array($extension, $imageExtensions)) {
                            return '<img src="' . $fileUrl . '" width="50" height="50" style="object-fit: cover;" />';
                        } elseif ($extension === 'pdf') {
                            $icon = asset('images/pdf-icon.png'); // your custom PDF icon
                            return '<a href="' . $fileUrl . '" target="_blank">
                                        <img src="' . $icon . '" width="60" />
                                    </a>';
                        } else {
                            return '<a href="' . $fileUrl . '" target="_blank">Download</a>';
                        }
                    })
                    ->addColumn('action', function($row) {
                        $downloadUrl = route('admin.patient.documents.download', $row->id);
                        $deleteUrl = route('admin.patient.documents.destroy', $row->id);

                        return '
                            <div class="d-flex gap-1">
                               <button data-id="'.$row->id.'" class="editBtn btn btn-sm btn-primary">Edit</button>
                                <a href="' . $downloadUrl . '" class="btn btn-sm btn-primary">Download</a>
                                <form method="POST" action="' . $deleteUrl . '" onsubmit="return confirm(\'Are you sure?\')">
                                    ' . csrf_field() . method_field('DELETE') . '
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                                
                            </div>
                        ';
                    })
                    ->rawColumns(['download', 'action','document'])
                    ->make(true);
            }

            $patients = User::where('role','patient')->get();
            return view('admin.patients.documents.list', compact('patients'));
        }
      

    public function store(Request $request)
    {
        $isUpdate = $request->filled('id');
        
        // Validation rules
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'file' => $isUpdate ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048' : 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $filePath = null;

        // Check for file upload
        if ($request->hasFile('file')) {
            // If updating, delete old file
            if ($isUpdate) {
                $document = Document::findOrFail($request->id);
                if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                    Storage::disk('public')->delete($document->file_path);
                }
            }

            // Upload new file
            $filePath = $request->file('file')->store('documents', 'public');
        }

        if ($isUpdate) {
            // Update record
            $document = Document::findOrFail($request->id);
            $document->update([
                'patient_id' => $request->patient_id,
                'title' => $request->title,
                'file_path' => $filePath ?? $document->file_path, // retain old path if no new file
            ]);
            $message = 'Document updated successfully';
        } else {
            // Create record
            Document::create([
                'patient_id' => $request->patient_id,
                'title' => $request->title,
                'file_path' => $filePath,
            ]);
            $message = 'Document uploaded successfully';
        }

        return response()->json(['success' => $message]);
    }


        public function edit($id)
        {
            return response()->json(Document::find($id));
        }

        public function destroy(Document $id)
        {
            Storage::delete($document->file_path);
            $document->delete();

            return response()->json(['success' => 'Document deleted successfully']);
        }

      public function download(Document $document)
        {
            $filePath = $document->file_path;

            // Ensure the file exists
            if (!Storage::disk('public')->exists($filePath)) {
                abort(404, 'File not found.');
            }

            // Get the filename for download
            $filename = pathinfo($filePath, PATHINFO_BASENAME);

            return Storage::disk('public')->download($filePath, $filename);
        }

}
