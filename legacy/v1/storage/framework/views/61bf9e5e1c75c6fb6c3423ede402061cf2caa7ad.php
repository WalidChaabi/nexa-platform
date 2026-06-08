<?php $__env->startSection('title', 'Default'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/animate.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/chartist.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/date-picker.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
     <h3 class="text-center"><?php echo e(auth()->user()->getFullNameAttribute()); ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item active"> <a href="<?php echo e(url('/dashboard/index')); ?>"><?php echo e(trans('dashboard.dashboard')); ?></a></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row second-chart-list third-news-update">
            <div class="col-xl-4 col-lg-12 xl-50 morning-sec box-col-12">
                <div class="card o-hidden profile-greeting"
                    style="background-image: url(<?php echo e(asset('assets/images/dashboard/bg.png')); ?>)">
                    <div class="card-body">
                        <div class="media">
                            <div class="badge-groups w-100">
                                <div class="badge f-12"><i class="mr-1" data-feather="clock"></i><span id="txt"></span>
                                </div>
                                <div class="badge f-12"><i class="fa fa-spin fa-cog f-14"></i></div>
                            </div>
                        </div>
                        <div class="greeting-user text-center">
                            <div class="profile-vector"><img class="img-fluid"
                                    src="<?php echo e(asset('assets/images/dashboard/welcome.png')); ?>" alt=""></div>
                            <h4 class="f-w-600"><span id="greeting">Bonjour Admin Nom</span> <span class="right-circle"><i
                                        class="fa fa-check-circle f-14 middle"></i></span></h4>
                            <p><span> Metter un rapport General.</span></p>
                            <div class="whatsnew-btn"><a class="btn btn-primary">Whats New !</a></div>
                            <div class="left-icon"><i class="fa fa-bell"> </i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 xl-100 dashboard-sec box-col-12">
                <div class="card earning-card">
                    <div class="card-body p-0">
                        <div class="row m-0">
                            <div class="col-xl-3 earning-content p-0">
                                <div class="row m-0 chart-left">
                                    <div class="col-xl-12 p-0 left_side_earning">
                                        <h5><?php echo e(trans('Tableau de bord')); ?></h5>
                                        <p class="font-roboto"><?php echo e(trans('Aperçu du mois dernier')); ?></p>
                                    </div>
                                    <div class="col-xl-12 p-0 left_side_earning">
                                        <h5>4055.56 Mad </h5>
                                        <p class="font-roboto"><?php echo e(trans('Gagner ce mois-ci')); ?></p>
                                    </div>
                                    <div class="col-xl-12 p-0 left_side_earning">
                                        <h5>1004.11 Mad </h5>
                                        <p class="font-roboto"><?php echo e(trans('Profit de ce mois-ci')); ?></p>
                                    </div>
                                    <div class="col-xl-12 p-0 left_side_earning">
                                        <h5>90%</h5>
                                        <p class="font-roboto"><?php echo e(trans('Vente du mois')); ?></p>
                                    </div>
                                    <div class="col-xl-12 p-0 left-btn"><a
                                            class="btn btn-gradient"><?php echo e(trans('Résumé')); ?></a></div>
                                </div>
                            </div>
                            <h1>Active Users</h1>
                            <ul id="active-users">
                                <!-- The active users will be dynamically added here -->
                            </ul>
                            <div class="col-xl-9 p-0">
                                <div class="chart-right">
                                    <div class="row m-0 p-tb">
                                        <div class="col-xl-8 col-md-8 col-sm-8 col-12 p-0">
                                            <div class="inner-top-left">
                                                <ul class="d-flex list-unstyled">
                                                    <li>Daily</li>
                                                    <li class="active">Weekly</li>
                                                    <li>Monthly</li>
                                                    <li>Yearly</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-sm-4 col-12 p-0 justify-content-end">
                                            <div class="inner-top-right">
                                                <ul class="d-flex list-unstyled justify-content-end">
                                                    <li>Online</li>
                                                    <li>Store</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="card-body p-0">
                                                <div class="current-sale-container">
                                                    <div id="chart-currently"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row border-top m-0">
                                    <div class="col-xl-4 pl-0 col-md-6 col-sm-6">
                                        <div class="media p-0">
                                            <div class="media-left"><i class="icofont icofont-crown"></i></div>
                                            <div class="media-body">
                                                <h6>Referral Earning</h6>
                                                <p>$5,000.20</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-sm-6">
                                        <div class="media p-0">
                                            <div class="media-left bg-secondary"><i class="icofont icofont-heart-alt"></i>
                                            </div>
                                            <div class="media-body">
                                                <h6>Cash Balance</h6>
                                                <p>$2,657.21</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-12 pr-0">
                                        <div class="media p-0">
                                            <div class="media-left"><i class="icofont icofont-cur-dollar"></i></div>
                                            <div class="media-body">
                                                <h6>Sales forcasting</h6>
                                                <p>$9,478.50 </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 xl-100 chart_data_left box-col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="row m-0 chart-main">
                            <div class="col-xl-3 col-md-6 col-sm-6 p-0 box-col-6">
                                <div class="media align-items-center">
                                    <div class="hospital-small-chart">
                                        <div class="small-bar">
                                            <div class="small-chart flot-chart-container"></div>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <div class="right-chart-content">
                                            <h4>1001</h4>
                                            <span><?php echo e(trans('SMS ENVOYÉS')); ?> </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 col-sm-6 p-0 box-col-6">
                                <div class="media align-items-center">
                                    <div class="hospital-small-chart">
                                        <div class="small-bar">
                                            <div class="small-chart1 flot-chart-container"></div>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <div class="right-chart-content">
                                            <h4>1005</h4>
                                            <span><?php echo e(trans('SMS REÇUS')); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 col-sm-6 p-0 box-col-6">
                                <div class="media align-items-center">
                                    <div class="hospital-small-chart">
                                        <div class="small-bar">
                                            <div class="small-chart2 flot-chart-container"></div>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <div class="right-chart-content">
                                            <h4>100</h4>
                                            <span><?php echo e(trans('SMS ECHOUÉS')); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 col-sm-6 p-0 box-col-6">
                                <div class="media border-none align-items-center">
                                    <div class="hospital-small-chart">
                                        <div class="small-bar">
                                            <div class="small-chart3 flot-chart-container"></div>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <div class="right-chart-content">
                                            <h4>101</h4>
                                            <span><?php echo e(trans('UNITÉS DE CRÉDIT')); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 xl-50 chart_data_right box-col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="media-body right-chart-content">
                                <h4>$95,900<span class="new-box"><?php echo e(trans('Total')); ?></span></h4>
                                <span><?php echo e(trans('CA Abonnement')); ?></span>
                            </div>
                            <div class="knob-block text-center">
                                <input class="knob1" data-width="10" data-height="70" data-thickness=".3"
                                    data-angleoffset="0" data-linecap="round" data-fgcolor="#7366ff" data-bgcolor="#eef5fb"
                                    value="60">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 xl-50 chart_data_right second d-none">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="media-body right-chart-content">
                                <h4>$95,000<span class="new-box"><?php echo e(trans('Total')); ?></span></h4>
                                <span><?php echo e(trans('CA SMS')); ?></span>
                            </div>
                            <div class="knob-block text-center">
                                <input class="knob1" data-width="50" data-height="70" data-thickness=".3"
                                    data-fgcolor="#7366ff" data-linecap="round" data-angleoffset="0" value="60">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 xl-50 news box-col-6">
                <div class="card">
                    <div class="card-header">
                        <div class="header-top">
                            <h5 class="m-0"><?php echo e(trans('Dernières Campagnes')); ?></h5>
                            <div class="card-header-right-icon">
                                <select class="button btn btn-primary">
                                    <option><?php echo e(trans('Aujourd\'hui')); ?></option>
                                    <option><?php echo e(trans('Demain')); ?></option>
                                    <option><?php echo e(trans('Hier')); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="news-update">
                            <h6>Report des paiements de factures</h6>
                            
                        </div>
                        <div class="news-update">
                            <h6>Joyeuse Saint-Valentin</h6>
                            
                        </div>
                        <div class="news-update">
                            <h6>50% de réduction pour les types de couslations COVID.</h6>
                            <span>Lorem Ipsum is simply dummy...</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="bottom-btn"><a href="#">Plus...</a></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 xl-50 appointment-sec box-col-6">
                <div class="row">
                    <div class="col-xl-12 appointment">
                        <div class="card">
                            <div class="card-header card-no-border">
                                <div class="header-top">
                                    <h5 class="m-0"><?php echo e(trans('Derniers Propriétaires')); ?></h5>
                                    <div class="card-header-right-icon">
                                        <select class="button btn btn-primary">
                                            <option><?php echo e(trans('Confirmé')); ?></option>
                                            <option><?php echo e(trans('En Attente')); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="appointment-table table-responsive">
                                    <table class="table table-bordernone">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <img class="img-fluid img-40 rounded-circle mb-3"
                                                        src="<?php echo e(asset('assets/images/appointment/app-ent.jpg')); ?>"
                                                        alt="Image description">
                                                    <div class="status-circle bg-primary"></div>
                                                </td>
                                                <td class="img-content-box"><span class="d-block">Venter Loren</span><span
                                                        class="font-roboto">Now</span></td>
                                                <td>
                                                    <p class="m-0 font-primary">28 Sept</p>
                                                </td>
                                                <td class="text-right">
                                                    <div class="button btn btn-primary">Done<i
                                                            class="fa fa-check-circle ml-2"></i></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <img class="img-fluid img-40 rounded-circle"
                                                        src="<?php echo e(asset('assets/images/appointment/app-ent.jpg')); ?>"
                                                        alt="Image description">
                                                    <div class="status-circle bg-primary"></div>
                                                </td>
                                                <td class="img-content-box"><span class="d-block">John Loren</span><span
                                                        class="font-roboto">11:00</span></td>
                                                <td>
                                                    <p class="m-0 font-primary">22 Sept</p>
                                                </td>
                                                <td class="text-right">
                                                    <div class="button btn btn-danger">Pending<i
                                                            class="fa fa-check-circle ml-2"></i></div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 alert-sec">
                        <div class="card bg-img">
                            <div class="card-header">
                                <div class="header-top">
                                    <h5 class="m-0">Alert </h5>
                                    <div class="dot-right-icon"><i class="fa fa-ellipsis-h"></i></div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="body-bottom">
                                    <h6> 10% off For drama lights Couslations...</h6>
                                    <span class="font-roboto">Lorem Ipsum is simply dummy...It is a long established fact
                                        that a reader will be distracted by </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 xl-50 notification box-col-6">
                <div class="card">
                    <div class="card-header card-no-border">
                        <div class="header-top">
                            <h5 class="m-0">notification</h5>
                            <div class="card-header-right-icon">
                                <select class="button btn btn-primary">
                                    <option>Today</option>
                                    <option>Tomorrow</option>
                                    <option>Yesterday</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="media">
                            <div class="media-body">
                                <p>20-04-2020 <span>10:10</span></p>
                                <h6>Updated Product<span class="dot-notification"></span></h6>
                                <span>Quisque a consequat ante sit amet magna...</span>
                            </div>
                        </div>
                        <div class="media">
                            <div class="media-body">
                                <p>20-04-2020<span class="pl-1">Today</span><span class="badge badge-secondary">New</span>
                                </p>
                                <h6>Tello just like your product<span class="dot-notification"></span></h6>
                                <span>Quisque a consequat ante sit amet magna... </span>
                            </div>
                        </div>
                        <div class="media">
                            <div class="media-body">
                                <div class="d-flex mb-3">
                                    <div class="inner-img"><img class="img-fluid"
                                            src="<?php echo e(asset('assets/images/notification/1.jpg')); ?>" alt="Product-1"></div>
                                    <div class="inner-img"><img class="img-fluid"
                                            src="<?php echo e(asset('assets/images/notification/2.jpg')); ?>" alt="Product-2"></div>
                                </div>
                                <span class="mt-3">Quisque a consequat ante sit amet magna...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Magasin Par Activité<span class="digits"></span></h5>
                    </div>
                    <div class="card-body p-0 chart-block">
                        <div class="chart-overflow" id="pie-chart1">
                            <div style="position: relative;">
                                <div dir="ltr" style="position: relative; width: 490px; height: 300px;">
                                    <div aria-label="A chart."
                                        style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%;">
                                        <svg width="490" height="300" aria-label="A chart." style="overflow: hidden;">
                                            <defs id="defs"></defs>
                                            <rect x="0" y="0" width="490" height="300" stroke="none" stroke-width="0"
                                                fill="#ffffff"></rect>
                                            <g>
                                                <rect x="300" y="58" width="97" height="88" stroke="none" stroke-width="0"
                                                    fill-opacity="0" fill="#ffffff"></rect>
                                                <g column-id="Work">
                                                    <rect x="300" y="58" width="97" height="12" stroke="none"
                                                        stroke-width="0" fill-opacity="0" fill="#ffffff"></rect>
                                                    <g><text text-anchor="start" x="317" y="68.2" font-family="Arial"
                                                            font-size="12" stroke="none" stroke-width="0"
                                                            fill="#222222">Autre</text></g>
                                                    <circle cx="306" cy="64" r="6" stroke="none" stroke-width="0"
                                                        fill="#f8d62b"></circle>
                                                </g>
                                                <g column-id="Eat">
                                                    <rect x="300" y="77" width="97" height="12" stroke="none"
                                                        stroke-width="0" fill-opacity="0" fill="#ffffff"></rect>
                                                    <g><text text-anchor="start" x="317" y="87.2" font-family="Arial"
                                                            font-size="12" stroke="none" stroke-width="0"
                                                            fill="#222222">Boutique</text></g>
                                                    <circle cx="306" cy="83" r="6" stroke="none" stroke-width="0"
                                                        fill="#51bb25"></circle>
                                                </g>
                                                <g column-id="Commute">
                                                    <rect x="300" y="96" width="97" height="12" stroke="none"
                                                        stroke-width="0" fill-opacity="0" fill="#ffffff"></rect>
                                                    <g><text text-anchor="start" x="317" y="106.2" font-family="Arial"
                                                            font-size="12" stroke="none" stroke-width="0"
                                                            fill="#222222">Restaurant</text></g>
                                                    <circle cx="306" cy="102" r="6" stroke="none" stroke-width="0"
                                                        fill="#a927f9"></circle>
                                                </g>
                                                <g column-id="Watch TV">
                                                    <rect x="300" y="115" width="97" height="12" stroke="none"
                                                        stroke-width="0" fill-opacity="0" fill="#ffffff"></rect>
                                                    <g><text text-anchor="start" x="317" y="125.2" font-family="Arial"
                                                            font-size="12" stroke="none" stroke-width="0"
                                                            fill="#222222">Hotel</text></g>
                                                    <circle cx="306" cy="121" r="6" stroke="none" stroke-width="0"
                                                        fill="#f73164"></circle>
                                                </g>
                                                <g column-id="Sleep">
                                                    <rect x="300" y="134" width="97" height="12" stroke="none"
                                                        stroke-width="0" fill-opacity="0" fill="#ffffff"></rect>
                                                    <g><text text-anchor="start" x="317" y="144.2" font-family="Arial"
                                                            font-size="12" stroke="none" stroke-width="0"
                                                            fill="#222222">Coiffure</text></g>
                                                    <circle cx="306" cy="140" r="6" stroke="none" stroke-width="0"
                                                        fill="#7366ff"></circle>
                                                </g>
                                            </g>
                                            <g>
                                                <path
                                                    d="M187,151L187,59A92,92,0,0,1,224.41977116297363,66.95381789688072L187,151A0,0,0,0,0,187,151"
                                                    stroke="#ffffff" stroke-width="1" fill="#f8d62b"></path>
                                            </g>
                                            <g>
                                                <path
                                                    d="M187,151L224.41977116297363,66.95381789688072A92,92,0,0,1,274.49719949915414,122.57043651750485L187,151A0,0,0,0,0,187,151"
                                                    stroke="#ffffff" stroke-width="1" fill="#51bb25"></path><text
                                                    text-anchor="start" x="214.4590686931586" y="113.81826867651773"
                                                    font-family="Arial" font-size="12" stroke="none" stroke-width="0"
                                                    fill="#ffffff">13.3%</text>
                                            </g>
                                            <g>
                                                <path
                                                    d="M187,151L274.49719949915414,122.57043651750485A92,92,0,0,1,241.07624321090753,225.42956348249515L187,151A0,0,0,0,0,187,151"
                                                    stroke="#ffffff" stroke-width="1" fill="#a927f9"></path><text
                                                    text-anchor="start" x="236.54559395180956" y="175.52229538752866"
                                                    font-family="Arial" font-size="12" stroke="none" stroke-width="0"
                                                    fill="#ffffff">20%</text>
                                            </g>
                                            <g>
                                                <path
                                                    d="M187,151L107.32566285183164,197A92,92,0,0,1,187,59L187,151A0,0,0,0,0,187,151"
                                                    stroke="#ffffff" stroke-width="1" fill="#7366ff"></path><text
                                                    text-anchor="start" x="115.7800509857928" y="124.76212324498412"
                                                    font-family="Arial" font-size="12" stroke="none" stroke-width="0"
                                                    fill="#ffffff">33.3%</text>
                                            </g>
                                            <g>
                                                <path
                                                    d="M187,151L241.07624321090753,225.42956348249515A92,92,0,0,1,107.32566285183162,196.99999999999994L187,151A0,0,0,0,0,187,151"
                                                    stroke="#ffffff" stroke-width="1" fill="#f73164"></path><text
                                                    text-anchor="start" x="154.2600290039514" y="222.19379630611013"
                                                    font-family="Arial" font-size="12" stroke="none" stroke-width="0"
                                                    fill="#ffffff">26.7%</text>
                                            </g>
                                            <g></g>
                                        </svg>
                                        <div aria-label="A tabular representation of the data in the chart."
                                            style="position: absolute; left: -10000px; top: auto; width: 1px; height: 1px; overflow: hidden;">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Task</th>
                                                        <th>Hours per Day</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Autre</td>
                                                        <td>5</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Eat</td>
                                                        <td>10</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Commute</td>
                                                        <td>15</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Watch TV</td>
                                                        <td>20</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Sleep</td>
                                                        <td>25</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div aria-hidden="true"
                                    style="display: none; position: absolute; top: 310px; left: 500px; white-space: nowrap; font-family: Arial; font-size: 12px;">
                                    Sleep</div>
                                <div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-12 xl-50 calendar-sec box-col-6">
                <div class="card gradient-primary o-hidden">
                    <div class="card-header">
                        <strong class="text-center">SMS QUICK SEND</strong>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo e(route('admin.single-campaign')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                
                                <div class="col-md-12 mb-3">
                                    <label for="validationServer02"><?php echo e(trans('Emetteur')); ?></label>
                                    <input name="sender" class="form-control " id="validationServer02" type="text" placeholder="<?php echo e(trans('XBEL STORE')); ?>" required="" data-original-title="" title="<?php echo e(trans('Nom d\'emetteur')); ?>">
                                    
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="validationServer02"><?php echo e(trans('Téléphone')); ?></label>
                                    <input class="form-control" id="validationServer02" name="phone" type="text" placeholder="+212 6 XX-XX-XX-XX" required="" data-original-title="" title="<?php echo e(trans('Téléphone Destinataire')); ?>">
                                    
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="validationServer01"><?php echo e(trans('Message')); ?></label>
                                    <textarea name="message" class="form-control" id="validationServer01" type="text"  data-original-title="" title="<?php echo e(trans('Message')); ?>"></textarea>
                                    
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit" data-original-title="" title=""><?php echo e(trans('Envoyer')); ?></button>
                        </form>
                    </div>
                </div>
            </div>
         
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/js/chart/chartist/chartist.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/chart/chartist/chartist-plugin-tooltip.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/chart/knob/knob.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/chart/knob/knob-chart.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/chart/apex-chart/apex-chart.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/chart/apex-chart/stock-prices.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/notify/bootstrap-notify.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/dashboard/default.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/notify/index.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.en.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.custom.js')); ?>"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        Pusher.logToConsole = true;

        var pusher = new Pusher('<?php echo e(env("PUSHER_APP_KEY")); ?>', {
            cluster: '<?php echo e(env("PUSHER_APP_CLUSTER")); ?>',
            encrypted: true
        });

        var channel = pusher.subscribe('user.<?php echo e(auth()->user()->id); ?>');
        channel.bind('App\\Events\\UserActivityEvent', function(data) {
            var status = data.status === 'login' ? 'Logged in' : 'Logged out';
            var listItem = document.createElement('li');
            listItem.textContent = data.user.name + ' - ' + status;
            document.getElementById('active-users').appendChild(listItem);
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>