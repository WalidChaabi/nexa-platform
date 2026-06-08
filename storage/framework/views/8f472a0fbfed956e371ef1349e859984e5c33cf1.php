<?php $__env->startSection('title', 'Default'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/animate.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/chartist.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/date-picker.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
    <h3 class="text-center"><?php echo e(trans('Mes Produits')); ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard/employe')); ?>"><?php echo e(trans('dashboard.dashboard')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e(trans('Produits')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <!-- Ajax Deferred rendering for speed start-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <button class="btn btn-info" type="button" data-toggle="modal" data-target=".bd-example-modal-lg"
                            title="Nouveau Produit">Nouveau</button>
                    </div>
                    <div class="card-body row">
                        <div class="table-responsive">
                            <table class="display" id="render-datatable">
                                <thead>
                                    <tr>
                                        <th><?php echo e(trans('libellé')); ?></th>
                                        <th><?php echo e(trans('Magasin')); ?></th>
                                        <th><?php echo e(trans('Image')); ?></th>
                                        <th><?php echo e(trans('Prix ')); ?></th>
                                        <th><?php echo e(trans('Qté')); ?></th>
                                        <th><?php echo e(trans('Modifier QS')); ?></th>
                                        <th><?php echo e(trans('Action')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <th scope="row"><?php echo e($item->label); ?></th>
                                            <td><?php echo e($item->store->name); ?></td>
                                            <input type="hidden" id="store_id" value="<?php echo e($item->store->id); ?>">
                                            <td>
                                                <?php if($item->image): ?>
                                                    <img src="http://127.0.0.1:8000/uploads/product/images/<?php echo e($item->id); ?>/<?php echo e($item->image); ?>" alt="Current Image" style="max-height: 70px;">
                                                <?php else: ?>
                                                    <p>No image selected</p>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($item->price); ?></td>
                                            <td id="quantite<?php echo e($item->id); ?>"><?php echo e($item->quantite); ?></td>
                                            <td>
                                                <div class="d-flex">
                                                    <input class="form-control mr-2 " id="sup_Qt<?php echo e($item->id); ?>" type="number" name="sup_Qt" title="<?php echo e(trans('Ajouter quantité ')); ?>"
                                                        min="0" oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null"
                                                        style="width:100px;border: #a927f9 1px solid;border-radius: 3px;">
                                                    <button class="btn btn-info" onclick="addQuantite(<?php echo e($item->id); ?>)"><?php echo e(trans('valider')); ?></button>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn" style="background-color: #a927f9" data-toggle="modal" data-target=".bd-example-modal-lg-product-<?php echo e($item->id); ?>"
                                                    title="<?php echo e(trans('Consulter')); ?>"><i class="fa fa-eye"></i></a>
                                                <a class="btn" style="background-color: #a927f9" data-toggle="modal" data-target=".bd-example-modal-lg-product-edit-<?php echo e($item->id); ?>"
                                                    title="<?php echo e(trans('Modifier')); ?>"><i class="fa fa-pencil"></i></a>
                                            </td>
                                        </tr>
                                        <div id="modal-owner" class="modal fade bd-example-modal-lg-product-<?php echo e($item->id); ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" 
                                            aria-hidden="true" style="display: none;">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="myLargeModalLabel">
                                                            <?php echo e(trans('Détail Produit')); ?></h4>
                                                        <button class="close" type="button" data-dismiss="modal"
                                                            aria-label="Close" data-original-title="" title=""><span
                                                                aria-hidden="true">×</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="exampleFormControlSelect9"><?php echo e(trans('Libellé')); ?></label>
                                                                    <input readonly class="form-control "
                                                                        id="validationServer02" type="text"
                                                                        value="<?php echo e($item->label); ?>" data-original-title=""
                                                                        title="<?php echo e(trans('Libellé')); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label
                                                                    for="validationServer02"><?php echo e(trans('Magasin')); ?></label>
                                                                <input readonly class="form-control "
                                                                    id="validationServer02" type="text"
                                                                    value="<?php echo e($item->store->name); ?>" data-original-title=""
                                                                    title="<?php echo e(trans('Magasin')); ?>">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="exampleFormControlSelect9"><?php echo e(trans('Prix')); ?></label>
                                                                    <input readonly class="form-control "
                                                                        id="validationServer01" type="text"
                                                                        value="<?php echo e($item->price); ?>" required=""
                                                                        data-original-title=""
                                                                        title="<?php echo e(trans('Prix Produit')); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label
                                                                    for="validationServer01"><?php echo e(trans('Prix Promotion')); ?></label>
                                                                <input readonly class="form-control "
                                                                    id="validationServer01" type="text"
                                                                    value="<?php echo e($item->promotion_price); ?>" required=""
                                                                    data-original-title=""
                                                                    title="<?php echo e(trans('Prix Promotion')); ?>">

                                                            </div>

                                                        </div>
                                                        <button class="btn btn-primary" data-dismiss="modal"
                                                            aria-label="Close" data-original-title=""
                                                            title=""><?php echo e(trans('Fermer')); ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="modal-owner" class="modal fade bd-example-modal-lg-product-edit-<?php echo e($item->id); ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" 
                                            aria-hidden="true" style="display: none;">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="myLargeModalLabel"> <?php echo e(trans('Détail Produit')); ?></h4>
                                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title="">
                                                            <span aria-hidden="true">×</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="<?php echo e(route('employe.products.update',$item->id)); ?>" method="POST" enctype="multipart/form-data">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="_method" value="PUT"/>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <input type="hidden" name="store_id" value="<?php echo e(auth()->user()->store->id); ?>">
                                                                    <input type="hidden" name="categorie_id" value="<?php echo e(collect(request()->segments())->last()); ?>">
                                                                    <label for="validationServer01"><?php echo e(trans('label')); ?></label>
                                                                    <input class="form-control" id="validationServer01" type="text" value="<?php echo e($item->label); ?>" name="label"  required="" data-original-title="" title="<?php echo e(trans('label')); ?>">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="validationServer01"><?php echo e(trans('Quantité')); ?></label>
                                                                    <input class="form-control " id="Equantite<?php echo e($item->id); ?>" value="<?php echo e($item->quantite); ?>" type="text" name="quantite" required="" data-original-title="" title="<?php echo e(trans('Quantité')); ?>">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="validationServer02"><?php echo e(trans('Prix')); ?></label>
                                                                    <input class="form-control " id="validationServer02" type="text" value="<?php echo e($item->price); ?>"  name="price"  required="" data-original-title="" title="<?php echo e(trans('Prix')); ?>">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="validationServer01"><?php echo e(trans('Prix d\'achat')); ?></label>
                                                                    <input class="form-control " id="validationServer01" type="text" value="<?php echo e($item->promotion_price); ?>" name="promotion_price" required="" data-original-title="" title="<?php echo e(trans('Prix d\'achat')); ?>">
                                                                </div>
                                                                <?php if(auth()->user()->store->POS == 1): ?>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="validationServer01"><?php echo e(trans('image')); ?></label>
                                                                    <div>
                                                                        <?php if($item->image): ?>
                                                                          <img src="http://127.0.0.1:8000/uploads/product/images/<?php echo e($item->id); ?>/<?php echo e($item->image); ?>" alt="Current Image" style="max-height: 200px;">
                                                                        <?php endif; ?>
                                                                      </div>
                                                                    <input type="file" name="image" class="form-control">
                                                                </div>
                                                                <?php endif; ?>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="validationServer02"><?php echo e(trans('Modifier Qté final de stock')); ?></label>
                                                                    <div class="d-flex" style="align-items: flex-end ;justify-content: space-between;width:100%;">
                                                                        <div class="mr-2">
                                                                            <input id="quantityInput<?php echo e($item->id); ?>" class="form-control" type="number" <?php echo e(trans('Modifier quantité ')); ?> min="0"
                                                                                    oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null">
                                                                        </div>
                                                                        <div class="btn btn-info" id="change" onclick="changeQuantite(<?php echo e($item->id); ?>)"><?php echo e(trans('Modifier')); ?></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button class="btn btn-primary" type="submit" data-original-title="" title="<?php echo e(trans('Enregistrer')); ?>"><?php echo e(trans('Enregistrer')); ?></button>                                                    
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
           <div class="modal-content">
              <div class="modal-header">
                 <h4 class="modal-title" id="myLargeModalLabel"><?php echo e(trans('Nouveau Produit')); ?></h4>
                 <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
              </div>
              <div class="modal-body">
                    <form action="<?php echo e(route('employe.products.categorie.store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="hidden" name="store_id" value="<?php echo e(auth()->user()->store->id); ?>">
                                <input type="hidden" name="categorie_id" value="<?php echo e(collect(request()->segments())->last()); ?>">
                                <label for="validationServer01"><?php echo e(trans('label')); ?></label>
                                <input class="form-control" id="validationServer01" type="text" name="label"  required="" data-original-title="" title="<?php echo e(trans('label')); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01"><?php echo e(trans('Quantité')); ?></label>
                                <input readonly class="form-control " id="validationServer01" type="text" name="quantite" required="" data-original-title="" title="<?php echo e(trans('Quantité')); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02"><?php echo e(trans('Prix')); ?></label>
                                <input class="form-control " id="validationServer02" type="text"  name="price"  required="" data-original-title="" title="<?php echo e(trans('Prix')); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01"><?php echo e(trans('Prix d\'achat')); ?></label>
                                <input class="form-control " id="validationServer01" type="text" name="promotion_price" required="" data-original-title="" title="<?php echo e(trans('Prix d\'achat')); ?>">
                            </div>                  
                            <?php if(auth()->user()->store->POS == 1): ?>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01"><?php echo e(trans('image')); ?></label>
                                <input type="file" name="image" class="form-control">
                            </div>
                            <?php endif; ?>
                        </div>
                        <button class="btn btn-primary" type="submit" data-original-title="" title="<?php echo e(trans('Enregistrer')); ?>"><?php echo e(trans('Enregistrer')); ?></button>
                    </form>
              </div>
           </div>
        </div>
     </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatables/datatable.custom.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/chart/chartist/chartist.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/chart/chartist/chartist-plugin-tooltip.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/chart/knob/knob.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/chart/knob/knob-chart.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/chart/apex-chart/apex-chart.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/chart/apex-chart/stock-prices.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/notify/bootstrap-notify.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/dashboard/default.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/notify/index.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.js²')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.en.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.custom.js')); ?>"></script>
    <script>
        function importProducts(store_id) {
            if (store_id != 0) {
                $('#store_id_hidden').attr('value', store_id);
                $('#importList').submit();
            } else {
                alert('Vous devez selectionnez un magasin!')
            }

        }

        function ShowItems(value, url) {
            window.location = url + "?items=" + value
        }

        function addQuantite(product_id) {
            var quantite = document.getElementById('quantite' + product_id).innerText;
            console.log(quantite);
            var store_id = document.getElementById('store_id').value;
            var sup_Qt = document.getElementById('sup_Qt' + product_id).value;
            $.ajax({
                url: "<?php echo e(route('employe.products.add.quantite')); ?>",
                type: 'POST',
                data: {
                    sup_Qt: sup_Qt,
                    quantite: quantite,
                    product_id: product_id,
                    store_id: store_id
                },
                success: function(data) {
                    $('#quantite' + product_id).text(data);
                    $('#sup_Qt' + product_id).val('');
                },
                error: function(error) {
                    console.error('Error fetching products:', error);
                }
            });
        }

        function changeQuantite(productId) {
            var quantite = document.getElementById('quantityInput'+productId).value;
            var store_id = document.getElementById('store_id').value;
            console.log('quantite',quantite)
            $.ajax({
                url: "<?php echo e(route('employe.products.change.quantite')); ?>",
                data: {
                    productId: productId,
                    quantite: quantite,
                    store_id: store_id
                },
                type: 'POST',
                success: function(data) {
                    console.log(data)
                    $('#Equantite'+productId ).val(data);
                    $('#quantityInput'+productId).val('');
                },
                error: function(error) {
                    console.log(error)
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>