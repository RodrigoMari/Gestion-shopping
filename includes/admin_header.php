<header class="admin-header fixed-top">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <button class="btn btn-secondary d-md-none">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="h4 mb-0"></h1>
        <div class="text-end">
            <span class="me-2">Bienvenido, Admin</span>
            <i class="fas fa-user-circle"></i>
        </div>
    </div>
</header>
<script>
    (function() {
        function adjustBodySpacing() {
            var header = document.querySelector('.admin-header');
            var sidebar = document.getElementById('app-fixed-sidebar');
            var body = document.body;
            if (!header) return;
            var headerHeight = header.offsetHeight;
            body.style.paddingTop = headerHeight + 'px';

            var mq = window.matchMedia('(min-width: 768px)');
            if (mq.matches && sidebar) {
                body.style.paddingLeft = sidebar.offsetWidth + 'px';
            } else {
                body.style.paddingLeft = '';
            }
        }

        window.addEventListener('resize', adjustBodySpacing);
        document.addEventListener('DOMContentLoaded', adjustBodySpacing);
        setTimeout(adjustBodySpacing, 300);
    })();
</script>