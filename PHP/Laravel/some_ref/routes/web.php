<?php

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


Route::get('/', function () {
    return view('welcome');
});


Route::get('wf', 'WorkflowController@step');
Route::get('wf/test', 'WorkflowController@test');


Route::get('sqs/createq/{qname}', 'SqsController@createq');
Route::get('sqs/send', 'SqsController@send');
Route::get('sqs/fetch', 'SqsController@fetch');
Route::get('sqs', 'SqsController@fetch');
Route::get('sqs/show', 'SqsController@show');

Route::get('rmq/fetch', 'RmqController@fetch');
Route::get('rmq', 'RmqController@fetch');
Route::get('batchnotify', 'BatchNotificationController@fetch');
Route::get('batchnotify/fetch', 'BatchNotificationController@fetch');

Route::get('workflow', 'ClinicalWorkflowController@index');
Route::get('workflow/fetch', 'ClinicalWorkflowController@fetch');

Route::get('resident/autocomplete', 'ResidentController@autocomplete');
Route::get('resident/search/{facilityId}', 'ResidentController@search');
Route::post('resident/browse', 'ResidentController@browse');
Route::get('resident/find/{name}', 'ResidentController@find');
Route::get('resident/findahead', 'ResidentController@findahead');
Route::get('resident/select/{residentId}', 'ResidentController@select');
Route::get('resident/select/{residentId}/{today}', 'ResidentController@select');
Route::get('resident/timeline/{residentId}/{today}/{days}', 'ResidentController@timeline');
Route::get('resident/edit/{residentId}', 'ResidentController@edit');
Route::post('resident/update', 'ResidentController@update');
Route::get('resident/movement/{residentId}', 'ResidentController@movement');
Route::get('resident/roomchange/{residentId}', 'ResidentController@roomchange');
Route::get('resident/locationchange/{residentId}', 'ResidentController@locationchange');
Route::get('resident/search1', 'ResidentController@search1');
Route::get('resident/bgl/{residentId}', 'ResidentController@bgl');

Route::get('inquiry/listing', 'InquiryController@listing');
Route::get('inquiry/add', 'InquiryController@add');
Route::post('inquiry/store', 'InquiryController@store');
Route::get('inquiry/view/{inquiryId}', 'InquiryController@view');
Route::post('inquiry/store_comment/{inquiryId}', 'InquiryController@store_comment');
Route::post('inquiry/archive', 'InquiryController@archive');

Route::get('admission/listing', 'AdmissionController@listing');
Route::get('admission/add', 'AdmissionController@add');
Route::post('admission/store', 'AdmissionController@store');
Route::get('admission/view/{admissionId}', 'AdmissionController@view');
Route::get('admission/archive/{admissionId}', 'AdmissionController@archive');
Route::get('admission/findahead', 'AdmissionController@findahead');

Route::get('mongo/nominate', 'MongoController@nominate');
Route::get('mongo/acfi12', 'MongoController@acfi12');
Route::get('mongo/setformcode', 'MongoController@set_form_code');
Route::get('mongo/listq', 'MongoController@listq');
Route::get('mongo/whoare/{roleName}', 'MongoController@whoare');


//Route::resource('sqs', 'SqsController');

Route::resource('/users', 'HubUserController');

Route::get('/info', function(){
    return phpinfo();
});

//Auth::routes();

//Route::get('/logout', function(){
//    Auth::logout();
//});

Route::get('/home', 'HomeController@index');
Route::get('/home/backdoor', 'HomeController@backdoor');

Route::get('/home/{locale}', 'HomeController@setlocale');


Route::get('test', 'TestController@index');
Route::get('test/cropper', 'TestController@cropper');
Route::post('test/cropper/cropper_upload', 'TestController@cropper_upload');
Route::get('test/clear', 'TestController@clear');
Route::get('test/pdf', 'TestController@pdf');
Route::get('test/photo/{sid}', 'TestController@photo');



