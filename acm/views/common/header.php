<?php
if (!defined('l2jmobius')) {
    die('Direct access not permitted');
}
?>
		<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?=($settings->has('main_website')) ? $settings->get('main_website') : $appURL.'/'.$language_id; ?>">
                <div class="sidebar-brand-text mx-3"><?=$appName;?></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item<?=($controllerName == 'index') ? ' active' : ''?>">
                <a class="nav-link" href="<?=$appURL.'/'.$language_id;?>">
                    <i class="fas fa-fw fa-gamepad"></i>
                    <span><?=_('Characters');?></span>
				</a>
            </li>

            <?php if($settings->check('enable_donations')){ ?>
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                <?=_('Donations');?>
            </div>

			<li class="nav-item<?=($controllerName == 'donate') ? ' active' : ''?>">
                <a class="nav-link" href="<?=$appURL.'/'.$language_id;?>/donate">
                    <i class="fas fa-fw fa-heart"></i>
                    <span><?=_('Donate');?></span>
				</a>
            </li>

			<li class="nav-item<?=($controllerName == 'donations-history') ? ' active' : ''?>">
                <a class="nav-link pt-0" href="<?=$appURL.'/'.$language_id;?>/donations-history">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span><?=_('Donations history');?></span>
				</a>
            </li>
			<?php } ?>
			
			<hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                <?=_('Account settings');?>
            </div>

			<li class="nav-item<?=($controllerName == 'change-password') ? ' active' : ''?>">
                <a class="nav-link" href="<?=$appURL.'/'.$language_id;?>/change-password">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span><?=_('Change your password');?></span>
				</a>
            </li>
			<li class="nav-item<?=($controllerName == 'change-email') ? ' active' : ''?>">
                <a class="nav-link pt-0" href="<?=$appURL.'/'.$language_id;?>/change-email">
                    <i class="fas fa-fw fa-envelope"></i>
                    <span><?=_('Change your email');?></span>
				</a>
            </li>

            <?php if($account->isAdmin) { ?>
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                <?=_('Administration');?>
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item<?=($controllerName == 'accounts' || $controllerName == 'characters') ? ' active' : ''?>">
                <a class="nav-link<?=($controllerName == 'accounts' || $controllerName == 'characters') ? '' : ' collapsed'?>" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span><?=_('Game data');?></span>
                </a>
                <div id="collapsePages" class="collapse<?=($controllerName == 'accounts' || $controllerName == 'characters') ? ' show' : ''?>" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item<?=($controllerName == 'accounts') ? ' active' : ''?>" href="<?=$appURL.'/'.$language_id;?>/accounts"><?=_('Accounts');?></a>
                        <a class="collapse-item<?=($controllerName == 'characters') ? ' active' : ''?>" href="<?=$appURL.'/'.$language_id;?>/characters"><?=_('Characters');?></a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Charts -->
            <li class="nav-item<?=($controllerName == 'settings') ? ' active' : ''?>">
                <a class="nav-link pt-0" href="<?=$appURL.'/'.$language_id;?>/settings">
                    <i class="fas fa-fw fa-cog"></i>
                    <span><?=_('ACM Settings');?></span>
				</a>
            </li>
			
			<?php if($settings->check('enable_task_manager')){ ?>
			<li class="nav-item<?=($controllerName == 'task-manager') ? ' active' : ''?>">
                <a class="nav-link pt-0" href="<?=$appURL.'/'.$language_id;?>/task-manager">
                    <i class="fas fa-fw fa-check"></i>
                    <span><?=_('Task Manager');?></span>
				</a>
            </li>
			<?php } ?>

            <!-- Nav Item - Charts -->
            <li class="nav-item<?=($controllerName == 'payments') ? ' active' : ''?>">
                <a class="nav-link pt-0" href="<?=$appURL.'/'.$language_id;?>/payments">
                    <i class="fas fa-fw fa-money-bill"></i>
                    <span><?=_('Payments history');?></span>
				</a>
            </li>

            <?php } if($settings->check('show_stats')){ ?>
			<hr class="sidebar-divider">
			
            <div class="sidebar-heading">
                <?=_('Server status');?>
            </div>
			
			<div class="status nav-item">
				<?php if($settings->check('show_login_status')){ ?>
				<div class="nav-link pb-0">
					<span>Login: <?=getPortStatus($_ENV['LOGIN_HOST'], $_ENV['LOGIN_PORT']);?></span>
				</div>
				<?php } if($settings->check('show_game_status')){ ?>
				<div class="nav-link pb-0">
					<span>Game: <?=getPortStatus($_ENV['GAME_HOST'], $_ENV['GAME_PORT']);?></span>
				</div>
				<?php } if($settings->check('show_online_players')){ ?>
				<div class="nav-link pb-0">
					<span><?=_('Online players');?>: <?=$db_game->total('characters', 'online = ?', [1]);?></span>
				</div>
				<?php } if($settings->check('show_online_gms')){ ?>
				<div class="nav-link pb-2">
					<span><?=_('Online GMs');?>: <?=$db_game->total('characters', 'online = ? AND accesslevel > ?', [1, 0]);?></span>
				</div>
				<?php } ?>
			</div>
			<?php } ?>
            


            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline mt-md-4">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
						<i class="fa fa-bars"></i>
					</button>
					<a href="<?=($settings->has('main_website')) ? $settings->get('main_website') : $appURL.'/'.$language_id; ?>">
						<img src="<?=$cdnURL;?>/img/logo2.png" class="main-logo" alt="<?=$appName;?>">
					</a>
					
					
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-inline text-gray-600 small"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> <?=$account->login;?></span>
                                
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
								<?php if($settings->check('use_balance')){ ?>
								<a class="dropdown-item">
                                    <i class="fas fa-money-bill fa-sm fa-fw mr-2 text-gray-400"></i>
                                    <?=_('Balance').': '.formatAmount($account->balance);?>
                                </a>
								<?php } ?>
                                <a class="dropdown-item" href="<?=$appURL.'/'.$language_id;?>/login-history">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    <?=_('Login history');?>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?=$appURL.'/'.$language_id;?>/login/logout">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    <?=_('Logout');?>
                                </a>
								
                            </div>
                        </li>

                    </ul>

                </nav>