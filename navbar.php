<!-- As a link -->
<nav class="navbar fixed-top">
  <div class="container">
    <a class="navbar-brand" href="<?= $baseurl ?>">COSMO sender</a>
    <a class="btn-menu" data-bs-toggle="offcanvas" href="#menu" role="button" aria-controls="menu"><i class="bi bi-grid-1x2-fill"></i></a>
  </div>
</nav>

<div class="offcanvas offcanvas-end" tabindex="-1" id="menu" aria-labelledby="menuLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title"><i class="bi bi-grid-1x2-fill"></i> Menu</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
  
    <div class="mt-3">Main Menu</div>
    <hr>
    <a class="btn-item-menu mb-2" href="<?= $baseurl ?>">
        <i class="bi bi-menu-button-wide me-2"></i> Home
    </a>
    <a class="btn-item-menu mb-2" href="<?= $baseurl ?>view/useremail/">
        <i class="bi bi-sliders me-2"></i> User Email Sync
    </a>
    <br>
    <div class="mt-3">Transactional Setting</div>
    <hr>
    <a class="btn-item-menu mb-2" href="<?= $baseurl ?>view/brand/">
        <i class="bi bi-sliders me-2"></i> Brand
    </a>
    <a class="btn-item-menu mb-2" href="<?= $baseurl ?>view/blastrule/">
        <i class="bi bi-sliders me-2"></i> Blast Rules
    </a>
    <br>
    <div class="mt-3">Master Setting</div>
    <hr>
    <a class="btn-item-menu mb-2" href="<?= $baseurl ?>view/server/">
        <i class="bi bi-sliders me-2"></i> Server
    </a>
    <a class="btn-item-menu mb-2" href="<?= $baseurl ?>view/service/">
        <i class="bi bi-sliders me-2"></i> Content Services
    </a>
    <a class="btn-item-menu mb-2" href="<?= $baseurl ?>view/host/">
        <i class="bi bi-sliders me-2"></i> Host
    </a>
    <a class="btn-item-menu mb-2" href="<?= $baseurl ?>view/emailrelay/">
        <i class="bi bi-sliders me-2"></i> Email Relay
    </a>
    <br>
    <hr>
    <a class="btn-item-menu mb-2" style="border-right: solid 2px red;">
        <i class="bi bi-box-arrow-left me-2" style="color:red"></i> Logout
    </a>
    <!-- <div class="btn-item-menu mb-2">
        <i class="bi bi-sliders me-2"></i> Configure
    </div>
    <div class="btn-item-menu mb-2">
        <i class="bi bi-sliders me-2"></i> Configure
    </div> -->
    <!-- <div class="dropdown mt-3">
      <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
        Dropdown button
      </button>
      <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="#">Action</a></li>
        <li><a class="dropdown-item" href="#">Another action</a></li>
        <li><a class="dropdown-item" href="#">Something else here</a></li>
      </ul>
    </div> -->
  </div>
</div>