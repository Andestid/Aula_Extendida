<?php

include '../components/connect.php';

if(isset($_POST['submit'])){

   $id = unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $profession = $_POST['profession'];
   $profession = filter_var($profession, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_files/'.$rename;

   $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ?");
   $select_tutor->execute([$email]);
   
   if($select_tutor->rowCount() > 0){
      $message[] = 'este correo ya existe!';
   }else{
      if($pass != $cpass){
         $message[] = 'las contraseñas no coinciden!';
      }else{
         $insert_tutor = $conn->prepare("INSERT INTO `tutors`(id, name, profession, email, password, image) VALUES(?,?,?,?,?,?)");
         $insert_tutor->execute([$id, $name, $profession, $email, $cpass, $rename]);
         move_uploaded_file($image_tmp_name, $image_folder);
         $message[] = 'nuevo tutor registrado! por favor inicia sesion';
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>registro</title>


   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">


   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body style="padding-left: 0;">

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message form">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>



<section class="form-container">

   <form class="register" action="" method="post" enctype="multipart/form-data">
      <h3>nuevo registro</h3>
      <div class="flex">
         <div class="col">
            <p> nombre <span>*</span></p>
            <input type="text" name="name" placeholder="introduce tu nombre" maxlength="50" required class="box">
            <p>seleccione su profesion <span>*</span></p>
            <select name="profession" class="box" required>
               <option value="" disabled selected> seleccione su profesion</option>
               <option value="developer">profesor</option>
               <option value="desginer">diseñador</option>
               <option value="biologist">biologo</option>
               <option value="engineer">ingeniero</option>
            </select>
            <p>correo electronico <span>*</span></p>
            <input type="email" name="email" placeholder="ingrese su correo" maxlength="20" required class="box">
         </div>
         <div class="col">
            <p> contraseña <span>*</span></p>
            <input type="password" name="pass" placeholder="ingrese su contraseña" maxlength="20" required class="box">
            <p>confirmar contraseña <span>*</span></p>
            <input type="password" name="cpass" placeholder="confirme su contraseña" maxlength="20" required class="box">
            <p>seleccione una foto <span>*</span></p>
            <input type="file" name="image" accept="image/*" required class="box">
         </div>
      </div>
      <p class="link">¿ya tienes una cuenta? <a href="login.php">inicia sesion </a></p>
      <input type="submit" name="submit" value="registrarse" class="btn">
   </form>

</section>













<script>

let darkMode = localStorage.getItem('dark-mode');
let body = document.body;

const enabelDarkMode = () =>{
   body.classList.add('dark');
   localStorage.setItem('dark-mode', 'enabled');
}

const disableDarkMode = () =>{
   body.classList.remove('dark');
   localStorage.setItem('dark-mode', 'disabled');
}

if(darkMode === 'enabled'){
   enabelDarkMode();
}else{
   disableDarkMode();
}

</script>
   
</body>
</html>