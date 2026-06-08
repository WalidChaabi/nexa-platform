<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guest\GuestController;
use Illuminate\Support\Facades\Broadcast;

//use Session;
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
/*NEW
*/




Broadcast::channel('user.{userId}', function ($user, $userId) {
    return $user->id === (int) $userId;
});

// Route::group(['domain' => 'www.sherin.ma','sherin.ma'], function () {
Route::group(['domain' => '127.0.0.1'], function () {
    /*Route::get('/', function () {
     return view('welcome');
    //return redirect()->route('index');
})->name('/');*/
    Route::get('/', [GuestController::class, 'index'])->name('/');
    Route::post('/send-email', [GuestController::class, 'store'])->name('home.send');
    Route::get('/mentions-légales', function () {
        return view('guest.mentions');
    })->name('mention');
    Route::post('/contact-us', 'HomeController@contactUs')->name('contactus');
});
Route::group(['domain' => 'www.sherin.ma',], function () {

    Route::post('/contact-us', 'HomeController@contactUs')->name('contactus');
    Route::get('/cgv', 'CgvController@index')->name('cgv');
    Route::get('/', [GuestController::class, 'index'])->name('/');
});
Route::group(['domain' => 'sherin.ma',], function () {

    Route::post('/contact-us', 'HomeController@contactUs')->name('contactus');
    Route::get('/cgv', 'CgvController@index')->name('cgv');
    Route::get('/', [GuestController::class, 'index'])->name('/');
});
//app.sherin.loc
//sherin-old.herokuapp.com
// Route::group(['domain' => 'app.sherin.ma'], function () {
Route::group(['domain' => '127.0.0.1'], function () {
    Auth::routes();
    Route::get('lang/{locale}', function ($lang) {
        \Session::put('locale', $lang);
        return redirect()->back();
    })->name('lang');
    Route::prefix('')->group(function () {
        Route::get('/', 'Auth\AuthController@getLogin')->name('authentication.login');
        Route::post('/login', 'Auth\AuthController@login')->middleware('throttle:5,1')->name('auth.login');
        Route::view('forget-password', 'authentication.forget-password')->name('forget-password');
        Route::view('reset-password', 'authentication.reset-password')->name('reset-password');
        Route::get('/checkout', 'Web\CheckoutController@index')->name('hold');
        Route::get('/payement', 'Web\PayementController@pay');
        Route::get('/payment/validate', 'Web\PayementController@checkout');
    });

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('dashboard/index', function () {
        return view('dashboard.index');
    })->middleware(['auth', 'admin', 'lang'])->name('index');
    Route::get('dashboard/owner', 'Web\OwnerController@getDashboard')->middleware(['auth', 'active', 'owner', 'lang'])->name('owner.dashboard');
    Route::get('dashboard/employe', 'Web\EmployerController@getDashboard')->middleware(['auth', 'active', 'employer', 'lang'])->name('employ.dashboard');

    Route::get('/hold-account-message', 'Web\SmsController@getData')->middleware('auth', 'lang')->name('holdAccount.message');

    Route::prefix('admin')->namespace('Web')->name('admin')->middleware('auth', 'lang', 'admin')->group(function (Illuminate\Routing\Router $router) {
        /**Admin Owner Routes */

        /**Admin Owner Routes */

        $router->get('campaign/birthday-count', 'CampaignController@getCampaignsByStore')->name('.campaigns.store.type');
        $router->get('campaign/countSms', 'CampaignController@getCountSms')->name('.campaigns.store.count');
        $router->post('campaign/reset', 'CampaignController@resetCampaigns')->name('.campaigns.reset');
        $router->put('campaign/activate', 'CampaignController@activateCampaigns')->name('.campaigns.activate');
        $router->put('campaign/Filter', 'CampaignController@activateFilters')->name('.campaigns.filter.activate');
        $router->post('campaign/credit', 'CampaignController@credit')->name('.campaigns.credit');
        $router->post('campaign/payAll', 'CampaignController@payAll')->name('.campaigns.payAll');
        $router->get('historique/transactions', 'HistoriquePaiementController@indexAdmin')->name('.historique-paiments');
        $router->get("/historique-paiements/{id}", 'HistoriquePaiementController@print')->name('.paiement-print');


        $router->get('this-month-customers-solde', 'CustomerController@getCustomersTarget');
        $router->get('owners', 'OwnerController@index')->name('.owners');
        $router->get('owners/{id}/edit', 'OwnerController@edit')->name('.edit.owner');
        $router->get('owners/{id}/delete', 'OwnerController@destroy')->name('.destroy.owners');
        $router->get('owners/{id}/active', 'OwnerController@active')->name('.active.owners');
        $router->post('/owners/store', 'OwnerController@store')->name('.store.owner');
        $router->post('/owner/{id}/update', 'OwnerController@update')->name('.update.owner');
        /**End of Admin Owner Routes */
        /** Admin Activity Routes */
        $router->get('/activities', 'ActivityController@index')->name('.activities');
        $router->post('/activities/store', 'ActivityController@store')->name('.store.activities');
        $router->get('/activities/{id}/edit', 'ActivityController@edit')->name('.edit.activities');
        $router->post('/activities/{id}/update', 'ActivityController@update')->name('.update.activities');
        /** End of Admin Activity Routes */

        /**Admin Stores Routes */
        $router->get('/stores', 'StoreController@index')->name('.stores');
        $router->Post('/stores/reste', 'StoreController@reste_Payer')->name('.stores.reste_Payer');
        $router->Post('/stores/rendez_vous', 'StoreController@rendez_vous_Payer')->name('.stores.rendez_vous_Payer');
        $router->Post('/stores/praticien/1', 'StoreController@praticien_1_Payer')->name('.stores.praticien_1_Payer');
        $router->Post('/stores/praticien/2', 'StoreController@praticien_2_Payer')->name('.stores.praticien_2_Payer');
        $router->Post('/stores/RA', 'StoreController@RA')->name('.stores.RA');
        $router->Post('/stores/Anniv', 'StoreController@Anniv')->name('.stores.Anniv');
        $router->Post('/stores/Table', 'StoreController@Table')->name('.stores.Table');
        $router->Post('/stores/QG', 'StoreController@QG')->name('.stores.QG');
        $router->Post('/stores/POS', 'StoreController@POS')->name('.stores.POS');
        $router->post('/stores/store', 'StoreController@store')->name('.stores.store');
        $router->get('/stores/{id}/edit', 'StoreController@edit')->name('.stores.edit');
        $router->post('/stores/{id}/update', 'StoreController@update')->name('.stores.update');
        $router->get('/stores/{id}/delete', 'StoreController@destroy')->name('.stores.delete');
        $router->get('/stores/{id}/active', 'StoreController@activate')->name('.stores.activate');
        $router->get('/stores/products', 'StoreController@getProductsStore')->name('.stores.products');
        /**End of Admin Stores Routes */

        /**Admin products Routes */
        $router->get('products', 'ProductController@index')->name('.products');
        $router->get('products/{id}/edit', 'ProductController@edit')->name('.products.edit');
        $router->post('products/{id}/update', 'ProductController@update')->name('.products.update');
        $router->post('products/store', 'ProductController@store')->name('.products.store');
        $router->post('/products/import', 'ProductController@import')->name('.import.products');

        $router->get('cross-products', 'CrossProductController@index')->name('.cross-products');
        $router->get('cross-products/{id}/delete', 'CrossProductController@destroy')->name('.cross-products.delete');
        $router->get('cross-products/{id}/edit', 'CrossProductController@edit')->name('.cross-products.edit');
        $router->post('cross-products/{id}/update', 'CrossProductController@update')->name('.cross-products.update');
        $router->post('cross-products/store', 'CrossProductController@store')->name('.cross-products.store');
        $router->get('cross-products/create', 'CrossProductController@create')->name('.cross-products.create');

        $router->get('categorie', 'CategorieController@index')->name('.menus');

        /**End Admin products Routes */

        /**Admin Employees Routes */
        $router->get('employees', 'EmployerController@index')->name('.employees');
        $router->get('employees/{id}/edit', 'EmployerController@edit')->name('.employees.edit');
        $router->post('employees/{id}/update', 'EmployerController@update')->name('.employees.update');
        $router->post('employees/store', 'EmployerController@store')->name('.employees.store');
        $router->get('employees/{id}/delete', 'EmployerController@destroy')->name('.employees.delete');
        $router->get('employees/{id}/active', 'EmployerController@active')->name('.employees.active');
        /**End of Admin Employees Routes */



        /**Admin Templates Routes */
        $router->get('templates', 'TemplateController@index')->name('.templates');
        $router->post('templates/store', 'TemplateController@store')->name('.templates.store');
        $router->get('templates/{id}/edit', 'TemplateController@edit')->name('.templates.edit');
        $router->post('templates/{id}/update', 'TemplateController@update')->name('.templates.update');


        /**End of Admin Templates Routes */

        /**Campaigns Routes*/
        $router->get('campaigns', 'CampaignController@index')->name('.campaigns');
        $router->post('campaigns/product', 'CampaignController@productStore')->name('.campaigns.product');
        $router->get('campaigns/create', 'CampaignController@create')->name('.campaigns.create');
        $router->get('campaigns/test', 'CampaignController@test')->name('.campaigns.test');
        $router->get('campaigns/details', 'CampaignController@details')->name('.campaigns.details');
        $router->get('sms-campaign', 'SmsController@index')->name('.sms.campaign');
        $router->view('sms-campaigns', 'admin.sms.campaigns')->name('.sms.campaigns');
        $router->view('sms-uniques', 'admin.sms.uniques')->name('.sms.uniques');
        $router->view('unique', 'admin.sms.unique')->name('.unique');
        $router->get('/campaign/message', 'CampaignController@getCampaignMessage')->name('.campaign.message');
        $router->get('/campaign/sms/stores', 'SmsController@getStoresByOwner')->name('.campaign.stores');
        $router->get('/campaign/sms/customers', 'SmsController@getCustomersByStore')->name('.campaign.customers');
        $router->get('/email-campaign', 'NewsLetterController@createCampaign')->name('.campaign.newsletter.create');
        $router->get('/email-campaigns', 'NewsLetterController@index')->name('.campaign.newsletter');
        $router->view('/newsLetter-1', 'newsletter.model-1')->name('.model-1');

        /**CampaignDetails Routes */

        $router->post('campaigns/store', 'CampaignDetailController@store')->name('.campaigns.store');
        $router->get('campaigns/edit/{id}', 'CampaignDetailController@edit')->name('.campaigns.edit');
        $router->get('campaigns/update/{id}', 'CampaignDetailController@update')->name('.campaigns.update');


        /**Routes of Admin Account */
        $router->view('access', 'admin.account.access')->name('.access');
        $router->view('personal', 'admin.account.personal')->name('.personal');
        $router->view('inbox', 'admin.inbox.list')->name('.inbox');

        /**Routes of SMS  */
        $router->post('/sendSms', 'SmsController@store')->name('.sendSms');
        /**Route of repports  */
        $router->view('campaign-report', 'admin.report.campaign')->name('.report.campaign');
        $router->view('sms-report', 'admin.report.sms')->name('.report.sms');
        $router->view('charge-report', 'admin.report.charge')->name('.report.charge');
        $router->post('single-campaign', 'CampaignController@singleCampaign')->name('.single-campaign');
    });

    Route::prefix('owner')->namespace('Web')->name('owner')->group(function (Illuminate\Routing\Router $router) {
        $router->get('/chart-spin', 'OwnerController@getChartSpin');
        $router->get('process-detail', 'CheckoutController@index')->name('.checkout-process-detail');
        $router->post('process', 'CheckoutController@process')->name('.checkout-process');
        $router->post('ok-fail', 'CheckoutController@fail')->name('.fail-checkout');
        $router->post('ok-success', 'CheckoutController@success')->name('.success-checkout');
        $router->post('callback', 'CheckoutController@callBack')->name('.callback-checkout');
        $router->post('/owner/{id}/update', 'OwnerController@update')->name('.update.owner')->middleware('auth');
        $router->view('access', 'admin.account.access')->name('.access')->middleware('auth');
        $router->view('personal', 'admin.account.personal')->name('.personal')->middleware('auth');
        // $router->get('personal', 'admin.account.personal')->name('.personal')->middleware('auth');
    });
    Route::prefix('owner')->namespace('Auth')->name('owner')->group(function (Illuminate\Routing\Router $router) {
        $router->post("/update/personalInfo", 'UserAuthController@update')->name('.personalInfo.update')->middleware('auth');
    });

    Route::prefix('owner')->namespace('Web')->name('owner')->middleware('auth', 'active', 'owner', 'lang')->group(function (Illuminate\Routing\Router $router) {
        $router->get('/calendar', 'CalendarController@indexOwner')->name('.calendar');
        $router->get('/events/{activityId}', 'CalendarController@getEvents')->name('.events');
        $router->post('/rdv/store', 'RdvController@create')->name('.rdv.store');
        $router->post('/rdv/delete', 'RdvController@delete')->name('.rdv.delete');
        $router->post('/rdv/update', 'RdvController@update')->name('.rdv.update');
        $router->get('/rdvs', 'RdvController@getAllByStoreId')->name('.rdvs');


        $router->post('/customer/create', 'CustomerController@storeOwnerCustomer')->name('.customers.store');
        $router->post('/epingles/create', 'EpinglesController@store')->name('.epingles.store');
        $router->delete('/epingles', 'EpinglesController@destroy')->name('.epingles.delete');
        $router->get('/epingles', 'EpinglesController@show')->name('.epingles.show');
        $router->put('/epingles', 'EpinglesController@update')->name('.epingles.update');


        $router->get("/historique-paiements", 'HistoriquePaiementController@index')->name('.historique-paiements');
        $router->get("/historique-paiements/{id}", 'HistoriquePaiementController@print')->name('.paiement-print');


        /**Owner Stores Routes */
        $router->get('/stores/', 'StoreController@index')->name('.stores');
        $router->get('/stores/{id}/edit', 'StoreController@edit')->name('.stores.edit');
        $router->post('/stores/{id}/update', 'StoreController@update')->name('.stores.update');
        $router->get('/stores/{id}/delete', 'StoreController@destroy')->name('.stores.delete');
        $router->get('/stores/{id}/active', 'StoreController@activate')->name('.stores.activate');
        /**End of Owner Stores Routes */

        /**Owner products Routes */
        $router->get('products', 'ProductController@index')->name('.products');
        $router->get('products/historique', 'ProductController@historiqueProduits')->name('.products.historique');
        $router->get('products/{id}/edit', 'ProductController@edit')->name('.products.edit');
        $router->post('products/{id}/update', 'ProductController@update')->name('.products.update');
        $router->post('products/store', 'ProductController@store')->name('.products.store');
        $router->post('/products/import', 'ProductController@import')->name('.import.products');
        $router->post('products/add', 'ProductController@addQuantite')->name('.products.add.quantite');
        $router->post('products/change', 'ProductController@changeQuantite')->name('.products.change.quantite');
        /**End Owner products Routes */

        $router->get('/invoices', 'InvoiceController@getOwnerInvoices')->name('.invoices');
        $router->get('/invoices/canceled', 'InvoiceController@getCanceledInvoices')->name('.invoices.canceled');
        /**Owner Employees Routes */
        $router->get('employees', 'EmployerController@index')->name('.employees');
        $router->post('employees/store', 'EmployerController@store')->name('.employees.store');
        $router->post('employees/{id}/update', 'EmployerController@update')->name('.employees.update');
        /**End of Owner Employees Routes */

        /**Route of employers objectives */
        $router->get('objectives', 'EmployerController@objectives')->name('.objectives');
        /**End of employers objectives */
        $router->view('access', 'admin.account.access')->name('.access');
        $router->view('personal', 'admin.account.personal')->name('.personal');
        $router->view('inbox', 'admin.inbox.list')->name('.inbox');
        $router->get('/solde', 'OwnerController@getSoldeDetail')->name('.solde');

        /**Routes of SMS  */

        /**Route of repports  */
        $router->view('campaign-report', 'admin.report.campaign')->name('.report.campaign');
        $router->view('sms-report', 'admin.report.sms')->name('.report.sms');
        $router->view('charge-report', 'admin.report.charge')->name('.report.charge');
        //evolution TurnOver by month &  Evolution Numbre of Clients Consommation Of BR par mois / magasin 	
        $router->get('/chart-data/{type}', 'OwnerController@getChartData')->name('.chart');
        $router->get('/repport', 'OwnerController@getRepportData')->name('.chart');
        $router->get('/repport/turnOver', 'OwnerController@getTurnOver')->name('.chart.turnOver');

        $router->get('caisse', 'CaisseController@index')->name('.caisse.index');
        $router->post('initial-fonds', 'InitialFondController@store')->name('.initial-fonds.store');
        $router->post('single-campaign', 'CampaignController@singleCampaign')->name('.singleCamp');
        $router->get('/repport/invoices', 'RepportController@getInvoices')->name('.invoices.repport');
        $router->get('/repport/invoices/check-store-property/{StoreId}', 'InvoiceController@checkStore')->name('.invoices.checkStore');
        $router->get('/repport/invoices/recherche', 'InvoiceController@recherche')->name('.invoices.recherche');
        $router->get('/repport/customers', 'RepportController@getCustomers')->name('.customers.repport');
        $router->get('/repport/products', 'RepportController@getProducts')->name('.products.repport');
        $router->get('/repport/products/table', 'RepportController@getProductsByTable')->name('.products.repport.table');
        $router->get('/repport/products/excel/{storeId}', 'RepportController@productExcel')->name('.products.repport.excel');
        $router->get('/repport/products/excel2/{storeId}', 'RepportController@productExcel2')->name('.products.repport.excel2');


        $router->get('/campaigns/rapport', 'RepportController@getCampaigns')->name('.campaigns.rapport');
        $router->get('/campaigns/list', 'RepportController@getCampaignsList')->name('.campaigns.list');
        $router->get('/campaigns/details/{type}/{id}', 'RepportController@getCampaignsDetails')->name('.campaigns.details');
        $router->get('/campaigns/find/{type}/{name}', 'RepportController@findCustomerByName')->name('.campaigns.find');
        $router->get('/returns', 'InvoiceController@getReturns')->name('.invoices.returns');
    });
    Route::prefix('employe')->namespace('Web')->name('employe')->middleware('auth', 'active', 'employer', 'lang')->group(function (Illuminate\Routing\Router $router) {


        $router->get('/calendar', 'CalendarController@index')->name('.calendar');
        $router->get('/anniversaire', 'AnniversaireController@index')->name('.anniversaire');
        $router->get('/events/{activityId}', 'CalendarController@getEvents')->name('.events');
        $router->post('/rdv/store', 'RdvController@create')->name('.rdv.store');
        $router->post('/rdv/delete', 'RdvController@delete')->name('.rdv.delete');
        $router->get('/rdv/delete-rdv/{id}', 'RdvController@deleteRdv')->name('.rdv.deleteRdv');
        $router->get('/rdv/detail', 'RdvController@detail')->name('.rdv.detail');
        $router->post('/rdv/update', 'RdvController@update')->name('.rdv.update');
        $router->get('/rdvs', 'RdvController@getAllByStoreId')->name('.rdvs');

        /**Employe products Routes */
        $router->get('products', 'ProductController@index')->name('.products');
        $router->put('products/update/{id}', 'ProductController@update')->name('.products.update');
        $router->get('products/{id}', 'ProductController@getProductByCatagorei')->name('.products.categorie');
        $router->post('products/store', 'ProductController@productInCategorie')->name('.products.categorie.store');
        $router->get('products/categorie/list', 'PosController@getProductByCategorieId')->name('.products.categorie.list');
        $router->post('products/add', 'ProductController@addQuantite')->name('.products.add.quantite');
        $router->post('products/change', 'ProductController@changeQuantite')->name('.products.change.quantite');

        /**End Employe products Routes */

        /**Employe categories Routes */
        $router->get('categorie', 'CategorieController@create')->name('.categorie.create');
        $router->post('categorie/store', 'CategorieController@store')->name('.categorie.store');
        $router->put('categorie/update/{id}', 'CategorieController@update')->name('.categorie.update');
        $router->delete('categorie/destroy/{id}', 'CategorieController@destroy')->name('.categorie.delete');
        /**End Employe categories Routes */
        
        /**Employe categories Routes */
        $router->get('table', 'TableController@index')->name('.table.index');
        $router->post('table/store', 'TableController@store')->name('.table.store');
        $router->post('table/tansferer', 'TableController@tansferer')->name('.table.tansferer');
        $router->get('table/getproduct', 'TableController@GetTableProducts')->name('.table.get.product');
        $router->get('table/update/status', 'TableController@updateTableStatus')->name('.table.update.status');
        /**End Employe categories Routes */

        /**Employe invoices Routes */
        $router->get('invoices', 'InvoiceController@index')->name('.invoices');
        $router->get('invoices/recherche', 'InvoiceController@recherche')->name('.invoices.recherche');
        $router->get('invoices/products/1', 'InvoiceController@renderProductRow1');
        $router->get('invoices/products/2', 'InvoiceController@renderProductRow2');
        $router->get('invoices/create', 'InvoiceController@create')->name('.invoices.create');

        $router->get('poss', 'PosController@index')->name('.poss');
        $router->get('pos/products', 'PosController@renderProductRow');
        $router->post('pos/check/password', 'PosController@checkPassword')->name('.pos.check.password');
        $router->get('pos/create/{id?}', 'PosController@create')->name('.pos.create');
        $router->post('pos/table/product', 'TableController@StoreTableProduct')->name('.pos.create.table.product');
        $router->post('pos/store', 'PosController@store')->name('.pos.store');

        $router->post('invoices/store', 'InvoiceController@store')->name('.invoices.store');
        $router->get('invoices/reste', 'InvoiceController@reste')->name('.invoices.reste');
        $router->post('invoices/restePayer', 'InvoiceController@restePayer')->name('.invoices.restePayer');
        $router->get('invoices/{id}/edit', 'InvoiceController@edit')->name('.invoices.edit');
        $router->get('invoices/{id}/show', 'InvoiceController@show')->name('.invoices.show');
        $router->post('invoices/{id}/update', 'InvoiceController@update')->name('.invoices.update');
        $router->get('invoices/{id}/print', 'InvoiceController@print')->name('.invoices.print');
        $router->get('invoices/{id}/print/invoice', 'InvoiceController@printInvoice')->name('.invoices.print.invoice');
        $router->get('invoices/{id}/cancel', 'InvoiceController@cancel')->name('.invoices.cancel');
        /**Employe Customers Routes */

        $router->get('customers', 'CustomerController@index')->name('.customers');
        $router->get('customers/create', 'CustomerController@create')->name('.customers.create');
        $router->post('customers/store', 'CustomerController@store')->name('.customers.store');
        $router->get('customers/{id}/edit', 'CustomerController@edit')->name('.customers.edit');
        $router->post('customers/{id}/update', 'CustomerController@update')->name('.customers.update');
        $router->get('customers/points', 'CustomerController@getCustomerPoints')->name('.customers.points');
        /** End of Employe Customers Routes */
        /** Employe Products Routes */
        $router->get('/product/price', 'ProductController@getPrice')->name('.productPrice');

        /**end of Employe Products Routes  */
        $router->get('/customer/code/red', 'CustomerController@getCustomerRed');

        $router->view('access', 'admin.account.access')->name('.access');
        $router->view('personal', 'admin.account.personal')->name('.personal');
        $router->view('inbox', 'admin.inbox.list')->name('.inbox');
        $router->get('caisse', 'CaisseController@getCaisseEmployee')->name('.caisse.index');
        $router->get('/initial-fonds', 'InitialFondController@index')->name('.initial-fonds.index');
        $router->post('/initial-fonds/store', 'InitialFondController@store')->name('.initial-fonds.store');
        $router->post('/invoice-return', 'InvoiceController@returnInvoice')->name('.invoices.return');
    });

    Route::prefix('auth')->namespace('Auth')->middleware('auth')->group(function (Illuminate\Routing\Router $router) {
        $router->post('/update-personal-info', 'UserAuthController@updateAuthInfo')->name('personal.info.update');
        $router->post('/update-access-info', 'UserAuthController@updateAuthAccess')->name('personal.access.update');
    });
    Route::view('/template-invoice', 'template');
});
