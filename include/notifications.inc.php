<?php
// Display all notifications
if(!empty($notifications)){
    foreach($notifications as $notification){
        $notification->display();
    }
}