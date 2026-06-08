@extends('layouts.authentication.master')
@section('title', 'Login')

@section('css')
@endsection

@section('style')
@endsection


@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="col-12">
        
         <div class="login-card">
            <div>
               {{-- href="{{ route('index') }}" --}}
               <div><a class="logo" ><img class="img-fluid for-light" width="150" height="150" src="{{asset('assets/images/logo/logo.png')}}" alt="looginpage"><img class="img-fluid for-dark" src="{{asset('assets/images/logo/logo.jpg')}}" alt="looginpage"></a></div>
               <div class="login-main">

                
                  <form class="theme-form"  method="POST" action="{{ route('auth.login') }}">
                     @csrf
                     <h4>{{trans('Se Connecter')}}</h4>
                     <p>{{trans('Entrer Votre E-mail et Mot de passe')}}</p>
                     <div class="form-group">
                        <label class="col-form-label">{{trans('E-mail')}}</label>
                        <input class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" type="email" required="" placeholder="Test@gmail.com">
                              @if ($errors->has('email'))
                                 <strong>{{ $errors->first('email') }}</strong>
                              @endif
                     </div>
                     <div class="form-group">
                        <label class="col-form-label">{{trans('Mot de Passe')}}</label>
                        <input id="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"" type="password"  required="" placeholder="*********">
                        <div class="show-hide" onclick="showHidePass()">
                           <i class="fa fa-eye-slash" id="hide" aria-hidden="true" style="display: none"></i>
                           <i class="fa fa-eye" id="show"aria-hidden="true"></i>
                        </div>
                        {{-- <div class="show-hide" onclick="showHidePass()"><span class="show"></span></div> --}}
                               
                     </div>
                     @if(isset($errors_auth))
                     <span style="color:red">{{$errors_auth}}</span>
                     @endif
                     <div class="mb-0 form-group">
                        <button class="btn btn-primary btn-block" id="error" type="submit">{{trans('Se Connecter')}}</button>
                     </div>
                     <div class="mt-4 social">
                        <div class="btn-showcase">
                     </div>
                     <p class="mt-4 mb-0"><a class="ml-2" href="{{ route('forget-password') }}">Mot de passe oublié ?</a></p>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('script')
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
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
@endsection