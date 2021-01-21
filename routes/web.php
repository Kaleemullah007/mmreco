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
 /*---get project API---*/
 Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    // return what you want
});
 

 Route::group([ 'prefix' => 'api', 'middleware' => 'auth' ], function () {

    Route::group([ 'prefix' => 'users' ], function () 
    {       
        Route::post('getusersassignment', [ 'as' => 'getusersassignment','uses' => 'Assignment\AssignmentController@getusersAssignment' ]);
      
    });

});

/*---Users API---*/
Route::group([ 'prefix' => 'users' ], function () 
{
    Route::post('/', [ 'as' => 'api.users.store', 'uses' => 'User\UsersController@store' ]);
    Route::post('two_factor_reset', [ 'as' => 'api.users.two_factor_reset', 'uses' => 'User\UsersController@postTwoFactorReset' ]);
    Route::get('list/{status?}', [ 'as' => 'api.users.list', 'uses' => 'User\UsersController@getDatatable' ]);
    Route::get('{userId}/assets', [ 'as' => 'api.users.assetlist', 'uses' => 'User\UsersController@getAssetList' ]);
    Route::post('{userId}/upload', [ 'as' => 'upload/user', 'uses' => 'User\UsersController@postUpload' ]);
});


Route::group([ 'prefix' => 'admin','middleware' => ['web','auth']], function () {


# User Management
Route::group([ 'prefix' => 'users', 'middleware' => ['web','auth','authorize:users.view']], function () {
    Route::get('ldap', ['as' => 'ldap/user', 'uses' => 'User\UsersController@getLDAP', 'middleware' => ['authorize:users.edit'] ]);
    Route::post('ldap', 'User\UsersController@postLDAP');

    Route::get('create', [ 'as' => 'create/user', 'uses' => 'User\UsersController@getCreate', 'middleware' => ['authorize:users.edit']  ]);

    Route::get('skill', [ 'as' => 'skill/user', 'uses' => 'User\UsersController@getSkillList', 'middleware' => ['authorize:users.skill']  ]);

    Route::post('create', [ 'uses' => 'User\UsersController@postCreate', 'middleware' => ['authorize:users.edit']  ]);

    Route::get('import', [ 'as' => 'import/user', 'uses' => 'User\UsersController@getImport', 'middleware' => ['authorize:users.edit']  ]);

    Route::post('import', [ 'uses' => 'User\UsersController@postImport', 'middleware' => ['authorize:users.edit']  ]);

    Route::get('export', [ 'uses' => 'User\UsersController@getExportUserCsv', 'middleware' => ['authorize:users.view']  ]);

    Route::get('{userId}/edit', [ 'as' => 'update/user', 'uses' => 'User\UsersController@getEdit', 'middleware' => ['authorize:users.edit']  ]);

    Route::post('{userId}/edit', [ 'uses' => 'User\UsersController@postEdit', 'middleware' => ['authorize:users.edit']  ]);

    Route::get('{userId}/clone', [ 'as' => 'clone/user', 'uses' => 'User\UsersController@getClone', 'middleware' => ['authorize:users.edit']  ]);

    Route::post('{userId}/clone', [ 'uses' => 'User\UsersController@postCreate', 'middleware' => ['authorize:users.edit']  ]);

    Route::get('{userId}/delete', [ 'as' => 'delete/user', 'uses' => 'User\UsersController@getDelete', 'middleware' => ['authorize:users.delete']  ]);

    Route::get('{userId}/restore', [ 'as' => 'restore/user', 'uses' => 'User\UsersController@getRestore', 'middleware' => ['authorize:users.edit']  ]);

    Route::get('{userId}/view', [ 'as' => 'view/user', 'uses' => 'User\UsersController@getView' , 'middleware' => ['authorize:users.view'] ]);

    Route::get('{userId}/unsuspend', [ 'as' => 'unsuspend/user', 'uses' => 'User\UsersController@getUnsuspend', 'middleware' => ['authorize:users.edit'] ]);

    Route::get('{userId}/deletefile/{fileId}', [ 'as' => 'delete/userfile', 'uses' => 'User\UsersController@getDeleteFile' ]
    );
    Route::get('{userId}/showfile/{fileId}', [ 'as' => 'show/userfile', 'uses' => 'User\UsersController@displayFile' ]
    );

    Route::post('bulkedit',['as'   => 'users/bulkedit', 'uses' => 'User\UsersController@postBulkEdit', 'middleware' => ['authorize:users.edit'],
        ]
    );
    Route::post('bulksave',['as' => 'users/bulksave', 'uses' => 'User\UsersController@postBulkSave', 'middleware' => ['authorize:users.edit'],
        ]
    );

    Route::post('saveimgupload',['as' => 'users/saveimgupload', 'uses' => 'User\UsersController@saveImgUpload', 'middleware' => ['authorize:users.saveimgupload'],
        ]
    );

    Route::get('/', [ 'as' => 'users', 'uses' => 'User\UsersController@getIndex' ]);

    Route::get('docList', [ 'as' => 'users/docList', 'uses' => 'User\UsersController@getDocListDataTable' ]);

    Route::post('savedocfile',['as' => 'users/savedocfile', 'uses' => 'User\UsersController@saveDocFile', 'middleware' => ['authorize:users.savedocfile']]);

    Route::get('deleteDocFile/{fid}', [ 'as' => 'users/deleteDocFile', 'uses' => 'User\UsersController@deleteDocFile' ]);
});

});

