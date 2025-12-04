<?php
    if (isset($_FILES['file'])) {
         print_r($_FILES);
         $username = brilliantDirectories::getDatabaseConfiguration('website_user');
         $password = brilliantDirectories::getDatabaseConfiguration('website_pass');
         $host = brilliantDirectories::getDatabaseConfiguration('ftp_server'); 


         $ftp = ftp_connect($host) or die("Couldn't connect to $ftp");
         ftp_login($ftp,$username,$password);

         ftp_pasv($ftp, true);

         $local_directory = $_FILES['file']['tmp_name'];

         //$server_path = "/public_html/images/fonts/".$_FILES['file']['name'];
		$server_path = "/public_html/".$_FILES['file']['name'];

         if (ftp_put($ftp, $server_path, $local_directory, FTP_BINARY)) {
         echo "Successfully uploaded $server_path.";
         } else {
         echo "Error uploading $server_path.";
         } 
         ftp_close($ftp);
     } 
 ?>


 <form action="" enctype="multipart/form-data" method="post">
     <div class="form-group">
         <input type="file" name="file" placeholder="File" title="File" class="form-control">
         <button type="submit" class="btn btn-primary">Upload file</button>
     </div>
 </form>


 <style>
     form {
        width: 400px;
        display: block;
        margin-left: 30px;
     }
     button {
        margin-top: 30px;
     }
 </style>