<?php
/*
 File: /app/controllers/components/image.php
*/
class ImageComponent extends Object
{
    /*
    *    Uploads an image and its thumbnail into $folderName/big and $folderName/small respectivley.
    *     the  generated thumnail could either have the same aspect ratio as the uploaded image, or could
    *    be a zoomed and cropped version.

    *     Directions:
    *    In view where you upload the image, make sure your form creation is similar to the following
    *    <?= $form->create('FurnitureSet',array('type' => 'file')); ?>
    *
    *    In view where you upload the image, make sure that you have a file input similar to the following
    *    <?= $form->file('Image/name1'); ?>
    *
    *    In the controller, add the component to your components array
    *    var $components = array("Image");
    *
    *    In your controller action (the parameters are expained below)
    *    $image_path = $this->Image->upload_image_and_thumbnail($this->data,"name1",573,80,"sets",true);
    *    this returns the file name of the result image.  You can  store this file name in the database
    *
    *    Note that your image will be stored in 2 locations:
    *    Image: /webroot/img/$folderName/big/$image_path
    *    Thumbnail:  /webroot/img/$folderName/small/$image_path
    *
    *    Finally in the view where you want to see the images
    *    <?= $html->image('sets/big/'.$furnitureSet['FurnitureSet']['image_path']);
    *     where "sets" is the folder name we saved our pictures in, and $furnitureSet['FurnitureSet']['image_path'] is the file name we stored in the database

    *    Parameters:
    *    $data: CakePHP data array from the form
    *    $datakey: key in the $data array. If you used <?= $form->file('Image/name1'); ?> in your view, then $datakey = name1
    *    $imgscale: the maximum width or height that you want your picture to be resized to
    *    $thumbscale: the maximum width or height that you want your thumbnail to be resized to
    *    $folderName: the name of the parent folder of the images. The images will be stored to /webroot/img/$folderName/big/ and  /webroot/img/$folderName/small/
    *    $square: a boolean flag indicating whether you want square and zoom cropped thumbnails, or thumbnails with the same aspect ratio of the source image
    */
    function upload_image_and_thumbnail($data, $datakey, $imgscale, $thumbscale, $folderName, $square) {
        if (strlen($data['Image'][$datakey]['name'])>4){
                    $error = 0;
                    $tempuploaddir = "img/temp"; // the /temp/ directory, should delete the image after we upload
                    $biguploaddir = "img/".$folderName."/big"; // the /big/ directory
                    $smalluploaddir = "img/".$folderName."/small"; // the /small/ directory for thumbnails

                    // Make sure the required directories exist, and create them if necessary
                    if(!is_dir($tempuploaddir)) mkdir($tempuploaddir,true);
                    if(!is_dir($biguploaddir)) mkdir($biguploaddir,true);
                    if(!is_dir($smalluploaddir)) mkdir($smalluploaddir,true);

                    $filetype = $this->getFileExtension($data['Image'][$datakey]['name']);
                    $filetype = strtolower($filetype);

                    if (($filetype != "jpeg")  && ($filetype != "jpg") && ($filetype != "gif") && ($filetype != "png"))
                    {
                        // verify the extension
                        return;
                    }
                    else
                    {
                        // Get the image size
                        $imgsize = GetImageSize($data['Image'][$datakey]['tmp_name']);
                    }

                    // Generate a unique name for the image (from the timestamp)
                    $id_unic = str_replace(".", "", strtotime ("now"));
                    $filename = $id_unic;

                    settype($filename,"string");
                    $filename.= ".";
                    $filename.=$filetype;
                    $tempfile = $tempuploaddir . "/$filename";
                    $resizedfile = $biguploaddir . "/$filename";
                    $croppedfile = $smalluploaddir . "/$filename";

                    if (is_uploaded_file($data['Image'][$datakey]['tmp_name']))
                    {
                    	umask(0);
                    	//echo $data['Image'][$datakey]['tmp_name']." ".$tempfile."<br/>";
                    	//echo umask();
                        // Copy the image into the temporary directory
                        if (!$this->copy($data['Image'][$datakey]['tmp_name'],"$tempfile"))
                        {
                            print "Error Uploading File!.";
                            exit();
                        }
                        else {
                            /*
                             *    Generate the big version of the image with max of $imgscale in either directions
                             */
                            $this->resize_img($tempfile, $imgscale, $resizedfile);                            

                            if($square) {
                                /*
                                 *    Generate the small square version of the image with scale of $thumbscale
                                 */
                                $this->crop_img($tempfile, $thumbscale, $croppedfile);
                            }
                            else {
                                /*
                                 *    Generate the big version of the image with max of $imgscale in either directions
                                 */
                                $this->resize_img($tempfile, $thumbscale, $croppedfile);
                            }

                            // Delete the temporary image
                            unlink($tempfile);
                        }
                    }

                     // Image uploaded, return the file name
                     return $filename;
        }
    }

