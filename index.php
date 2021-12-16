<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styling.css">
    <title>Document</title>
</head>
<body>
    <header class="mpesa-header">
        <h1 class="title">Mpesa integration with PHP</h1>
    </header>
    <section class="form-section">
        <form action="kandyprocessor.php" class="form" role="form" method="POST">
            <div class="form-group">
                <label for="name">Full name</label>
                <input type="text" name="fullname" id="" class="form-control" placeholder="Enter your full name">
            </div>
            <div class="form-group">
                <label for="">Bought product</label>
                <input type="text" name="product" id="" class="form-control" placeholder="bought product">
            </div>
            <div class="form-group">
                <label for="">Price</label>
                <input type="text" name="price" id="" class="form-control" placeholder="enter the price">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-mine" name="pay" id="" value="Pay">
            </div>
        </form>
    </section>
</body>
</html>