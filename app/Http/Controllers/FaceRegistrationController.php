<?php

namespace App\Http\Controllers;

use App\Models\FaceRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class FaceRegistrationController extends Controller
{
    public function index()
    {
        $users = \App\Models\User::select('name', 'profile_photo')->get();
        return view('pwa.verification.face-register', compact('users'));
    
    }

    /**
     * Capture and store face image.
     */
    public function capture(Request $request)
    {
        $request->validate([
            'image' => 'required|string',
            'verification_type' => 'required|in:check_in,check_out',
        ]);

        try {
            // Get the base64 image data
            $imageData = $request->input('image');
            
            // Remove data:image/jpeg;base64, part
            $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
            $imageData = base64_decode($imageData);

            if (!$imageData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid image data'
                ], 400);
            }

            // Generate unique filename
            $fileName = 'face_verification_' . Auth::id() . '_' . time() . '_' . Str::random(10) . '.jpg';
            $filePath = 'face_verifications/' . date('Y/m/d') . '/' . $fileName;

            // Create directory if it doesn't exist
            $directory = dirname(storage_path('app/public/' . $filePath));
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Process and optimize image using Intervention Image (optional)
            $image = Image::make($imageData);
            $image->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->encode('jpg', 80);

            // Store the image
            Storage::disk('public')->put($filePath, (string) $image);

            // Get image metadata
            $metadata = [
                'file_size' => strlen($image->encoded),
                'dimensions' => [
                    'width' => $image->width(),
                    'height' => $image->height()
                ],
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
            ];

            // Save to database
            $facerFaceRegistration = FaceRegistration::create([
                'user_id' => Auth::id(),
                'image_path' => $filePath,
                'image_name' => $fileName,
                'verification_type' => $request->input('verification_type'),
                'status' => 'verified', // You can set this to 'pending' if you want manual verification
                'metadata' => $metadata,
                'captured_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Face verification captured successfully',
                'data' => [
                    'id' => $facerFaceRegistration->id,
                    'image_url' => $facerFaceRegistration->image_url,
                    'status' => $facerFaceRegistration->status,
                    'captured_at' => $facerFaceRegistration->captured_at->format('Y-m-d H:i:s'),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to capture face verification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's face verification history.
     */
    public function history(Request $request)
    {
        $verifications = FaceRegistration::where('user_id', Auth::id())
            ->when($request->type, function ($query, $type) {
                return $query->byType($type);
            })
            ->when($request->status, function ($query, $status) {
                return $query->byStatus($status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $verifications
        ]);
    }

    /**
     * Delete a face verification record.
     */
    public function destroy($id)
    {
        $verification = FaceRegistration::where('user_id', Auth::id())
            ->findOrFail($id);

        // Delete the image file
        if (Storage::disk('public')->exists($verification->image_path)) {
            Storage::disk('public')->delete($verification->image_path);
        }

        // Delete the record
        $verification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Face verification deleted successfully'
        ]);
    }

    /**
     * Get verification statistics.
     */
    public function statistics()
    {
        $userId = Auth::id();
        
        $stats = [
            'total_verifications' => FaceRegistration::where('user_id', $userId)->count(),
            'check_in_count' => FaceRegistration::where('user_id', $userId)->byType('check_in')->count(),
            'check_out_count' => FaceRegistration::where('user_id', $userId)->byType('check_out')->count(),
            'recent_verifications' => FaceRegistration::where('user_id', $userId)->recent(7)->count(),
            'last_verification' => FaceRegistration::where('user_id', $userId)
                ->latest()
                ->first()?->created_at?->format('Y-m-d H:i:s'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
