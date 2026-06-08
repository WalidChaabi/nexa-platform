<?php $__env->startSection('title', 'Login'); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<div class="container-fluid">
   <div class="row">
      <div class="col-12">
        
         <div class="login-card">
            <div>
               
               <div><a class="logo" ><img class="img-fluid for-light" width="150" height="150" src="<?php echo e(asset('assets/images/logo/logo.png')); ?>" alt="looginpage"><img class="img-fluid for-dark" src="<?php echo e(asset('assets/images/logo/logo.jpg')); ?>" alt="looginpage"></a></div>
               <div class="login-main">

                
                  <form class="theme-form"  method="POST" action="<?php echo e(route('auth.login')); ?>">
                     <?php echo csrf_field(); ?>
                     <h4><?php echo e(trans('Se Connecter')); ?></h4>
                     <p><?php echo e(trans('Entrer Votre E-mail et Mot de passe')); ?></p>
                     <div class="form-group">
                        <label class="col-form-label"><?php echo e(trans('E-mail')); ?></label>
                        <input class="form-control <?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" name="email" value="<?php echo e(old('email')); ?>" type="email" required="" placeholder="Test@gmail.com">
                              <?php if($errors->has('email')): ?>
                                 <strong><?php echo e($errors->first('email')); ?></strong>
                              <?php endif; ?>
                     </div>
                     <div class="form-group">
                        <label class="col-form-label"><?php echo e(trans('Mot de Passe')); ?></label>
                        <input id="password" class="form-control <?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>" name="password"" type="password"  required="" placeholder="*********">
                        <div class="show-hide" onclick="showHidePass()">
                           <i class="fa fa-eye-slash" id="hide" aria-hidden="true" style="display: none"></i>
                           <i class="fa fa-eye" id="show"aria-hidden="true"></i>
                        </div>
                        
                               
                     </div>
                     <?php if(isset($errors_auth)): ?>
                     <span style="color:red"><?php echo e($errors_auth); ?></span>
                     <?php endif; ?>
                     <div class="mb-0 form-group">
                        <button class="btn btn-primary btn-block" id="error" type="submit"><?php echo e(trans('Se Connecter')); ?></button>
                     </div>
                     <div class="mt-4 social">
                        <div class="btn-showcase">
                     </div>
                     <p class="mt-4 mb-0"><a class="ml-2" href="<?php echo e(route('forget-password')); ?>">Mot de passe oublié ?</a></p>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>
<script>

  function  showHidePass() {
        console.log('hello');
      var type = $('#password').attr('type');
      if(type ==="password"){
         $('#password').attr('type','text');
         $('#hide').css('display', 'block');
         $('#show').css('display', 'none');
      }
      else  {
         $('#password').attr('type','password');  
         $('#show').css('display', 'block');
         $('#hide').css('display', 'none');   
      }
         
  }
   $(document).on('click', '#error', function(e) {
      if($('.email').val() == '' || $('.pwd').val() == ''){
         swal(
            "Error!", "Sorry, looks like some data are not filled, please try again !", "error"           
         )
      }
   });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.authentication.master', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>