<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                {{ date('Y') }} © {{ config('app.name', 'Laravel') }}.
            </div>
            <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block">
                    Crafted with <i class="mdi mdi-heart text-danger"></i> by <a href="{{ url('/') }}" target="_blank" class="text-reset">{{ config('app.name', 'Laravel') }}</a>
                </div>
            </div>
        </div>
    </div>
</footer>