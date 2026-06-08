<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>
<?php $id = uniqid(); ?>
<div class="row" id="<?php echo e($id); ?>">
    <div class="col-md-12 d-flex" id="produitPraicien">
        <div class="col-md-4 d-flex" style="flex-direction: column">
            <label for="validationServer01"><?php echo e(trans('communs.Produit')); ?> :</label>
            <select onchange="setPriceProduct(this, this.value,'<?php echo e($id); ?>')"
                class="js-example-basic-single col-sm-12" name="products[]">
                <option data-tokens="0">--- <?php echo e(trans('communs.Choisir Produit/Service')); ?> ---
                </option>
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option data-tokens="<?php echo e($item->label); ?>" value="<?php echo e($item->id); ?>">
                        <?php echo e($item->label); ?> | <?php echo e($item->price); ?> Mad
                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-3 d-flex" style="flex-direction: column">
            <label for="validationServer01"><?php echo e(trans('communs.Praticien')); ?> :</label>
            <select class="js-example-basic-single col-sm-12" name="userId" onchange="getPraticien($(this).val())">
                <option value="0">--- <?php echo e(trans('communs.Choisir le/la Praticien/ne')); ?>

                    ---</option>
                <?php $__currentLoopData = $employes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($item->id); ?>">
                        <?php echo e($item->getFullNameAttribute()); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-4">
            <div class="d-flex" style="align-items: flex-start;justify-content: space-between;">
                <div class="col-md-3 pl-0">
                    <label for="validationServer02"><?php echo e(trans('communs.Prix')); ?></label>
                    <input class="form-control prices" disabled value="0" id="price_<?php echo e($id); ?>"
                        type="text" name="price[]" data-original-title="" title="<?php echo e(trans('communs.Prix')); ?>">
                </div>
                <div class="col-md-4 d-flex">
                    <div class="d-flex mr-3 mb-3" style="flex-direction: column">
                        <label for="validationServer02"><?php echo e(trans('communs.Quantité')); ?></label>
                        <input class="form-control qts" value="1" id="qte_<?php echo e($id); ?>" name="quantity[]"
                            min="1" required onchange="changeTotalOnce()" data-original-title=""
                            title="<?php echo e(trans('communs.Quantité')); ?>">
                    </div>
                    <div class="d-flex" style="flex-direction: column ;justify-content :flex-end;">
                        <span class="btn btn-primary" onclick="changeQuantity('<?php echo e($id); ?>',1)"
                            style="width: 5px!important;height:25px!important ;display: flex;justify-content:center;align-items: center;">+</span>
                        <span class="btn btn-primary" onclick="changeQuantity('<?php echo e($id); ?>',-1)"
                            style="width: 5px!important;height:25px!important ;display: flex;justify-content:center;align-items: center;margin-top:10px;">-</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="validationServer02"><?php echo e(trans('communs.Supprimer P/S')); ?></label>
                    <button class="form-control btn btn-primary " type="button"
                        onclick="removeProductRow('<?php echo e($id); ?>')"
                        title="<?php echo e(trans('communs.Supprimer Produit')); ?>">-</button>
                </div>
            </div>
        </div>
    </div>
</div>
