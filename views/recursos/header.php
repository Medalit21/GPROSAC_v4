<nav class="navbar top-navbar navbar-expand-md navbar-dark">
    <div class="navbar-header" data-logobg="skin5">
        <!-- This is for the sidebar toggle which is visible on mobile only -->
        <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
        <!-- ============================================================== -->
        <!-- Logo -->
        <!-- ============================================================== -->
        <a class="navbar-brand" href="#" style="padding: 0 6.3rem !important;">
            <!-- Logo icon -->
             <!--<b class="logo-icon p-l-10">
           
                <img src="<?php echo $NAME_SERVER; ?>views/assets/images/acg-logo-sm.png" alt="homepage" class="light-logo" />

            </b>-->
            <!--End Logo icon -->
            <!-- Logo text -->
            <span class="logo-lg">
                            <img src="<?php echo $NAME_SERVER; ?>views/assets/images/acg-logo-sm.png" alt="" height="50">
                        </span>
            

            
            <!-- Logo icon -->
            <!-- <b class="logo-icon"> -->
            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
            <!-- Dark Logo icon -->
            <!-- <img src="assets/images/logo-text.png" alt="homepage" class="light-logo" /> -->

            <!-- </b> -->
            <!--End Logo icon -->
        </a>

        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Toggle which is visible on mobile only -->
        <!-- ============================================================== -->
        <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
    </div>
    <!-- ============================================================== -->
    <!-- End Logo -->
    <!-- ============================================================== -->
    <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
        <!-- ============================================================== -->
        <!-- toggle and nav items -->
        <!-- ============================================================== -->
        <ul class="navbar-nav float-left mr-auto">
            <li class="nav-item d-none d-md-block"><a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)" data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-24"></i></a></li>
            <!-- ============================================================== -->
            <!-- create new -->
            <!-- ============================================================== -->
            <li class="nav-item dropdown">
                
            </li>
            <!-- ============================================================== -->
            <!-- Search -->
            <!-- ============================================================== -->
            <li class="nav-item search-box"> 
            </li>
        </ul>
        <!-- ============================================================== -->
        <!-- Right side toggle and nav items -->
        <!-- ============================================================== -->
        <ul class="navbar-nav float-right">
            <!-- ============================================================== -->
            <!-- Comment -->
            <!-- ============================================================== -->
            <li class="nav-item dropdown">
                <!--
                <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-bell font-24"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
                -->

            </li>
            <!-- ============================================================== -->
            <!-- End Comment -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Messages -->
            <!-- ============================================================== -->
            <li class="nav-item dropdown">
                <!--
                <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="font-24 mdi mdi-comment-processing"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown" aria-labelledby="2">
                    <ul class="list-style-none">
                        <li>
                            <div class="">
                                <a href="javascript:void(0)" class="link border-top">
                                    <div class="d-flex no-block align-items-center p-10">
                                        <span class="btn btn-success btn-circle"><i class="ti-calendar"></i></span>
                                        <div class="m-l-10">
                                            <h5 class="m-b-0">Event today</h5>
                                            <span class="mail-desc">Just a reminder that event</span>
                                        </div>
                                    </div>
                                </a>
                                <a href="javascript:void(0)" class="link border-top">
                                    <div class="d-flex no-block align-items-center p-10">
                                        <span class="btn btn-info btn-circle"><i class="ti-settings"></i></span>
                                        <div class="m-l-10">
                                            <h5 class="m-b-0">Settings</h5>
                                            <span class="mail-desc">You can customize this template</span>
                                        </div>
                                    </div>
                                </a>
                                <a href="javascript:void(0)" class="link border-top">
                                    <div class="d-flex no-block align-items-center p-10">
                                        <span class="btn btn-primary btn-circle"><i class="ti-user"></i></span>
                                        <div class="m-l-10">
                                            <h5 class="m-b-0">Pavan kumar</h5>
                                            <span class="mail-desc">Just see the my admin!</span>
                                        </div>
                                    </div>
                                </a>
                                <a href="javascript:void(0)" class="link border-top">
                                    <div class="d-flex no-block align-items-center p-10">
                                        <span class="btn btn-danger btn-circle"><i class="fa fa-link"></i></span>
                                        <div class="m-l-10">
                                            <h5 class="m-b-0">Luanch Admin</h5>
                                            <span class="mail-desc">Just see the my new admin!</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
                -->
            </li>
            <!-- ============================================================== -->
            <!-- End Messages -->
            <!-- ============================================================== -->
            <li class="nav-item dropdown" hidden>               
                <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['datos']; ?></i>
                </a>
            </li>
            <!-- ============================================================== -->
            <!-- TIPO CAMBIO-->
            <!-- ============================================================== -->
            <li class="nav-item dropdown" <?php echo $_SESSION['filtro']; ?>>
                <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?php echo $NAME_SERVER; ?>views/assets/images/users/tipocambio.png" title="Tipo de Cambio" width="32"></a>
                <div class="dropdown-menu dropdown-menu-right user-dd animated text-center">
                    <a class="dropdown-item" href="#"><i class="fas fa-donate"></i> Tipo Cambio : <?php echo $_SESSION['tipo_cambio']; ?></a>
                    <a class="dropdown-item btn btn-registro" href="<?php echo $NAME_SERVER; ?>views/M00_Home/M01_Home/tipo_cambio.php<?php echo "?Vsr=".$user_sesion; ?>"><i class="fas fa-edit"></i> Agregar</a>
                    <div class="dropdown-divider"></div>
                </div>
            </li> 
            <!-- ============================================================== -->
            <!--END TIPO CAMBIO -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- NOTIFICACIONES-->
            <!-- ============================================================== -->
            <li class="nav-item dropdown" <?php echo $_SESSION['filtro']; ?>>
                <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="<?php echo $NAME_SERVER; ?>views/M01_Proyecto/M01SM05_Inventario/M01_SM06_Manzana.php<?php echo "?Vsr=".$user_sesion; ?>"><img src="<?php echo $NAME_SERVER; ?>views/assets/images/users/inventario.png" title="Inventario" width="32"></a>
                <div class="dropdown-menu dropdown-menu-right user-dd animated" hidden>
                    <a class="dropdown-item" href="#"><i class="ti-settings m-r-5 m-l-5"></i> Tipo Cambio : <?php echo $tipocambio; ?></a>
                    <a class="dropdown-item" href="#"><i class="ti-settings m-r-5 m-l-5"></i> RMV : <?php echo "S/. ".$rmv; ?></a>
                    <a class="dropdown-item" href="#"><i class="ti-settings m-r-5 m-l-5"></i> UIT : <?php echo "S/. ".$uit; ?></a>
                    <div class="dropdown-divider"></div>
                </div>
            </li> 
            <!-- ============================================================== -->
            <!--EN NOTIFICACIONES -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- MANUAL USUARIO-->
            <!-- ============================================================== -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?php echo $NAME_SERVER; ?>views/assets/images/users/guia.png" alt="user" width="32" title="Manual de Usuario"></a>
                <div class="dropdown-menu dropdown-menu-right user-dd animated">
                    <a class="dropdown-item" href="../../archivos/Adjuntos/manual_usuario.pdf" download="GPROSAC_Manual_Usuario" <?php echo $_SESSION['filtro']; ?>><i class="fas fa-download m-r-5 m-l-5"></i> Manual de Usuario</a>
                    <a class="dropdown-item" href="../../archivos/Adjuntos/guia_cliente.pdf" download="GPROSAC_Guia_Cliente"><i class=" fas fa-download m-r-5 m-l-5"></i> Guía de Cliente</a>
                    <div class="dropdown-divider"></div>
                </div>
            </li> 
            <!-- ============================================================== -->
            <!--EN MANUAL USUARIO -->
            <!-- ============================================================== -->
            
            <!-- ============================================================== -->
            <!-- User profile and search -->
            <!-- ============================================================== -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?php echo $NAME_SERVER; ?>views/assets/images/users/1.jpg" alt="user" class="rounded-circle" width="31"></a>
                <div class="dropdown-menu dropdown-menu-right user-dd animated">
                    <a class="dropdown-item" href="#"><i class="ti-user m-r-5 m-l-5"></i> <?php echo $_SESSION['datos']; ?></a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" 
                    href="<?php echo $NAME_SERVER; ?>views/exit.php"><i class="fa fa-power-off m-r-5 m-l-5"></i> Cerrar Session</a>
                </div>
            </li> 
            <!-- ============================================================== -->
            <!-- User profile and search -->
            <!-- ============================================================== -->
        </ul>
    </div>
</nav>