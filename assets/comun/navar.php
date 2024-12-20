<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group fade">
                            <input disabled type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group fade">
                                        <input disabled type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </li>
                            
                            <!-- Nav Item - Alerts -->
                            <li class="nav-item dropdown no-arrow mx-1">
                                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter"></span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                            aria-labelledby="alertsDropdown">
                            <h6 class="dropdown-header">
                                Notificaciones
                            </h6>
                            <!-- Las notificaciones se agregarán aquí dinámicamente -->
                            <div class="dropdown-list" id="contenido_notificaciones"></div>
                            <a class="dropdown-item text-center small text-gray-500" href="?p=notificaciones">Mostrar todas</a>
                        </div>
                    </li>
                    
                    <div class="topbar-divider d-none d-sm-block"></div>
                    
                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle no-select" draggable="false" href="#" id="userDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?=$_SESSION["usuario_rotario_name"] ?></span>
                        <!-- <img class="img-profile rounded-circle" src=""> -->
                                <span class="img-profile rounded-circle bi bi-person-circle d-flex justify-content-center align-items-center" style="font-size: 2rem"></span>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <!-- <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a> -->
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item cursor-pointer" href="?p=perfil">
                                    <!-- <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400 cursor-pointer"></i> -->
                                    <span>Mi Perfil</span>
                                    <span class="fas fa-id-card"></span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item cursor-pointer" href="#" id="help-btn" onclick="return false;">
                                    <span>Mostrar Ayuda</span>
                                    <span class="fas fa-question"></span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item cursor-pointer" href="#" id="logout_btn" onclick="return false">
                                    <!-- <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400 cursor-pointer"></i> -->
                                    <span>Cerrar Sesión</span>
                                    <span class="fas fa-reply"></span>
                                </a>
                                
                            </div>
                        </li>

                    </ul>

                </nav>
                <script type="text/javascript" src="assets/js/comun/nav.js"></script>
