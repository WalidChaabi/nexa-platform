<footer class="footer @if(url()->current() == route('footer-dark')) footer-dark @elseif(url()->current() == route('footer-fixed')) footer-fix @endif">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 footer-copyright text-center">
              <p class="mb-0">Copyright {{date('Y')}} © SHERIN theme by pixelstrap  </p>
            </div>
        </div>
    </div>
</footer>