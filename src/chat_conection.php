<?php

require_once(__DIR__.  "/../supportboard/include/functions.php");

function ch_add_user_and_login($user) {
    $cUser = sb_get_user($user->id);
    if (!$cUser){
        $settings=[
            'id'=>$user->id,
            'email'=>$user->email,
            'first_name'=>$user->fname,
            'last_name'=>$user->lname,
            'user_type'=>$user->role=='user'?'user':'agent',
            'department'=>$user->company,
            'creation_time'=>$user->created_at
        ];
        if (!empty($user->avatar))
            $settings['profile_image']='https://portal.irhliving.com/uploads/avatar/'.$user->avatar;
        $settings_extra=[
            'unit'=>[$user->unit,'Room'],
            'country'=>[$user->country,'Country'],
            'lease_end'=>[$user->lease_end,'Anticipated End'],
            'current_url'=>['https://portal.irhliving.com/student/'.$user->id,'Current URL'],
        ];
        akio_add_user($settings,$settings_extra);
        $cUser = sb_db_get('SELECT token FROM sb_users WHERE id = ' . $user->id);
    }
//    $res= sb_login('', '', $user->id, $cUser['token']);
}

function ch_logout() {
    sb_logout();
}