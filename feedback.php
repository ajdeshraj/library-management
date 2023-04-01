<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>User Feedback</title>
 <style>
  body {
   background-color: #f1f1f1;
  }

  form {
   width: 50%;
   margin: 0 auto;
   padding: 20px;
   background-color: #fff;
   border: 1px solid #ccc;
   border-radius: 5px;
   margin-top: 100px;
  }

  label {
   display: block;
   margin-bottom: 5px;
  }

  input[type="text"] {
   width: 98%;
   padding: 5px;
   border: 1px solid #ccc;
   border-radius: 5px;
  }

  input[type="submit"] {
   width: 100%;
   padding: 5px;
   border: 1px solid #ccc;
   border-radius: 5px;
   background-color: #ccc;
   cursor: pointer;
  }
 </style>
</head>

<body>
 <!-- user feedback form with only 1 input text field and 1 submit button -->
 <form action="" method="post">
  <label for="feedback">Feedback</label>
  <br />
  <input type="text" name="feedback" id="feedback" placeholder="Feedback">
  <br />
  <br />
  <input type="submit" value="Submit">
 </form>
</body>

</html>