<?php 
// ini_set('memory_limit','-1');

$imagePath = './'; 

if(!empty($_FILES)) {
    // compress files
    function compress($source, $destination, $quality) {

        $info = getimagesize($source);

        if ($info['mime'] == 'image/jpeg') 
            $image = imagecreatefromjpeg($source);

        elseif ($info['mime'] == 'image/gif') 
            $image = imagecreatefromgif($source);

        elseif ($info['mime'] == 'image/png') 
            $image = imagecreatefrompng($source);

        imagejpeg($image, $destination, $quality);

        return ['name' => $destination, 'type' => $info['mime']];
    }

    $defaultType = isset($_POST['image-type']) ? $_POST['image-type'] : ".jpg";

    $source_img = $_FILES['image-file']['tmp_name'];
    
    $destination_img = !empty($_POST['image-name']) ? $_POST['image-name'] . '.' . $defaultType : date("d_m_y_h_i_sa") . '_convert.' . $defaultType;
    $destination_img = $imagePath . $destination_img;

    $resoltion_type = !empty($_POST['image-resolution']) ? $_POST['image-resolution'] : 50;
    
    $response = compress($source_img, $destination_img, $resoltion_type);
    if ($response) {
        downloadImage($response);        
    } else {
        echo 'unable to convert';
    }
} else {
    header('Location: ./index.html');
}

function downloadImage($data) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/force-download');
    header('Content-type: ' . $data['type']);
    header("Content-Disposition: attachment; filename=\"" . basename($data['name']) . "\";");
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($data['name']));
    ob_clean();
    flush();
    readfile($data['name']); //showing the path to the server where the file is to be download
    unlink($data['name']);
    exit;
}
?>