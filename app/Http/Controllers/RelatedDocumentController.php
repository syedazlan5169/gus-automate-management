<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RelatedDocument;
use App\Models\Booking;
use Illuminate\Support\Facades\Storage;
class RelatedDocumentController extends Controller
{
    public function store(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,ppt,pptx',
            'document_number' => 'nullable|string',
            'invoice_amount' => 'nullable|numeric',
        ]);

        if ($request->input('document_name_select') == 'Other') {
            $validated['document_name'] = $request->input('document_name');
        }
        else {
            $validated['document_name'] = $request->input('document_name_select');
        }

        $document = $request->file('document_file');
        
        // Generate filename using booking number and timestamp
        $fileName = $booking->booking_number . '_' . $validated['document_name'] . '.' . $document->getClientOriginalExtension();
        $documentPath = $document->storeAs('documents', $fileName, 'public');

        RelatedDocument::create([
            'booking_id' => $booking->id,
            'document_name' => $validated['document_name'],
            'document_number' => $validated['document_number'],
            'invoice_amount' => $validated['invoice_amount'],
            'document_file' => $documentPath,
        ]);

        return redirect()->route('booking.show', $booking)->with('success', 'Document uploaded successfully');
    }

    public function destroy(Booking $booking, RelatedDocument $document)
    {
        // Check if the user has permission to delete this document
        if (auth()->user()->role === 'customer') {
            abort(403);
        }

        // Delete the document file from storage
        if ($document->document_file && Storage::disk('public')->exists($document->document_file)) {
            Storage::disk('public')->delete($document->document_file);
        }

        // Delete the document record
        $document->delete();

        return redirect()->back()->with('success', 'Document deleted successfully');
    }

    public function download(Booking $booking, RelatedDocument $document)
    {
        // Check if the document belongs to the current user's booking
        if ($document->booking->user_id !== auth()->id() && auth()->user()->role === 'customer') {
            abort(403);
        }

        // Check if document file exists
        if (!$document->document_file) {
            abort(404, 'Document file not found');
        }

        // Check if file exists in storage
        if (!Storage::disk('public')->exists($document->document_file)) {
            abort(404, 'Document file not found in storage');
        }

        // Get the mime type
        $mimeType = Storage::disk('public')->mimeType($document->document_file);

        // Extract file extension from the document_file path
        $extension = pathinfo($document->document_file, PATHINFO_EXTENSION);
        
        // Generate a friendly filename without path separators
        $filename = 'Document-' . $document->document_name . '.' . $extension;

        // Return the file download response
        return Storage::disk('public')->download($document->document_file, $filename, [
            'Content-Type' => $mimeType
        ]);
    }
}