    /*
    *    Deletes the image and its associated thumbnail
    *    Example in controller action:    $this->Image->delete_image("1210632285.jpg","sets");
    *
    *    Parameters:
    *    $filename: The file name of the image
    *    $folderName: the name of the parent folder of the images. The images will be stored to /webroot/img/$folderName/big/ and  /webroot/img/$folderName/small/
    */
    function delete_image($filename,$folderName) {
        unlink("img/".$folderName."/big/".$filename);
        unlink("img/".$folderName."/small/".$filename);
    }

    function copy($file1,$file2){
          $contentx =@file_get_contents($file1);
                   $openedfile = fopen($file2, "w");
                   fwrite($openedfile, $contentx);
                   fclose($openedfile);
                    if ($contentx === FALSE) {
                    $status=false;
                    }else $status=true;
                   
                    return $status;
    } 
    
    function crop_img($imgname, $scale, $filename) {
        $filetype = $this->getFileExtension($imgname);
        $filetype = strtolower($filetype);

        switch($filetype){
            case "jpeg":
            case "jpg":
              $img_src = ImageCreateFromjpeg ($imgname);
             break;
             case "gif":
              $img_src = imagecreatefromgif ($imgname);
             break;
             case "png":
              $img_src = imagecreatefrompng ($imgname);
             break;
        }

        $width = imagesx($img_src);
        $height = imagesy($img_src);
        $ratiox = $width / $height * $scale;
        $ratioy = $height / $width * $scale;

        //-- Calculate resampling
        $newheight = ($width <= $height) ? $ratioy : $scale;
        $newwidth = ($width <= $height) ? $scale : $ratiox;

        //-- Calculate cropping (division by zero)
        $cropx = ($newwidth - $scale != 0) ? ($newwidth - $scale) / 2 : 0;
        $cropy = ($newheight - $scale != 0) ? ($newheight - $scale) / 2 : 0;

        //-- Setup Resample & Crop buffers
        $resampled = imagecreatetruecolor($newwidth, $newheight);
        $cropped = imagecreatetruecolor($scale, $scale);

        //-- Resample
        imagecopyresampled($resampled, $img_src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        //-- Crop
        imagecopy($cropped, $resampled, 0, 0, $cropx, $cropy, $newwidth, $newheight);

        // Save the cropped image
        switch($filetype)
        {
            case "jpeg":
            case "jpg":
             imagejpeg($cropped,$filename,80);
             break;
             case "gif":
             imagegif($cropped,$filename,80);
             break;
             case "png":
             imagepng($cropped,$filename,8);
             break;
        }
    }

    function resize_img($imgname, $size, $filename)    {
        $filetype = $this->getFileExtension($imgname);
        $filetype = strtolower($filetype);

        switch($filetype) {
            case "jpeg":
            case "jpg":
            $img_src = ImageCreateFromjpeg ($imgname);
            break;
            case "gif":
            $img_src = imagecreatefromgif ($imgname);
            break;
            case "png":
            $img_src = imagecreatefrompng ($imgname);
            break;
        }

        $true_width = imagesx($img_src);
        $true_height = imagesy($img_src);

        //if ($true_width>=$true_height)
        //{
            $width=$size;
            $height = ($width/$true_width)*$true_height;
        //}
        //else
        //{
            //$height = $size;
            //$width = ($height/$true_height)*$true_width;
        //}
        $img_des = ImageCreateTrueColor($width,$height);
        imagecopyresampled ($img_des, $img_src, 0, 0, 0, 0, $width, $height, $true_width, $true_height);

        // Save the resized image
        switch($filetype)
        {
            case "jpeg":
            case "jpg":
             imagejpeg($img_des,$filename,80);
             break;
             case "gif":
             imagegif($img_des,$filename,80);
             break;
             case "png":
             imagepng($img_des,$filename,8);
             break;
        }
    }

    function getFileExtension($str) {

        $i = strrpos($str,".");
        if (!$i) { return ""; }
        $l = strlen($str) - $i;
        $ext = substr($str,$i+1,$l);
        return $ext;
    }
} 
?>
