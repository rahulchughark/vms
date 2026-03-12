<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Services\FirebaseNotificationService;
use App\Services\VisitorStatusService;
use App\Models\UserDevice;
use App\Models\User;
use App\Models\Notification;

class VisitorController extends Controller
{
    private VisitorStatusService $visitorStatusService;

    public function __construct(VisitorStatusService $visitorStatusService)
    {
        $this->visitorStatusService = $visitorStatusService;
    }

 /**
     * @OA\Post(
     *     path="/api/manage-exit-status",
     *     tags={"Visitors"},
     *     summary="Update exit status by visitor ID",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"visitor_id", "exit_date", "exit_time"},
     *             @OA\Property(property="visitor_id", type="integer", example=15, description="Visitor ID"),
     *             @OA\Property(property="exit_date", type="string", format="date", example="2026-02-27"),
     *             @OA\Property(property="exit_time", type="string", format="time", example="17:30:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exit status updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Visitor not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function manageExitStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'visitor_id' => 'required|integer|exists:visitors,id',
            'exit_date' => 'required|date_format:Y-m-d',
            'exit_time' => 'required|date_format:H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        $visitor = Visitor::find($request->visitor_id);
        if (!$visitor) {
            return response()->json([
                'status' => false,
                'message' => 'Visitor not found',
            ], 404);
        }

        $visitor->exit_date = $request->exit_date;
        $visitor->exit_time = $request->exit_time;
        $visitor->visit_status = 3; // Completed
        $visitor->save();

        return response()->json([
            'status' => true,
            'message' => 'Exit status updated and visitor marked as completed',
            'data' => [
                'visitor_id' => $visitor->id,
                'card_number' => $visitor->card_number,
                'exit_date' => $visitor->exit_date,
                'exit_time' => $visitor->exit_time,
                'visit_status' => $visitor->visit_status,
                'visit_status_label' => $this->visitorStatusService->label((int) $visitor->visit_status),
            ]
        ]);
    }


        /**
         * @OA\Post(
         *     path="/api/visitors/card-number",
         *     tags={"Visitors"},
         *     summary="Update card number by visitor ID in body",
         *     @OA\RequestBody(
         *         required=true,
         *         @OA\JsonContent(
         *             required={"visitor_id", "card_number"},
         *             @OA\Property(property="visitor_id", type="integer", example=15, description="Visitor ID"),
         *             @OA\Property(property="card_number", type="string", example="ARK0015", description="Card number to assign to the visitor")
         *         )
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Card number updated successfully"
         *     ),
         *     @OA\Response(
         *         response=404,
         *         description="Visitor not found"
         *     ),
         *     @OA\Response(
         *         response=422,
         *         description="Validation error"
         *     )
         * )
         */
        public function updateCardNumber(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'visitor_id' => 'required|integer|exists:visitors,id',
                'card_number' => 'required|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $visitor = Visitor::find($request->visitor_id);

            if (!$visitor) {
                return response()->json([
                    'status' => false,
                    'message' => 'Visitor not found',
                ], 404);
            }

            $visitor->card_number = $request->card_number;
            $visitor->visit_status = 4; // In Progress
            $visitor->save();

            return response()->json([
                'status' => true,
                'message' => 'Card number updated successfully',
                'data' => [
                    'visitor_id' => $visitor->id,
                    'card_number' => $visitor->card_number,
                    'visit_status' => $visitor->visit_status,
                    'visit_status_label' => $this->visitorStatusService->label((int) $visitor->visit_status),
                ]
            ]);
        }
    


