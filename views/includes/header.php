<header>
        <!-- Hambager -->
        <div class="humbager">
            <i class="ion-navicon-round"></i>
        </div>
        <!-- logo -->
        <div class="logo">
            <a href="<?=url("");?>">
<!--                --><?php //echo SITE_TITLE; ?><!--<span class="hidden-xs"> | HIAWATHA Admin</span>-->
                <img src="<?=url("");?>uploads/app/{{ env('APP_LOGO'); }}" class="img-responsive">
            </a>
        </div>

        @if (isset($user) && $user->role != "user" )
        <div class="header-search hidden-xs">
            <form action="<?=url("Customer@get");?>" class="search-form" style="float: left">
                <span class="search-icon">
                    <i class="ion-android-search"></i>
                </span>
                <input type="text" name="search" value="<?php if(isset($_GET['search'])){ echo $_GET['search']; } ?>" placeholder="Search First Name for Students" class="search-field">
            </form>
            <form action="<?=url("Customer@get");?>" class="search-form" style="float: right">
                <span class="search-icon">
                    <i class="ion-android-search"></i>
                </span>
                <input type="search" name="unit" value="<?php if(isset($_GET['unit'])){ echo $_GET['unit']; } ?>" placeholder="Room or Bed" style="width: 110px" class="search-field">
            </form>
        </div>
        @endif

        <!-- top right -->
        <ul class="nav header-links pull-right">
<!--            <li class="notify  hidden-xs">-->
<!--                <a href="{{ url('Notification@get') }}" class="notification-holder">-->
<!--                    <span class="notifications">-->
<!--                        <i class="notifications-count ion-ios-bell"></i>-->
<!--                    </span>-->
<!--                </a>-->
<!--            </li>-->
<!--            <li class="notify  hidden-xs">-->
<!--                <a target="_blank" href="{{env('LMS_SITE_URL').'login/fromMain/'.strtr(base64_encode($user->email), '+/=', '.-')}}" class="link_lms">-->
<!--                    Learning Site-->
<!--                </a>-->
<!--            </li>-->
            @if ( $user->role != "user" )
            <li class="notify  hidden-xs">
                <a target="_blank" href="<?=url("");?>supportboard/admin.php" >
                    <span style="font-size: 18px;color:#dcecff">
                        <i class="ion-chatbox-working"></i> Chats
                    </span>
                </a>
            </li>
            <li class="notify  hidden-xs">
                <a target="_blank" href="<?=url("Room@roomBedStatus").'?building=0';?>" >
                    <span style="font-size: 18px;color:#dcecff">
                        <i class="ion-connection-bars"></i> Room Assignments
                    </span>
                </a>
            </li>
            @endif
            <li class="profile">
                <div class="dropdown">
                    <span class="dropdown-toggle" data-toggle="dropdown">
                        <span class="profile-name"> <span class="hidden-xs"> {{ $user->fname }} </span> <i class="ion-ios-arrow-down"></i> </span>
                        <span class="avatar">
                            @if( !empty($user->avatar) )
                            <img src="<?=url("");?>uploads/avatar/{{ $user->avatar }}" class="user-avatar img-circle">
                            @else
                            <img src="<?=url("");?>assets/images/avatar.png" class="user-avatar">
                            @endif
                        </span>
                    </span>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                        <li role="presentation"><a role="menuitem" href="<?=url("Settings@get");?>">
                                <i class="ion-ios-person-outline"></i> Profile</a></li>
                        <li role="presentation" class="divider"></li>
                        <li role="presentation"><a role="menuitem" href="<?=url("Settings@get");?>">
                                <i class="ion-ios-gear-outline"></i> Settings</a></li>
                        <li role="presentation" class="divider"></li>
                        <li role="presentation"><a role="menuitem" href="<?=url("Auth@signout");?>">
                                <i class="ion-ios-arrow-right"></i> Logout</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </header>

    <script type="text/javascript">
        window._mfq = window._mfq || [];
        (function() {
            var mf = document.createElement("script");
            mf.type = "text/javascript"; mf.defer = true;
            mf.src = "//cdn.mouseflow.com/projects/<?=env('Mouseflow_Script','db8e75a0-90d3-4cfb-9a62-a7e091e1e5be.js')?>";
            document.getElementsByTagName("head")[0].appendChild(mf);
        })();
    </script>

<script src="<?=url("");?>assets/js/jquery-3.2.1.min.js"></script>
@if ( $user->role == "user" )
    <script id="sbinit" src="<?=url("");?>supportboard/js/main.js"></script>
@endif