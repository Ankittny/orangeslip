<?php

use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\API;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

 

// Route::get('/app/home', 'API\HomeController@index');
Route::post('/app/register', 'API\AuthController@register');
Route::post('/app/enroll_staff', 'API\HomeController@enrollStaff');
Route::post('/app/login',  'API\AuthController@login');
Route::post('/app/reset_password',  'API\AuthController@getResetToken');
Route::post('/app/send_otp_phone', 'API\AuthController@sendOtpPhone');
Route::get('/app/all_access', 'API\BusinessController@addHr');
Route::get('/app/all_country', 'API\SettingController@allCountry');

Route::post('/app/enroll_company', 'API\BusinessController@enrollCompanyStore');
Route::post('/app/hr_registration','API\BusinessController@registrationHr');
Route::get('/app/all_business','API\BusinessController@businessList');


//Master TAble
Route::get('/app/job_role', 'API\SettingController@jobRole');
Route::get('/app/salary_component', 'API\SettingController@salaryComponent');     
Route::get('/app/manage_state', 'API\SettingController@manageState');
Route::get('/app/manage_city', 'API\SettingController@manageCity');
Route::get('/app/manage_document_type', 'API\SettingController@manageDocType');
Route::get('/app/designation', 'API\SettingController@designationView');
Route::get('/app/emp_range', 'API\SettingController@empRangeView');
Route::get('/app/response_reason', 'API\SettingController@responseReason');
Route::get('/app/packages', 'API\SettingController@packagesView');
Route::get('/app/bank_details', 'API\SettingController@bankDetailsView');

Route::get('/app/all_course', 'API\SettingController@allCourse');
Route::get('/app/all_specialization', 'API\SettingController@allSpecialization');