Route::group([ 'prefix' => 'bankstatement', 'middleware' => ['web','auth']], function () {

    Route::get('import', [ 'as' => 'import', 'uses' => 'Bankstatement\BankstatementController@getImport' ]);
    Route::get('bankstatement', [ 'as' => 'bankstatement', 'uses' => 'Bankstatement\BankstatementController@getIndex' ]);

    Route::post('import', [ 'uses' => 'Bankstatement\BankstatementController@postImport' ]); 
    Route::post('setBankStatementExtraFlg', [ 'as' => 'setBankStatementExtraFlg', 'uses' => 'Bankstatement\BankstatementController@setBankStatementExtraFlg' ]);  
     
    Route::post('duplicationCheck', [ 'as' => 'duplicationCheck', 'uses' => 'Bankstatement\BankstatementController@duplicationCheck' ]);   
    Route::post('submitbstdata', [ 'as' => 'submitbstdata', 'uses' => 'Bankstatement\BankstatementController@submitbstdata' ]);

    Route::post('setbstflag', [ 'as' => 'bankstatement/setbstflag' , 'uses' => 'Bankstatement\BankstatementController@setbstflag' ]);

});
/*---Bank Statement API---*/
Route::group([ 'prefix' => 'bankstatement' ], function () 
{
    Route::get('list', [ 'as' => 'api.bankstatement.list', 'uses' => 'Bankstatement\BankstatementController@getDatatable' ]);
});




Route::group([ 'prefix' => 'bankbalance', 'middleware' => ['web','auth']], function () {

    Route::get('import', [ 'as' => 'import', 'uses' => 'Bankbalance\BankbalanceController@getImport' ]);

    Route::post('import', [ 'uses' => 'Bankbalance\BankbalanceController@postImport' ]);

    Route::get('bankbalance', [ 'as' => 'bankbalance', 'uses' => 'Bankbalance\BankbalanceController@getIndex' ]);
    Route::post('bankbalance', [ 'as' => 'bankbalance', 'uses' => 'Bankbalance\BankbalanceController@getIndex' ]);

	Route::get('bankbalanceList', [ 'as' =>'bankbalance/bankbalanceList' , 'uses' => 'Bankbalance\BankbalanceController@postBankBalance' ]);    
    Route::post('cardlist', [ 'uses' => 'Bankbalance\BankbalanceController@getBankBalanceCard' ]);

    Route::post('changeflag', [ 'uses' => 'Bankbalance\BankbalanceController@changeFileFlag' ]);

});