    /**
     * @OA\Post(
     *     path="/api/manage-visit-status",
     *     tags={"Visitors"},
     *     summary="Manage visit status",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"visitor_id", "status"},
     *             @OA\Property(property="visitor_id", type="integer", example=1),
     *             @OA\Property(property="status", type="integer", example=1, description="0:Pending, 1:Approve, 2:Reject, 3:Completed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Visit status updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Visitor not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function manageVisitStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'visitor_id' => 'required|integer|exists:visitors,id',
            'status' => 'required|integer|in:1,2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        $visitor = Visitor::find($request->visitor_id);
        if (!$visitor) {
            return response()->json([
                'status' => false,
                'message' => 'Visitor not found',
            ], 404);
        }

        // Only pending visitors can be moved to Approve/Reject from this endpoint.
        if ((int) $visitor->visit_status !== 0) {
            return response()->json([
                'status' => false,
                'message' => 'You already updated status',
            ], 422);
        }

        $visitor->visit_status = $request->status;
        $visitor->save();

        $statusLabel = $this->visitorStatusService->label((int) $request->status);

        return response()->json([
            'status' => true,
            'message' => "Visit status updated to {$statusLabel} successfully",
            'data' => [
                'visitor_id' => $visitor->id,
                'visit_status' => $visitor->visit_status,
                'visit_status_label' => $statusLabel,
            ]
        ]);
    }
    /**
     * @OA\Get(
     *     path="/api/visitors/{id}",
     *     tags={"Visitors"},
     *     summary="Get visitor detail",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Visitor ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Visitor not found"
     *     )
     * )
     */
    public function show($id)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }
        $visitor = Visitor::find($id);
        if (!$visitor) {
            return response()->json([
                'status' => false,
                'message' => 'Visitor not found',
            ], 404);
        }
        // Role-based access: EMP can only view their own record
        // if ((int)$user->role === 3 && $visitor->email !== $user->email) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Unauthorized',
        //     ], 403);
        // }
        // Admin, HR, Gate Keeper can view any
        // elseif (!in_array((int)$user->role, [1,2,4], true) && (int)$user->role !== 3) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Unauthorized role',
        //     ], 403);
        // }
        // Add status labels
        $visitorDetail = $visitor->toArray();
        $visitorDetail['visit_status_label'] = $this->visitorStatusService->label((int) $visitor->visit_status);
        $visitorDetail['approval_status_label'] = match($visitor->approval_status) {
            0 => 'Pending',
            1 => 'Approved',
            2 => 'Rejected',
            default => 'Unknown',
        };
        return response()->json([
            'status' => true,
            'visitor' => $visitorDetail,
        ]);
    }
    
    /**
     * @OA\Post(
     *     path="/api/visitors",
     *     tags={"Visitors"},
     *     summary="Create visitor",
     *     @OA\Response(
     *         response=201,
     *         description="Visitor created"
     *     )
     * )
     */
    public function store(Request $request)
    {
        //  $loggedInUser = $request->user();

        //  return response()->json([
        //     'status' => true,
        //     'message' => 'You are logged in',
        //     'user' => $loggedInUser]);

        //     exit;        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'mobile_no' => 'required|string|max:20',
            'profile_image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'visitor_id_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'company_id' => 'required|string|max:50',
            'meet_person_name' => 'required|string|max:255',
            'meet_person_email' => 'required|email',
            'purpose' => 'required|string|max:500',
            'visit_date' => 'required|date_format:Y-m-d',
            'approx_total_time' => 'required',
            'in_time' => 'required|date_format:H:i:s',
            'visit_status' => 'nullable|integer|in:0,1,2,3,4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        // Handle profile_image upload to public folder
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = time() . '_profile_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('visitors/profile');
            
            // Create directory if it doesn't exist
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $file->move($destinationPath, $filename);
            $data['profile_image'] = 'visitors/profile/' . $filename;
        }

        
        // Handle visitor_id_proof upload to public folder
        if ($request->hasFile('visitor_id_proof')) {
            $file = $request->file('visitor_id_proof');
            $filename = time() . '_idproof_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('visitors/id_proof');
            
            // Create directory if it doesn't exist
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $file->move($destinationPath, $filename);
            $data['visitor_id_proof'] = 'visitors/id_proof/' . $filename;
        }

        $loggedInUser = $request->user();
        $data['created_by'] = $loggedInUser->id;
        $data['visit_status'] = (int) $loggedInUser->role === 3 ? 1 : 0;


        $visitor = Visitor::create($data);

        // Set card_number as ARK00{id}
        // $visitor->card_number = 'ARK00' . $visitor->id;
        // $visitor->save();

         /*
        |--------------------------------------------------------------------------
        | FIREBASE NOTIFICATION START
        |--------------------------------------------------------------------------
        */

        // Find meeting user by email
        $user = User::where('id', 1)
            ->select('id')
            ->first();

        if ($user) {
            $title   = 'New Visitor Arrived';
            $message = $data['name'] . ' has arrived to meet you.';

            // Save notification
            // $notification = Notification::create([
            //     'user_id'    => $user->id,
            //     'visitor_id'=> $visitor->id,
            //     'title'      => $title,
            //     'message'    => $message,
            //     'status'     => 0,
            // ]);

            // Get active device tokens
            // $tokens = UserDevice::where('user_id', $user->id)
            //                     ->where('is_active', 1)
            //                     ->pluck('device_token');

            // $sent = false;

            // foreach ($tokens as $token) {
            //     $response = sendFirebase($token, $title, $message);

            //     if ($response === true) {
            //         $sent = true;
            //     }
            // }

            // Mark notification as sent ONLY if push attempted
            // if ($sent) {
            //     $notification->update([
            //         'status' => 1
            //     ]);
            // }
        }

        /*
        |--------------------------------------------------------------------------
        | FIREBASE NOTIFICATION END
        |--------------------------------------------------------------------------
        */

        // Mail::send('emails.visitor-created', $data, function ($message) use ($data) {
        //     $message->to($data['meet_person_email'])
        //         ->subject('New Visitor Scheduled - ' . $data['name']);
        // });

        return response()->json([
            'status' => true,
            'message' => 'Visitor added successfully',
            'data' => [
                // 'card_number' => $visitor->card_number,
                'name' => $visitor->name,
                'email' => $visitor->email,
                'mobile_no' => $visitor->mobile_no,
            ],
        ], 201);
    }

    /**
 * @OA\Get(
 *     path="/api/visitors",
 *     tags={"Visitors"},
 *     summary="Get visitor list",
 *     @OA\Response(
 *         response=200,
 *         description="Success"
 *     )
 * )
 */ 
     // commented on 05-Feb-2026
    // public function index(Request $request)
    // {
    //     $query = Visitor::where('created_by', $request->user()->id);

    //     // Filter by visit_status
    //     if ($request->has('visit_status') && $request->visit_status !== '') {
    //         $query->where('visit_status', $request->visit_status);
    //     }

    //     $visitors = $query->selectRaw("
    //             id,
    //             name,
    //             email,
    //             mobile_no,
    //             company_id,
    //             profile_image,
    //             visitor_id_proof,
    //             in_time,
    //             visit_date,
    //             visit_status,
    //             approval_status,
    //             approved_by,
    //             card_number,
    //             exit_date,
    //             exit_time,
    //             created_at,
    //             CASE visit_status
    //                 WHEN 0 THEN 'Pending'
    //                 WHEN 1 THEN 'In Progress'
    //                 WHEN 2 THEN 'Approved'
    //                 WHEN 3 THEN 'Completed'
    //                 ELSE 'Unknown'
    //             END AS visit_status_label,
    //             CASE approval_status
    //                 WHEN 0 THEN 'Pending'
    //                 WHEN 1 THEN 'Approved'
    //                 WHEN 2 THEN 'Rejected'
    //                 ELSE 'Unknown'
    //             END AS approval_status_label
    //         ")
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     return response()->json([
    //         'status' => true,
    //         'visitors' => $visitors,
    //     ]);
    // }
    public function index(Request $request)
{
        $user = auth()->user();

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

    // Role 3 = EMP: show only records where visitor email matches logged-in user email
    if ((int) $user->role === 3) {
        $query = Visitor::where('meet_person_email', $user->email);
    // Roles 1 = Admin, 2 = HR, 4 = Gate Keeper: show all visitors
    } elseif (in_array((int) $user->role, [1, 2, 4], true)) {
        $query = Visitor::query();
    } else {
        // Any other role is not authorized
        return response()->json([
            'status' => false,
            'message' => 'Unauthorized role',
        ], 403);
    }

    // Filter by visit_status
    if ($request->filled('visit_status')) {
        // Accept string or int, map to correct status
        $statusMap = [
            'pending' => 0,
            'approve' => 1,
            'approved' => 1,
            'reject' => 2,
            'rejected' => 2,
            'completed' => 3,
            'in_progress' => 4,
            'in-progress' => 4,
            'in progress' => 4,
            0 => 0,
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
        ];
        $filter = strtolower($request->visit_status);
        $status = $statusMap[$filter] ?? $request->visit_status;
        $query->where('visit_status', $status);
    }

    // Filter by visit_date (single date)
    if ($request->filled('visit_date')) {
        $query->whereDate('visit_date', $request->visit_date);
    }

    // Filter by visit_date range
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('visit_date', [
            $request->from_date,
            $request->to_date
        ]);
    }

    $perPage = max(1, min((int) $request->input('per_page', 10), 100));

    $visitors = $query->selectRaw(" 
            id,
            name,
            email,
            mobile_no,
            company_id,
            profile_image,
            visitor_id_proof,
            in_time,
            visit_date,
            visit_status,
            approval_status,
            approved_by,
            card_number,
            exit_date,
            exit_time,
            created_at,
            CASE visit_status
                WHEN 0 THEN 'Pending'
                WHEN 1 THEN 'Approve'
                WHEN 2 THEN 'Reject'
                WHEN 3 THEN 'Completed'
                WHEN 4 THEN 'In Progress'
                ELSE 'Unknown'
            END AS visit_status_label,
            CASE approval_status
                WHEN 0 THEN 'Pending'
                WHEN 1 THEN 'Approved'
                WHEN 2 THEN 'Rejected'
                ELSE 'Unknown'
            END AS approval_status_label
        ")
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);

    return response()->json([
        'status' => true,
        'count' => count($visitors->items()),
        'total' => $visitors->total(),
        'per_page' => $visitors->perPage(),
        'current_page' => $visitors->currentPage(),
        'last_page' => $visitors->lastPage(),
        'visitors' => $visitors->items(),
    ]);
}

    /**
     * Role-based visitor listing with the same filters as /visitors and
     * custom status semantics for EMP (3) and Gatekeeper (4).
     */
    public function visitorsList(Request $request)
    {
        $user = auth()->user();

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        $role = (int) $user->role;

        if ($role === 3) {
            // EMP users can only see records tied to their email.
            $query = Visitor::where(function ($q) use ($user) {
                $q->where('meet_person_email', $user->email)
                    ->orWhere('email', $user->email)
                    ->orWhere('reassign_email', $user->email);
            });
        } elseif (in_array($role, [1, 2, 4], true)) {
            // Admin, HR and Gatekeeper can see all records.
            $query = Visitor::query();
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized role',
            ], 403);
        }

        if ($request->filled('visit_status')) {
            $this->applyVisitorsListStatusFilter($query, $request->visit_status);
        }

        // Keep the same date filters as the existing /visitors API.
        if ($request->filled('visit_date')) {
            $query->whereDate('visit_date', $request->visit_date);
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('visit_date', [$request->from_date, $request->to_date]);
        }

        $perPage = max(1, min((int) $request->input('per_page', 10), 100));
        $userEmail = $user->email;

        $visitors = $query->select([
                'id',
                'name',
                'email',
                'mobile_no',
                'company_id',
                'profile_image',
                'visitor_id_proof',
                'in_time',
                'visit_date',
                'visit_status',
                // 'approval_status',
                'approved_by',
                'card_number',
                'exit_date',
                'exit_time',
                'reassign_email',
                'created_at',
            ])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $items = collect($visitors->items())->map(function ($visitor) use ($userEmail) {
            $row = is_array($visitor) ? $visitor : $visitor->toArray();
            // $row['visit_status_label'] = $this->buildVisitorsListStatusLabel($row);
            $row['visit_status_label'] = $this->visitorStatusService->label((int) ($row['visit_status'] ?? -1));

            if (($row['email'] ?? null) === $userEmail) {
                $row['type'] = 'fresh';
            } elseif (($row['reassign_email'] ?? null) === $userEmail) {
                $row['type'] = 'reassign';
            }

            return $row;
        })->values();

        return response()->json([
            'status' => true,
            'count' => $items->count(),
            'total' => $visitors->total(),
            'per_page' => $visitors->perPage(),
            'current_page' => $visitors->currentPage(),
            'last_page' => $visitors->lastPage(),
            'visitors' => $items,
        ]);
    }

    private function applyVisitorsListStatusFilter($query, $rawStatus): void
    {
        $status = strtolower(trim((string) $rawStatus));

        if (in_array($status, ['0', 'pending'], true)) {
            $query->where('visit_status', 0);

            return;
        }

        if (in_array($status, ['1', 'approve', 'approved'], true)) {
            $query->where('visit_status', 1)
                ->whereNull('exit_date')
                ->whereNull('exit_time');

            return;
        }

        if (in_array($status, ['2', 'reject', 'rejected'], true)) {
            $query->whereIn('visit_status', [2, 3]);

            return;
        }

        if (in_array($status, ['3', 'completed'], true)) {
            $query->whereIn('visit_status', [2, 3]);

            return;
        }

        if (in_array($status, ['4', 'in_progress', 'in-progress', 'in progress'], true)) {
            $query->where(function ($q) {
                $q->whereNull('exit_date')
                    ->whereNull('exit_time');

                $q->where('visit_status', 4);
            });

            return;
        }

        if (is_numeric($rawStatus)) {
            $query->where('visit_status', (int) $rawStatus);
        }
    }

    private function buildVisitorsListStatusLabel(array $row): string
    {
        $visitStatus = (int) ($row['visit_status'] ?? -1);
        $isCompleted = $visitStatus === 3 || !empty($row['exit_date']) || !empty($row['exit_time']);

        if ($isCompleted) {
            return 'Completed';
        }

        return match ($visitStatus) {
            0 => 'Pending',
            1 => 'Approve',
            2 => 'Reject',
            3 => 'Completed',
            4 => 'In Progress',
            default => 'Unknown',
        };
    }




    /**
     * @OA\Post(
     *     path="/api/visitors/{id}/approve",
     *     tags={"Visitors"},
     *     summary="Approve or reject visitor",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Visitor ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"approval_status"},
     *             @OA\Property(
     *                 property="approval_status",
     *                 type="integer",
     *                 description="Approval status: 0 = Pending, 1 = Approved, 2 = Rejected",
     *                 enum={0, 1, 2}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Visitor approval status updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Visitor not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function approveVisitor(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'approval_status' => 'required|integer|in:0,1,2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        $visitor = Visitor::find($id);

        if (!$visitor) {
            return response()->json([
                'status' => false,
                'message' => 'Visitor not found',
            ], 404);
        }

        $visitor->approval_status = $request->approval_status;
        $visitor->approved_by = $request->user()->id;
        $visitor->save();

       $approvalStatus = (int) $request->approval_status;

        $statusLabel = match($approvalStatus) {
            0 => 'Pending',
            1 => 'Approved',
            2 => 'Rejected',
            default => 'Unknown',
        };
        
        // if (in_array($request->approval_status, [1, 2])) {
        //         Mail::send('emails.visitor_approval_status', [
        //             'meet_person_name' => $visitor->meet_person_name,
        //             'visitor_name'     => $visitor->name,
        //             'visit_date'       => $visitor->visit_date,
        //             'in_time'          => $visitor->in_time,
        //             'purpose'          => $visitor->purpose,
        //             'status'           => $statusLabel,
        //         ], function ($message) use ($visitor, $statusLabel) {
        //             $message->to($visitor->meet_person_email)
        //                 ->subject("Visitor {$statusLabel} - {$visitor->name}");
        //         });
        // }


        return response()->json([
            'status' => true,
            'message' => "Visitor status updated to {$statusLabel} successfully",
            'data' => [
                'approval_status' => $visitor->approval_status,
                'approved_by' => $visitor->approved_by,
            ]
        ]);
    }
    /**
     * @OA\Post(
     *     path="/api/visitors/{id}/exit",
     *     tags={"Visitors"},
     *     summary="Update visitor exit status (Check-out)",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Visitor ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"card_number", "exit_date", "exit_time"},
     *             @OA\Property(property="card_number", type="string", example="ABC0024"),
     *             @OA\Property(property="exit_date", type="string", format="date", example="2025-12-18"),
     *             @OA\Property(property="exit_time", type="string", example="10:00 am")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Visitor exit status updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Visitor not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function updateExitStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'card_number' => 'required|string|max:100',
            'exit_date' => 'required|date_format:Y-m-d',
            'exit_time' => 'required|date_format:H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        $visitor = Visitor::find($id);

        if (!$visitor) {
            return response()->json([
                'status' => false,
                'message' => 'Visitor not found',
            ], 404);
        }

        $visitor->update([
            'card_number' => $request->card_number,
            'exit_date' => $request->exit_date,
            'exit_time' => $request->exit_time,
            'visit_status' => 3, // Set status to Completed
        ]);

        $emailData = [
                'meet_person_name' => $visitor->meet_person_name,
                'visitor_name'     => $visitor->name,
                'visitor_email'    => $visitor->email,
                'purpose'          => $visitor->purpose,
                'visit_date'       => $visitor->visit_date,
                'exit_time'        => $visitor->exit_time,
                'card_number'      => $visitor->card_number,
                'meet_person_email' => $visitor->meet_person_email
            ];

        // Mail::send('emails.visitor_exit', $emailData, function ($message) use ($emailData) {
        //     $message->to($emailData['meet_person_email'])
        //         ->subject('🚪 Visitor Exit Notification - ' . $emailData['visitor_name']);
        // });

        return response()->json([
            'status' => true,
            'message' => 'Visitor exit status updated successfully',
            'data' => $visitor->only(['id', 'card_number', 'exit_date', 'exit_time', 'visit_status'])
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/visitors/{id}/action",
     *     tags={"Visitors"},
     *     summary="Update visitor action (Cancel or Reassign)",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Visitor ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"action_type"},
     *             @OA\Property(property="action_type", type="integer", description="1 = Cancelled, 2 = Reassigned", enum={1, 2}),
     *             @OA\Property(property="reassign_name", type="string", example="John Doe"),
     *             @OA\Property(property="reassign_email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="reassign_phone", type="string", example="1234567890")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Visitor action updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Visitor not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function updateVisitorAction(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'action_type' => 'required|integer|in:1,2',
            'reassign_name' => 'required_if:action_type,2|nullable|string|max:255',
            'reassign_email' => 'required_if:action_type,2|nullable|email|max:255',
            'reassign_phone' => 'required_if:action_type,2|nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        $visitor = Visitor::find($id);

        if (!$visitor) {
            return response()->json([
                'status' => false,
                'message' => 'Visitor not found',
            ], 404);
        }

        $updateData = [
            'action_type' => $request->action_type,
        ];

        if ($request->action_type == 2) {
            $updateData['reassign_name'] = $request->reassign_name;
            $updateData['reassign_email'] = $request->reassign_email;
            $updateData['reassign_phone'] = $request->reassign_phone;
        } else {
            // If action_type is 1 (Cancelled), clear reassignment fields
            $updateData['reassign_name'] = null;
            $updateData['reassign_email'] = null;
            $updateData['reassign_phone'] = null;
        }

        $visitor->update($updateData);

        // Send email if reassigned
         if ($request->action_type == 2 && !empty($request->reassign_email)) {
        $emailData = [
            'visitor_name' => $visitor->name,
            'visitor_email' => $visitor->email,
            'visitor_phone' => $visitor->mobile_no,
            'company_id' => $visitor->company_id,
            'reassign_name' => $visitor->reassign_name,
            'reassign_email' => $visitor->reassign_email,
            'action_by' => $request->user()->name ?? 'System',
            'reassign_date' => now()->format('Y-m-d H:i:s'),
        ];

        // Mail::send('emails.visitor_reassigned', $emailData, function ($message) use ($emailData) {
        //     $message->to($emailData['reassign_email'])
        //         ->subject('🔔 Visitor Reassigned - ' . $emailData['visitor_name']);
        // });
    }

        $actionLabel = $request->action_type == 1 ? 'cancelled' : 'reassigned';

        return response()->json([
            'status' => true,
            'message' => "Visitor {$actionLabel} successfully",
            'data' => $visitor->only(['id', 'action_type', 'reassign_name', 'reassign_email', 'reassign_phone'])
        ]);
    }


   public function testFirebaseNotification()
{
    // Get all notifications with status 0 (not sent)
    $notifications = Notification::where('status', 0)->get();

    if ($notifications->isEmpty()) {
        return response()->json([
            'status' => false,
            'message' => 'No pending notifications found.'
        ]);
    }

    $results = [];

    foreach ($notifications as $notification) {
      

        // Fetch active device tokens for the user
        $tokens = UserDevice::where('user_id', $notification->user_id)
                            ->where('is_active', 1)
                            ->pluck('device_token');


        if ($tokens->isEmpty()) {
            $results[] = [
                'notification_id' => $notification->id,
                'message' => 'No active device tokens'
            ];
            continue;
        }

        $sent = false;

        

        foreach ($tokens as $token) {
            $response = sendFirebase($token, $notification->title, $notification->message);

            echo "<pre>";
            print_r($response);
            return ;

            $results[] = [
                'notification_id' => $notification->id,
                'token' => $token,
                'response' => $response
            ];

            if ($response === true) {
                $sent = true;
            }
        }

        // Update notification status if sent
        if ($sent) {
            $notification->update(['status' => 1]);
        }
    }



    return response()->json([
        'status' => true,
        'message' => 'Pending notifications processed',
        'results' => $results
    ]);
}



}
