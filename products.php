<?php
session_start();
if (isset($_SESSION["success"])) {
    echo "<div id='successContainer'>
            <div class='successBox'>
                <dotlottie-player 
                    id='lottieAnimation'
                    src='https://lottie.host/39e2d42c-1cbc-4ab3-ac71-a28064ff260a/Ff2BIIUOlw.lottie'
                    background='transparent' 
                    speed='1' 
                    style='width: 80px; height: 80px; margin: 0 auto 20px;'
                    loop 
                    autoplay>
                </dotlottie-player>
                <p>".$_SESSION["success"]."</p>
            </div>
          </div>
          <script>
            setTimeout(() => {
                document.getElementById('lottieAnimation').stop(); // Stop Lottie after 1s
            }, 3000);

            setTimeout(() => {
                document.getElementById('successContainer').classList.add('fadeOut'); // Add fade-out effect
                setTimeout(() => {
                    document.getElementById('successContainer').style.display = 'none'; // Hide after fade-out
                }, 3000); // Extra 0.5s for smooth transition
            }, 3000);
          </script>";
    unset($_SESSION["success"]); // Clear session after displaying
}
?>
  <?php

$conn = new mysqli("localhost", "root", "", "joy_music");

  function getRatingStats($conn, $product_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) as total_reviews, AVG(rating) as avg_rating FROM product_reviews WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $total_reviews = $result['total_reviews'] ?? 0;
    $avg_rating = isset($result['avg_rating']) ? round($result['avg_rating'], 1) : 0;

    return [
        'total_reviews' => $total_reviews,
        'avg_rating' => $avg_rating
    ];
}


?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Products - JMC Music Corner</title>
			<link rel="icon" href="images/favicon.png" type="image/png">

		 <script src="https://lottie.host/39e2d42c-1cbc-4ab3-ac71-a28064ff260a/Ff2BIIUOlw.lottie"></script>
        <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs"
        type="module"></script>
         <link rel="stylesheet" href="styles/tester.css">
        <link rel="stylesheet" href="styles/home.css">
		<link rel="stylesheet" href="styles/nav.css">
		
		
        <!-- Font Awesome CDN for Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
		<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
		



        <script defer src="script.js"></script>
    </head>
    
<body>
	<?php include("./views/includes/header.php"); ?>
    
    


<style>


    



    .products-section {
        max-width: 90%;
        margin: 50px auto;
        position: relative;
        overflow: hidden;
      }



    .products-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 20px;
      }
  
      .product-box {
        flex: 0 0 23%;
        height: 350px;
        background-size: cover;
        background-position: center;
        border-radius: 15px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        background-color: #f8f8f8;
      }
  
      .product-box:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2);
      }
  
      .product-content {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
      }
  
      .product-box:hover .product-content {
        opacity: 1;
      }
  
      .product-content h3,
      .product-content p {
        color: #fff;
        text-align: center;
        margin: 10px 0;
      }
  
      .product-content button {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        background: #2575fc;
        color: #fff;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.3s ease, transform 0.2s ease;
      }
  
      .product-content button:hover {
        background: #6a11cb;
        transform: scale(1.05);
      }



	.star-rating .star:hover,
.star-rating .star.hover,
.star-rating .star.selected {
  color: gold !important;
}

	.btn-star {
    background: transparent;
    border: none;
    font-size: 18px;
    cursor: pointer;
    color: gold;
    padding: 0;
    outline: none;
  }

  .btn-star:hover,
  .btn-star:focus,
  .btn-star:active {
    background: transparent;
    color: gold;
    box-shadow: none;
  }

  .top-right-stars {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 10;
    opacity: 1;
  }

  #ratingModal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    justify-content: center;
    align-items: center;
  }

 .rating-box {
  background: white;
  padding: 30px;
  border-radius: 10px;
  text-align: center;
  width: 600px;         /* match with #emojiPopup */
  max-width: 130vw;      /* keep mobile friendly */
}


  .rating-box h3 {
    margin-bottom: 20px;
  }

  .emoji-feedback {
    font-size: 40px;
    cursor: pointer;
    margin: 10px;
  }

  .emoji-feedback:hover {
    transform: scale(1.1);
  }
	
	@keyframes pop {
  0% { transform: scale(0.5); opacity: 0; }
  60% { transform: scale(1.3); opacity: 1; }
  100% { transform: scale(1); }
}

@keyframes fadeInZoom {
  from { transform: translate(-50%, -50%) scale(0.8); opacity: 0; }
  to { transform: translate(-50%, -50%) scale(1); opacity: 1; }
}
	.hot-pick-badge {
  position: absolute;
  top: 300px;         /* slight distance from the top */
  left: 40px;        /* align to the left edge */
  background-color: #e63946;
  color: white;
  font-weight: bold;
  padding: 5px 15px;
  font-size: 13px;
  text-align: center;
  box-shadow: 0 2px 5px rgba(0,0,0,0.3);
  pointer-events: none;
  z-index: 10;
  border-radius: 10px;
  user-select: none;
}
     .footer {
             background-color: #222;
    color: white;
    padding: 40px 20px;
        }
        .footer h4 {
    color: #2196F3; /* Blue */
    font-weight: 600;
}

/* Ensure rest of the text and links are white */
.footer a,
.footer ul li,
.footer-col li {
    color: white;
    text-decoration: none;
}

.footer a:hover {
    text-decoration: underline;
    color: #90caf9; /* Lighter blue on hover */
}

/* Optional: adjust spacing */
.footer-col {
    margin-bottom: 30px;
}

        .footer-container {
            width: 100%;
        }

        .footer-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

       

        .footer-row:last-child {
            margin-top: 20px; 
        }

     .footer h5 {
    font-size: 14px;
    color: #aaa; /* Optional: a soft gray for footer note */
    font-weight: normal;
    margin-top: 30px;
}


    </style>
  </head>
  <body>
    
	


	  
    <section class="products-section">

      <!-- Headphone Products -->
  <h2>Headphones</h2><br>
 
  <div class="products-container">

    <!-- 1. Your EXISTING static cards -->
	  
    <div class="product-box" data-product-id="STATIC1" style="background-image: url('https://d1b5h9psu9yexj.cloudfront.net/57440/Anker-s-Soundcore-Space-One_20230829-151132_full.jpeg');">
		<div class="top-right-stars">
  <?php
    $static_id = 1001;
    $rating_data = getRatingStats($conn, $static_id);
	$avg_rating = $rating_data['avg_rating'];
			$total_reviews = $rating_data['total_reviews'];
   if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
			
			<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
			
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>

      <div class="product-content">
  
       
        <h3>Noise-Cancelling Headphones</h3>
        <h3>12,000 LKR</h3>
        <p>Immerse yourself in music with active noise cancellation.</p>
        <button class="btn btn-primary"
          onclick="openCartModal('STATIC1', 'Noise-Cancelling Headphones','12 000 LKR','https://d1b5h9psu9yexj.cloudfront.net/57440/Anker-s-Soundcore-Space-One_20230829-151132_full.jpeg')">
          Add to Cart
        </button>
      </div>
    </div>

    <div class="product-box" data-product-id="STATIC2" style="background-image: url('https://celltronics.lk/wp-content/uploads/2024/02/Baseus-AeQur-G10-True-Wireless-Earbuds.jpg');">
		<div class="top-right-stars">
  <?php
    $static_id = 1002;
    $rating_data = getRatingStats($conn, $static_id);
			$avg_rating = $rating_data['avg_rating']; 
			$total_reviews = $rating_data['total_reviews'];
    if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
			
			 <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
			
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>

      <div class="product-content">
 
        <h3>True Wireless Earbuds</h3>
        <h3>8,000 LKR</h3>
        <p>Seamless connectivity with long battery life.</p>
        <button class="btn btn-primary"
          onclick="openCartModal('STATIC2', 'True Wireless Earbuds','8 000 LKR','https://celltronics.lk/wp-content/uploads/2024/02/Baseus-AeQur-G10-True-Wireless-Earbuds.jpg')">
          Add to Cart
        </button>
      </div>
    </div>

    <div class="product-box" data-product-id="STATIC3" style="background-image: url('https://tecroot.lk/wp-content/uploads/2023/03/Pro-10-Over-Ear-Wired-Headphones.jpg');">
		<div class="top-right-stars">
  <?php
    $static_id = 1003;
    $rating_data = getRatingStats($conn, $static_id);
			$avg_rating = $rating_data['avg_rating']; 
			$total_reviews = $rating_data['total_reviews'];
    if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
			
			<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
			
			
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>

      <div class="product-content">

        <h3>Over-Ear Studio Headphones</h3>
        <h3>20,000 LKR</h3>
        <p>High-fidelity audio for professionals.</p>
        <button class="btn btn-primary"
          onclick="openCartModal('STATIC3', 'Over-Ear Studio Headphones','20 000 LKR','https://tecroot.lk/wp-content/uploads/2023/03/Pro-10-Over-Ear-Wired-Headphones.jpg')">
          Add to Cart
        </button>
      </div>
    </div>

    <div class="product-box" data-product-id="STATIC4" style="background-image: url('https://shop.zebronics.com/cdn/shop/products/Zeb-Jet-Pro-pic1.jpg?v=1659157507&width=1200');">
		 <div class="top-right-stars">
  <?php
    $static_id = 1004;
    $rating_data = getRatingStats($conn, $static_id);
			 $avg_rating = $rating_data['avg_rating']; 
			 $total_reviews = $rating_data['total_reviews'];
   if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
			 
			  <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
			 
			 
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>

      <div class="product-content">
      
        <h3>Gaming Headset Pro</h3>
        <h3>25,000 LKR</h3>
        <p>Surround sound with a noise-cancelling mic.</p>
        <button class="btn btn-primary"
          onclick="openCartModal('STATIC4','Gaming Headset Pro','25 000 LKR','https://shop.zebronics.com/cdn/shop/products/Zeb-Jet-Pro-pic1.jpg?v=1659157507&width=1200')">
          Add to Cart
        </button>
      </div>
    </div>

    <!-- 2. DYNAMIC APPEND: loop over new “Headphones” entries in your addproducts table -->
    <?php
// Prepare the query for headphone products
$stmt = $conn->prepare("SELECT * FROM addproducts WHERE category = ?");
$category = "Headphones";
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
	  