Route::group([ 'prefix' => 'bankbalance', 'middleware' => ['web']], function () {
    Route::get('importbalance', [ 'uses' => 'Bankbalance\BankbalanceController@importBalanceFile' ]);
});


Route::group([ 'prefix' => 'bankbalance' ], function () 
{
    Route::get('list', [ 'as' => 'api.bankbalance.list', 'uses' => 'Bankbalance\BankbalanceController@postBankBalanceDataTable' ]);
});

Route::group([ 'prefix' => 'banktransactions', 'middleware' => ['web','auth']], function () {

 Route::get('import', [ 'as' => 'import', 'uses' => 'Banktransactions\BanktransactionsController@getImport' ]);

Route::post('import', [ 'uses' => 'Banktransactions\BanktransactionsController@postImport' ]);

Route::post('changeflag', [ 'uses' => 'Banktransactions\BanktransactionsController@changeFileFlag' ]);

Route::get('comparetxn', [ 'uses' => 'Banktransactions\BanktransactionsController@comparetxn' ]);

});

Route::group([ 'prefix' => 'manualtransaction', 'middleware' => ['web','auth']], function () {

Route::get('comparetxn', [ 'uses' => 'Manualtransaction\ManualtransactionController@comparetxn' ]);
Route::get('getBstDatatable', [ 'as' => 'manualtransaction/getBstDatatable','uses' => 'Manualtransaction\ManualtransactionController@getBstDatatable']);
Route::post('bstRadioSelect', [ 'uses' => 'Manualtransaction\ManualtransactionController@bstRadioSelect' ]);
Route::post('txnSelect', [ 'uses' => 'Manualtransaction\ManualtransactionController@txnSelect' ]);
Route::post('bstRadioDelete', [ 'uses' => 'Manualtransaction\ManualtransactionController@bstRadioDelete' ]);
Route::post('transactionDelete', [ 'uses' => 'Manualtransaction\ManualtransactionController@transactionDelete' ]);
Route::post('filterTransaction', [ 'uses' => 'Manualtransaction\ManualtransactionController@filterTransaction' ]);
Route::get('getAbdDatatable', [ 'as' => 'manualtransaction/getAbdDatatable','uses' => 'Manualtransaction\ManualtransactionController@getAbdDatatable']);
Route::get('getAbDeclinedDatatable', [ 'as' => 'manualtransaction/getAbDeclinedDatatable','uses' => 'Manualtransaction\ManualtransactionController@getAbDeclinedDatatable']);
Route::get('getFpoutDatatable', [ 'as' => 'manualtransaction/getFpoutDatatable','uses' => 'Manualtransaction\ManualtransactionController@getFpoutDatatable']);
Route::post('submitMatches', [ 'uses' => 'Manualtransaction\ManualtransactionController@submitMatches']);
Route::get('getBalanceAdjDatatable', [ 'as' => 'manualtransaction/getBalanceAdjDatatable','uses' => 'Manualtransaction\ManualtransactionController@getBalanceAdjDatatable']);

Route::get('manualComparedTransaction', [ 'as' => 'manualComparedTransaction' , 'uses' => 'Manualtransaction\ManualtransactionController@manualComparedTransaction' ]);
Route::post('manualComparedTransaction', [ 'uses' => 'Manualtransaction\ManualtransactionController@manualComparedTransaction' ]);
Route::get('manualComparedTransactionTable', [ 'as' => 'manualComparedTransactionTable' , 'uses' => 'Manualtransaction\ManualtransactionController@manualComparedTransactionTable' ]);
Route::post('fetchRelatedData', [ 'uses' => 'Manualtransaction\ManualtransactionController@fetchRelatedData' ]);

 Route::get('unMatchTransaction', [ 'as' => 'manualtransaction/unMatchTransaction' , 'uses' => 'Manualtransaction\ManualtransactionController@unMatchTransaction' ]);
 
 Route::get('resetTxn', [ 'as' => 'manualtransaction/resetTxn' , 'uses' => 'Manualtransaction\ManualtransactionController@resetTxn' ]);

});