Route::get('facility/select/{facilityId}', 'FacilityController@select');
Route::get('facility/show', 'FacilityController@show');
Route::get('facility/add', 'FacilityController@add');
Route::get('facility/edit/{facilityId}', 'FacilityController@edit');
Route::get('facility/facilityview/{facilityId}', 'FacilityController@facilityview');
Route::get('facility/editroom/{facilityId}', 'FacilityController@editroom');
Route::get('facility/editroom/{facilityId}/{roomId}', 'FacilityController@editroom');
Route::post('facility/saveroom/{facilityId}', 'FacilityController@saveroom');
Route::get('facility/editlocation/{facilityId}', 'FacilityController@editlocation');
Route::get('facility/editlocation/{facilityId}/{locationId}', 'FacilityController@editlocation');
Route::post('facility/savelocation/{facilityId}', 'FacilityController@savelocation');
Route::get('facility/add_area/{facilityId}', 'FacilityController@add_area');
Route::get('facility/search/{facilityId}', 'FacilityController@search');
Route::post('facility/updatefacility/{facilityId}', 'FacilityController@updatefacility');
Route::post('facility/addfacility', 'FacilityController@addfacility');
Route::get('facility/movement/{facilityId}', 'FacilityController@movement');


Route::get('progressnote/add/{residentId}', 'ProgressNoteController@add');
Route::post('progressnote/store', 'ProgressNoteController@store');
Route::get('progressnote/search/{residentId}/', 'ProgressNoteController@search');
Route::post('progressnote/search_result/{residentId}', 'ProgressNoteController@search_result');
Route::get('progressnote/view/{pnId}', 'ProgressNoteController@view');
Route::get('progressnote/edit/{pnId}', 'ProgressNoteController@edit');
Route::post('progressnote/update/{pnId}', 'ProgressNoteController@update');
Route::get('progressnote/archive/{pnId}', 'ProgressNoteController@archive');
//Route::get('progressnote/additional_note/{residentId}/{pnId}', 'ProgressNoteAdditionalController@add');
Route::post('progressnote/additional_note/{pnId}', 'ProgressNoteController@add_additional_note');

Route::get('assessment/add/{residentId}/{formId}', 'AssessmentController@add');
Route::get('assessment/add/{residentId}', 'AssessmentController@add');
Route::get('assessment/select/{residentId}/{formId}', 'AssessmentController@select');
Route::get('assessment/select/{residentId}/{formId}/{parentAssessmentId}', 'AssessmentController@select');
Route::post('assessment/store', 'AssessmentController@store');
Route::get('assessment/', 'AssessmentController@show');
Route::get('assessment/show', 'AssessmentController@show');
Route::get('assessment/edit/{formId}', 'AssessmentController@edit');
Route::post('assessment/validateTemplate', 'AssessmentController@validateTemplate');
Route::post('assessment/storeTemplate', 'AssessmentController@storeTemplate');
Route::get('assessment/view/{assessmentId}', 'AssessmentController@view');
Route::get('assessment/search/{residentId}', 'AssessmentController@search');
Route::get('assessment/selectparent/{residentId}/{parentForm}/{formId}', 'AssessmentController@selectparent');
Route::get('assessment/confirmparent/{residentId}/{parentAssessmentId}/{childFormId}', 'AssessmentController@confirmparent');
Route::get('assessment/findahead/{category}/{residentId}', 'AssessmentController@findahead');
Route::get('assessment/findaheadForm', 'AssessmentController@findaheadForm');

Route::get('form/listing/{showAll}', 'FormController@listing');
Route::get('form/listing', 'FormController@listing');
Route::get('form/search', 'FormController@listingSearch');
Route::get('form/add', 'FormController@add');
Route::get('form/edit/{formId}', 'FormController@edit');
Route::get('form/template/{formId}', 'FormController@template');
Route::post('form/store', 'FormController@store');
Route::post('form/validateTemplate', 'FormController@validateTemplate');
Route::get('form/preview/{formId}', 'FormController@preview');
Route::get('form/findbyname', 'FormController@findByName');
Route::get('form/autocomplete', 'FormController@autocomplete');

Route::get('careplan/view/{residentId}', 'CarePlanController@view');
Route::get('careplan/evaluate/{residentId}', 'CarePlanController@evaluate');
Route::get('careplan/print/{residentId}', 'CarePlanController@print_care_plan');
Route::get('careplan/search', 'CarePlanController@search');