// Loop through each headphone product
while ($row = $result->fetch_assoc()):
  $product_id = (int)$row['id'];
  $product_name = htmlspecialchars($row['name']);
  $product_price = htmlspecialchars($row['price']);
  $product_image = htmlspecialchars($row['image_url']);
  $product_description = htmlspecialchars($row['description']);
 
	  $rating_data = getRatingStats($conn, $product_id);
  $total_reviews = $rating_data['total_reviews'];
  $avg_rating = $rating_data['avg_rating'];
?>
	  
	  
  <div class="product-box" data-product-id="<?= $product_id ?>" style="background-image: url('<?= $product_image ?>');">
  <div class="top-right-stars">
	  
    <?php
     if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

    ?>
	  
	 <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
	  
	  
    <button class="btn-star" onclick="openRatingModal(<?= $product_id ?>)">
      <?php
      for ($i = 1; $i <= 5; $i++) {
        if ($i <= $filled) echo '⭐';
        elseif ($half && $i == $filled + 1) echo '🌓';
        else echo '☆';
      }
      echo " (" . $avg_rating . ")";
      ?>
    </button>
  </div>

  <div class="product-content">
    <h3><?= $product_name ?></h3>
    <h3><?= $product_price ?> LKR</h3>

    <p><?= $product_description ?></p>

    <button class="btn btn-primary"
      onclick="openCartModal(
        '<?= $product_id ?>',
        '<?= $product_name ?>',
        '<?= $product_price ?> LKR',
        '<?= $product_image ?>'
      )">
      Add to Cart
    </button>
  </div>
</div>


<?php endwhile; ?>

  </div>
  <br>
        <!-- Speaker Products -->
        <h2>Speakers</h2><br>
        <div class="products-container">
          <div class="product-box" data-product-id="STATIC5" style="background-image: url('https://ptron.in/cdn/shop/files/B0CTR4BXK7.MAIN.jpg?v=1709878984');">
			  <div class="top-right-stars">
  <?php
    $static_id = 1005;
    $rating_data = getRatingStats($conn, $static_id);
				  $avg_rating = $rating_data['avg_rating'];
$total_reviews = $rating_data['total_reviews'];
   if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
				  
				<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
				  
				  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
            <div class="product-content">
			
              <h3>Smart Bluetooth Speaker</h3>
              <h3>7,000 LKR</h3>
              <p>Voice-activated for hands-free control.</p>
             <button class="btn btn-primary" onclick="openCartModal('STATIC5','Smart Bluetooth Speaker', '7000 LKR', 'https://ptron.in/cdn/shop/files/B0CTR4BXK7.MAIN.jpg?v=1709878984')">
  Add to Cart
</button>

            </div>
          </div>
          <div class="product-box" data-product-id="STATIC6" style="background-image: url('https://expertlaois.ie/wp-content/uploads/2024/06/jbl-partybox-encore-or-jblpbencore1micuk__46577.jpg');">
			  <div class="top-right-stars">
  <?php
    $static_id = 1006;
    $rating_data = getRatingStats($conn, $static_id);
				  $avg_rating = $rating_data['avg_rating'];
				  $total_reviews = $rating_data['total_reviews'];
   if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
				  
				<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
				  
				  
				  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
            <div class="product-content">
				

              <h3>Portable Party Speaker</h3>
              <h3>15,000 LKR</h3>
              <p>LED lights and deep bass for parties.</p>
          <button class="btn btn-primary" onclick="openCartModal('STATIC6', 'Portable Party Speaker', '15 000 LKR', 'https://expertlaois.ie/wp-content/uploads/2024/06/jbl-partybox-encore-or-jblpbencore1micuk__46577.jpg')">
  Add to Cart
</button>

            </div>
          </div>
          <div class="product-box" data-product-id="STATIC7" style="background-image: url('https://avechi.co.ke/wp-content/uploads/2021/12/LG-SN8Y-440W-3.1.2-Channel-Sound-bar.jpg');">
			  <div class="top-right-stars">
  <?php
    $static_id = 1007;
    $rating_data = getRatingStats($conn, $static_id);
				  $avg_rating = $rating_data['avg_rating']; 
				  $total_reviews = $rating_data['total_reviews'];
   if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
				  
				  <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
				  
				  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
            <div class="product-content">
				
              <h3>High-Resolution Soundbar</h3>
              <h3>75,000 LKR</h3>
              <p>Ultimate sound experience for your TV.</p>
           <button class="btn btn-primary" onclick="openCartModal('STATIC7',  'High-Resolution Soundbar', '75 000 LKR', 'https://avechi.co.ke/wp-content/uploads/2021/12/LG-SN8Y-440W-3.1.2-Channel-Sound-bar.jpg')">
  Add to Cart
</button>
            </div>
          </div>
          <div class="product-box" data-product-id="STATIC8" style="background-image: url('https://wish.lk/wp-content/uploads/2023/12/Anker-Soundcore-Motion-Boom-Plus-Portable-Waterproof-Outdoor-Speaker.png');">
			  <div class="top-right-stars">
  <?php
    $static_id = 1008;
    $rating_data = getRatingStats($conn, $static_id);
				  $avg_rating = $rating_data['avg_rating']; 
				  $total_reviews = $rating_data['total_reviews'];
  if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
				  
				<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
				  
				  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
            <div class="product-content">
					
              <h3>Waterproof Outdoor Speaker</h3>
              <h3>12,500 LKR</h3>
              <p>Durable design with rich sound.</p>
          <button class="btn btn-primary" onclick="openCartModal('STATIC8', 'Waterproof Outdoor Speaker', '12 500 LKR', 'https://wish.lk/wp-content/uploads/2023/12/Anker-Soundcore-Motion-Boom-Plus-Portable-Waterproof-Outdoor-Speaker.png')">
  Add to Cart
</button>

            </div>
          </div>
			
			 <!-- 2. DYNAMIC APPEND: loop over new “Headphones” entries in your addproducts table -->
    <?php
// Prepare the query for headphone products
$stmt = $conn->prepare("SELECT * FROM addproducts WHERE category = ?");
$category = "Speakers";
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
	  


// Loop through each headphone product
while ($row = $result->fetch_assoc()):
  $product_id = (int)$row['id'];
  $product_name = htmlspecialchars($row['name']);
  $product_price = htmlspecialchars($row['price']);
  $product_image = htmlspecialchars($row['image_url']);
  $product_description = htmlspecialchars($row['description']);
  
	  $rating_data = getRatingStats($conn, $product_id);
  $total_reviews = $rating_data['total_reviews'];
  $avg_rating = $rating_data['avg_rating'];
?>
	  
	  
  <div class="product-box" data-product-id="<?= $product_id ?>" style="background-image: url('<?= $product_image ?>');">
  <div class="top-right-stars">
    <?php
     if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

    ?>
	  
	  <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
	  
	  
    <button class="btn-star" onclick="openRatingModal(<?= $product_id ?>)">
      <?php
      for ($i = 1; $i <= 5; $i++) {
        if ($i <= $filled) echo '⭐';
        elseif ($half && $i == $filled + 1) echo '🌓';
        else echo '☆';
      }
      echo " (" . $avg_rating . ")";
      ?>
    </button>
  </div>

  <div class="product-content">
    <h3><?= $product_name ?></h3>
    <h3><?= $product_price ?> LKR</h3>

    <p><?= $product_description ?></p>

    <button class="btn btn-primary"
      onclick="openCartModal(
        '<?= $product_id ?>',
        '<?= $product_name ?>',
        '<?= $product_price ?> LKR',
        '<?= $product_image ?>'
      )">
      Add to Cart
    </button>
  </div>
</div>

<?php endwhile; ?>

  </div>
  <br>
    
        <!-- Piano Products -->
        <h2>Pianos</h2><br>
        <div class="products-container">
          <div class="product-box" data-product-id="STATIC9" style="background-image: url('https://images-na.ssl-images-amazon.com/images/I/61ZwmI8oNPL.jpg');">
			  <div class="top-right-stars">
  <?php
    $static_id = 1009;
    $rating_data = getRatingStats($conn, $static_id);
				  $avg_rating = $rating_data['avg_rating']; 
				  $total_reviews = $rating_data['total_reviews'];
    if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
				  
				<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
				  
				  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
            <div class="product-content">
				
              <h3>Smart Grand Piano</h3>
              <h3>620,000 LKR</h3>
              <p>Blend tradition with modern features like app connectivity.</p>
               <button class="btn btn-primary" onclick="openCartModal('STATIC9',  'Smart Grand Piano', '620 000 LKR', 'https://images-na.ssl-images-amazon.com/images/I/61ZwmI8oNPL.jpg')">
  Add to Cart
</button>

            </div>
          </div>
       <div class="product-box" data-product-id="STATIC10" style="background-image: url('https://media.musicarts.com/is/image/MMGS7/L47306000000000-01-720x720.jpg');">
  <div class="top-right-stars">
    <?php
      $static_id = 1010;
      $rating_data = getRatingStats($conn, $static_id);
      $avg_rating = $rating_data['avg_rating'];
	  $total_reviews = $rating_data['total_reviews'];
     if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

    ?>

   <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

      <div class="hot-pick-badge">🔥 Hot Pick</div>
    <?php endif; ?>

    <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
      <?php
        for ($i = 1; $i <= 5; $i++) {
          if ($i <= $filled) echo '⭐';
          elseif ($half && $i == $filled + 1) echo '🌓';
          else echo '☆';
        }
        echo " (" . $rating_data['avg_rating'] . ")";
      ?>
    </button>
  </div>

  <div class="product-content">
    <h3>Digital Stage Piano</h3>
    <h3>900,000 LKR</h3>
    <p>Perfect for performances with customizable sounds.</p>
    <button class="btn btn-primary" onclick="openCartModal('STATIC10',  'Digital Stage Piano', '900 000 LKR', 'https://media.musicarts.com/is/image/MMGS7/L47306000000000-01-720x720.jpg')">
      Add to Cart
    </button>
  </div>
</div>

          <div class="product-box" data-product-id="STATIC11" style="background-image: url('https://img.drz.lazcdn.com/static/lk/p/a3f5962d8c1a6a76c47e7e06d3d5ce11.jpg_720x720q80.jpg');">
			  <div class="top-right-stars">
  <?php
    $static_id = 1011;
    $rating_data = getRatingStats($conn, $static_id);
				  $avg_rating = $rating_data['avg_rating'];
				  $total_reviews = $rating_data['total_reviews'];
   if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
				  
				  <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
				  
				  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
            <div class="product-content">
					

              <h3>Beginner's Electric Piano</h3>
              <h3>420,000 LKR</h3>
              <p>Easy to use and ideal for learning.</p>
              <button class="btn btn-primary" onclick="openCartModal('STATIC11',  'Beginners Electric Piano', '420 000 LKR', 'https://img.drz.lazcdn.com/static/lk/p/a3f5962d8c1a6a76c47e7e06d3d5ce11.jpg_720x720q80.jpg')">
  Add to Cart