Route::group([ 'prefix' => 'banktransactions', 'middleware' => ['web']], function () {
    Route::get('importtransaction', [ 'uses' => 'Banktransactions\BanktransactionsController@importTransactionFile' ]);
});


Route::group([ 'prefix' => 'advice', 'middleware' => ['web', 'auth']], function () {    
    Route::get('/', [  'uses' => 'Advice\AdviceController@getIndex' ]);        
    Route::post('/', [  'uses' => 'Advice\AdviceController@getIndex' ]);
});
/*---Card API---*/
Route::group([ 'prefix' => 'advice' ], function () 
{

    Route::get('advice/list', [ 'as' => 'api.advice.list', 'uses' => 'Advice\AdviceController@getDatatable' ]);
});


Route::group([ 'prefix' => 'advice', 'middleware' => ['web']], function () {
    Route::get('importadvice', [ 'uses' => 'Advice\AdviceController@importAdviceFile' ]);
    Route::get('linkadvice', [ 'uses' => 'Advice\AdviceController@generateAdviceLink' ]);
});

Route::group([ 'prefix' => 'fpout', 'middleware' => ['web','auth']], function () {
    Route::get('/', [ 'as' => 'fpout', 'uses' => 'Fpout\FpoutController@getIndex' ]);
});

Route::group([ 'prefix' => 'fpout', 'middleware' => ['web']], function () {
    Route::get('importfpout', [ 'uses' => 'Fpout\FpoutController@importFpoutFile' ]);
    Route::get('linkfpout', [ 'uses' => 'Fpout\FpoutController@generateFpoutAbLink' ]);
});


/*---FP Out API---*/
Route::group([ 'prefix' => 'fpout' ], function () 
{
    Route::get('list', [ 'as' => 'api.fpout.list', 'uses' => 'Fpout\FpoutController@getDatatable' ]);
});

/*---Directdebit CRUD---*/
Route::group([ 'prefix' => 'directdebits', 'middleware' => ['web','auth']], function () {    
    
    Route::get('/', [ 'as' => 'directdebits', 'uses' => 'Directdebits\DirectdebitsController@getIndex' ]);
    Route::get('create', [ 'as' => 'create/directdebit', 'uses' => 'Directdebits\DirectdebitsController@getCreate' ]);
    Route::get('{ddId}/delete', [ 'as' => 'delete/directdebit', 'uses' => 'Directdebits\DirectdebitsController@getDelete']);
    Route::get('import', [ 'as' => 'import', 'uses' => 'Directdebits\DirectdebitsController@getImport' ]);
    Route::post('import', [ 'uses' => 'Directdebits\DirectdebitsController@postImport' ]);
    
});
/*---Directdebits API---*/
Route::group([ 'prefix' => 'directdebits' ], function () 
{
    Route::get('list', [ 'as' => 'api.directdebits.list', 'uses' => 'Directdebits\DirectdebitsController@getDatatable' ]);
});

/*--- Rejected bacs CRUD---*/
Route::group([ 'prefix' => 'rejectedbacs', 'middleware' => ['web','auth']], function () {    
    
    Route::get('/', [ 'as' => 'rejectedbacs', 'uses' => 'Rejectedbacs\RejectedbacsController@getIndex' ]);
    Route::get('create', [ 'as' => 'create/rejectedbacs', 'uses' => 'Rejectedbacs\RejectedbacsController@getCreate' ]);
    Route::post('create', [ 'uses' => 'Rejectedbacs\RejectedbacsController@postCreate']);
    Route::get('{Id}/edit', [ 'as' => 'update/rejectedbacs', 'uses' => 'Rejectedbacs\RejectedbacsController@getEdit']);
    Route::post('{Id}/edit', [ 'uses' => 'Rejectedbacs\RejectedbacsController@postEdit']);
    Route::get('{Id}/delete', [ 'as' => 'delete/rejectedbacs', 'uses' => 'Rejectedbacs\RejectedbacsController@getDelete']);   

    Route::get('import', [ 'as' => 'import', 'uses' => 'Rejectedbacs\RejectedbacsController@getImport' ]);
    Route::post('import', [ 'uses' => 'Rejectedbacs\RejectedbacsController@postImport' ]);
     
    
});
/*---Rejected bacs API---*/
Route::group([ 'prefix' => 'rejectedbacs' ], function () 
{
    Route::get('list', [ 'as' => 'api.rejectedbacs.list', 'uses' => 'Rejectedbacs\RejectedbacsController@getDatatable' ]);
});

