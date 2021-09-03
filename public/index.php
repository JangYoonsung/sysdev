<?php

$dbh = new PDO('mysql:host=mysql;dbname=techc', 'root', '');

if(isset($_POST['message']) && !empty($_POST['message'])) {
  $imgFile = null;
  if(isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {
    if(preg_match('/^image\//', $_FILES['image']['type']) !== 1) {
      header("HTTP/1.1 302 Found");
      header("LOCATION: ./index.php");
    }
    $pathInfo = pathinfo($_FILES['image']['name']);
    $extension = $pathInfo['extension'];

    $imgFile = strval(time()) . bin2hex(random_bytes(25)) . '.' . $extension;
    $filepath = '/var/www/public/image/' . $imgFile;
    move_uploaded_file($_FILES['image']['tmp_name'], $filepath);
  }

  $sql = "INSERT INTO bbs (message, image_filename) VALUES (:message, :image_filename)";
  $prepare = $dbh->prepare($sql);
  $prepare->bindValue('message', $_POST['message']);
  $prepare->bindValue('image_filename', $imgFile);
  $prepare->execute();

  header("HTTP/1.1 302 Found");
  header("LOCATION: ./index.php");
  return;
}

$sql = "SELECT * FROM bbs ORDER BY created_at DESC";
$prepare = $dbh->prepare($sql);
$prepare->execute();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./style.css">
  <title>Document</title>
</head>
<body>
  <form action="./index.php" method="post" enctype="multipart/form-data">
    <textarea name="message"></textarea>
    <div>
      <input type="file" accept="image/*" name="image" id="image">
    </div>
    <div>
      <button>submit</button>
    </div>
  </form>

  <table>
    <tr>
      <td>
        no
      </td>
      <td>
        date
      </td>
      <td>
        message
      </td>
      <td>
        image
      </td>
    </tr>
    <?php foreach($prepare as $val): ?>
    <tr>
      <td class='no'><?= $val['id'] ?></td>
      <td><?= $val['created_at'] ?></td>
      <td><?= nl2br(htmlspecialchars($val['message'])); ?></td>
      <?php if(isset($val['image_filename'])): ?>
        <td>
          <img src="/image/<?= $val['image_filename'] ?>">
        </td>
      <?php else: ?>
        <td>
          -
        </td>
      <?php endif; ?>
    <?php endforeach; ?>
  </table>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const imageInput = document.getElementById("image");
      imageInput.addEventListener("change", () => {
        if (imageInput.files.length < 1) {
          return;
        }
        if (imageInput.files[0].size > 5 * 1024 * 1024) {
          alert("this img over 5mb");
          imageInput.value = "";
        }
      });
    });
</script>

</body>
</html>