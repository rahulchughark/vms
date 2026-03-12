<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{

    use HasFactory;
    
     protected $fillable = [
        'name', 'email', 'mobile_no', 'profile_image', 'visitor_id_proof',
        'company_id', 'meet_person_name', 'meet_person_email', 'purpose',
        'visit_date', 'approx_total_time', 'in_time', 'approval_status', 'approved_by',
        'card_number', 'exit_date', 'exit_time', 'created_by',
        'action_type', 'reassign_name', 'reassign_email', 'reassign_phone',
    ];


}