</button>
            </div>
          </div>
          <div class="product-box" data-product-id="STATIC12" style="background-image: url('https://m.media-amazon.com/images/I/71FobkNjTZL._AC_UF894,1000_QL80_.jpg');">
			  <div class="top-right-stars">
  <?php
    $static_id = 1012;
    $rating_data = getRatingStats($conn, $static_id);
				  $avg_rating = $rating_data['avg_rating']; 
				  $total_reviews = $rating_data['total_reviews'];
  if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
				  
				<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
				  
				  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
            <div class="product-content">
					
              <h3>Compact Digital Piano</h3>
              <h3>270,000 LKR</h3>
              <p>Portable with premium sound quality.</p>
          <button class="btn btn-primary" onclick="openCartModal('STATIC12','Compact Digital Piano', '270 000 LKR', 'https://m.media-amazon.com/images/I/71FobkNjTZL._AC_UF894,1000_QL80_.jpg')">
  Add to Cart
</button>
            </div>
          </div>
			
			 <!-- 2. DYNAMIC APPEND: loop over new “Headphones” entries in your addproducts table -->
    <?php
// Prepare the query for headphone products
$stmt = $conn->prepare("SELECT * FROM addproducts WHERE category = ?");
$category = "Pianos";
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
	  


// Loop through each headphone product
while ($row = $result->fetch_assoc()):
  $product_id = (int)$row['id'];
  $product_name = htmlspecialchars($row['name']);
  $product_price = htmlspecialchars($row['price']);
  $product_image = htmlspecialchars($row['image_url']);
  $product_description = htmlspecialchars($row['description']);
 
	  $rating_data = getRatingStats($conn, $product_id);
  $total_reviews = $rating_data['total_reviews'];
  $avg_rating = $rating_data['avg_rating'];
?>
	  
	  
  <div class="product-box" data-product-id="<?= $product_id ?>" style="background-image: url('<?= $product_image ?>');">
  <div class="top-right-stars">
    <?php
      if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

    ?>
	  
	 <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
	  
	  
    <button class="btn-star" onclick="openRatingModal(<?= $product_id ?>)">
      <?php
      for ($i = 1; $i <= 5; $i++) {
        if ($i <= $filled) echo '⭐';
        elseif ($half && $i == $filled + 1) echo '🌓';
        else echo '☆';
      }
      echo " (" . $avg_rating . ")";
      ?>
    </button>
  </div>

  <div class="product-content">
    <h3><?= $product_name ?></h3>
    <h3><?= $product_price ?> LKR</h3>

    <p><?= $product_description ?></p>

    <button class="btn btn-primary"
      onclick="openCartModal(
        '<?= $product_id ?>',
        '<?= $product_name ?>',
        '<?= $product_price ?> LKR',
        '<?= $product_image ?>'
      )">
      Add to Cart
    </button>
  </div>
</div>

<?php endwhile; ?>

  </div>
  <br>
    
        <!-- Guitar Products -->
        <h2>Guitars</h2><br>
        <div class="products-container">
          <div class="product-box" data-product-id="STATIC13" style="background-image: url('images/g5.jpg');">
			  <div class="top-right-stars">
  <?php
    $static_id = 1013;
    $rating_data = getRatingStats($conn, $static_id);
				  $avg_rating = $rating_data['avg_rating']; 
				  $total_reviews = $rating_data['total_reviews'];
   if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
				  
				<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
				  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
            <div class="product-content">
					

              <h3>Smart Electric Guitar</h3>
              <h3>500,000 LKR</h3>
              <p>Connects to apps for seamless tuning and effects.</p>
         <button class="btn btn-primary" onclick="openCartModal('STATIC13', 'Smart Electric Guitar', '500 000 LKR', 'images/g5.jpg')">
  Add to Cart
</button>
            </div>
          </div>
          <div class="product-box" data-product-id="STATIC14" style="background-image: url('https://images.ctfassets.net/m8onsx4mm13s/4sVZTzSsV03FalcqXa7Pxk/cfad09cccf03ee0a1c633dca28be57b8/GG-632AH_front_side.png');">
			  <div class="top-right-stars">
  <?php
    $static_id = 1014;
    $rating_data = getRatingStats($conn, $static_id);
				  $avg_rating = $rating_data['avg_rating']; 
				  $total_reviews = $rating_data['total_reviews'];
    if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
				  
				<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
				  
				  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
            <div class="product-content">
					
              <h3>Classic Acoustic Guitar</h3>
              <h3>820,000 LKR</h3>
              <p>Rich and full-bodied sound for all musicians.</p>
            <button class="btn btn-primary" onclick="openCartModal('STATIC14',  'Classic Acoustic Guitar', '820 000 LKR', 'https://images.ctfassets.net/m8onsx4mm13s/4sVZTzSsV03FalcqXa7Pxk/cfad09cccf03ee0a1c633dca28be57b8/GG-632AH_front_side.png')">
  Add to Cart
</button>
            </div>
          </div>
          <div class="product-box" data-product-id="STATIC15" style="background-image: url('https://cdn11.bigcommerce.com/s-dks6ju/images/stencil/1280x1280/products/3951/383831/00018NAMM_11__16223.1715917188.jpg?c=2');">
			  <div class="top-right-stars">
  <?php
    $static_id = 1050;
    $rating_data = getRatingStats($conn, $static_id);
				  $avg_rating = $rating_data['avg_rating']; 
				  $total_reviews = $rating_data['total_reviews'];
   if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
				  
				<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
				  
				  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
            <div class="product-content">
				
              <h3>Travel Acoustic Guitar</h3>
              <h3>920,000 LKR</h3>
              <p>Compact design for musicians on the go.</p>
          <button class="btn btn-primary" onclick="openCartModal('STATIC15',  'Travel Acoustic Guitar', '920 000 LKR', 'https://cdn11.bigcommerce.com/s-dks6ju/images/stencil/1280x1280/products/3951/383831/00018NAMM_11__16223.1715917188.jpg?c=2')">
  Add to Cart
</button>

            </div>
          </div>
          <div class="product-box" data-product-id="STATIC16" style="background-image: url('https://img-zuhalmuzik.mncdn.com/mnresize/1000/1000/images/product/0972023200_1.jpg');">
			  <div class="top-right-stars">
  <?php
    $static_id = 1015;
    $rating_data = getRatingStats($conn, $static_id);
				  $avg_rating = $rating_data['avg_rating']; 
				  $total_reviews = $rating_data['total_reviews'];
   if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
				  
				 <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
				  
				  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
            <div class="product-content">
				

              <h3>Hybrid Acoustic-Electric Guitar</h3>
              <h3>720,000 LKR</h3>
              <p>Versatile for studio or stage performances.</p>
          <button class="btn btn-primary" onclick="openCartModal('STATIC16',  'Hybrid Acoustic-Electric Guitar', '720 000 LKR', 'https://img-zuhalmuzik.mncdn.com/mnresize/1000/1000/images/product/0972023200_1.jpg')">
  Add to Cart
</button>

            </div>
          </div>
			
				 <!-- 2. DYNAMIC APPEND: loop over new “Headphones” entries in your addproducts table -->
    <?php
// Prepare the query for headphone products
$stmt = $conn->prepare("SELECT * FROM addproducts WHERE category = ?");
$category = "Guitars";
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
	  


// Loop through each headphone product
while ($row = $result->fetch_assoc()):
  $product_id = (int)$row['id'];
  $product_name = htmlspecialchars($row['name']);
  $product_price = htmlspecialchars($row['price']);
  $product_image = htmlspecialchars($row['image_url']);
  $product_description = htmlspecialchars($row['description']);

	  $rating_data = getRatingStats($conn, $product_id);
  $total_reviews = $rating_data['total_reviews'];
  $avg_rating = $rating_data['avg_rating'];
?>
	  
	  
  <div class="product-box" data-product-id="<?= $product_id ?>" style="background-image: url('<?= $product_image ?>');">
  <div class="top-right-stars">
    <?php
    if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

    ?>
	  
	 <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
	  
	  
    <button class="btn-star" onclick="openRatingModal(<?= $product_id ?>)">
      <?php
      for ($i = 1; $i <= 5; $i++) {
        if ($i <= $filled) echo '⭐';
        elseif ($half && $i == $filled + 1) echo '🌓';
        else echo '☆';
      }
      echo " (" . $avg_rating . ")";
      ?>
    </button>
  </div>

  <div class="product-content">
    <h3><?= $product_name ?></h3>
    <h3><?= $product_price ?> LKR</h3>

    <p><?= $product_description ?></p>

    <button class="btn btn-primary"
      onclick="openCartModal(
        '<?= $product_id ?>',
        '<?= $product_name ?>',
        '<?= $product_price ?> LKR',
        '<?= $product_image ?>'
      )">
      Add to Cart
    </button>
  </div>
</div>

<?php endwhile; ?>

  </div>
  <br>
    
        <!-- Microphone Products -->
        <h2>Microphones</h2><br>
        <div class="products-container">
          <div class="product-box" data-product-id="STATIC17"  style="background-image: url('https://destiny-files.com/image/webp_large/LLA3669_1.webp');">
			  <div class="top-right-stars">
  <?php
    $static_id = 1016;
    $rating_data = getRatingStats($conn, $static_id);
				  $avg_rating = $rating_data['avg_rating'];
				  $total_reviews = $rating_data['total_reviews'];
   if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
				  
				<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
				  
				  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
            <div class="product-content">
					

              <h3>USB Podcast Microphone</h3>
              <h3>20,000 LKR</h3>
              <p>Crystal-clear audio for streamers and podcasters.</p>
           <button class="btn btn-primary" onclick="openCartModal('STATIC17', 'USB Podcast Microphone', '20 000 LKR', 'https://destiny-files.com/image/webp_large/LLA3669_1.webp')">
  Add to Cart
