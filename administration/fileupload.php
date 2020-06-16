<?php
    require_once('init.inc.php');

    $target_dir = "../distributors/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
     
    // Check if file already exists
    if (file_exists($target_file)) {
        $notification = new Notification("Sorry, file already exists.", 'success');
        $notification->redirect("distributors.php");
        $uploadOk = 0;
    }
    
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        $notification = new Notification("Sorry, your file is too large.", 'success');
        $notification->redirect("distributors.php");
        $uploadOk = 0;
    }
    
    // Allow certain file formats
    if($FileType != "jpg" && $FileType != "jpeg" && $FileType != "png" && $FileType != "pdf" && $FileType != "doc" && $FileType != "docx" && $FileType != "odt" && $FileType != "mp4" && $FileType != "ai" && $FileType != "tiff" && $FileType != "zip" && $FileType != "rar" && $FileType != "txt" && $FileType != "ppt" && $FileType != "pptx" && $FileType != "docx" && $FileType != "7z" && $FileType != "svg") {
        $notification = new Notification("Sorry, only JPG, JPEG, PNG, SVG, TXT, PDF, AI, PSD, DOC, DOCX, ODT, mp4, tiff, ZIP, RAR, 7z, PPT, PPTX files are allowed.", 'success');
        $notification->redirect("distributors.php");
        $uploadOk = 0;
    }
    
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $notification = new Notification("Sorry, your file was not uploaded.", 'success');
        $notification->redirect("distributors.php");
    // if everything is ok, try to upload file
    } else {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $notification = new Notification("The file has been uploaded.", 'success');
        $notification->redirect("distributors.php");
      } else {
        $notification = new Notification("Sorry, there was an error uploading your file.", 'success');
        $notification->redirect("distributors.php");
      }
    }
    ?>