Route::group([ 'prefix' => 'account', 'middleware' => ['web', 'auth']], function () {
    # Profile
    Route::get('profile', [ 'as' => 'profile', 'uses' => 'User\ProfileController@getIndex' ]);
    Route::post('profile', 'User\ProfileController@postIndex');

    Route::get('changepassword', [ 'as' => 'changepassword', 'uses' => 'User\ProfileController@getChangepassword' ]);
    Route::post('changepassword', 'User\ProfileController@postChangepassword');  
    
});

Route::group([ 'prefix' => 'reports', 'middleware' => ['web', 'auth']], function () {
    # Profile
    Route::get('settelementsummary', [ 'as' => 'settelementsummary', 'uses' => 'Settelementsummary\SettelementsummaryController@generateSettelementSummary' ]); 

    Route::get('dailybalanceshift', [ 'as' => 'dailybalanceshift', 'uses' => 'Dailybalanceshift\DailybalanceshiftController@generateDailyBalanceShift' ]); 
    
    Route::get('monthlybalanceshift', [ 'as' => 'monthlybalanceshift', 'uses' => 'Monthlybalanceshift\MonthlybalanceshiftController@generateMonthlyBalanceShift' ]);  
});

Route::group([ 'prefix' => 'settelementsummary', 'middleware' => ['web', 'auth']], function () {
    # Profile
    Route::get('recalculate', [ 'as' => 'recalculate', 'uses' => 'Settelementsummary\SettelementsummaryController@getRegenerateSettelemaneSummary' ]);  
    Route::post('recalculate', 'Settelementsummary\SettelementsummaryController@postRegenerateSettelemaneSummary');
    Route::get('/', [ 'as' => 'settelementsummary', 'uses' => 'Settelementsummary\SettelementsummaryController@getIndex' ]);    
});
/*---Settelement Summary API---*/
Route::group([ 'prefix' => 'settelementsummary' ], function () 
{
    Route::get('settelementsummary/list', [ 'as' => 'api.settelementsummary.list', 'uses' => 'Settelementsummary\SettelementsummaryController@getDatatable' ]);    
});

Route::group([ 'prefix' => 'dailybalanceshift', 'middleware' => ['web', 'auth']], function () {
    Route::get('/', [ 'as' => 'dailybalanceshift', 'uses' => 'Dailybalanceshift\DailybalanceshiftController@getIndex' ]);
    Route::post('list', [ 'uses' => 'Dailybalanceshift\DailybalanceshiftController@getDatatable' ]);

    Route::get('recalculate', [ 'as' => 'recalculate', 'uses' => 'Dailybalanceshift\DailybalanceshiftController@getRegenerateDailyBalanceShift' ]);  
    Route::post('recalculate', 'Dailybalanceshift\DailybalanceshiftController@postRegenerateDailyBalanceShift');

    Route::post('filterTransaction', [ 'uses' => 'Dailybalanceshift\DailybalanceshiftController@filterTransaction' ]);
    Route::post('linkTxnData', [ 'uses' => 'Dailybalanceshift\DailybalanceshiftController@linkTxnData' ]);
    Route::post('removelinktxnData', [ 'uses' => 'Dailybalanceshift\DailybalanceshiftController@removelinktxnData' ]);
    
    Route::get('getCardfeeDatatable', [ 'as' => 'dailybalanceshift/getCardfeeDatatable','uses' => 'Dailybalanceshift\DailybalanceshiftController@getCardfeeDatatable']);
    
    Route::get('getCardfinancialDatatable', [ 'as' => 'dailybalanceshift/getCardfinancialDatatable','uses' => 'Dailybalanceshift\DailybalanceshiftController@getCardfinancialDatatable']);
});
/*---Agency Banking API---*/
Route::group([ 'prefix' => 'dailybalanceshift' ], function () 
{
    Route::get('dailybalanceshift/list', [ 'as' => 'api.dailybalanceshift.list', 'uses' => 'Dailybalanceshift\DailybalanceshiftController@getDatatable' ]);    
});

