<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['flash']) && !empty($_SESSION['flash']['message'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    $allowedVariants = ['success', 'warning', 'danger', 'info'];
    $variant = in_array($flash['type'] ?? 'info', $allowedVariants, true) ? $flash['type'] : 'info';
    $message = htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8');
    ?>
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;">
      <div id="flashToast" class="toast align-items-center text-bg-<?= $variant ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body">
            <?= $message ?>
          </div>
          <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    </div>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var toastEl = document.getElementById('flashToast');
        if (toastEl && typeof bootstrap !== 'undefined' && typeof bootstrap.Toast !== 'undefined') {
          var toast = new bootstrap.Toast(toastEl);
          toast.show();
        }
      });
    </script>
<?php
}
