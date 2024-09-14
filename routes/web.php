<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/thankyou', function () {
    return view('page.thankyou');
});

Route::get("send-email", [App\Http\Controllers\HomeController::class, "composeEmail"])->name("send-email");
Route::post('login', 'Auth\LoginController@login');
Auth::routes();
Route::get('images/{flolder?}/{file?}/{user?}', ['as' => 'image.get', 'uses' => '\App\Http\Controllers\FileController@getFile']);//for all Role
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/candidate_signup', [App\Http\Controllers\Auth\RegisterController::class, 'candidateSignup'])->name('candidateSignup');

Route::post('/send_otp_phone', [App\Http\Controllers\Auth\RegisterController::class, 'sendOtpPhone'])->name('sendOtpPhone');

// Route::get('/candidate/login', [App\Http\Controllers\CandidateController::class, 'LoginView'])->name('candidate.LoginView');
Route::get('/candidate/login', [App\Http\Controllers\CandidateController::class, 'LoginView'])->name('candidate.LoginView');
Route::post('/candidate/login', [App\Http\Controllers\CandidateController::class, 'LoginCheck'])->name('candidate.LoginCheck');
Route::get('/candidate/home', [App\Http\Controllers\CandidateController::class, 'candidateHome'])->name('candidateHome');
Route::post('/candidate/home', [App\Http\Controllers\CandidateController::class, 'setPassword'])->name('setPassword');
Route::post('/send_otp', [App\Http\Controllers\CandidateController::class, 'sendOtp'])->name('sendOtp');

Route::get('/candidate/logout', [App\Http\Controllers\CandidateController::class, 'LogOut'])->name('LogOut');

Route::get('/enroll_company', [App\Http\Controllers\BusinessController::class, 'enrollCompanyView'])->name('enrollCompanyView');
Route::post('/enroll_company', [App\Http\Controllers\BusinessController::class, 'enrollCompanyStore'])->name('enrollCompanyStore');

Route::post('/offerletterresponse', [App\Http\Controllers\OfferLetterController::class, 'offerLetterResponse'])->name('offerLetterResponse');

Route::get("/verify_email/{token}", [App\Http\Controllers\CandidateProfileController::class, 'verify_email_user'])->name('user_verify_email');
Route::get('/offer_letter/{id}', [App\Http\Controllers\OfferLetterController::class,'offerLetter'])->name('offerLetter');

Route::get('/candidate_list_csv/{id}',  [App\Http\Controllers\CandidateController::class, 'ApiExportCsv']);

Route::get('/check_package_status', [App\Http\Controllers\HomeController::class, 'checkPackageSubscription'])->name('checkPackageSubscription');

/** for chatbot */
Route::match(['get', 'post'], '/botman', [App\Http\Controllers\BotManController::class, 'handle'])->name('botman.handle');
Route::get('/botman/index', [App\Http\Controllers\BotManController::class, 'view']);
Route::get('/botman/chatfrane', [App\Http\Controllers\BotManController::class, 'chat']);

Route::get('/test_botman', [App\Http\Controllers\BotManController::class, 'test_botman']);
/** end chatbot */