Route::get('charting/add/{residentId}', 'ChartingController@add');
Route::get('charting/select/{residentId}/{formId}', 'ChartingController@select');
Route::post('charting/store', 'ChartingController@store');
Route::get('charting/view/{assessmentId}', 'ChartingController@view');
Route::get('charting/search/{residentId}', 'ChartingController@search');
Route::get('charting/findaheadForm', 'ChartingController@findaheadForm');

Route::get('user', 'UserController@dashboard');
Route::post('user/search', 'UserController@search');
Route::get('user/link_to_hub/{userNo}', 'UserController@link_to_hub');
Route::get('user/linkuser/{userNo}/{userId}', 'UserController@linkuser');
Route::get('user/create_role/{userNo}', 'UserController@create_role');
Route::get('user/assign_role/{userNo}', 'UserController@assign_role');
Route::get('user/assign_facility/{userNo}', 'UserController@assign_facility');

Route::get('task/action/{taskId}', 'TaskController@action');
Route::get('task/goback/{taskId}', 'TaskController@goback');
Route::get('task/search', 'TaskController@search');
Route::get('task/show', 'TaskController@show');
Route::get('task/add', 'TaskController@add');
Route::post('task/addtask', 'TaskController@addtask');

Route::get('ccs/listq/{formId}', 'ClassificationController@listq');
Route::post('ccs/store', 'ClassificationController@store');
Route::get('ccs/edit/{formId}/{code}', 'ClassificationController@edit');
Route::get('ccs/delete/{formId}/{code}', 'ClassificationController@delete');

Route::get('hubuser/profile', 'HubUserController@profile');

Route::get('dashboard', 'DashboardController@res_incident');
Route::get('dashboard/res_incident', 'DashboardController@res_incident');
Route::get('dashboard/med_incident', 'DashboardController@med_incident');
Route::get('dashboard/infection', 'DashboardController@infection');
Route::get('dashboard/bowel', 'DashboardController@bowel');
Route::get('dashboard/bgl', 'DashboardController@bgl');

Route::get('export', 'ExportController@index');
Route::get('export/forms', 'ExportController@forms');
Route::get('export/documents', 'ExportController@documents');
Route::get('export/edocs', 'ExportController@edocs');
Route::get('export/visa457', 'ExportController@visa457');
Route::get('export/visanot457', 'ExportController@visanot457');
Route::get('export/aussie', 'ExportController@aussie');

Route::get('permission/listing', 'PermissionController@listing');
Route::get('permission/edit/{roleId}', 'PermissionController@edit');
Route::post('permission/update', 'PermissionController@update');

Route::get('residentform/add/{residentId}', 'ResidentFormController@add');
Route::get('residentform/select/{residentId}/{formId}', 'ResidentFormController@select');
Route::post('residentform/store', 'ResidentFormController@store');
Route::get('residentform/view/{assessmentId}', 'ResidentFormController@view');
Route::get('residentform/search/{residentId}', 'ResidentFormController@search');

Route::get('/businessrules', 'BusinessRulesController@index');
Route::get('/businessrules/add', 'BusinessRulesController@add');
Route::get('/businessrules/edit/{ruleId}', 'BusinessRulesController@edit');
Route::post('/businessrules/store', 'BusinessRulesController@store');

Route::get('residentmovement/fetch', 'ResidentMovementController@fetch');
Route::get('residentmovement/listing', 'ResidentMovementController@listing');
Route::get('residentmovement/listing/{residentId}', 'ResidentMovementController@listing');
Route::get('residentmovement/findahead', 'ResidentMovementController@findahead');

Route::post('oauth/login', 'OAuthController@login');
Route::get('login', 'OAuthController@login')->name('login');
Route::post('login', 'OAuthController@login')->name('login');
Route::get('logout', 'OAuthController@logout')->name('logout');
Route::post('logout', 'OAuthController@logout')->name('logout');
Route::get('resetpassword', 'OAuthController@resetpassword')->name('password.request');
