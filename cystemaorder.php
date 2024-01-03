<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CYSTEMA-Order</title>
    <link rel="stylesheet" type="text/css" href="cystemaorder.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
   
    <header>    
        <nav>
        <div class="wrapper">
              
              <input type="checkbox" id="toggle-menu" class="toggle-menu">
              <label for="toggle-menu" class="menu-icon"><i class="fas fa-bars"></i></label>
              <label for="toggle-menu" class="exit-icon"><i class="fas fa-times"></i></label>
             
              <ul class="nav-area">
                  <li><a href="cystemahome.php">Home</a></li>
                  <li><a href="cystemashirt.php" id="adopt-link">Shirt</a></li>
                  <li><a href="cystemabags.php">Bag</a></li>
                  <li><a href="cystemaaccessories.php">Accessories</a></li>
              </ul>
            
              <div class="logo">
                  <a href="cystemahome.php">
                      <img src="Logo Big.jpg" alt="logo" height="100px" width="100px">
                  </a>
              </div>

                <div class="leftpane">
                <i class="fa-solid fa-magnifying-glass" style="color: white;"></i>
                <a href="cystemalogin.php"><i class="fa-solid fa-user" style="color: #eaecf1;"></i></a>
                <i class="fa-solid fa-cart-shopping" name="carticon" style="color: #e0e0e0;"></i>
                 
                </div>

          </div>
           
        </nav>

        <div class="checkout-sidebar">
        <h2>Checkout</h2>
        <ul class="checkout-list" id="cart-list"></ul>
    </div>




      <div class = "vertical">
</div>

<div class="container">
        <h3>Contact</h3>
      
            <div class="textfield">
                <input type="text" name="name" required><br>
                <span></span>
                <label>Email</label>
            </div>

            <div class="textfield">
                <input type="text" name="name" required><br>
                <span></span>
                <label>Address</label>
            </div>
            
            <div class="textfield">
                <input type="number" name="postalcode" required><br>
                <span></span>    
                <label>Postal Code</label>
            </div>
            
            <div class="textfield">
                <input type="text" namtexte="city" required><br>
                <span></span>
                <label>City</label>
            </div>

            <div class="textfield">
                <input type="text" namtexte="region" required><br>
                <span></span>
                <label>Region</label>
            </div>

            <div class="textfield">
                <input type="number" name="phonenumber" required><br>
                <span></span>    
                <label>Phone Number</label>
            </div>
            
           
            
        </form>
    </div>
    


       

       

      
    </header>
    <footer class= "footer">

    <div class="links">
            <h4>Follow Us</h4>
            <ul class="pics">
                <a href="https://www.facebook.com/profile.php?id=100092460100994&mibextid=LQQJ4d">
                    <img src="fbo.png">
                </a>
                <a href="https://www.instagram.com/adoptapurr__/">
                    <img src="iggo.png" alt border-radius="10px">
                </a>
                <a href="https://twitter.com/AdoptAPurr?"> <img src="twto.png"></a>
            </ul>
        </div>

    </footer>

    <script>
            $(document).ready(function () {
        $(".fa-cart-shopping").on("click", function () {
            $(".checkout-sidebar").toggleClass("show");
        });

        $(".add-to-cart-btn").on("click", function () {
            var productId = $(this).data("product-id");
            var productName = $(this).data("product-name");
            var productPrice = $(this).data("product-price");

            // Send AJAX request to add the item to the cart
            $.ajax({
                url: 'addToCart.php',
                method: 'POST',
                data: {
                    productId: productId,
                    productName: productName,
                    productPrice: productPrice
                },
                success: function (response) {
                    console.log(response);
                },
                error: function (error) {
                    console.error(error);
                }
            });

            var listItem = document.createElement('li');
            listItem.innerHTML = productName + " - ₱" + productPrice + " <button class='remove-from-cart-btn' data-product-id='" + productId + "'>Remove</button>";

            $("#cart-list").append(listItem);
        });

        $("#cart-list").on("click", ".remove-from-cart-btn", function () {
            var productId = $(this).data("product-id");

           
            $.ajax({
                url: 'removecart.php',
                method: 'POST',
                data: {
                    productId: productId
                },
                success: function (response) {
                    console.log(response);
                },
                error: function (error) {
                    console.error(error);
                }
            });

            
            $(this).closest("li").remove();
        });
    });
        </script>

</body>
</html>