Route::group([ 'prefix' => 'monthlybalanceshift', 'middleware' => ['web', 'auth']], function () {
    # Profile    
    Route::get('/', [ 'as' => 'monthlybalanceshift', 'uses' => 'Monthlybalanceshift\MonthlybalanceshiftController@getIndex' ]);
    Route::post('list', [ 'uses' => 'Monthlybalanceshift\MonthlybalanceshiftController@getDatatable' ]);

    Route::get('recalculate', [ 'as' => 'recalculate', 'uses' => 'Monthlybalanceshift\MonthlybalanceshiftController@getRegenerateMonthlyBalanceShift' ]);  
    Route::post('recalculate', 'Monthlybalanceshift\MonthlybalanceshiftController@postRegenerateMonthlyBalanceShift');
       
});

Route::group([ 'prefix' => 'monthlybalanceshift' ], function () 
{
    Route::get('monthlybalanceshift/list', [ 'as' => 'api.monthlybalanceshift.list', 'uses' => 'Monthlybalanceshift\MonthlybalanceshiftController@getDatatable' ]);    
});

Route::group([ 'prefix' => 'agencybanking', 'middleware' => ['web', 'auth']], function () {    
    Route::get('approved', [ 'as' => 'approved', 'uses' => 'Agencybanking\AgencybankingController@getIndex' ]);    
    Route::get('declined', [ 'as' => 'declined', 'uses' => 'Agencybanking\AgencybankingController@getDeclinedIndex' ]);    
    Route::get('fee', [ 'as' => 'agencybanking/fee', 'uses' => 'Agencybanking\AgencybankingController@getFeeIndex' ]);    
});

/*---Agency Banking API---*/
Route::group([ 'prefix' => 'agencybanking' ], function () 
{
    Route::get('approved/list', [ 'as' => 'api.agencybanking.approved.list', 'uses' => 'Agencybanking\AgencybankingController@getDatatable' ]);
    Route::get('declined/list', [ 'as' => 'api.agencybanking.declined.list', 'uses' => 'Agencybanking\AgencybankingController@getDeclinedDatatable' ]);
    Route::get('fee/list', [ 'as' => 'api.agencybanking.fee.list', 'uses' => 'Agencybanking\AgencybankingController@getFeeDatatable' ]);
});

