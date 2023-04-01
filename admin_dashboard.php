<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Admin Dashboard</title>
 <style>
  .card-holder {
   display: flex;
   justify-content: space-between;
   flex-wrap: wrap;
   margin: 90px;
  }

  .card-holder a {
   border: 1px solid black;
   width: 300px;
   display: flex;
   flex-direction: column;
   justify-content: center;
  }

  .card-holder a h2 {
   text-align: center;
  }

  .card-holder a h3 {
   text-align: center;
  }

  .heading {
   width: 100%;
   max-width: 100vw;
  }

  .heading h1 {
   text-align: center;
  }

  a {
   text-decoration: none;
   color: black;
  }
 </style>
</head>

<body>
 <div>
  <div class="heading">
   <h1>Admin Dashboard</h1>
  </div>
  <div class="card-holder">
   <a href="">
    <h2>Books Listed</h2>
    <h3>100</h3>
   </a>
   <a href="">
    <h2>Times books issued</h2>
    <h3>100</h3>
   </a>
   <a href="">
    <h2>Times books left to return</h2>
    <h3>100</h3>
   </a>
   <a href="">
    <h2>Categories</h2>
    <h3>100</h3>
   </a>
  </div>
 </div>
</body>

</html>