</button>

            </div>
          </div>
          <div class="product-box" data-product-id="STATIC18" style="background-image: url('https://bowerusa.com/cdn/shop/files/WA-LAPMIC_web_1280x1280.jpg?v=1697569302');">
			  <div class="top-right-stars">
  <?php
    $static_id = 1017;
    $rating_data = getRatingStats($conn, $static_id);
				  $avg_rating = $rating_data['avg_rating'];
				  $total_reviews = $rating_data['total_reviews'];
    if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
				  
				 <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
				  
				  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
            <div class="product-content">
			

              <h3>Wireless Lavalier Mic</h3>
              <h3>30,000 LKR</h3>
              <p>Perfect for presentations and mobile filming.</p>
           <button class="btn btn-primary" onclick="openCartModal('STATIC18',  'Wireless Lavalier Mic', '30 000 LKR', 'https://bowerusa.com/cdn/shop/files/WA-LAPMIC_web_1280x1280.jpg?v=1697569302')">
  Add to Cart
</button>

            </div>
          </div>
          <div class="product-box" data-product-id="STATIC19" style="background-image: url('https://nxtleveltech.co.za/wp-content/uploads/2023/05/AKG20P420High-performance20dynamic20instrument20microphone.jpg');">
			  <div class="top-right-stars">
  <?php
    $static_id = 1018;
    $rating_data = getRatingStats($conn, $static_id);
				  $avg_rating = $rating_data['avg_rating']; 
				  $total_reviews = $rating_data['total_reviews'];
   if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
				  
				<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
				  
				  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
            <div class="product-content">
				
              <h3>High-Performance Dynamic Mic</h3>
              <h3>19,000 LKR</h3>
              <p>Robust design with unmatched audio quality.</p>
             <button class="btn btn-primary" onclick="openCartModal('STATIC19',  'High-Performance Dynamic Mic', '19 000 LKR', 'https://nxtleveltech.co.za/wp-content/uploads/2023/05/AKG20P420High-performance20dynamic20instrument20microphone.jpg')">
  Add to Cart
</button>

            </div>

          </div>
          <div class="product-box" data-product-id="STATIC20" style="background-image: url('https://i0.wp.com/rentitem.lk/wp-content/uploads/2019/01/Web-Image-4.jpg?fit=1000%2C1000&ssl=1');">
			  <div class="top-right-stars">
  <?php
    $static_id = 1019;
    $rating_data = getRatingStats($conn, $static_id);
				  $avg_rating = $rating_data['avg_rating'];
				  $total_reviews = $rating_data['total_reviews'];
    if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
				  
				 <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
				  
				  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
            <div class="product-content">
				
              <h3>Studio-Grade Condenser Mic</h3>
              <h3>200,000 LKR</h3>
              <p>Delivers rich vocals for music recording.</p>
              <button class="btn btn-primary" onclick="openCartModal('STATIC20', 'Studio-Grade Condenser Mic', '200 000 LKR', 'https://i0.wp.com/rentitem.lk/wp-content/uploads/2019/01/Web-Image-4.jpg?fit=1000%2C1000&ssl=1')">
  Add to Cart
</button>

            </div>
          </div>
			 <!-- 2. DYNAMIC APPEND: loop over new “Headphones” entries in your addproducts table -->
    <?php
// Prepare the query for headphone products
$stmt = $conn->prepare("SELECT * FROM addproducts WHERE category = ?");
$category = "Microphones";
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
	  


// Loop through each headphone product
while ($row = $result->fetch_assoc()):
  $product_id = (int)$row['id'];
  $product_name = htmlspecialchars($row['name']);
  $product_price = htmlspecialchars($row['price']);
  $product_image = htmlspecialchars($row['image_url']);
  $product_description = htmlspecialchars($row['description']);
  
	  $rating_data = getRatingStats($conn, $product_id);
  $total_reviews = $rating_data['total_reviews'];
  $avg_rating = $rating_data['avg_rating'];
?>
	  
	  
  <div class="product-box" data-product-id="<?= $product_id ?>" style="background-image: url('<?= $product_image ?>');">
  <div class="top-right-stars">
    <?php
      if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

    ?>
	  
	 <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
	  
	  
    <button class="btn-star" onclick="openRatingModal(<?= $product_id ?>)">
      <?php
      for ($i = 1; $i <= 5; $i++) {
        if ($i <= $filled) echo '⭐';
        elseif ($half && $i == $filled + 1) echo '🌓';
        else echo '☆';
      }
      echo " (" . $avg_rating . ")";
      ?>
    </button>
  </div>

  <div class="product-content">
    <h3><?= $product_name ?></h3>
    <h3><?= $product_price ?> LKR</h3>

    <p><?= $product_description ?></p>

    <button class="btn btn-primary"
      onclick="openCartModal(
        '<?= $product_id ?>',
        '<?= $product_name ?>',
        '<?= $product_price ?> LKR',
        '<?= $product_image ?>'
      )">
      Add to Cart
    </button>
  </div>
</div>

<?php endwhile; ?>

  </div>
  <br>
    
        <!-- Electronic Keyboard Products -->
        <h2>Electronic Keyboards</h2><br>
        <div class="products-container">
          <div class="product-box" data-product-id="STATIC21" style="background-image: url('https://cdn.shopaccino.com/procraftindia/products/dmk-619553_l.jpeg?v=523');">
			  <div class="top-right-stars">
  <?php
    $static_id = 1020;
    $rating_data = getRatingStats($conn, $static_id);
				  $avg_rating = $rating_data['avg_rating']; 
				  $total_reviews = $rating_data['total_reviews'];
   if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
				  
				  <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
				  
				  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
            <div class="product-content">
					

              <h3>Compact MIDI Keyboard</h3>
              <h3>320,000 LKR</h3>
              <p>Essential for music producers and DJs.</p>
             <button class="btn btn-primary" onclick="openCartModal('STATIC21', 'Compact MIDI Keyboard', '320 000 LKR', 'https://cdn.shopaccino.com/procraftindia/products/dmk-619553_l.jpeg?v=523')">
  Add to Cart
</button>

            </div>
          </div>
          <div class="product-box" data-product-id="STATIC22" style="background-image: url('https://www.konixmus.com/wp-content/uploads/2022/11/1-slt.jpg');">
			  <div class="top-right-stars">
  <?php
    $static_id = 1021;
    $rating_data = getRatingStats($conn, $static_id);
				  $avg_rating = $rating_data['avg_rating']; 
				  $total_reviews = $rating_data['total_reviews'];
    if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
				  
				<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
				  
				  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
            <div class="product-content">
				

              <h3>Smart Digital Keyboard</h3>
              <h3>700,000 LKR</h3>
              <p>Features customizable effects and virtual lessons.</p>
             <button class="btn btn-primary" onclick="openCartModal('STATIC22',  'Smart Digital Keyboard', '700 000 LKR', 'https://www.konixmus.com/wp-content/uploads/2022/11/1-slt.jpg')">
  Add to Cart
</button>

            </div>
          </div>
          <div class="product-box" data-product-id="STATIC23" style="background-image: url('https://cdn11.bigcommerce.com/s-3by9a48r25/images/stencil/1280x1280/products/9950/17905/roland_go_piano_angle__01807.1734547365.jpg?c=1');">
			  <div class="top-right-stars">
  <?php
    $static_id = 1022;
    $rating_data = getRatingStats($conn, $static_id);
				  $avg_rating = $rating_data['avg_rating']; 
				  $total_reviews = $rating_data['total_reviews'];
   if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
				  
				  <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
				  
				  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
            <div class="product-content">
					

              <h3>Performance Digital Piano</h3>
              <h3>600,000 LKR</h3>
              <p>Realistic feel with authentic grand piano sounds.</p>
            <button class="btn btn-primary" onclick="openCartModal('STATIC23', 'Performance Digital Piano', '600 000 LKR', 'https://cdn11.bigcommerce.com/s-3by9a48r25/images/stencil/1280x1280/products/9950/17905/roland_go_piano_angle__01807.1734547365.jpg?c=1')">
  Add to Cart
</button>

            </div>
          </div>
          <div class="product-box" data-product-id="STATIC24" style="background-image: url('https://www.sensorytoywarehouse.com/wp-content/uploads/cm/catalog/products/playlearn/SKBKeyboard1copy.jpg');">
			  <div class="top-right-stars">
  <?php
    $static_id = 1023;
    $rating_data = getRatingStats($conn, $static_id);
				  $avg_rating = $rating_data['avg_rating'];
				  $total_reviews = $rating_data['total_reviews'];
   if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
				  
				 <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
				  
				  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
            <div class="product-content">
				

              <h3>Educational Keyboard</h3>
              <h3>3,000 LKR</h3>
              <p>Interactive features for beginners to learn.</p>
             <button class="btn btn-primary" onclick="openCartModal('STATIC24',  'Educational Keyboard', '3000 LKR', 'https://www.sensorytoywarehouse.com/wp-content/uploads/cm/catalog/products/playlearn/SKBKeyboard1copy.jpg')">
  Add to Cart
</button>

            </div>
          </div>
			
			 <!-- 2. DYNAMIC APPEND: loop over new “Headphones” entries in your addproducts table -->
    <?php
// Prepare the query for headphone products
$stmt = $conn->prepare("SELECT * FROM addproducts WHERE category = ?");
$category = "Electronic Keyboards";
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
	  


// Loop through each headphone product
while ($row = $result->fetch_assoc()):
  $product_id = (int)$row['id'];
  $product_name = htmlspecialchars($row['name']);
  $product_price = htmlspecialchars($row['price']);
  $product_image = htmlspecialchars($row['image_url']);
  $product_description = htmlspecialchars($row['description']);

	  $rating_data = getRatingStats($conn, $product_id);
  $total_reviews = $rating_data['total_reviews'];
  $avg_rating = $rating_data['avg_rating'];
?>
	  
	  
  <div class="product-box" data-product-id="<?= $product_id ?>" style="background-image: url('<?= $product_image ?>');">
  <div class="top-right-stars">
    <?php
      if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

    ?>
	  
	<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
	  
	  
    <button class="btn-star" onclick="openRatingModal(<?= $product_id ?>)">
      <?php
      for ($i = 1; $i <= 5; $i++) {
        if ($i <= $filled) echo '⭐';
        elseif ($half && $i == $filled + 1) echo '🌓';
        else echo '☆';
      }
      echo " (" . $avg_rating . ")";
      ?>
    </button>
  </div>

  <div class="product-content">
    <h3><?= $product_name ?></h3>
    <h3><?= $product_price ?> LKR</h3>

    <p><?= $product_description ?></p>

    <button class="btn btn-primary"
      onclick="openCartModal(
        '<?= $product_id ?>',
        '<?= $product_name ?>',
        '<?= $product_price ?> LKR',
        '<?= $product_image ?>'
      )">
      Add to Cart
    </button>
  </div>
