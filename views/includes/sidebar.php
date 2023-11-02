<link href="<?=url("");?>assets/css/pushy.css" rel="stylesheet">
<div class="left-bar">
    <div class="slimscroll-menu">
        @if ( $user->role == "superadmin")
        <li><a href="<?=url("Company@get");?>">
                <label class="menu-icon"><i class="ion-ios-location"></i> </label><span class="text">Locations</span>
            </a>
        </li>
        @endif
        <li><a href="<?= url(""); ?>">
            <label class="menu-icon"><i class="ion-ios-speedometer"></i> </label><span class="text">Dashboard</span>
        </a></li>
    @if ( $user->role != "user" )
        <li><a href="<?= url("Customer@get"); ?>">
                <label class="menu-icon"><i class="ion-ios-people"></i> </label><span class="text">Students</span>
            </a>
        </li>
        <li><a href="<?= url("Import@get_errors"); ?>">
                <label class="menu-icon"><i class="ion-ios-personadd"></i> </label><span class="text">Import Students</span>
            </a>
        </li>
        <li class="pushy-submenu">
            <a>
                <label class="menu-icon"><i class="ion-wrench"></i> </label>
                <span class="text">Rooms & Beds</span>
            </a>
            <ul>
                <li class="pushy-link">
                    <a href="<?= url("Room@getRoomList"); ?>">
                        <span class="text">Room Manage</span>
                    </a>
                </li>
                <li class="pushy-link">
                    <a href="<?= url("Room@getList"); ?>">
                        <span class="text">Bed Status</span>
                    </a>
                </li>
<!--                <li class="pushy-link">-->
<!--                    <a href="--><?//= url("FeeManage@specialFee"); ?><!--">-->
<!--                        <span class="text">Bed Fees</span>-->
<!--                    </a>-->
<!--                </li>-->
            </ul>
        </li>
        <li><a href="<?=url("Report@create");?>">
                <label class="menu-icon"><i class="ion-clipboard"></i> </label><span class="text">Report</span>
            </a>
        </li>
        <li><a href="<?=url("Employer@get");?>">
                <label class="menu-icon"><i class="ion-cash"></i></label><span class="text">Employers</span>
            </a>
        </li>

        <li>
            <a href="<?= url("Fine@get"); ?>">
                <label class="menu-icon"><i class="ion-calculator"></i> </label><span class="text">Fees</span>
            </a>
        </li>

        <li><a href="<?= url("Drawer@get"); ?>">
                <label class="menu-icon"><i class="ion-ios-time-outline"></i> </label><span class="text">Drawer</span>
            </a>
        </li>
        <li><a href="<?= url("Settings@actionLog"); ?>">
                <label class="menu-icon"><i class="ion-ios-list"></i> </label><span class="text">Action Logs</span>
            </a>
        </li>
        <li>
            <a href="<?= url("EmailTemplate@get"); ?>">
                <label class="menu-icon"><i class="ion-email"></i> </label><span class="text">Email Template</span>
            </a>
        </li>

        <?php //include "includes/head.php" ?>
<!--            <li><a href="--><?//=url("Request@get");?><!--">-->
<!--                    <label class="menu-icon"><i class="ion-fireball"></i> </label><span class="text">Leases View</span>-->
<!--            </a></li>-->
        @endif
        <!--        <li><a href="--><? //=url("Notification@get");?><!--" class="notification-holder">-->
        <!--                <label class="menu-icon"><i class="ion-ios-bell"></i> </label><span class="text">Notifications</span>-->
        <!--            </a></li>            -->
<!--        <li><a href="--><?//= url("Document@get"); ?><!--">-->
<!--                <label class="menu-icon"><i class="ion-document-text"></i> </label><span class="text">Documents</span>-->
<!--            </a>-->
<!--        </li>-->


        @if ( $user->role == "user" )
        <li><a href="<?= url("Payment@payment"); ?>">
                <label class="menu-icon"><i class="ion-card"></i> </label><span class="text">Make Payment</span>
            </a></li>
        @endif
        <li><a href="<?= url("Maintenance@list"); ?>">
                <label class="menu-icon"><i class="ion-pull-request"></i> </label><span class="text">Maintenance Request</span>
            </a>
        </li>
<!--        <li><a href="--><?//= url("Payment@history"); ?><!--">-->
<!--                <label class="menu-icon"><i class="ion-social-usd-outline"></i> </label><span class="text">Payment History</span>-->
<!--            </a>-->
<!--        </li>-->
<!--        <li><a href="--><?//= url("Checkout@getCheckoutHistory"); ?><!--">-->
<!--                <label class="menu-icon"><i class="ion-cash"></i> </label><span class="text">Checkout History</span>-->
<!--            </a>-->
<!--        </li>-->

        @if ( $user->role == "superadmin" || $user->role == "admin" )
        <li><a href="<?=url("Team@get");?>">
                <label class="menu-icon"><i class="ion-ios-people"></i> </label><span class="text">Staff</span>
            </a></li>
        <li><a href="<?= url("Template@get"); ?>">
                <label class="menu-icon"><i class="ion-document"></i> </label><span class="text">Lease Templates</span>
            </a>
        </li>
        @endif

        <li><a href="<?= url("Settings@get"); ?>">
                <label class="menu-icon"><i class="ion-gear-a"></i> </label><span class="text">Settings</span>
            </a>
        </li>
    </div>
</div>