Route::group(['middleware' => ['auth:api']], function(){
     
    Route::get('/app/user_access', 'API\SettingController@userAccess');
    Route::get('/app/mail_server_setting', 'API\SettingController@mailServerSetting');
    Route::post('/app/mail_server_setting_store', 'API\SettingController@mailServerSettingStore');
    Route::post('/app/mail_server_setting_update', 'API\SettingController@mailServerSettingUpdate');


    Route::post('/app/offerletterresponse/{id}', 'API\OfferLetterController@offerLetterResponse');

    Route::get('/app/user', 'API\AuthController@user');
    Route::post('/app/logout', 'API\AuthController@logout');
    Route::post('/app/change_password', 'API\HomeController@updatePassword');

    Route::get('/app/user_dashboard', 'API\HomeController@Userdashboard');
    Route::get('/app/business_list','API\BusinessController@index');
    Route::post('/app/business_create','API\BusinessController@store');
    Route::post('/app/hr_create','API\BusinessController@saveHrDetails');
    Route::get('/app/hr_list','API\BusinessController@hrList');
    Route::get('/app/enroll_list', 'API\BusinessController@enrollList');
    Route::post('/app/update_business/{id}', 'API\BusinessController@updateBusiness');
    Route::post('/app/updateHr/{id}', 'API\BusinessController@updateHr');
    Route::post('/app/edit_profile', 'API\BusinessController@updateProfile');
    Route::post('/app/update_profile_image', 'API\BusinessController@updateProfileImage');
    Route::post('/app/update_business_logo', 'API\BusinessController@updateBusinessLogo');
    Route::get('/app/editHr/{id}', 'API\BusinessController@editHr');
    Route::get('/app/change_status/{id}', 'API\BusinessController@changeStatus');
    Route::post('/app/package_subscription', 'API\BusinessController@packageSubscription');
    Route::get('/app/subscribed_packages', 'API\BusinessController@subscribedPackages');
    Route::get('/app/affiliate_links_history', 'API\BusinessController@affiliateLinks');

    // Route::get('/app/get_city', 'API\CandidateController@getCity');
    Route::post('/app/chk_hr_access', 'API\CandidateController@chkHrAccess');    
    Route::get('/app/get_hr', 'API\CandidateController@getHr');    
    Route::post('/app/add_candidate','API\CandidateController@registrationStore');
    Route::post('/app/bulkDataUpload','API\CandidateController@Upload');
    Route::get('/app/bulkDataList','API\CandidateController@UploadView');
    Route::get('/app/candidate_list','API\CandidateController@list');
    Route::get('/app/candidate_view/{id}','API\CandidateController@candidateView');
    Route::post('/app/basicdetails/{id}', 'API\CandidateController@BasicDetailsUpdate');
    Route::post('/app/educationdetails/{id}', 'API\CandidateController@EducationDetailsUpdate');   
    Route::post('/app/professionaldetails/{id}', 'API\CandidateController@ProfessionalDetailsUpdate');
    Route::post('/app/othersdetails/{id}', 'API\CandidateController@OthersDetailsUpdate');   
    Route::post('/app/upload_document/{id}', 'API\CandidateController@uploadDocumentStore');
    Route::get('/app/upload_document/{id}', 'API\CandidateController@uploadDocumentView');
    Route::get('/app/candidate_follow_up/{id}', 'API\CandidateController@candidateFollowUpList');
    Route::post('/app/candidate_follow_up/{id}', 'API\CandidateController@candidateFollowUpStore');
    Route::post('/app/is_selected', 'API\CandidateController@isSelected');
    Route::get('/app/export-csv',  'API\CandidateController@exportCSV');
    Route::post('/app/rating-review',  'API\CandidateController@ratingReviewStore');
    Route::get('/app/joiningdetails/{id}',  'API\CandidateController@joiningDetailsView');
    Route::post('/app/joiningdetails/{id}',  'API\CandidateController@joiningDetailsUpdate');
    // Route::get('/app/exportCsv', 'API\CandidateController@curl');

    Route::get('/app/deleteeducation/{id}', 'API\CandidateController@deleteEducation');
    Route::get('/app/deleteprofession/{id}', 'API\CandidateController@deleteProfession');
    Route::get('/app/deleteother/{id}', 'API\CandidateController@deleteOthers');
    Route::get('/app/deletedocument/{id}', 'API\CandidateController@deleteDocument');
    Route::get('/app/import_bulk_data', 'API\CandidateController@importBulkData');
    Route::post('/app/reallot_candidate', 'API\CandidateController@reallotCandidate');
    
    Route::post('/app/create_offer_letter','API\OfferLetterController@CreateofferLetterStore');
    Route::get('/app/offer_letter_list','API\OfferLetterController@offerLetterList');
    Route::get('/app/offer_letter/{id}','API\OfferLetterController@offerLetter');
    Route::get('/app/resend_mail/{id}','API\OfferLetterController@resendMail');
    Route::post('/app/regenerate_offer_letter/{id}','API\OfferLetterController@duplicateOfferLetter');

    Route::get('/app/candidate_profile','API\CandidateProfileController@candidateProfile');
    Route::post('/app/add_personal', 'API\CandidateProfileController@addPersonal');
    Route::post('/app/add_education', 'API\CandidateProfileController@addEducation');
    Route::post('/app/add_profession', 'API\CandidateProfileController@addProfession');
    Route::post('/app/add_skills', 'API\CandidateProfileController@addSkills');
    Route::post('/app/add_language', 'API\CandidateProfileController@addLanguage');
    Route::post('/app/add_hobbies', 'API\CandidateProfileController@addHobbies');
    Route::get('/app/candidate_offer', 'API\CandidateProfileController@candidateOffer');
    Route::get('/app/candidate_uncheck_offer', 'API\CandidateProfileController@candidateUncheckOffer');
    Route::post('/app/upload_file', 'API\CandidateProfileController@uploadFile');

    Route::get('/app/del_edu/{id}', 'API\CandidateProfileController@delEdu');
    Route::get('/app/del_prof/{id}', 'API\CandidateProfileController@delProf');
    Route::get('/app/del_oth/{id}', 'API\CandidateProfileController@delOth');

    
    Route::post('/app/is_checked', 'API\CandidateProfileController@isChecked');
    Route::post('/app/business_review_submit', 'API\CandidateProfileController@businessReviewSubmit');
    Route::get('/app/rating_review_list', 'API\CandidateProfileController@ratingReviewList');

    
    Route::get('/app/depositlist', 'API\WalletController@DepositList');
    Route::post('/app/deposit', 'API\WalletController@DepositStore');
    Route::post('/app/credit_amount/{id}', 'API\WalletController@credit_amount');
    Route::post('/app/debit_amount/{id}', 'API\WalletController@debit_amount');
    Route::get('/app/transaction', 'API\WalletController@index');
    Route::get('/app/hr_deposit_list', 'API\WalletController@hrDepositList');
    Route::post('/app/approve_hr_deposit', 'API\WalletController@ApproveHrDeposit');

    Route::post('/app/verification/{id}', 'API\VerificationController@store');
    Route::get('/app/get_staff', 'API\VerificationController@getStaff');
    Route::get('/app/verificationtypes', 'API\VerificationController@verificationTypes');
    Route::get('/app/verificationlist', 'API\VerificationController@verificationList');  

    Route::get('/app/view_verification_report/{id}', 'API\VerificationStaffController@ViewVerificationReport');

    Route::get('/app/lead_staff_list', 'API\LeadStaffController@leadStaffList');
    Route::get('/app/add_lead_staff', 'API\LeadStaffController@addLeadStaff');
    Route::post('/app/add_lead_staff', 'API\LeadStaffController@saveLeadStaff');
    Route::get('/app/edit_lead_staff/{id}', 'API\LeadStaffController@editLeadStaff');
    Route::post('/app/update_lead_staff/{id}', 'API\LeadStaffController@updateLeadStaff');
    Route::get('/app/assign_enroll_lead', 'API\LeadStaffController@assignBusLead');
    Route::post('/app/assign_enroll_lead', 'API\LeadStaffController@assignBusLeadStore');
   
    Route::get('/app/verification_staff', 'API\VerificationStaffController@addVerificationStaff');
    Route::post('/app/verification_staff', 'API\VerificationStaffController@storeVerificationStaff');
    Route::get('/app/verification_staff_list', 'API\VerificationStaffController@verificationStaffList');
    Route::get('/app/edit_verification_staff/{id}', 'API\VerificationStaffController@editVerificationStaff');
    Route::post('/app/update_verification_staff/{id}', 'API\VerificationStaffController@updateVerificationStaff');

    Route::post('/app/verification_assign',  'API\VerificationStaffController@VerificationAssign');
    Route::post('/app/submit_verification_report',  'API\VerificationStaffController@SubmitVerificationReport');
 
    Route::get('/app/view_verification_doc/{id}',  'API\VerificationStaffController@ViewVerificationDoc');
    Route::get('/app/reject-request/{id}',  'API\VerificationStaffController@rejectRequest');

    
});

 

 


 