</div>

<?php endwhile; ?>

  </div>
  <br>
        <h2>Drums</h2> <br>
<div class="products-container">
  <div class="product-box" data-product-id="STATIC25" style="background-image: url('https://th.bing.com/th/id/OIP.6jGKtasQs-sK_S1rAmjyvAHaFM?rs=1&pid=ImgDetMain');">
	  <div class="top-right-stars">
  <?php
    $static_id = 1024;
    $rating_data = getRatingStats($conn, $static_id);
		  $avg_rating = $rating_data['avg_rating']; 
		  $total_reviews = $rating_data['total_reviews'];
    if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
		  
		<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
		  
		  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
    <div class="product-content">
	

      <h3>Acoustic Drum Set</h3>
      <h3>150,000 LKR</h3>
      <p>Perfect for live performances with premium sound quality.</p>
   <button class="btn btn-primary" onclick="openCartModal('STATIC25', 'Acoustic Drum Set', '150 000 LKR', 'https://th.bing.com/th/id/OIP.6jGKtasQs-sK_S1rAmjyvAHaFM?rs=1&pid=ImgDetMain')">
  Add to Cart
</button>

    </div>
  </div>
  <div class="product-box" data-product-id="STATIC26" style="background-image: url('https://www.orchestramegastore.com/media/catalog/product/cache/1/image/1200x/9df78eab33525d08d6e5fb8d27136e95/t/d/td-1kpx2_f_gal.jpg');">
	  <div class="top-right-stars">
  <?php
    $static_id = 1025;
    $rating_data = getRatingStats($conn, $static_id);
		  $avg_rating = $rating_data['avg_rating']; 
		  $total_reviews = $rating_data['total_reviews'];
  if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
		  
		 <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
		  
		  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
    <div class="product-content">
		
      <h3>Portable Digital Drum Kit</h3>
      <h3>40,000 LKR</h3>
      <p>Compact and versatile, ideal for home practice or travel.</p>
     <button class="btn btn-primary" onclick="openCartModal('STATIC26',  'Portable Digital Drum Kit', '40 000 LKR', 'https://www.orchestramegastore.com/media/catalog/product/cache/1/image/1200x/9df78eab33525d08d6e5fb8d27136e95/t/d/td-1kpx2_f_gal.jpg')">
  Add to Cart
</button>

    </div>
  </div>
  <div class="product-box" data-product-id="STATIC27" style="background-image: url('https://th.bing.com/th/id/OIP.oqVf4duhoBdtn2aqlFJh0gAAAA?w=178&h=184&c=7&r=0&o=7&pid=1.7&rm=3');">
	  <div class="top-right-stars">
  <?php
    $static_id = 1026;
    $rating_data = getRatingStats($conn, $static_id);
		  $avg_rating = $rating_data['avg_rating']; 
		  $total_reviews = $rating_data['total_reviews'];
    if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
		 <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
		  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
    <div class="product-content">
		

      <h3>Acrylic Cocktail Drum Set</h3>
      <h3>100,000 LKR</h3>
      <p>Stylish and sleek drums with crystal-clear sound.</p>
     <button class="btn btn-primary" onclick="openCartModal('STATIC27', 'Acrylic Cocktail Drum Set', '100 000 LKR', 'https://th.bing.com/th/id/OIP.oqVf4duhoBdtn2aqlFJh0gAAAA?w=178&h=184&c=7&r=0&o=7&pid=1.7&rm=3')">
  Add to Cart
</button>

    </div>
  </div>
  <div class="product-box" data-product-id="STATIC28" style="background-image: url('https://th.bing.com/th/id/OIP.BwVhDBGXINMPya1dj9o4cAHaIf?w=167&h=191&c=7&r=0&o=7&pid=1.7&rm=3');">
	  <div class="top-right-stars">
  <?php
    $static_id = 1027;
    $rating_data = getRatingStats($conn, $static_id);
		  $avg_rating = $rating_data['avg_rating']; 
		  $total_reviews = $rating_data['total_reviews'];
  if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
		 <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
		  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
    <div class="product-content">
		

      <h3>Wooden Cajon Drum</h3>
      <h3>25,000 LKR</h3>
      <p>Lightweight percussion instrument with adjustable snare.</p>
      <button class="btn btn-primary" onclick="openCartModal('STATIC28',  'Wooden Cajon Drum', '25 000 LKR', 'https://th.bing.com/th/id/OIP.BwVhDBGXINMPya1dj9o4cAHaIf?w=167&h=191&c=7&r=0&o=7&pid=1.7&rm=3')">
  Add to Cart
</button>


    </div>
  </div>
	
	 <!-- 2. DYNAMIC APPEND: loop over new “Headphones” entries in your addproducts table -->
    <?php
// Prepare the query for headphone products
$stmt = $conn->prepare("SELECT * FROM addproducts WHERE category = ?");
$category = "Drums";
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
	  


// Loop through each headphone product
while ($row = $result->fetch_assoc()):
  $product_id = (int)$row['id'];
  $product_name = htmlspecialchars($row['name']);
  $product_price = htmlspecialchars($row['price']);
  $product_image = htmlspecialchars($row['image_url']);
  $product_description = htmlspecialchars($row['description']);
  
	  $rating_data = getRatingStats($conn, $product_id);
  $total_reviews = $rating_data['total_reviews'];
  $avg_rating = $rating_data['avg_rating'];
?>
	  
	  <div class="rating-summary-container" style="text-align:center; margin-bottom: 5px; color: #333; font-weight: 600;">
  
  <div style="font-size: 14px; color: #555;">
    (<?= $total_reviews ?> reviews) — <?= $avg_rating ?>/5 avg
  </div>
</div>
  <div class="product-box" data-product-id="<?= $product_id ?>" style="background-image: url('<?= $product_image ?>');">
  <div class="top-right-stars">
    <?php
      if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

    ?>
	  
	<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
	  
	  
    <button class="btn-star" onclick="openRatingModal(<?= $product_id ?>)">
      <?php
      for ($i = 1; $i <= 5; $i++) {
        if ($i <= $filled) echo '⭐';
        elseif ($half && $i == $filled + 1) echo '🌓';
        else echo '☆';
      }
      echo " (" . $avg_rating . ")";
      ?>
    </button>
  </div>

  <div class="product-content">
    <h3><?= $product_name ?></h3>
    <h3><?= $product_price ?> LKR</h3>

    <p><?= $product_description ?></p>

    <button class="btn btn-primary"
      onclick="openCartModal(
        '<?= $product_id ?>',
        '<?= $product_name ?>',
        '<?= $product_price ?> LKR',
        '<?= $product_image ?>'
      )">
      Add to Cart
    </button>
  </div>
</div>

<?php endwhile; ?>

  </div>
  <br>

<h2>Sound Mixers</h2> <br>
<div class="products-container">
  <div class="product-box" data-product-id="STATIC29" style="background-image: url('https://i0.wp.com/rentitem.lk/wp-content/uploads/2019/01/1Web-Image03.jpg?fit=1000%2C1000&ssl=1');">
	  <div class="top-right-stars">
  <?php
    $static_id = 1028;
    $rating_data = getRatingStats($conn, $static_id);
		  $avg_rating = $rating_data['avg_rating'];
		  $total_reviews = $rating_data['total_reviews'];
    if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
		  
		 <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
		  
		  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
    <div class="product-content">
			

      <h3>Analog Audio Mixer</h3>
      <h3>75,000 LKR</h3>
      <p>Perfect for live gigs with smooth mixing and built-in effects.</p>
     <button class="btn btn-primary" onclick="openCartModal('STATIC29', 'Analog Audio Mixer', '75 000 LKR', 'https://i0.wp.com/rentitem.lk/wp-content/uploads/2019/01/1Web-Image03.jpg?fit=1000%2C1000&ssl=1')">
  Add to Cart
</button>

    </div>
  </div>
  <div class="product-box" data-product-id="STATIC30" style="background-image: url('https://www.bashsmusic.com.au/wp-content/uploads/FLOW8_1.webp');">
	  <div class="top-right-stars">
  <?php
    $static_id = 1029;
    $rating_data = getRatingStats($conn, $static_id);
		  $avg_rating = $rating_data['avg_rating'];
		  $total_reviews = $rating_data['total_reviews'];
    if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
		  
		 <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
		  
		  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
    <div class="product-content">
		

      <h3>Digital Mixer with Bluetooth</h3>
      <h3>120,000 LKR</h3>
      <p>Feature-packed with Bluetooth and high-quality digital audio.</p>
        <button class="btn btn-primary" onclick="openCartModal('STATIC30', 'Digital Mixer with Bluetooth', '120 000 LKR', 'https://www.bashsmusic.com.au/wp-content/uploads/FLOW8_1.webp')">
  Add to Cart
</button>

    </div>
  </div>
  <div class="product-box" data-product-id="STATIC31" style="background-image: url('https://rangashopping.lk/wp-content/uploads/2024/12/MCP0274B_1-1.webp');">
	  <div class="top-right-stars">
  <?php
    $static_id = 1030;
    $rating_data = getRatingStats($conn, $static_id);
		  $avg_rating = $rating_data['avg_rating']; 
		  $total_reviews = $rating_data['total_reviews'];
   if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
		  
		<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
		  
		  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
    <div class="product-content">
			

      <h3>Compact 6-Channel Mixer</h3>
      <h3>55,000 LKR</h3>
      <p>Lightweight and portable, ideal for smaller setups.</p>
        <button class="btn btn-primary" onclick="openCartModal('STATIC31', 'Compact 6-Channel Mixer', '55 000 LKR', 'https://rangashopping.lk/wp-content/uploads/2024/12/MCP0274B_1-1.webp')">
  Add to Cart
</button>
    </div>
  </div>
  <div class="product-box" data-product-id="STATIC32" style="background-image: url('https://media.musicarts.com/is/image/MMGS7/L69788000000000-00-720x720.jpg');">
	  <div class="top-right-stars">
  <?php
    $static_id = 1031;
    $rating_data = getRatingStats($conn, $static_id);
		  $avg_rating = $rating_data['avg_rating']; 
		  $total_reviews = $rating_data['total_reviews'];
   if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
		  
		<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
		  
		  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
    <div class="product-content">
	
      <h3>Professional Mixer with Effects</h3>
      <h3>85,000 LKR</h3>
      <p>Integrated effects and seamless control for pro audio mixing.</p>
       <button class="btn btn-primary" onclick="openCartModal('STATIC32',  'Professional Mixer with Effects', '85 000 LKR', 'https://media.musicarts.com/is/image/MMGS7/L69788000000000-00-720x720.jpg')">
  Add to Cart