Route::group([ 'prefix' => 'card', 'middleware' => ['web', 'auth']], function () {    
    Route::get('authorisation', [ 'as' => 'authorisation', 'uses' => 'Card\CardController@getAuthorisationIndex' ]);        
    Route::get('baladjust', [ 'as' => 'baladjust', 'uses' => 'Card\CardController@getBalAdjustIndex' ]);
    Route::get('fee', [ 'as' => 'fee', 'uses' => 'Card\CardController@getFeeIndex' ]);        
    Route::get('financial', [ 'as' => 'financial', 'uses' => 'Card\CardController@getFinancialIndex' ]);        
    Route::get('loadunload', [ 'as' => 'loadunload', 'uses' => 'Card\CardController@getLoadunloadIndex' ]);        
    Route::get('chrgbackrepres', [ 'as' => 'chrgbackrepres', 'uses' => 'Card\CardController@getChrgbackrepresIndex' ]);
    Route::get('event', [ 'as' => 'event', 'uses' => 'Card\CardController@getEventIndex' ]);
    Route::post('setExtraFlg', [ 'as' => 'setExtraFlg', 'uses' => 'Card\CardController@setExtraFlg' ]);
    Route::post('setExtraFlgDrCr', [ 'as' => 'setExtraFlgDrCr', 'uses' => 'Card\CardController@setExtraFlgDrCr' ]);

    Route::post('setmultiadjflag', [ 'as' => 'card/setmultiadjflag' , 'uses' => 'Card\CardController@setmultiadjflag' ]);
});
/*---Card API---*/
Route::group([ 'prefix' => 'card' ], function () 
{
    Route::get('authorisation/list', [ 'as' => 'api.card.authorisation.list', 'uses' => 'Card\CardController@getAuthorisationDatatable' ]);
    Route::get('baladjust/list', [ 'as' => 'api.card.baladjust.list', 'uses' => 'Card\CardController@getBalAdjustDatatable' ]);
    Route::get('fee/list', [ 'as' => 'api.card.fee.list', 'uses' => 'Card\CardController@getFeeDatatable' ]);
    Route::get('financial/list', [ 'as' => 'api.card.financial.list', 'uses' => 'Card\CardController@getFinancialDatatable' ]);
    Route::get('loadunload/list', [ 'as' => 'api.card.loadunload.list', 'uses' => 'Card\CardController@getLoadunloadDatatable' ]);
    Route::get('chrgbackrepres/list', [ 'as' => 'api.card.chrgbackrepres.list', 'uses' => 'Card\CardController@getChrgbackrepresDatatable' ]);
    Route::get('event/list', [ 'as' => 'api.card.event.list', 'uses' => 'Card\CardController@getEventDatatable' ]);
});


Route::group([ 'prefix' => 'autocomparetxn', 'middleware' => ['web']], function () {
    Route::get('generateautocmp', [ 'uses' => 'Autocomparetxn\AutocomparetxnController@generateAutoMapTxnData' ]);
});


Route::group([ 'prefix' => 'autocomparetxn', 'middleware' => ['web','auth']], function () {
    Route::get('recompare', [ 'uses' => 'Autocomparetxn\AutocomparetxnController@getReAutoCompareTxn' ]);
    Route::post('recompare', [ 'uses' => 'Autocomparetxn\AutocomparetxnController@postReAutoCompareTxn' ]);

    Route::get('autoComparedTransaction', [ 'as' => 'autoComparedTransaction' , 'uses' => 'Autocomparetxn\AutocomparetxnController@autoComparedTransaction' ]);
    Route::post('autoComparedTransaction', [ 'uses' => 'Autocomparetxn\AutocomparetxnController@autoComparedTransaction' ]);
    
    Route::get('autoComparedTransactionTable', [ 'as' => 'autoComparedTransactionTable' , 'uses' => 'Autocomparetxn\AutocomparetxnController@autoComparedTransactionTable' ]);
    Route::post('fetchRelatedData', [ 'uses' => 'Autocomparetxn\AutocomparetxnController@fetchRelatedData' ]);

    Route::get('unMatchTransaction', [ 'as' => 'autocomparetxn/unMatchTransaction' , 'uses' => 'Autocomparetxn\AutocomparetxnController@unMatchTransaction' ]);

    Route::post('deletebulktxn', [ 'as' => 'autocomparetxn/deletebulktxn' , 'uses' => 'Autocomparetxn\AutocomparetxnController@deletebulktxn' ]);
    
    Route::post('exportall', [ 'as' => 'autocomparetxn/exportall' , 'uses' => 'Autocomparetxn\AutocomparetxnController@exportall' ]);

});


Route::group([ 'prefix' => 'mainrecodaily', 'middleware' => ['web']], function () {
    Route::get('generatemainreco', [ 'uses' => 'Mainrecoreportdaily\MainrecoreportdailyController@generateMainRecoDaily' ]);
});


