<?php
namespace App\Exports;

use App\Invoice;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection,WithMapping,WithHeadings
{
    protected $expData;

    public function __construct($type,$expData)
    {
        $this->expData = $expData;
        $this->type = $type;
    }

    // public function array(): array
    // {
    //     return $this->expData;
    // }
    public function collection()
    {
        // return Invoice::all();
        return $this->expData;
    }

    public function map($expData): array
    {
        // dd($expData);
        // return [
        //     $expData['email'],
        //     $expData['mobile_no']   
        // ];

        if($this->type=='business')
        {
            return [
                $expData->business_name,
                $expData->first_name.' '. $expData->last_name,          
                $expData->email,
                $expData->mobile_no,                
                $expData->range_start.'-'.$expData->range_end,
                $expData->user_code,
                $expData->referral_code,
                $expData->created_at,
                $expData->userStatus==1 ? 'Active':'Inactive',
                $expData->account_type
            ];
        }
        else if($this->type=='hr')
        {           
            return [
                $expData->first_name.' '. $expData->last_name,
                $expData->email,
                $expData->profile->mobile_no,
                $expData->profile->countryDetails->name,
                $expData->Parent->business->business_name,  
                $expData->created_at,
                $expData->status==1 ? 'Active':'Inactive',
                $expData->account_type                    
            ];          
        }
        else if($this->type=='lead head')
        {
            return [
                $expData->first_name.' '. $expData->last_name,
                $expData->email,
                $expData->profile->mobile_no,
                $expData->profile->countryDetails->name,
                 
                $expData->created_at,
                $expData->status==1 ? 'Active':'Inactive',
                $expData->account_type                    
            ];
        }
        else if($this->type=='lead staff')
        {
            return [
                $expData->first_name.' '. $expData->last_name,
                $expData->email,
                $expData->profile->mobile_no,
                $expData->profile->countryDetails->name,
                $expData->Parent->first_name.' '.$expData->Parent->last_name,  
                $expData->created_at,
                $expData->status==1 ? 'Active':'Inactive',
                $expData->account_type                    
            ];
        }
        else if($this->type=='verification head')
        {
            return [
                $expData->first_name.' '. $expData->last_name,
                $expData->email,
                $expData->profile->mobile_no,
                $expData->profile->countryDetails->name,
                $expData->verificationstaff->department,
                
                $expData->created_at,
                $expData->status==1 ? 'Active':'Inactive',
                $expData->account_type                    
            ];
        }
        else if($this->type=='verification staff')
        {
            return [
                $expData->first_name.' '. $expData->last_name,
                $expData->email,
                $expData->profile->mobile_no,
                $expData->profile->countryDetails->name,
                $expData->verificationstaff->department,
                $expData->Parent->first_name.' '.$expData->Parent->last_name,  
                $expData->created_at,
                $expData->status==1 ? 'Active':'Inactive',
                $expData->account_type                    
            ];
        }
        else if($this->type=='enroll')
        {
            // dd($expData);
            if($expData->status==1)
            { $status='Pending'; }                             
            else if($expData->status==2){ $status='Verified'; }
            else if($expData->status==3){ $status='Created'; }
            else if($expData->status==4){ $status='Rejected'; }
            else if($expData->status==5){ $status='Assigned to Staff'; }

            return [                       
                strtoupper($expData->business_name),
                strtoupper($expData->owner_first_name.' '.$expData->owner_last_name),
                strtoupper($expData->email),
                $expData->mobile_no,
                strtoupper($expData->countryDetails->name),
                $expData->noOfEmp->range_start.'-'.$expData->noOfEmp->range_end,
                date('d-m-Y', strtotime($expData->created_at)),
                $expData->gst,
                $expData->pan,
                $status,      
                $expData->verifier_id!=Null ? strtoupper($expData->Verifier->first_name.' '.$expData->Verifier->last_name) : '',
                $expData->creator_id!=Null ? strtoupper($expData->Creator->first_name.' '.$expData->Verifier->last_name) : '',
                $expData->agent_id!=Null ? strtoupper($expData->Agent->first_name.' '.$expData->Verifier->last_name) : ''                
            ];
        }
        else if($this->type=='offerletter')
        {
            
            // dd($expData);
            if($expData->is_accepted==0){ $status='Pending'; }                             
            else if($expData->is_accepted==1){ $status='Accepted(Joining Confirmed)'; }
            else if($expData->is_accepted==2){ $status='Offer Rejected'; }
            else if($expData->is_accepted==3){ $status='Request For Reschedule'; }
           

            return [                       
                strtoupper($expData->candidateDetails->name),
                $expData->joining_date,
                $expData->place_of_joining,
                $expData->time_of_joining,
                $expData->annual_ctc,
                strtoupper($expData->hrDetails->first_name),
                strtoupper($expData->businessDetails->business->business_name),
                $status               


                              
            ];
        }
        else if($this->type=='kyc')
        {
            
            // dd($expData);
            if($expData->status==1){ $status='Pending'; }
            else if($expData->status==2){ $status='Assign to Staff'; }
            else if($expData->status==3){ $status='Verified'; }
            else if($expData->status==4){ $status='Unverified'; }
            else if($expData->status==5){ $status='Reject Request'; }
           

            return [ 
                strtoupper($expData->candidate->name),                     
                $expData->verification_type,
                $expData->hr_id!=null ? strtoupper($expData->hr->first_name.' '.$expData->hr->last_name) : '',
                $expData->staff_id!=null ? strtoupper($expData->staff->first_name.' '.$expData->hr->last_name) :'',
                $expData->business_id!=null ? strtoupper($expData->businessUser->business->business_name) :'',
                $status        
            ];
        }        
        else if($this->type=='candidate')
        {
                if($expData->hr_id==$expData->added_by){
                $addedby=strtoupper($expData->hrDetails->first_name).'(HR)';
                }
                else if($expData->business_id==$expData->added_by)
                {
                    $addedby=strtoupper($expData->businessDetails->business->business_name).'(Business)';
                }
            return[
                strtoupper($expData->name),
                strtoupper($expData->email),
                $expData->phone,
                strtoupper($expData->gender),
                $addedby,
                strtoupper($expData->assignTo->first_name)
            ];
        }
        else if($this->type=='txn')
        {
                
            return[
                $expData->transaction_id,
                ($expData->user->first_name.' '.$expData->user->last_name),
                $expData->type,
                $expData->source,
                $expData->description,
                $expData->amount,
                $expData->updated_balance,
                $expData->created_at,
                $expData->status == '1' ? "Success":"Fail"
            ];
        }
        else if($this->type=='subpack')
        {
                
            return[
                strtoupper($expData->pack_name),
                $expData->expire_date,
                $expData->remain_qty,
                $expData->used_qty,
                $expData->business_name ,              
                $expData->status == '1' ? "Active":"Expired"
            ];
        }
        else if($this->type=='depositlist')
        {
            if($expData->status==2)
                { $status='Approved'; }
            else if($expData->status==3)
                { $status='Rejected'; }
            else
                { $status='Pending'; }
            

            return[
                strtoupper($expData->user->first_name.' '.$expData->user->last_name),
                $expData->tid,
                $expData->amount,
                $expData->comment,
                $expData->created_at ,              
                $status
            ];
        }
        else if($this->type=='hrdeposit')
        {
            if($expData->status==2)
                { $status='Approved'; }
            else if($expData->status==3)
                { $status='Rejected'; }
            else
                { $status='Pending'; }
            

            return[
                strtoupper($expData->user->first_name.' '.$expData->user->last_name),
                $expData->tid,
                $expData->amount,
                $expData->comment,
                $expData->created_at ,              
                $status
            ];
        }
    }

    public function headings(): array
    {        
        

        if($this->type=='business')        
        {
            return ['Business Name','Name','Email','Phone','No Of Employee','User Code','Reference Code','Created Date','Status','User Type'];
        }
        else if($this->type=='hr')
        {
            return ['Name','Email','Phone','Country','Business Name','Created Date','Status','User Type'];
        }
        else if($this->type=='lead head')
        {
            return ['Name','Email','Phone','Country','Created Date','Status','User Type'];
        }
        else if($this->type=='lead staff')
        {
            return ['Name','Email','Phone','Country','Head','Created Date','Status','User Type'];
        }
        else if($this->type=='verification head')
        {
            return ['Name','Email','Phone','Country','Department','Created Date','Status','User Type'];
        }
        else if($this->type=='verification staff')
        {
            return ['Name','Email','Phone','Country','Department','Head','Created Date','Status','User Type'];
        }
        else if($this->type=='enroll')
        {
            return ['Business Name','Owner Name','Email','Phone','Country','No Of Employee','Date','GST No','PAN No','Status','Verifier','Creator','Assign To'];
        }
        else if($this->type=='offerletter')
        {
            return ['Candidate Name','Joining Date','Place Of Joining','Time Of Joining','Annual CTC (Rs.)','HR','Business','Status'];
        }
        else if($this->type=='kyc')
        {
            return ['Candidate Name','Verification Type','HR','Staff','Business','Status'];
        }
        else if($this->type=='candidate')
        {
            return ['Name','Email','Phone','Gender','Added By','AssignTo'];
        }
        else if($this->type=='txn')
        {
            return ['Transaction ID','User Name','Type','Source','Description','Amount(Rs.)','Updated Balance(Rs.)','Date','Status'];
        }
        else if($this->type=='subpack')
        {
            return ['Package Name','Expire Date','Remaining Qty','Used Qty','Business Name','Status'];
        }
        else if($this->type=='depositlist')
        {
            return ['User Name','Transaction ID','Amount (Rs.)','Comment','Date','Status'];
        }
        else if($this->type=='hrdeposit')
        {
            return ['User Name','Transaction ID','Amount (Rs.)','Comment','Date','Status'];
        }

    }
}