</button>

    </div>
  </div>
	
		 <!-- 2. DYNAMIC APPEND: loop over new “Headphones” entries in your addproducts table -->
    <?php
// Prepare the query for headphone products
$stmt = $conn->prepare("SELECT * FROM addproducts WHERE category = ?");
$category = "Sound Mixers";
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
	  


// Loop through each headphone product
while ($row = $result->fetch_assoc()):
  $product_id = (int)$row['id'];
  $product_name = htmlspecialchars($row['name']);
  $product_price = htmlspecialchars($row['price']);
  $product_image = htmlspecialchars($row['image_url']);
  $product_description = htmlspecialchars($row['description']);
  
	  $rating_data = getRatingStats($conn, $product_id);
  $total_reviews = $rating_data['total_reviews'];
  $avg_rating = $rating_data['avg_rating'];
?>
	  
	  
  <div class="product-box" data-product-id="<?= $product_id ?>" style="background-image: url('<?= $product_image ?>');">
  <div class="top-right-stars">
    <?php
      if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

    ?>
	  
	 <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
	  
	  
    <button class="btn-star" onclick="openRatingModal(<?= $product_id ?>)">
      <?php
      for ($i = 1; $i <= 5; $i++) {
        if ($i <= $filled) echo '⭐';
        elseif ($half && $i == $filled + 1) echo '🌓';
        else echo '☆';
      }
      echo " (" . $avg_rating . ")";
      ?>
    </button>
  </div>

  <div class="product-content">
    <h3><?= $product_name ?></h3>
    <h3><?= $product_price ?> LKR</h3>

    <p><?= $product_description ?></p>

    <button class="btn btn-primary"
      onclick="openCartModal(
        '<?= $product_id ?>',
        '<?= $product_name ?>',
        '<?= $product_price ?> LKR',
        '<?= $product_image ?>'
      )">
      Add to Cart
    </button>
  </div>
</div>

<?php endwhile; ?>

  </div>
  <br>

<h2>Trumpets</h2> <br>
<div class="products-container">
  <div class="product-box" data-product-id="STATIC33" style="background-image: url('https://rvb-img.reverb.com/image/upload/s--CJaaiGM6--/t_large-square/v1589578509/cg7l03t0vwrnzkvz8r53.jpg');">
	  <div class="top-right-stars">
  <?php
    $static_id = 1032;
    $rating_data = getRatingStats($conn, $static_id);
		  $avg_rating = $rating_data['avg_rating'];
		  $total_reviews = $rating_data['total_reviews'];
  if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
		  
		 <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
		  
		  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
    <div class="product-content">
			

      <h3>Proline Trumpet</h3>
      <h3>70,000 LKR</h3>
      <p>Professional trumpet with a gold lacquer finish.</p>
     <button class="btn btn-primary" onclick="openCartModal('STATIC33',  'Proline Trumpet', '70 000 LKR', 'https://rvb-img.reverb.com/image/upload/s--CJaaiGM6--/t_large-square/v1589578509/cg7l03t0vwrnzkvz8r53.jpg')">
  Add to Cart
</button>

    </div>
  </div>
  <div class="product-box" data-product-id="STATIC34" style="background-image: url('https://th.bing.com/th/id/OIP.TSf5vSiQKPP1RtxNpWuerQHaE8?w=268&h=180&c=7&r=0&o=7&pid=1.7&rm=3');">
	  <div class="top-right-stars">
  <?php
    $static_id = 1033;
    $rating_data = getRatingStats($conn, $static_id);
		  $avg_rating = $rating_data['avg_rating']; 
		  $total_reviews = $rating_data['total_reviews'];
   if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
		  
		  <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
		  
		  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
    <div class="product-content">
		

      <h3>Silver Jazz Trumpet</h3>
      <h3>85,000 LKR</h3>
      <p>Elegant silver trumpet designed for jazz performances.</p>
    <button class="btn btn-primary" onclick="openCartModal('STATIC34',  'Silver Jazz Trumpet', '85 000 LKR', 'https://th.bing.com/th/id/OIP.TSf5vSiQKPP1RtxNpWuerQHaE8?w=268&h=180&c=7&r=0&o=7&pid=1.7&rm=3')">
  Add to Cart
</button>
    </div>
  </div>
  <div class="product-box" data-product-id="STATIC35" style="background-image: url('https://teds-list.com/wp-content/uploads/2021/01/Jupiter-JTR700Q-Bb-Trumpet-Lacquer3.jpg');">
	  <div class="top-right-stars">
  <?php
    $static_id = 1034;
    $rating_data = getRatingStats($conn, $static_id);
		  $avg_rating = $rating_data['avg_rating'];
		  $total_reviews = $rating_data['total_reviews'];
    if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
		  
		  <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
		  
		  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
    <div class="product-content">
		
      <h3>Beginner's Trumpet</h3>
      <h3>40,000 LKR</h3>
   <button class="btn btn-primary" onclick="openCartModal('STATIC35',  'Beginners Trumpet', '40 000 LKR', 'https://teds-list.com/wp-content/uploads/2021/01/Jupiter-JTR700Q-Bb-Trumpet-Lacquer3.jpg')">
  Add to Cart
</button>


    </div>
  </div>
  <div class="product-box" data-product-id="STATIC36" style="background-image: url('https://m.media-amazon.com/images/I/51cvtihawxL.jpg');">
	  <div class="top-right-stars">
  <?php
    $static_id = 1035;
    $rating_data = getRatingStats($conn, $static_id);
		  $avg_rating = $rating_data['avg_rating'];
		  $total_reviews = $rating_data['total_reviews'];
    if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
		  
		  <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
		  
		  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
    <div class="product-content">
		
      <h3>Orchestra Performance Trumpet</h3>
      <h3>150,000 LKR</h3>
      <p>Premium-grade trumpet for orchestral performances.</p>
 <button class="btn btn-primary" onclick="openCartModal('STATIC36', 'Orchestra Performance Trumpet', '150 000 LKR', 'https://m.media-amazon.com/images/I/51cvtihawxL.jpg')">
  Add to Cart
</button>

    </div>
  </div>
	
		 <!-- 2. DYNAMIC APPEND: loop over new “Headphones” entries in your addproducts table -->
    <?php
// Prepare the query for headphone products
$stmt = $conn->prepare("SELECT * FROM addproducts WHERE category = ?");
$category = "Trumpets";
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
	  


// Loop through each headphone product
while ($row = $result->fetch_assoc()):
  $product_id = (int)$row['id'];
  $product_name = htmlspecialchars($row['name']);
  $product_price = htmlspecialchars($row['price']);
  $product_image = htmlspecialchars($row['image_url']);
  $product_description = htmlspecialchars($row['description']);
  
	  $rating_data = getRatingStats($conn, $product_id);
	$avg_rating = $rating_data['avg_rating']; 
  $total_reviews = $rating_data['total_reviews'];
  $avg_rating = $rating_data['avg_rating'];
?>
	  
	  
  <div class="product-box" data-product-id="<?= $product_id ?>" style="background-image: url('<?= $product_image ?>');">
  <div class="top-right-stars">
    <?php
      if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

    ?>
	  
	  <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
	  
	  
    <button class="btn-star" onclick="openRatingModal(<?= $product_id ?>)">
      <?php
      for ($i = 1; $i <= 5; $i++) {
        if ($i <= $filled) echo '⭐';
        elseif ($half && $i == $filled + 1) echo '🌓';
        else echo '☆';
      }
      echo " (" . $avg_rating . ")";
      ?>
    </button>
  </div>

  <div class="product-content">
    <h3><?= $product_name ?></h3>
    <h3><?= $product_price ?> LKR</h3>

    <p><?= $product_description ?></p>

    <button class="btn btn-primary"
      onclick="openCartModal(
        '<?= $product_id ?>',
        '<?= $product_name ?>',
        '<?= $product_price ?> LKR',
        '<?= $product_image ?>'
      )">
      Add to Cart
    </button>
  </div>
</div>

<?php endwhile; ?>

  </div>
  <br>

<h2>Violins</h2> <br>
<div class="products-container">
  <div class="product-box" data-product-id="STATIC37" style="background-image: url('https://thumbs.dreamstime.com/b/violin-viola-bowed-musical-instrument-classical-symphony-orchestra-realistic-drawing-isolated-image-white-background-violin-235458524.jpg');">
	  <div class="top-right-stars">
  <?php
    $static_id = 1036;
    $rating_data = getRatingStats($conn, $static_id);
		  $avg_rating = $rating_data['avg_rating'];
		  $total_reviews = $rating_data['total_reviews'];
    if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
		  
		<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
		  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
    <div class="product-content">
			

      <h3>Beginner Violin</h3>
      <h3>40,000 LKR</h3>
      <p>Perfect for students, lightweight and easy to use.</p>
      <button class="btn btn-primary" onclick="openCartModal('STATIC37', 'Beginner Violin', '40 000 LKR', 'https://thumbs.dreamstime.com/b/violin-viola-bowed-musical-instrument-classical-symphony-orchestra-realistic-drawing-isolated-image-white-background-violin-235458524.jpg')">
  Add to Cart
</button>

    </div>
  </div>
  <div class="product-box" data-product-id="STATIC38" style="background-image: url('https://luisandclark.com/wp-content/uploads/2012/01/LuisandClarkVIOLIN_2K.jpg');">
	  <div class="top-right-stars">
  <?php
    $static_id = 1037;
    $rating_data = getRatingStats($conn, $static_id);
		  $avg_rating = $rating_data['avg_rating']; 
		  $total_reviews = $rating_data['total_reviews'];
  if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
		  
		  <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
		  
		  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
    <div class="product-content">
			
      <h3>Acoustic Violin</h3>
      <h3>80,000 LKR</h3>
      <p>Classic design with excellent sound quality for performers.</p>
        <button class="btn btn-primary" onclick="openCartModal('STATIC38', 'Acoustic Violin', '80 000 LKR', 'https://luisandclark.com/wp-content/uploads/2012/01/LuisandClarkVIOLIN_2K.jpg')">
  Add to Cart