Route::group([ 'prefix' => 'mainrecodaily', 'middleware' => ['web','auth']], function () {

    Route::get('/', [ 'as' => 'mainrecodaily', 'uses' => 'Mainrecoreportdaily\MainrecoreportdailyController@getIndex' ]);
    Route::post('/', [ 'as' => 'mainrecodaily', 'uses' => 'Mainrecoreportdaily\MainrecoreportdailyController@getIndex' ]);
    Route::post('list', [ 'uses' => 'Mainrecoreportdaily\MainrecoreportdailyController@getDatatable' ]);

    Route::get('recalculate', [ 'uses' => 'Mainrecoreportdaily\MainrecoreportdailyController@getRegenerateMainRecoDaily' ]);
    Route::post('recalculate', [ 'uses' => 'Mainrecoreportdaily\MainrecoreportdailyController@postRegenerateMainRecoDaily' ]);
});

Route::get('regeneratereport', [ 'as' => 'regeneratereport', 'middleware' => ['web','auth'], 'uses' => 'DashboardController@getGenReportInRange' ]);
 Route::post('regeneratereport', [ 'as' => 'regeneratereport','middleware' => ['web','auth'], 'uses' => 'DashboardController@postGenReportInRange' ]);


Route::group([ 'prefix' => 'mainrecodaily' ], function () 
{
    Route::get('mainrecodaily/list', [ 'as' => 'api.mainrecodaily.list', 'uses' => 'Mainrecoreportdaily\MainrecoreportdailyController@getDatatable' ]);    
});

Route::get(
    'auth/signin',
    ['uses' => 'Auth\LoginController@legacyAuthRedirect' ]
);

Route::get(
    '/',
    [
    'as' => 'home',
    'middleware' => ['auth'],
    'uses' => 'DashboardController@getIndex' ]
);


Route::group([ 'prefix' => 'notifications', 'middleware' => ['web','auth']], function () {

    Route::get('import', [ 'as' => 'import', 'uses' => 'FszNotificationsImport\FszNotificationsImportController@getImport' ]);

    Route::get('ImportData', [ 'as' => 'ImportData', 'uses' => 'FszNotificationsImport\FszNotificationsImportController@ImportData' ]);

    Route::post('import', [ 'as' => 'import', 'uses' => 'FszNotificationsImport\FszNotificationsImportController@postImport' ]);

    // Route::post('import', [ 'uses' => 'Bankbalance\BankbalanceController@postImport' ]);
    Route::get('showentries', [ 'as' => 'view', 'uses' => 'FszNotificationsImport\FszNotificationsImportController@showEntries']);
    Route::get('showentries', [ 'as' => 'showentries','uses'=>'FszNotificationsImport\FszNotificationsImportController@showEntries']);

    // Route::get('bankbalance', [ 'as' => 'bankbalance', 'uses' => 'Bankbalance\BankbalanceController@getIndex' ]);
    // Route::post('bankbalance', [ 'as' => 'bankbalance', 'uses' => 'Bankbalance\BankbalanceController@getIndex' ]);

    // Route::get('bankbalanceList', [ 'as' =>'bankbalance/bankbalanceList' , 'uses' => 'Bankbalance\BankbalanceController@postBankBalance' ]);    
    // Route::post('cardlist', [ 'uses' => 'Bankbalance\BankbalanceController@getBankBalanceCard' ]);

    // Route::post('changeflag', [ 'uses' => 'Bankbalance\BankbalanceController@changeFileFlag' ]);

});

Route::get(
    'home',
    [
    'as' => 'home',
    'middleware' => ['auth'],
    'uses' => 'DashboardController@getIndex' ]
);

Route::group(['middleware' => 'web'], function () {
    //Route::auth();
    Route::get(
        'login',
        [
            'as' => 'login',
            'middleware' => ['web'],
            'uses' => 'Auth\LoginController@showLoginForm' ]
    );

    Route::post(
        'login',
        [
            'as' => 'login',
            'middleware' => ['web'],
            'uses' => 'Auth\LoginController@login' ]
    );

    Route::get(
        'logout',
        [
            'as' => 'logout',
            'uses' => 'Auth\LoginController@logout' ]
    );

});

Auth::routes();
