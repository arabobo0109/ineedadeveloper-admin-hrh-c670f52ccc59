<?php

use Simcify\Router;
use Simcify\Exceptions\Handler;
use Simcify\Middleware\Authenticate;
use Simcify\Middleware\RedirectIfAuthenticated;
use Pecee\Http\Middleware\BaseCsrfVerifier;

/**
 * ,------,
 * | NOTE | CSRF Tokens are checked on all PUT, POST and GET requests. It
 * '------' should be passed in a hidden field named "csrf-token" or a header
 * Should be passed in a hidden field named "csrf-
 *          (in the case of AJAX without credentials) called "X-CSRF-TOKEN"
 *  */ 
Router::csrfVerifier(new BaseCsrfVerifier());

// Router::group(['prefix' => '/signer'], function() {

    Router::group(['exceptionHandler' => Handler::class], function() {

        Router::group(['middleware' => [Simcify\Middleware\Authenticate::class, \Simcify\Middleware\FilterRequestParameters::class]], function() {

            /**
             *  login Required pages
             **/
            Router::get('/', 'Dashboard@get');

            //db test
            Router::get('/db/get_balance', 'DbControl@GetBalance');
            Router::get('/db/add_order_and_balancehistory/{user_id}', 'DbControl@addOrderAndBalancehistory');
            Router::get('/sendDocsign', 'DbControl@sendDocsign');
            Router::get('/db/remove_user/{user_id}', 'DbControl@removeUser');
            Router::get('/db/removeNotArrivedUser', 'DbControl@removeNotArrivedUser');

            // Notifications
            Router::get('/notifications', 'Notification@get');
            Router::post('/notifications/read', 'Notification@read');
            Router::post('/notifications/count', 'Notification@count');
            Router::post('/notifications/delete', 'Notification@delete');

            // Documents
            Router::get('/documents', 'Document@get');
            Router::get('/document/{docId}/download', 'Document@download', ['as' => 'docId']);
            Router::get('/document/{document_key}', 'Document@open');
            Router::post('/documents/sign', 'Document@sign');
            Router::post('/documents/send', 'Document@send');
            Router::post('/documents/fetch', 'Document@fetch');
            Router::post('/documents/delete', 'Document@delete');
            Router::post('/documents/restore', 'Document@restore');

            Router::post('/documents/replace', 'Document@replace');
            Router::post('/documents/relocate', 'Document@relocate');
            Router::post('/documents/duplicate', 'Document@duplicate');
            Router::post('/documents/upload/file', 'Document@uploadfile');
            Router::post('/documents/update/file', 'Document@updatefile');
            Router::post('/documents/update/file/access', 'Document@updatefileaccess');
            Router::post('/documents/update/file/acess/view', 'Document@updatefileaccessview');
            Router::post('/documents/delete/file', 'Document@deletefile');
            Router::post('/documents/create/folder', 'Document@createfolder');
            Router::post('/documents/update/folder', 'Document@updatefolder');
            Router::post('/documents/update/folder/access', 'Document@updatefolderaccess');
            Router::post('/documents/update/folder/access/view', 'Document@updatefolderaccessview');
            Router::post('/documents/update/folder/protect', 'Document@updatefolderprotect');
            Router::post('/documents/update/folder/protect/view', 'Document@updatefolderprotectview');
            Router::post('/documents/delete/folder', 'Document@deletefolder');


            Router::post('/documents/update/permissions', 'Document@permissions');

            // Templates
            Router::get('/templates', 'Template@get');
            Router::post('/templates/fetch', 'Template@fetch');
            Router::post('/templates/create', 'Template@create');
            Router::post('/templates/upload/file', 'Template@uploadfile');

            // Chat
            Router::post('/chat/post', 'Chat@post');
            Router::post('/chat/fetch', 'Chat@fetch');

            // Fields
            Router::post('/field/save', 'Field@save');
            Router::post('/field/delete', 'Field@delete');

            // Requests
            Router::get('/requests', 'Request@get');
            Router::post('/requests/delete', 'Request@delete');
            Router::post('/requests/cancel', 'Request@cancel');
            Router::post('/requests/remind', 'Request@remind');
            Router::post('/requests/decline', 'Request@decline');

            // Chat
            Router::post('/signature/save', 'Signature@save');
            Router::post('/signature/save/upload', 'Signature@upload');
            Router::post('/signature/save/draw', 'Signature@draw');

            // customers
            Router::get('/students', 'Customer@get');
            Router::get('/student/{user_id}', 'Customer@profile');
            Router::post('/students/create', 'Customer@create');
            Router::post('/students/create_employer', 'Customer@create_employer');
            Router::post('/students/saveNote', 'Student@saveNote');
            Router::post('/students/check_names', 'Student@check_names');
            Router::post('/students/check_email', 'Student@check_email');

            Router::post('/students/update_specific_weekly_rate', 'Customer@updateSpecificWeeklyRate');
            Router::post('/students/update_lease_start', 'Customer@updateLeaseStartDate');
            Router::post('/students/add_amount_to_balance', 'Customer@addAmountToBalance');
            Router::post('/students/update_dashboard', 'Student@updateDashboardUser');
            Router::post('/students/room/update', 'Customer@updateRoom');
            Router::post('/students/room/view', 'Customer@assign_room');
            Router::post('/students/cancel_lease', 'Customer@cancel_lease');
            Router::post('/students/comeback', 'Customer@comeback');
            Router::post('/students/violation', 'Customer@violation');
            Router::post('/students/employer_paid', 'Customer@employer_paid');
            Router::post('/students/update_cancel_lease', 'Customer@updateCancelLease');

            Router::post('/students/add_fine', 'Customer@add_fine');

            Router::get('/students/checkout_history', 'Checkout@getCheckoutHistory');
            Router::post('/students/update_checkout', 'Checkout@finalCheckout');

            Router::post('/students/cancelFine', 'Fine@cancel');
            Router::post('/students/updateAddFine', 'Fine@updateAddFine');
            Router::post('/students/delete', 'Customer@delete');
            Router::post('/students/makeTester', 'Customer@makeTester');
            Router::post('/students/changeStatus', 'Customer@changeStatus');
            Router::post('/send_checkin', 'Customer@sendCheckin');
            Router::post('/set_arrived', 'Customer@set_arrived');
            Router::post('/resendDocusign', 'Customer@resendDocusign');
            Router::post('/sendEmail', 'EmailTemplate@sendEmail');
            Router::get('/students/remaining_balance/{user_id}', 'Checkout@remaining_balance');
            Router::post('/students/update_remaining_balance', 'Checkout@refundRemainingBalance');

//employer payment
            Router::post('/employer/employer_payment', 'Employer@employer_payment');
            Router::post('/employer/employer_payment_cash', 'Employer@employer_payment_cash');
            Router::post('/employer/employer_payment_credit_card', 'Employer@employer_payment_credit_card');

            Router::get('/profilePicture/{mode}', 'Student@profilePicture');
            Router::get('/viewRoommate/{user_id}', 'Student@viewRoommate');
            Router::post('/updateRoommate', 'Student@updateRoommate');
            Router::get('/take_picture/{user_id}', 'Student@takePicture');
            Router::get('/take_picture_id/{user_id}', 'Student@takePictureID');
            Router::get('/upload_lease/{user_id}', 'Customer@uploadLease');
            Router::post('/update_upload_lease', 'Customer@updateUploadLease');

            //Print
            Router::get('/student/id_print/{user_id}', 'PrintController@idPrint');
            Router::get('/proof_of_address/{user_id}', 'PrintController@proofOfAddress');
            Router::get('/invoice_print/{invoice_id}', 'PrintController@invoicePrint');

            //ZK Security
            Router::get('/zk/{user_id}', 'Zk@index');
            Router::post('/zk/AssignCard', 'Zk@AssignCard');

            // room management
            Router::get('/room/list', 'Room@getList');
            Router::get('/room/room_list', 'Room@getRoomList');
            Router::get('/room/review/{room_id}', 'Room@reviewRoom');
            Router::post('/room/delete', 'Room@delete');
            Router::post('/room/create', 'Room@create');
            Router::post('/room/bed/change_status', 'Room@ChangeBedStatus');
            Router::post('/room/bed/create', 'Room@createBed');
            Router::post('/room/bed/delete', 'Room@deleteBed');
            Router::post('/room/update_bed', 'Room@updateBed');
            Router::post('/room/update_view_bed', 'Room@updateViewBed');
            Router::get('/room/room_bed_status', 'Room@roomBedStatus');
            Router::get('/room/block-list', 'Room@getBlockList');

            // Report
            Router::get('/report', 'Report@get');
            Router::get('/report/create', 'Report@create');
            Router::post('/report/create_form', 'Report@createReport');
            Router::post('/report/report_filter', 'Report@reportFilter');
            Router::post('/report/report_total', 'Report@reportTotal');
            Router::post('/report/update', 'Report@update');
            Router::post('/report/update/view', 'Report@updateview');
            Router::post('/report/delete', 'Report@delete');
            Router::get('/report/browse/{rid}', 'Report@browse', ['as' => 'rid']);
            Router::post('/report/update_columns', 'Report@updateColumns');
            Router::post('/report/update_access', 'Report@updateAccess');
            Router::post('/report/export_excel', 'Report@exportExcel');

            // Fine
            Router::get('/fine', 'Fine@get');
            Router::post('/fine/update/fine', 'Fine@updateFine');
            Router::post('/fine/update/view', 'Fine@updateview');
            Router::post('/fine/delete', 'Fine@delete');
            Router::post('/fine/create', 'Fine@create');
            Router::post('/fine/createByAjax', 'Fine@createByAjax');

            // EmailTemplate
            Router::get('/email/template', 'EmailTemplate@get');
            Router::post('/email/template/update', 'EmailTemplate@update');
            Router::post('/email/template/update/view', 'EmailTemplate@updateview');
            Router::post('/email/template/delete', 'EmailTemplate@delete');
            Router::post('/email/template/create', 'EmailTemplate@create');
            
            // Drawer
			Router::get('/drawer', 'Drawer@get');
			Router::get('/drawer/{drawer_id}', 'Drawer@viewTransaction');
			Router::post('/drawer/create', 'Drawer@create');
			Router::post('/drawer/close', 'Drawer@close');
            Router::post('/drawer/close', 'Drawer@insertCloseAmount');
            Router::post('/drawer/close/view', 'Drawer@closeview');
            Router::post('/drawer/delete', 'Drawer@delete');

            //excel
            Router::post('/students/excel', 'Import@Excel');
            Router::get('/students_import', 'Import@get_errors');

            // Employer
            Router::get('/employers', 'Employer@get');
            Router::post('/employers/update', 'Employer@update');
            Router::post('/employers/update/view', 'Employer@updateview');
            Router::post('/employers/delete', 'Employer@delete');
            Router::post('/employers/create', 'Employer@create');

            // Maintenance
            Router::get('/maintenance', 'Maintenance@list');
            Router::get('/maintenance_tab/{user_id}', 'Maintenance@tab');
            Router::post('/maintenance/update', 'Maintenance@update');
            Router::post('/maintenance/update/view', 'Maintenance@updateview');
            Router::post('/maintenance/delete', 'Maintenance@delete');
            Router::post('/maintenance/create', 'Maintenance@create');

            // Team
            Router::get('/team', 'Team@get');
            Router::post('/team/create', 'Team@create');
            Router::post('/team/update', 'Team@update');
            Router::post('/team/update/view', 'Team@updateview');
            Router::post('/team/delete', 'Team@delete');
            Router::post('/team/changeStatus', 'Team@changeStatus');

            // Companies
            Router::get('/companies/go/{cid}', 'Company@go');
            Router::get('/companies', 'Company@get');
            Router::post('/companies/update', 'Company@update');
            Router::post('/companies/update/view', 'Company@updateview');
            Router::post('/companies/delete', 'Company@delete');
            Router::post('/companies/create', 'Company@create');

            // settings
            Router::get('/settings', 'Settings@get');

            Router::get('/special_fee', 'FeeManage@specialFee');
            Router::get('/action_log', 'Settings@actionLog');
            Router::post('/settings/create/specialFee', 'FeeManage@createSpecialFee');
            Router::post('/settings/delete/specialFee', 'FeeManage@deleteSpecialFee');
            Router::post('/settings/update/specialFee', 'FeeManage@updateSpecialFee');
            Router::post('/settings/updateView/specialFee', 'FeeManage@updateSpecialFeeView');
            Router::post('/settings/update/profile', 'Settings@updateprofile');
            Router::post('/settings/update/company', 'Settings@updatecompany');
            Router::post('/settings/update/reminders', 'Settings@updatereminders');
            Router::post('/settings/update/paymentreminders', 'Settings@updatepaymentreminders');
            Router::post('/settings/update/updateMondayEmails', 'Settings@updateMondayEmails');
            Router::post('/settings/update/password', 'Settings@updatepassword');
            Router::post('/settings/synchronizeTimezone', 'Settings@synchronizeTimezone');

            // Auth
            Router::get('/signout', 'Auth@signout');

            //payment

            Router::get('/payment', 'Payment@payment');
            Router::get('/payment/pay_at_checkin', 'Payment@pay_at_checkin');
            Router::get('/payment_result', 'PP@result');

            Router::get('/history', 'Payment@history');            
            Router::post('/payment/refund', 'CC@refundInvoice');

            Router::post('/select_payment', 'Payment@submitPaymentMode');
            Router::post('/submitCard', 'CC@submitCard');
            Router::post('/submitSubscribe', 'Payment@submitSubscribe');
            Router::post('/submitPaypal', 'PP@submitPaypal');

//admin payment
            Router::post('/take_payment', 'Payment@take_payment');

        });

        Router::group(['middleware' => [Simcify\Middleware\RedirectIfAuthenticated::class, \Simcify\Middleware\FilterRequestParameters::class]], function() {

            /**
             * No login Required pages
             **/
            Router::get('/signin', 'Auth@get');
//            Router::get('../admin', 'Auth@get');
            Router::post('/signin/validate', 'Auth@signin');
            Router::post('/forgot', 'Auth@forgot');
            Router::get('/reset/{token}', 'Auth@getreset', ['as' => 'token']);

        });

        Router::get('/404', function() {
            response()->httpCode(404);
            echo view();
        });
        
        Router::get('/mailopen', 'Guest@mailopen');
        Router::get('/view/{document_key}', 'Guest@open');
        Router::post('/guest/decline', 'Guest@decline');
        Router::post('/guest/sign', 'Guest@sign');

        Router::post('/reset_post', 'Auth@reset');

        Router::post('/room/getRooms', 'Room@getRooms');
        Router::post('/room/getBeds', 'Room@getBeds');
        Router::get('/checkin', 'Payment@checkin');
        Router::post('/student/uploadPicture', 'Student@uploadPicture');
    });
// });