Route::group(['middleware' => 'auth'], function(){

    Route::get('export', [App\Http\Controllers\CandidateController::class, 'export']);

    Route::get('/candidate_offer_letter/{id}', [App\Http\Controllers\OfferLetterController::class,'candidateOfferLetter'])->name('candidateOfferLetter');

    Route::get('/change_password', [App\Http\Controllers\HomeController::class, 'changePassword'])->name('changePassword');
    Route::post('/change_password', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

    Route::get('/export-csv',  [App\Http\Controllers\CandidateController::class, 'exportCSV']);
   
    Route::get("/login_as_admin", "BusinessController@login_as_admin")->name('login_as_admin');


    Route::resource('/business', BusinessController::class);
    Route::post('/update_business/{id}', [App\Http\Controllers\BusinessController::class, 'updateBusiness'])->name('updateBusiness');
    Route::get('/hr_list', [App\Http\Controllers\BusinessController::class, 'hrList'])->name('hr_list');
    Route::get('/addHr', [App\Http\Controllers\BusinessController::class, 'addHr'])->name('add_hr');
    Route::get('/editHr/{id}', [App\Http\Controllers\BusinessController::class, 'editHr'])->name('edit_hr');
    Route::post('/updateHr/{id}', [App\Http\Controllers\BusinessController::class, 'updateHr'])->name('update_hr');
    Route::get('/edit_profile', [App\Http\Controllers\BusinessController::class, 'editProfile'])->name('editProfile');
    Route::post('/edit_profile', [App\Http\Controllers\BusinessController::class, 'updateProfile'])->name('updateProfile');
    Route::post('/saveHr', [App\Http\Controllers\BusinessController::class, 'saveHrDetails'])->name('hrdetails.store');
    Route::get('/enroll_list', [App\Http\Controllers\BusinessController::class, 'enrollList'])->name('enrollList');
    Route::post('/company_enroll_response', [App\Http\Controllers\BusinessController::class, 'companyEnrollResponse'])->name('companyEnrollResponse');
     
    Route::get('/follow_up/{id}', [App\Http\Controllers\BusinessController::class, 'followUpList'])->name('followUpList');
    Route::post('/follow_up/{id}', [App\Http\Controllers\BusinessController::class, 'followUpStore'])->name('followUpStore');
    Route::post('/follow_up_status', [App\Http\Controllers\BusinessController::class, 'followUpStatusUpdate']);
    Route::get('/change_status/{id}', [App\Http\Controllers\BusinessController::class, 'changeStatus']);

    Route::get('/import_bulk_data', [App\Http\Controllers\CandidateController::class, 'importBulkData'])->name('importBulkData');
    Route::get('/bulk_upload', [App\Http\Controllers\CandidateController::class, 'UploadView'])->name('bulk_upload');
    Route::post('/upload', [App\Http\Controllers\CandidateController::class, 'Upload'])->name('Upload'); 
    Route::get('/registration', [App\Http\Controllers\CandidateController::class, 'registration'])->name('registration');
    Route::post('/registration', [App\Http\Controllers\CandidateController::class, 'registrationStore'])->name('registration.store');
    Route::get('/candidate_list', [App\Http\Controllers\CandidateController::class, 'list'])->name('candidate_list');
    Route::get('/candidate_view/{id}', [App\Http\Controllers\CandidateController::class, 'candidateView'])->name('candidateview');
    Route::get('/basicdetails/{id}', [App\Http\Controllers\CandidateController::class, 'BasicDetailsView'])->name('BasicDetailsView');
    Route::post('/basicdetails/{id}', [App\Http\Controllers\CandidateController::class, 'BasicDetailsUpdate'])->name('BasicDetailsUpdate');
    Route::get('/educationdetails/{id}', [App\Http\Controllers\CandidateController::class, 'EducationDetailsView'])->name('EducationDetailsView');
    Route::post('/educationdetails/{id}', [App\Http\Controllers\CandidateController::class, 'EducationDetailsUpdate'])->name('EducationDetailsUpdate');
    Route::get('/deleteeducation/{id}', [App\Http\Controllers\CandidateController::class, 'deleteEducation'])->name('deleteEducation');
    Route::get('/deleteprofession/{id}', [App\Http\Controllers\CandidateController::class, 'deleteProfession'])->name('deleteProfession');
    Route::get('/professionaldetails/{id}', [App\Http\Controllers\CandidateController::class, 'ProfessionalDetailsView'])->name('ProfessionalDetailsView');
    Route::post('/professionaldetails/{id}', [App\Http\Controllers\CandidateController::class, 'ProfessionalDetailsUpdate'])->name('ProfessionalDetailsUpdate');
    Route::get('/othersdetails/{id}', [App\Http\Controllers\CandidateController::class, 'OthersDetailsView'])->name('OthersDetailsView');
    Route::post('/addothersdetails', [App\Http\Controllers\CandidateController::class, 'OthersDetailsUpdate'])->name('OthersDetailsUpdate');
    Route::get('/deleteother', [App\Http\Controllers\CandidateController::class, 'deleteOthers'])->name('deleteOthers');
    Route::post('/is_selected', [App\Http\Controllers\CandidateController::class, 'isSelected'])->name('isSelected');
    Route::get('/joiningdetails/{id}', [App\Http\Controllers\CandidateController::class, 'joiningDetailsView'])->name('joiningDetailsView');
    Route::post('/joiningdetails/{id}', [App\Http\Controllers\CandidateController::class, 'joiningDetailsUpdate'])->name('joiningDetailsUpdate');
    Route::get('/reviewdetails/{id}', [App\Http\Controllers\CandidateController::class, 'reviewDetailsView'])->name('reviewDetailsView');    
    Route::post('/reviewdetails/{id}', [App\Http\Controllers\CandidateController::class, 'reviewDetailsUpdate'])->name('reviewDetailsUpdate');
    Route::get('/dispute_view/{id}', [App\Http\Controllers\CandidateController::class,'DisputeView'])->name('disputeview');
    Route::post('/dispute_view/{id}', [App\Http\Controllers\CandidateController::class,'DisputeStore'])->name('disputestore');
    Route::get('/get_city', [App\Http\Controllers\CandidateController::class,'getCity'])->name('getCity');
    Route::get('/get_hr', [App\Http\Controllers\CandidateController::class,'getHr'])->name('getHr');
    Route::get('/get_template', [App\Http\Controllers\CandidateController::class,'getTemplate'])->name('getTemplate');
    Route::get('/candidate_follow_up/{id}', [App\Http\Controllers\CandidateController::class, 'candidateFollowUpList'])->name('candidateFollowUpList');
    Route::post('/candidate_follow_up/{id}', [App\Http\Controllers\CandidateController::class, 'candidateFollowUpStore'])->name('candidateFollowUpStore');
    Route::get('/candidate_follow_up_status/{id}', [App\Http\Controllers\CandidateController::class, 'candidateFollowUpStatusUpdate']);
    Route::get('/upload_document/{id}', [App\Http\Controllers\CandidateController::class, 'uploadDocumentView'])->name('uploadDocumentView');
    Route::post('/upload_document/{id}', [App\Http\Controllers\CandidateController::class, 'uploadDocumentStore'])->name('uploadDocumentStore');
    Route::get('/deletedocument/{id}', [App\Http\Controllers\CandidateController::class, 'deleteDocument'])->name('deleteDocument');
    Route::get('/edit_candidate/{id}', [App\Http\Controllers\CandidateController::class, 'editCandidate'])->name('editCandidate');
    Route::post('/rating_review_store', [App\Http\Controllers\CandidateController::class, 'ratingReviewStore'])->name('ratingReviewStore');
    Route::post('/reallot_candidate', [App\Http\Controllers\CandidateController::class, 'reallotCandidate'])->name('reallotCandidate');
    Route::get('/professional-feedback/{id}', [App\Http\Controllers\CandidateController::class, 'professionalFeedback'])->name('professionalFeedback');
    Route::post('/professional-feedback/{id}', [App\Http\Controllers\CandidateController::class, 'professionalFeedbackStore'])->name('professionalFeedbackStore');
    Route::post('/physical_joining_point_store', [App\Http\Controllers\CandidateController::class, 'physical_joining_point_store'])->name('physical_joining_point_store');



    Route::get('/candidate_profile', [App\Http\Controllers\CandidateProfileController::class,'candidateProfile'])->name('candidateProfile');
    Route::get('/candidate_uncheck_offer', [App\Http\Controllers\CandidateProfileController::class,'candidateUncheckOffer'])->name('candidateUncheckOffer');
    Route::post('/is_checked', [App\Http\Controllers\CandidateProfileController::class, 'isChecked'])->name('isChecked');
    Route::get('/candidate_offer', [App\Http\Controllers\CandidateProfileController::class,'candidateOffer'])->name('candidateOffer');
    Route::post('/add_education', [App\Http\Controllers\CandidateProfileController::class,'addEducation'])->name('addEducation');
    Route::post('/add_profession', [App\Http\Controllers\CandidateProfileController::class,'addProfession'])->name('addProfession');
    Route::post('/add_personal', [App\Http\Controllers\CandidateProfileController::class,'addPersonal'])->name('addPersonal');
    Route::post('/add_language', [App\Http\Controllers\CandidateProfileController::class,'addLanguage'])->name('addLanguage');
    Route::post('/add_skills', [App\Http\Controllers\CandidateProfileController::class,'addSkills'])->name('addSkills');
    Route::post('/add_hobbies', [App\Http\Controllers\CandidateProfileController::class,'addHobbies'])->name('addHobbies');
    Route::get('/del_edu/{id}', [App\Http\Controllers\CandidateProfileController::class, 'delEdu'])->name('delEdu');
    Route::get('/del_prof/{id}', [App\Http\Controllers\CandidateProfileController::class, 'delProf'])->name('delProf');
    Route::get('/del_oth/{id}', [App\Http\Controllers\CandidateProfileController::class, 'delOth'])->name('delOth');
    Route::post('/upload_file', [App\Http\Controllers\CandidateProfileController::class, 'uploadFile'])->name('uploadFile');
    Route::post('/business_review_submit', [App\Http\Controllers\CandidateProfileController::class, 'businessReviewSubmit'])->name('businessReviewSubmit');
    Route::get('/rating_review_list', [App\Http\Controllers\CandidateProfileController::class, 'ratingReviewList'])->name('ratingReviewList');

    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');//need to change controller

    Route::get('/assign_enroll_lead', [App\Http\Controllers\LeadStaffController::class, 'assignBusLead'])->name('assignBusLead');
    Route::post('/assign_enroll_lead', [App\Http\Controllers\LeadStaffController::class, 'assignBusLeadStore'])->name('assignBusLeadStore');

    Route::get('/lead_head_list', [App\Http\Controllers\LeadStaffController::class, 'leadHeadList'])->name('leadHeadList');
    Route::get('/add_lead_head', [App\Http\Controllers\LeadStaffController::class, 'addLeadHead'])->name('addLeadHead');
    Route::post('/add_lead_head', [App\Http\Controllers\LeadStaffController::class, 'saveLeadHead'])->name('saveLeadHead');
    Route::get('/edit_lead_head/{id}', [App\Http\Controllers\LeadStaffController::class, 'editLeadHead'])->name('editLeadHead');
    Route::post('/update_lead_head/{id}', [App\Http\Controllers\LeadStaffController::class, 'updateLeadHead'])->name('updateLeadHead');
    
    Route::get('/lead_staff_list', [App\Http\Controllers\LeadStaffController::class, 'leadStaffList'])->name('leadStaffList');
    Route::get('/add_lead_staff', [App\Http\Controllers\LeadStaffController::class, 'addLeadStaff'])->name('addLeadStaff');
    Route::post('/add_lead_staff', [App\Http\Controllers\LeadStaffController::class, 'saveLeadStaff'])->name('saveLeadStaff');
    Route::get('/edit_lead_staff/{id}', [App\Http\Controllers\LeadStaffController::class, 'editLeadStaff'])->name('editLeadStaff');
    Route::post('/update_lead_staff/{id}', [App\Http\Controllers\LeadStaffController::class, 'updateLeadStaff'])->name('updateLeadStaff');


    // Route::get('/generateofferletter/{id}', [App\Http\Controllers\OfferLetterController::class, 'GenerateOfferLetterView'])->name('GenerateOfferLetterView');
    // Route::post('/generateofferletter/{id}', [App\Http\Controllers\OfferLetterController::class, 'GenerateOfferLetterStore'])->name('GenerateOfferLetterStore');
    Route::get('/offer_letter_list', [App\Http\Controllers\OfferLetterController::class,'offerLetterList'])->name('offerLetterList');
    
    Route::get('/create_offer_letter', [App\Http\Controllers\OfferLetterController::class,'CreateofferLetter'])->name('CreateofferLetter');
    Route::post('/create_offer_letter', [App\Http\Controllers\OfferLetterController::class,'CreateofferLetterStore'])->name('CreateofferLetterStore');
    Route::get('/resend_mail/{id}', [App\Http\Controllers\OfferLetterController::class,'resendMail']);
    Route::get('/regenerate_offer_letter/{id}', [App\Http\Controllers\OfferLetterController::class,'editOfferLetter']);
    Route::post('/regenerate_offer_letter/{id}', [App\Http\Controllers\OfferLetterController::class,'duplicateOfferLetter']);
  

    
    Route::get('/verification', [App\Http\Controllers\VerificationController::class, 'create'])->name('verification.create');
    Route::post('/verification', [App\Http\Controllers\VerificationController::class, 'store'])->name('verification.store');
    Route::get('/verificationlist', [App\Http\Controllers\VerificationController::class, 'verificationList'])->name('verification.list');
    Route::get('/get_staff', [App\Http\Controllers\VerificationController::class, 'getStaff'])->name('getStaff');
     

    Route::get('/verification_head', [App\Http\Controllers\VerificationStaffController::class, 'addVerificationHead'])->name('addVerificationHead');
    Route::post('/verification_head', [App\Http\Controllers\VerificationStaffController::class, 'storeVerificationHead'])->name('storeVerificationHead');
    Route::get('/verification_head_list', [App\Http\Controllers\VerificationStaffController::class, 'verificationHeadList'])->name('verificationHeadList');
    Route::get('/edit_verification_head/{id}', [App\Http\Controllers\VerificationStaffController::class, 'editVerificationHead'])->name('editVerificationHead');
    Route::post('/update_verification_head/{id}', [App\Http\Controllers\VerificationStaffController::class, 'updateVerificationHead'])->name('updateVerificationHead');

    Route::get('/verification_staff', [App\Http\Controllers\VerificationStaffController::class, 'addVerificationStaff'])->name('addVerificationStaff');
    Route::post('/verification_staff', [App\Http\Controllers\VerificationStaffController::class, 'storeVerificationStaff'])->name('storeVerificationStaff');
    Route::get('/verification_staff_list', [App\Http\Controllers\VerificationStaffController::class, 'verificationStaffList'])->name('verificationStaffList');
    Route::get('/edit_verification_staff/{id}', [App\Http\Controllers\VerificationStaffController::class, 'editVerificationStaff'])->name('editVerificationStaff');
    Route::post('/update_verification_staff/{id}', [App\Http\Controllers\VerificationStaffController::class, 'updateVerificationStaff'])->name('updateVerificationStaff');

   

    Route::post('/verification_assign', [App\Http\Controllers\VerificationStaffController::class,'VerificationAssign'])->name('verification.assign');
    Route::post('/submit_verification_report', [App\Http\Controllers\VerificationStaffController::class,'SubmitVerificationReport'])->name('submit_verification_report');
    Route::post('/view_verification_report', [App\Http\Controllers\VerificationStaffController::class,'ViewVerificationReport'])->name('view_verification_report');
    Route::post('/view_verification_doc', [App\Http\Controllers\VerificationStaffController::class,'ViewVerificationDoc'])->name('view_verification_doc');
    Route::get('/reject-request/{id}', [App\Http\Controllers\VerificationStaffController::class,'rejectRequest'])->name('rejectRequest');


    Route::get("/credit_amount/{id}", [App\Http\Controllers\WalletController::class, 'credit_amount_form']);
    Route::post("/credit_amount/{id}", [App\Http\Controllers\WalletController::class, 'credit_amount'])->name("admin.user.credit_amount");
    Route::get("/debit_amount/{id}", [App\Http\Controllers\WalletController::class, 'debit_amount_form']);
    Route::post("/debit_amount/{id}", [App\Http\Controllers\WalletController::class,'debit_amount'])->name("admin.user.debit_amount");
    Route::get('/transaction', [App\Http\Controllers\WalletController::class, 'index']);
    Route::get('/deposit', [App\Http\Controllers\WalletController::class,'DepositPage']);
    Route::post('/deposit', [App\Http\Controllers\WalletController::class,'DepositStore']);
    Route::get('/depositlist', [App\Http\Controllers\WalletController::class,'DepositList']);
    Route::post('/approvedeposit', [App\Http\Controllers\WalletController::class,'ApproveDeposit']);
    Route::post('/approve_hr_deposit', [App\Http\Controllers\WalletController::class,'ApproveHrDeposit']);
    Route::get('/hr_deposit_requests', [App\Http\Controllers\WalletController::class,'hrDepositList']);
    Route::get('/packages_details', [App\Http\Controllers\WalletController::class,'packagesDetails']);
    Route::get('/package_subscription/{pack_id}', [App\Http\Controllers\WalletController::class,'packageSubscription']);

    Route::get('/create', [App\Http\Controllers\SettingController::class,'createView'])->name('setting.createView');   
    Route::post('/store', [App\Http\Controllers\SettingController::class,'SettingsStore'])->name('settings.store');
    Route::get('/edit_setting', [App\Http\Controllers\SettingController::class,'settingEdit'])->name('settingEdit');
    Route::post('/update_setting', [App\Http\Controllers\SettingController::class,'settingUpdate'])->name('settingUpdate');



    Route::get('/salary_component', [App\Http\Controllers\SettingController::class,'salaryComponent'])->name('setting.salaryComponent');   
    Route::post('/salary_component', [App\Http\Controllers\SettingController::class,'salaryComponentStore'])->name('setting.salaryComponent.store');   
    Route::get('/get_sal_component', [App\Http\Controllers\SettingController::class,'getSalComponent'])->name('setting.getSalComponent');   
    Route::post('/update_sal_component', [App\Http\Controllers\SettingController::class,'updateSalComponent'])->name('setting.updateSalComponent');   

    Route::get('/job_role', [App\Http\Controllers\SettingController::class,'jobRole'])->name('setting.jobRole');   
    Route::post('/job_role', [App\Http\Controllers\SettingController::class,'jobRoleStore'])->name('setting.jobRole.store');   
    Route::get('/get_job_role', [App\Http\Controllers\SettingController::class,'getJobRoll'])->name('setting.getJobRoll');  
    Route::post('/update_job_role', [App\Http\Controllers\SettingController::class,'updateJobRoll'])->name('setting.updateJobRoll');  

    Route::get('/response_reason', [App\Http\Controllers\SettingController::class,'responseReason'])->name('setting.responseReason');   
    Route::post('/response_reason', [App\Http\Controllers\SettingController::class,'responseReasonStore'])->name('setting.responseReason.store');   
    Route::get('/get_res_reason', [App\Http\Controllers\SettingController::class,'getResReason'])->name('setting.getResReason');   
    Route::post('/update_res_reason', [App\Http\Controllers\SettingController::class,'updateResReason'])->name('setting.updateResReason');   
    Route::get('/user_access', [App\Http\Controllers\SettingController::class,'userAccess'])->name('setting.userAccess');   
    Route::post('/user_access', [App\Http\Controllers\SettingController::class,'userAccessStore'])->name('setting.userAccess.store');   
    Route::get('/get_user_access', [App\Http\Controllers\SettingController::class,'getUserAccess'])->name('setting.getUserAccess');   
    Route::post('/update_user_access', [App\Http\Controllers\SettingController::class,'updateUserAccess'])->name('setting.updateUserAccess');

    Route::get('/manage_state', [App\Http\Controllers\SettingController::class,'manageState'])->name('setting.manageState');   
    Route::post('/manage_state', [App\Http\Controllers\SettingController::class,'manageStateStore'])->name('setting.manageStateStore');   
    Route::get('/get_state_details', [App\Http\Controllers\SettingController::class,'getStateDetails'])->name('setting.getStateDetails');   
    Route::post('/update_state_details', [App\Http\Controllers\SettingController::class,'updateStateDetails'])->name('setting.updateStateDetails');   

    Route::get('/manage_industries', [App\Http\Controllers\SettingController::class,'manageIndustry'])->name('setting.manageIndustry');   
    Route::post('/manage_industries', [App\Http\Controllers\SettingController::class,'manageIndustryStore'])->name('setting.manageIndustryStore');  
   
    Route::get('/manage_city', [App\Http\Controllers\SettingController::class,'manageCity'])->name('setting.manageCity');   
    Route::post('/manage_city', [App\Http\Controllers\SettingController::class,'manageCityStore'])->name('setting.manageCityStore');   
    Route::get('/get_city_details', [App\Http\Controllers\SettingController::class,'getCityDetails'])->name('setting.getCityDetails');
    Route::post('/update_city_details', [App\Http\Controllers\SettingController::class,'updateCityDetails'])->name('setting.updateCityDetails');
    
    Route::get('/manage_document_type', [App\Http\Controllers\SettingController::class,'manageDocType'])->name('setting.manageDocType');   
    Route::post('/manage_document_type', [App\Http\Controllers\SettingController::class,'manageDocTypeStore'])->name('setting.manageDocTypeStore');   
    Route::get('/get_doc_type', [App\Http\Controllers\SettingController::class,'getDocTypeDetails'])->name('setting.getDocTypeDetails');
    Route::post('/update_doc_type_details', [App\Http\Controllers\SettingController::class,'updateDocTypeDetails'])->name('setting.updateDocTypeDetails');
     

    Route::get('/mail_server_setting', [App\Http\Controllers\SettingController::class, 'mailServerSetting'])->name('mailServerSetting');
    Route::post('/mail_server_setting', [App\Http\Controllers\SettingController::class, 'mailServerSettingStore'])->name('mailServerSetting.store');
    Route::post('/mail_server_setting_update', [App\Http\Controllers\SettingController::class, 'mailServerSettingUpdate'])->name('mailServerSetting.update');

    Route::get('/designation', [App\Http\Controllers\SettingController::class,'designationView'])->name('setting.designationView');   
    Route::post('/designation', [App\Http\Controllers\SettingController::class,'designationStore'])->name('setting.designation.store');   
    Route::get('/get_designation', [App\Http\Controllers\SettingController::class,'getDesignation'])->name('setting.getDesignation');  
    Route::post('/update_designation', [App\Http\Controllers\SettingController::class,'updateDesignation'])->name('setting.updateDesignation'); 

    Route::get('/emp_range', [App\Http\Controllers\SettingController::class,'empRangeView'])->name('setting.empRangeView');   
    Route::post('/emp_range', [App\Http\Controllers\SettingController::class,'empRangeStore'])->name('setting.empRange.store');   
    Route::get('/get_emp_range', [App\Http\Controllers\SettingController::class,'getempRange'])->name('setting.getempRange');  
    Route::post('/update_emp_range', [App\Http\Controllers\SettingController::class,'updateempRange'])->name('setting.updateempRange'); 

    Route::get('/packages', [App\Http\Controllers\SettingController::class,'packagesView'])->name('setting.packagesView'); 
    Route::post('/packages', [App\Http\Controllers\SettingController::class,'packageStore'])->name('setting.packageStore'); 
    Route::get('/edit_package', [App\Http\Controllers\SettingController::class,'packageEdit'])->name('setting.packageEdit'); 
    Route::post('/update_package', [App\Http\Controllers\SettingController::class,'packageUpdate'])->name('setting.packageUpdate'); 

    Route::get('/bank_details', [App\Http\Controllers\SettingController::class,'bankDetailsView'])->name('setting.bankDetailsView'); 
    Route::post('/bank_details', [App\Http\Controllers\SettingController::class,'bankDetailsStore'])->name('setting.bankDetailsStore'); 
    Route::get('/edit_bank', [App\Http\Controllers\SettingController::class,'bankEdit'])->name('setting.bankEdit'); 
    Route::post('/update_bank', [App\Http\Controllers\SettingController::class,'bankUpdate'])->name('setting.bankUpdate'); 

    Route::get('/meta_data_details', [App\Http\Controllers\SettingController::class,'metaDataView'])->name('setting.metaDataView'); 
    Route::post('/meta_data_details', [App\Http\Controllers\SettingController::class,'metaDataStore'])->name('setting.metaDataStore'); 
    Route::get('/edit_meta_data', [App\Http\Controllers\SettingController::class,'metaDataEdit'])->name('setting.metaDataEdit'); 
    Route::post('/update_meta_data', [App\Http\Controllers\SettingController::class,'metaDataUpdate'])->name('setting.vUpdate'); 

    Route::get('/manageKyc', [App\Http\Controllers\SettingController::class,'manageKyc'])->name('setting.manageKyc'); 
    Route::post('/manageKyc', [App\Http\Controllers\SettingController::class,'updateKyc'])->name('setting.updateKyc'); 

    Route::get('/manage-offer-letter', [App\Http\Controllers\SettingController::class,'manageOfferLetter'])->name('setting.manageOfferLetter'); 
    Route::post('/manage-offer-letter', [App\Http\Controllers\SettingController::class,'manageOfferLetterUpdate'])->name('setting.manageOfferLetterUpdate'); 

    Route::get('/acl/role',['as'=>'acl.role.index','uses'=>'\App\Http\Controllers\AclController@indexRole']);

    Route::get('/get_domain', [App\Http\Controllers\BusinessController::class,'getDomain'])->name('getDomain');
   

    Route::get('/candidate-list', [App\Http\Controllers\CandidateController::class, 'adminCandidateList'])->name('admin-candidate-list');
});

//Route::group(['middleware' => ['auth','is_admin']], function(){
/**
 * Routes for manage ACL
 */
Route::post("/login_as_member", [App\Http\Controllers\BusinessController::class,'login_as_member'])->name('login_as_member');


Route::get('/acl/role/create',['as'=>'acl.role.create','uses'=>'\App\Http\Controllers\AclController@createRole']);
Route::post('/acl/role/store',['as'=>'acl.role.store','uses'=>'\App\Http\Controllers\AclController@storeRole']);
Route::get('/acl/role/{role}/manage-permission',['as'=>'acl.role.manage.permission','uses'=>'\App\Http\Controllers\AclController@managePermission']);
Route::post('/acl/role/{role}/manage-permission',['as'=>'acl.role.manage.permission.set','uses'=>'\App\Http\Controllers\AclController@managePermissionSet']);

Route::get('/acl/assign_role',['as'=>'acl.assign_role','uses'=>'App\Http\Controllers\AclController@assignRole']);
Route::post('/acl/assign_role/save',['as'=>'acl.assign_role.save','uses'=>  'App\Http\Controllers\AclController@saveAssignedRole']);
// Route::get('acl/permission',['as'=>'acl.permission.index','uses'=>'App\Http\Controllers\AclController@indexPermission']);
// Route::get('acl/assign_role',['as'=>'acl.assign_role','uses'=>'App\Http\Controllers\AclController@assignRole']);
//Route::get('acl/permission/{permission}/edit',['as'=>'acl.permission.edit','uses'=>'App\Http\Controllers\AclController@editPermission']);
//Route::post('acl/permission/{permission}/',['as'=>'acl.permission.update','uses'=>'App\Http\Controllers\AclController@updatePermission']);
//});

// Route::post('profile/role/change/',[
//     'as'=>'profile.role.change','uses'=>  'ProfileController@changeRole'
// ]);

Route::get('/about-us', [App\Http\Controllers\HomeController::class, 'about'])->name('about');
Route::get('/employee-onboarding-software', [App\Http\Controllers\HomeController::class, 'onbording'])->name('onbording');
Route::get('/Employee-KYC-verification', [App\Http\Controllers\HomeController::class, 'KYCverification'])->name('KYC-verification');
Route::get('/resume-building-services', [App\Http\Controllers\HomeController::class, 'resumebuilder'])->name('resume-builder');
Route::get('/EMPILY-score', [App\Http\Controllers\HomeController::class, 'EMPILYscore'])->name('EMPILY-score');
Route::get('/blockchain-development', [App\Http\Controllers\HomeController::class, 'blockchaindevelopment'])->name('blockchain-development');




Route::get('/privacy-policy', [App\Http\Controllers\HomeController::class, 'privacyPolicy'])->name('privacyPolicy');
Route::get('/terms-conditions', [App\Http\Controllers\HomeController::class, 'termsConditions'])->name('termsConditions');
Route::get('/cookie-policy', [App\Http\Controllers\HomeController::class, 'cookiepolicy'])->name('cookiepolicy');
Route::get('/faq', [App\Http\Controllers\HomeController::class, 'faq'])->name('faq');
Route::get('/pricing', [App\Http\Controllers\HomeController::class, 'pricing'])->name('pricing');
Route::get('/contact', [App\Http\Controllers\HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [App\Http\Controllers\HomeController::class, 'contactUs'])->name('contactUs');
Route::get('/blog', [App\Http\Controllers\HomeController::class, 'blog'])->name('blog');
Route::get('/blog_details', [App\Http\Controllers\HomeController::class, 'blogDetails'])->name('blog_details');

Route::post('/support', [App\Http\Controllers\HomeController::class, 'support'])->name('support');

Route::resource('resume', '\App\Http\Controllers\ResumeController');

Route::get('/matrix-attributes', [App\Http\Controllers\EmpilyController::class, 'matrixAttribute'])->name('matrixAttribute');
Route::post('/matrix-attributes', [App\Http\Controllers\EmpilyController::class, 'matrixAttributeSave'])->name('matrixAttributeSave');
Route::get('/empily/{id}', [App\Http\Controllers\EmpilyController::class, 'empily'])->name('empily');