</button>

    </div>
  </div>
  <div class="product-box" data-product-id="STATIC39" style="background-image: url('https://th.bing.com/th/id/OIP.9d8IbfXayqhqFbGUtH0FWgHaEL?w=298&h=180&c=7&r=0&o=7&pid=1.7&rm=3');">
	  <div class="top-right-stars">
  <?php
    $static_id = 1038;
    $rating_data = getRatingStats($conn, $static_id);
		  $avg_rating = $rating_data['avg_rating'];
		  $total_reviews = $rating_data['total_reviews'];
  if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
		  
		  <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
		  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
    <div class="product-content">
	

      <h3>Electric Violin</h3>
      <h3>120,000 LKR</h3>
      <p>Sleek, modern electric violin for versatile performances.</p>
      <button class="btn btn-primary" onclick="openCartModal('STATIC39',  'Electric Violin', '120 000 LKR', 'https://th.bing.com/th/id/OIP.9d8IbfXayqhqFbGUtH0FWgHaEL?w=298&h=180&c=7&r=0&o=7&pid=1.7&rm=3')">
  Add to Cart
</button>

    </div>
  </div>
  <div class="product-box" data-product-id="STATIC40" style="background-image: url('https://5.imimg.com/data5/UD/OI/MY-45334370/professional-musical-wood-violin.jpg');">
	  <div class="top-right-stars">
  <?php
    $static_id = 1039;
    $rating_data = getRatingStats($conn, $static_id);
		  $avg_rating = $rating_data['avg_rating'];
		  $total_reviews = $rating_data['total_reviews'];
   if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
		  
		<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
		  
		  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
    <div class="product-content">
		

      <h3>Professional Violin</h3>
      <h3>200,000 LKR</h3>
      <p>Handcrafted masterpiece for professional musicians.</p>
      <button class="btn btn-primary" onclick="openCartModal('STATIC40', 'Professional Violin', '200 000 LKR', 'https://5.imimg.com/data5/UD/OI/MY-45334370/professional-musical-wood-violin.jpg')">
  Add to Cart
</button>

    </div>
  </div>
	
		 <!-- 2. DYNAMIC APPEND: loop over new “Headphones” entries in your addproducts table -->
    <?php
// Prepare the query for headphone products
$stmt = $conn->prepare("SELECT * FROM addproducts WHERE category = ?");
$category = "Violins";
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
	  


// Loop through each headphone product
while ($row = $result->fetch_assoc()):
  $product_id = (int)$row['id'];
  $product_name = htmlspecialchars($row['name']);
  $product_price = htmlspecialchars($row['price']);
  $product_image = htmlspecialchars($row['image_url']);
  $product_description = htmlspecialchars($row['description']);

	  $rating_data = getRatingStats($conn, $product_id);
  $total_reviews = $rating_data['total_reviews'];
  $avg_rating = $rating_data['avg_rating'];
?>
	  
	  
  <div class="product-box" data-product-id="<?= $product_id ?>" style="background-image: url('<?= $product_image ?>');">
  <div class="top-right-stars">
    <?php
    if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

    ?>
	  
	<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
	  
    <button class="btn-star" onclick="openRatingModal(<?= $product_id ?>)">
      <?php
      for ($i = 1; $i <= 5; $i++) {
        if ($i <= $filled) echo '⭐';
        elseif ($half && $i == $filled + 1) echo '🌓';
        else echo '☆';
      }
      echo " (" . $avg_rating . ")";
      ?>
    </button>
  </div>

  <div class="product-content">
    <h3><?= $product_name ?></h3>
    <h3><?= $product_price ?> LKR</h3>

    <p><?= $product_description ?></p>

    <button class="btn btn-primary"
      onclick="openCartModal(
        '<?= $product_id ?>',
        '<?= $product_name ?>',
        '<?= $product_price ?> LKR',
        '<?= $product_image ?>'
      )">
      Add to Cart
    </button>
  </div>
</div>


<?php endwhile; ?>

  </div>
  <br>
<h2>Traditional Instruments</h2> <br>
<div class="products-container">

  <div class="product-box" data-product-id="STATIC41" style="background-image: url('https://r2.gear4music.com/media/106/1066581/600/preview.jpg');">
	  <div class="top-right-stars">
  <?php
    $static_id = 1040;
    $rating_data = getRatingStats($conn, $static_id);
		  $avg_rating = $rating_data['avg_rating'];
		  $total_reviews = $rating_data['total_reviews'];
   if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
		  
		<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
		  
		  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
    <div class="product-content">
		
      <h3>Tabla</h3>
      <h3>25,000 LKR</h3>
      <p>Classical percussion instrument for Indian music.</p>
      <button class="btn btn-primary" onclick="openCartModal('STATIC41', 'Tabla', '25 000 LKR', 'https://r2.gear4music.com/media/106/1066581/600/preview.jpg')">
  Add to Cart
</button>

    </div>
  </div>
  <div class="product-box" data-product-id="STATIC42" style="background-image: url('https://i.pinimg.com/564x/18/36/c4/1836c43331de411e061370d89c7b4d6d.jpg');">
	  <div class="top-right-stars">
  <?php
    $static_id = 1041;
    $rating_data = getRatingStats($conn, $static_id);
		  $avg_rating = $rating_data['avg_rating']; 
		  $total_reviews = $rating_data['total_reviews'];
    if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
		  
		<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
		  
		  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
    <div class="product-content">
		
		

      <h3>Sitar</h3>
      <h3>60,000 LKR</h3>
      <p>Elegant string instrument with a mesmerizing tone.</p>
      <button class="btn btn-primary" onclick="openCartModal('STATIC42', 'Sitar', '60 000 LKR', 'https://i.pinimg.com/564x/18/36/c4/1836c43331de411e061370d89c7b4d6d.jpg')">
  Add to Cart
</button>

    </div>
  </div>
  <div class="product-box" data-product-id="STATIC43" style="background-image: url('https://karumusic.lk/wp-content/uploads/2023/08/455.jpg');">
	  <div class="top-right-stars">
  <?php
    $static_id = 1042;
    $rating_data = getRatingStats($conn, $static_id);
		  $avg_rating = $rating_data['avg_rating']; 
		  $total_reviews = $rating_data['total_reviews'];
   if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
		  
		<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
		  
		  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
    <div class="product-content">
			

      <h3>Djembe</h3>
      <h3>35,000 LKR</h3>
      <p>Traditional African drum with rich, resonating sound.</p>
     <button class="btn btn-primary" onclick="openCartModal('STATIC43', 'Djembe', '35 000 LKR', 'https://karumusic.lk/wp-content/uploads/2023/08/455.jpg')">
  Add to Cart
</button>

    </div>
  </div>
  <div class="product-box" data-product-id="STATIC44" style="background-image: url('https://dagnamusic.com/wp-content/uploads/2024/05/harmonium-12500-1-scaled.jpg');">
	  <div class="top-right-stars">
  <?php
    $static_id = 1043;
    $rating_data = getRatingStats($conn, $static_id);
		  $avg_rating = $rating_data['avg_rating'];
		  $total_reviews = $rating_data['total_reviews'];
  if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

  ?>
		  
		<?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
		  
		  
  <button class="btn-star" onclick="openRatingModal(<?= $static_id ?>)">
    <?php
    for ($i = 1; $i <= 5; $i++) {
      if ($i <= $filled) echo '⭐';
      elseif ($half && $i == $filled + 1) echo '🌓';
      else echo '☆';
    }
    echo " (" . $rating_data['avg_rating'] . ")";
    ?>
  </button>
</div>
    <div class="product-content">
	

      <h3>Harmonium</h3>
      <h3>50,000 LKR</h3>
      <p>Portable keyboard instrument for devotional and classical music.</p>
     <button class="btn btn-primary" onclick="openCartModal('STATIC44', 'Harmonium', '50 000 LKR', 'https://dagnamusic.com/wp-content/uploads/2024/05/harmonium-12500-1-scaled.jpg')">
  Add to Cart
</button>


    </div>
  </div>
	
		 <!-- 2. DYNAMIC APPEND: loop over new “Headphones” entries in your addproducts table -->
    <?php
// Prepare the query for headphone products
$stmt = $conn->prepare("SELECT * FROM addproducts WHERE category = ?");
$category = "Traditional Instruments";
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
	  


// Loop through each headphone product
while ($row = $result->fetch_assoc()):
  $product_id = (int)$row['id'];
  $product_name = htmlspecialchars($row['name']);
  $product_price = htmlspecialchars($row['price']);
  $product_image = htmlspecialchars($row['image_url']);
  $product_description = htmlspecialchars($row['description']);
	  $rating_data = getRatingStats($conn, $product_id);
  $total_reviews = $rating_data['total_reviews'];
  $avg_rating = $rating_data['avg_rating'];
?>
	  
	  
  <div class="product-box" data-product-id="<?= $product_id ?>" style="background-image: url('<?= $product_image ?>');">
  <div class="top-right-stars">
    <?php
     if ($rating_data['total_reviews'] == 0) {
    $filled = 0;
    $half = false;
    $avg_rating = 0.0; // Show as (0.0)
} else {
    $filled = floor($avg_rating);
    $half = ($avg_rating - $filled) >= 0.5;
}

    ?>
	  
	 <?php if ($avg_rating >= 4.5 && $total_reviews > 0): ?>

    <div class="hot-pick-badge">🔥 Hot Pick</div>
  <?php endif; ?>
	  
	  
    <button class="btn-star" onclick="openRatingModal(<?= $product_id ?>)">
      <?php
      for ($i = 1; $i <= 5; $i++) {
        if ($i <= $filled) echo '⭐';
        elseif ($half && $i == $filled + 1) echo '🌓';
        else echo '☆';
      }
      echo " (" . $avg_rating . ")";
      ?>
    </button>
  </div>

  <div class="product-content">
    <h3><?= $product_name ?></h3>
    <h3><?= $product_price ?> LKR</h3>

    <p><?= $product_description ?></p>

    <button class="btn btn-primary"
      onclick="openCartModal(
        '<?= $product_id ?>',
        '<?= $product_name ?>',
        '<?= $product_price ?> LKR',
        '<?= $product_image ?>'
      )">
      Add to Cart
    </button>
  </div>
</div>

<?php endwhile; ?>

  </div>
  <br>


	<!-- Floating Chatbot Button -->
<div id="chatToggle" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
  <button class="btn btn-primary">💬 FAQ</button>
</div>

