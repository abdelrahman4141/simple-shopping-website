<?php include 'config.php';?>
<?php 
    
    session_start();
    $user_id= $_SESSION['user_id'];
    if(!isset($user_id)) {
        header('location: login.php');
    }
    
// logout
if(isset($_GET["logout"])) {
    unset($user_id);
    session_destroy();
    header('location: login.php');
}
// end logout

// add to cart
if(isset($_POST["add_to_cart"])) {
    $product_name =$_POST['product_name'];
    $product_image =$_POST['product_image'];
    $product_price =$_POST['product_price'];
    $product_quantity =$_POST['product_quantity'];

    $select_cart =mysqli_query($conn,"SELECT * FROM cart WHERE name= '$product_name' AND user_id = '$user_id' ");

    if (mysqli_num_rows($select_cart) > 0) {
        $message = "Product already added to cart!";
    }else {
        
        mysqli_query($conn,"INSERT INTO cart (user_id,name,price,image,quantity)
         VALUES ('$user_id','$product_name','$product_price','$product_image','$product_quantity')");
         $message = "Product added to cart!";
    }
}
// end add to cart

//update cart
if(isset($_POST["update_cart"])){
    $cart_id= $_POST['cart_id'];
    $update_quantity= $_POST['cart_quantity'];
    mysqli_query($conn,"UPDATE cart SET quantity='$update_quantity' WHERE id='$cart_id' ");
    $message = "Cart quantity is updated successfully!";
}
//end update cart

//remove
if(isset($_GET["remove"])){
    $item_id_delete = $_GET['remove'];
    
    mysqli_query($conn,"DELETE FROM `cart` WHERE `id`='$item_id_delete'");
    header("Location: index.php ");
}
if(isset($_GET['delete_all'])) {
    mysqli_query($conn,"DELETE FROM `cart`");
    header("Location: index.php ");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shopping cart</title>
    <!-- css link  -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php if(isset($message)): ?>
        
        <div class="message" onclick="this.remove()">
            <?php echo $message ?>
        </div>
       
    <?php endif;?>

    <div class="container">

             <div class="user-profile">
                    <?php  
                    $select_user=mysqli_query($conn,"SELECT * FROM users WHERE id= '$user_id' ");
                    if (mysqli_num_rows($select_user) > 0) {
                        $fetech_user= mysqli_fetch_assoc($select_user) or die('error');
                        
                    }
                    ?>
                <p>Welcome <span><?php echo $fetech_user['name'] ?></span> </p>
                <p>Email: <span><?php echo $fetech_user['email'] ?></span> </p>
                <div class=flex>
                    <a href="login.php" class="btn">login</a>
                    <a href="resgister.php" class="option-btn">register</a>
                    <a href="index.php?logout=<?php echo $user_id;?>" onclick="return confirm('You want to logout?');"class="delete-btn">logout</a>
                </div>
        
         </div>
   

     <div class="products">
        <h1 class="heading">Latest Products</h1>
        <div class="box-container">
        <?php  $select_product=mysqli_query($conn,"SELECT * FROM products ");?>
                   <?php if (mysqli_num_rows($select_product) > 0):?>
                         <?php while( $fetech_product= mysqli_fetch_assoc($select_product)): ?>
                       
                                <form action="" method="post" class="box">
                                     <img src="images/<?php echo $fetech_product['image'];?>" alt="">
                                     <div class="name">
                                        <?php echo $fetech_product['name']?>
                                    </div>
                                     <div class="price">
                                        $<?php echo $fetech_product['price']?>
                                    </div>
                                    <input type="number" min="1" name="product_quantity" value="1">
                                    <input type="hidden" value="<?php echo $fetech_product['name'];?>" name="product_name">
                                    <input type="hidden" value="<?php echo $fetech_product['image'];?>" name="product_image">
                                    <input type="hidden" value="<?php echo $fetech_product['price'];?>" name="product_price">
                                    <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                    
                                </form>
                        <?php endwhile; ?>
                    <?php endif; ?>
                    
        </div>
     </div>
     <div class="shopping-cart">
        <h1 class="heading">Shopping Cart</h1>
        <table>
            <thead>

                <th>Image</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total price</th>
                <th>Action</th>
            </thead>
            <tbody>
            <?php  $select_cart=mysqli_query($conn,"SELECT * FROM cart WHERE user_id= '$user_id'");
            $total_price =0;?>
                   <?php if (mysqli_num_rows($select_cart) > 0):?>
                         <?php while( $fetech_cart= mysqli_fetch_assoc($select_cart)): ?>
                            <tr>
                                <td><img src="images/<?php echo $fetech_cart['image'];?>" width="100"></td>
                                <td><?php echo $fetech_cart['name'];?></td>
                                <td><?php echo $fetech_cart['price'];?>
                                </td>
                                <td>
                                    <form action="" method="post">
                                        <input type="hidden" name="cart_id" value="<?php echo $fetech_cart['id'];?>">
                                        <input type="number" min="1" name= "cart_quantity" value="<?php echo $fetech_cart['quantity'];?>">
                                        <input type="submit" value="update" name="update_cart" class="option-btn">
                                    </form>
                                </td>
                                <td>$<?php echo $sub_total = $fetech_cart['price'] * $fetech_cart['quantity']?></td>
                                <td><a href="index.php?remove=<?php echo $fetech_cart['id'];?>" class="delete-btn" onclick="return confirm('remove item?');">remove</a>
                                </td>
                                <?php  $total_price+= $sub_total; ?>
                            </tr>
                            <?php endwhile; ?>
                           
                            <?php else: ?>
                                <tr><td colspan="6" stlye="padding:20px ; text-transform:capitalize;">No item  in the shopping cart.</td></tr>
                                <?php endif; ?>
                                <tfoot class="table-bottom">
                                <td colspan="4">Total price :</td>
                                <td >$<?php echo $total_price?></td>
                                <td ><a href="index.php?delete_all" onclick="return confirm('remove all item?');" class="delete-btn <?php echo ($total_price > 0)?'':'disabled';?>">Delete all</a></td>
                            </tfoot>
              </tbody>
        </table>
        <div class="cart-btn">
            <a href="#" class="btn <?php echo ($total_price > 0)?'':'disabled';?>">Pocessed to Checkout</a>
        </div>
     </div>
     </div>
</body>
</html>