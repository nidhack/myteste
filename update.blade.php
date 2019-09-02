<?php


use App\Login;
use App\Category;
if(session()->has('email')){


$email = session()->get('email');
$a = Login::where('email',$email)->get();
        foreach ($a as $object)
        {
            $level = $object->ulevel;
            $block = $object->ublock;
            $name = $object->uname;
        }






if($level == 1)
{

    ?>


@extends('users.admin.admin_dash')
@section('content')












<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <link rel = "stylesheet" href = "{{asset('/css/panel.css')}}"/>
  <title>
    Edit Product
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">

  <!-- CSS Files -->
  <link href="../assets/css/material-dashboard.css?v=2.1.0" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="../assets/demo/demo.css" rel="stylesheet" />
</head>

<body class="dark-edition">
    <div class="container">

    <h2>Edit Product Details</h2>
    <form name = "admin_product_update" method = "POST" action = "/update_product" enctype="multipart/form-data">

        @csrf

        <div class="panel panel-default">
                <div class="panel-heading"><b>Name</b></div>
                <div class="panel-body"><input class = "form-control" id = "name" name = "name" type = "text" value = "{{$product->name}}" onkeyup="valName()"/><span id="name_span" style="color:red"></span></div>
                </div>
                <div class="panel panel-default">
                <div class="panel-heading"><b>Description</b></div>
                <div class="panel-body"><input class = "form-control" id="desc" name = "description" type = "text" value = "{{$product->description}}" onkeyup="valDesc()"/><span id="desc_span" style="color:red"></div>
                </div>

                <div class="panel panel-default">
                <div class="panel-heading"><b>Image</b></div>
                <div class="panel-body">
                  <input type = "file" name = "image" id="img" onchange="validateFileUpload()"/>
                <img class="magnifiedImg" width = "455px" height = "355px" src = "/images/{{$product->image}}"/></div>
                </div>
                <div class="panel panel-default">
                <div class="panel-heading"><b>Price</b></div>
                <div class="panel-body"><input class = "form-control" id="price" name = "price" type = "number" value = "{{$product->price}}" onchange="valPrice()"/><span id="price_span" style="color:red"></span></div>
                </div><br><br>

                <div class= "col-md-4 offset-md-4">
                        <input type = "hidden" value = "{{$product->pid}}" name = "id" />
                      <input type = "submit" class="btn btn-success" value = "Update" />
                     </div>

                    </div>

</body>

<script>
    function valPrice(){
        document.getElementById("price_span").innerHTML = "";
        if(document.getElementById('price').value == ""){
            document.getElementById("price_span").innerHTML = "Please put a price";
        }
        if(document.getElementById('price').value < 0){
            document.getElementById("price_span").innerHTML = "Price cannot be negative";
            document.getElementById('price').value = "";
            document.getElementById('price').focus();
        }
    }

    function valName(){
        document.getElementById("name_span").innerHTML = "";
        if(document.getElementById('name').value == ""){
            document.getElementById("name_span").innerHTML = "Please fill in the name";
        }
        pattern = /^[a-zA-Z0-9_ ]+$/;
        str = document.getElementById('name').value;
        if(pattern.test(str) == false){
            document.getElementById("name_span").innerHTML = "Name can only include alphabets and numbers";
            document.getElementById('name').focus();
        }

    }


    function valDesc(){
        document.getElementById("desc_span").innerHTML = "";
        if(document.getElementById('desc').value == ""){
            document.getElementById("desc_span").innerHTML = "Please fill in the description";
        }
        pattern = /^[a-zA-Z0-9_.,' ]+$/;
        str = document.getElementById('desc').value;
        if(pattern.test(str) == false){
            document.getElementById("desc_span").innerHTML = "Description can only include alphabets and numbers";
            document.getElementById('desc').focus();
        }

    }
    </script>

<script>
        function validateFileUpload() {
            var fuData = document.getElementById('img');
            var FileUploadPath = fuData.value;

    //To check if user upload any file
            if (FileUploadPath == '') {
                alert("Please upload an image");

            } else {
                var Extension = FileUploadPath.substring(
                        FileUploadPath.lastIndexOf('.') + 1).toLowerCase();

    //The file uploaded is an image

    if (Extension == "gif" || Extension == "png" || Extension == "bmp"
                        || Extension == "jpeg" || Extension == "jpg") {

    // To Display
                    if (fuData.files && fuData.files[0]) {
                        return true;
                    }

                }

    //The file upload is NOT an image
    else {
      fuData.value = "";
                    alert("Photo only allows file types of GIF, PNG, JPG, JPEG and BMP. ");

        return false;
                }
            }
        }
    </script>

</html>

@endsection
<?php

}

 else {
    header("location: ../index.php");}
    }
    else{
        header("location: ../index.php");}

	?>