<!-- Chatbot Window -->
<div id="chatWindow" style="display: none; position: fixed; bottom: 80px; right: 20px; width: 300px; height: 400px; background: #fff; border: 1px solid #ccc; border-radius: 10px; z-index: 9999; box-shadow: 0 0 10px rgba(0,0,0,0.2);">
  <div id="chatLog" style="padding: 10px; height: 320px; overflow-y: auto;"></div>
  <div style="padding: 10px;">
    <input type="text" id="chatInput" class="form-control" placeholder="Ask something...">
    <button class="btn btn-success mt-2" onclick="sendChat()">Send</button>
  </div>
</div>

<script>
document.getElementById("chatToggle").onclick = () => {
  const chat = document.getElementById("chatWindow");
  chat.style.display = (chat.style.display === "none") ? "block" : "none";
};

// SEND MESSAGE FUNCTION
function sendChat() {
  const input = document.getElementById("chatInput");
  const message = input.value.trim();
  if (!message) return;

  const chatLog = document.getElementById("chatLog");

  // User bubble
  chatLog.innerHTML += `
    <div style="text-align: right; margin-bottom: 10px;">
      <div style="
        display: inline-block;
        background-color: #d4edda;
        color: #155724;
        padding: 8px 12px;
        border-radius: 15px 15px 0 15px;
        max-width: 80%;
      ">${message}</div>
    </div>
  `;

  fetch("chatbot.php", {
    method: "POST",
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: "message=" + encodeURIComponent(message)
  })
  .then(res => res.text())
  .then(reply => {
    if (reply.startsWith("REDIRECT:")) {
      const url = reply.replace("REDIRECT:", "").trim();
      window.location.href = url;
    } else {
      // Bot bubble
      chatLog.innerHTML += `
        <div style="text-align: left; margin-bottom: 10px;">
          <div style="
            display: inline-block;
            background-color: #e7f3fe;
            color: #0c5460;
            padding: 8px 12px;
            border-radius: 15px 15px 15px 0;
            max-width: 80%;
          ">${reply}</div>
        </div>
      `;
      chatLog.scrollTop = chatLog.scrollHeight;
    }
    input.value = "";
  });
}

// 🔹 Enter key listener
document.getElementById("chatInput").addEventListener("keydown", function(e) {
  if (e.key === "Enter") {
    e.preventDefault();
    sendChat();
  }
});

// 🔹 Click outside to close chatbot
document.addEventListener("click", function(event) {
  const chat = document.getElementById("chatWindow");
  const toggle = document.getElementById("chatToggle");
  if (
    chat.style.display === "block" &&
    !chat.contains(event.target) &&
    !toggle.contains(event.target)
  ) {
    chat.style.display = "none";
  }
});
</script>


    
    </section>
	  
<!-- Cart Modal -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="cartModalLabel">Add to Cart</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img id="modalProductImage" src="" class="img-fluid" style="max-height: 150px;" />
        <h5 id="modalProductName" class="mt-3"></h5>
        <p>Price: <span id="modalProductPrice"></span> LKR</p>
        <form id="addToCartForm">
          <input type="hidden" name="product_id" id="modalProductId">
			<input type="hidden" name="image" id="modalImage">

          <input type="hidden" name="name" id="modalName">
          <input type="hidden" name="price" id="modalPrice">
          <label>Quantity:</label>
          <input type="number" name="quantity" id="modalQuantity" value="1" min="1" class="form-control w-50 mx-auto mb-3" />
          <button type="submit" class="btn btn-success">Confirm Add to Cart</button>
        </form>
      </div>
    </div>
  </div>
</div>

    <!-- Rating Modal -->
<div id="ratingModal">
  <div class="rating-box">
    <h3>Rate this product</h3>
    <div id="emojiOptions">
      <span class="emoji-feedback" data-value="5" title="Excellent 😍">😍</span>
      <span class="emoji-feedback" data-value="4" title="Good 🙂">🙂</span>
      <span class="emoji-feedback" data-value="3" title="Average 😐">😐</span>
      <span class="emoji-feedback" data-value="2" title="Bad 🙁">🙁</span>
      <span class="emoji-feedback" data-value="1" title="Terrible 😢">😢</span>
    </div>
    <br />
   <button style="background-color:#D92D30; border-radius:8px; color: white; padding: 8px 16px;" onclick="closeRatingModal()">
  <b>Cancel</b>
</button>

  </div>
</div>


<!-- Big Emoji Popup -->
<div id="emojiPopup" style="
  display: none;
  position: fixed;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  background: white;
  padding: 30px; 
  text-align: center;
  z-index: 99999;
  animation: fadeInZoom 0.3s ease;
  box-shadow: 0 12px 30px rgba(0,0,0,0.25);
  border-radius: 10px;
  text-align: center;
  width: 600px;         /* match with #emojiPopup */
  max-width: 130vw; 
">
  <div id="bigEmoji" style="font-size: 80px; animation: pop 0.6s ease;"></div>
  <h4 class="mt-3 text-success">Thanks for your rating!</h4>
</div>

  <footer class="footer">
        <div class="footer-container">
            <div class="footer-row">
                <div class="footer-col">
                    <h4><i class="fas fa-user-circle"></i> My Account</h4>
                    <ul>
                        <li><a href="login.php"><i class="fas fa-cogs"></i> Edit Account</a></li>
                        <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> View Cart</a></li>
                        <li><a href="cart.php"><i class="fas fa-map-marker-alt"></i> Edit Address</a></li>
                        <li><a href="payment_history.php"><i class="fas fa-box"></i> Track Order</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4><i class="fas fa-link"></i> Quick Links</h4>
                    <ul>
                        <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="Aboutus.php"><i class="fas fa-shield-alt"></i> Privacy Policy</a></li>
                        <li><a href="products.php"><i class="fas fa-guitar"></i> Products</a></li>
                        <li><a href="ContactUs.php"><i class="fas fa-phone-alt"></i> Contact</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4><i class="fas fa-share-alt"></i> Follow Us</h4>
                    <div class="social-links">
                       <li><a href="https://github.com/shaliniarchana"><i class="fab fa-github"></i> GitHub</a></li>
                        <li><a href="https://telegram.org/"><i class="fab fa-telegram"></i> Telegram</a></li>
                        <li><a href="https://facebook.com"><i class="fab fa-facebook"></i> Facebook</a></li>
                        <li><a href="https://instagram.com"><i class="fab fa-instagram"></i> Instagram</a></li>
                    </div>
                </div>
				
				 <div class="footer-col">
                    <h4><i class="fas fa-info-circle"></i> Company Info</h4>
                    <ul>
                        <li><a href="Aboutus.php"><i class="fas fa-building"></i> About Us</a></li>
                        <li><a href="ContactUs.php"><i class="fas fa-briefcase"></i> Careers</a></li>
                        <li><a href="Aboutus.php"><i class="fas fa-file-alt"></i> Terms of Service</a></li>
         
                    </ul>
                </div>
                <div class="footer-col">
                    <h4><i class="fas fa-clock"></i> Opening Hours</h4>
                    <ul>
                        <li><span>Mon - Fri:</span> 9:00 AM - 8:00 PM</li>
                        <li><span>Sat - Sun:</span> 10:00 AM - 9:00 PM</li>
                    </ul>
                </div>
            </div>
            
            
            <h5 align="center">
                Copyright © 2025 JMC Music Corner. All Rights Reserved.
            </h5>
        </div>
    </footer>

    
 

     <!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	  
	  <script>
  function openCartModal(id, name, price, image) {
  document.getElementById('modalProductImage').src = image;
  document.getElementById('modalProductName').innerText = name;
  document.getElementById('modalProductPrice').innerText = price;
  document.getElementById('modalProductId').value = id;
  document.getElementById('modalName').value = name;
  document.getElementById('modalPrice').value = price;
  document.getElementById('modalImage').value = image; // ✅ ADD THIS

  new bootstrap.Modal(document.getElementById('cartModal')).show();
}


  document.getElementById('addToCartForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('cart.php', {
  method: 'POST',
  body: formData
})

      .then(res => res.text())
      .then(data => {
        alert("Item added to cart!");
        location.reload(); // Optional: Refresh to show updates
      })
      .catch(err => {
        console.error(err);
        alert("Failed to add item.");
      });
  });
</script>
	

	  <script>
    const toggleBtn = document.getElementById("modeToggle");
    const body = document.body;

    toggleBtn.addEventListener("click", () => {
        body.classList.toggle("dark-mode");

        // Toggle icon between moon/sun
        const icon = toggleBtn.querySelector("i");
        if (body.classList.contains("dark-mode")) {
            icon.classList.remove("fa-sun");
            icon.classList.add("fa-moon");
        } else {
            icon.classList.remove("fa-moon");
            icon.classList.add("fa-sun");
        }

        // Optional: save preference
        localStorage.setItem("theme", body.classList.contains("dark-mode") ? "dark" : "light");
    });

    // Load saved preference
    if (localStorage.getItem("theme") === "dark") {
        body.classList.add("dark-mode");
        toggleBtn.querySelector("i").classList.replace("fa-sun", "fa-moon");
    }
</script>
		
<script>
	  function submitReview(select, productId) {
  const rating = select.value;
  if (!rating) return;

  fetch('submit_review.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `product_id=${productId}&rating=${rating}`
  })
  .then(res => res.text())
  .then(data => {
    alert(data);
    location.reload(); // Reload to update rating display
  });
}

</script>
	  
	  <script>
let selectedProductId = null;

// Show rating modal
function openRatingModal(productId, currentRating = 0) {
  selectedProductId = productId;
  document.getElementById("ratingModal").style.display = "flex";
}

// Close modal
function closeRatingModal() {
  document.getElementById("ratingModal").style.display = "none";
  selectedProductId = null;
}

// Submit rating when emoji is clicked
document.querySelectorAll('.emoji-feedback').forEach(emoji => {
  emoji.addEventListener('click', function () {
    const rating = this.dataset.value;
    const selectedEmoji = this.textContent;

    if (!selectedProductId || !rating) return;

    // Submit to server
    fetch('submit_review.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `product_id=${selectedProductId}&rating=${rating}`
    })
    .then(res => res.text())
    .then(data => {
      // Show big emoji popup
      document.getElementById("bigEmoji").textContent = selectedEmoji;
      document.getElementById("emojiPopup").style.display = "block";

      // Hide modal & popup after delay
      setTimeout(() => {
        document.getElementById("emojiPopup").style.display = "none";
        closeRatingModal();
        location.reload(); // ✅ reload to update rating
      }, 3000);
    });
  });
});

</script>



  
      
    
</body>
</html>
