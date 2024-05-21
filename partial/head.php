<header class="header">
    <a href="#" class="logo">
        <img src="../assets/images/logo.png" class="img-logo" alt="KapeTann Logo">
    </a>

    <!-- MAIN MENU FOR SMALLER DEVICES -->
    <nav class="navbar navbar-expand-lg">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a href="#home" class="text-decoration-none">Home</a>
            </li>
            <li class="nav-item">
                <a href="#about" class="text-decoration-none">About</a>
            </li>
            <li class="nav-item">
                <a href="#menu" class="text-decoration-none">Menu</a>
            </li>
            <!-- <li class="nav-item">
                    <a href="#gallery" class="text-decoration-none">Gallery</a>
                </li>
                <li class="nav-item">
                    <a href="#blogs" class="text-decoration-none">Blogs</a>
                </li> -->
            <li class="nav-item">
                <a class="text-decoration-none" id="function-button">Function button</a>
            </li>
            <li class="nav-item">
                <a href="#contact" class="text-decoration-none">Contact</a>
            </li>
            <li class="nav-item">
                <a href="logout.php" class="text-decoration-none">Logout</a>
            </li>
        </ul>
        </div>
    </nav>
    <div class="icons">
        <div class="fas fa-search" id="search-btn"></div>
        <div class="fas fa-shopping-cart" id="cart-btn"></div>
        <div class="fas fa-bars" id="menu-btn"></div>
        <div class="fas fa-user" id="profile" onclick="window.location.href='../profile'"></div>
    </div>

    <!-- SEARCH TEXT BOX -->
    <div class="search-form">
        <input type="search" id="search-box" class="form-control" placeholder="Search here..." oninput="searching(this.value)">
        <label for="search-box" class="fas fa-search"></label>
    </div>

    <!-- CART SECTION -->
    <div class="cart">
        <h2 class="cart-title">Your Cart:</h2>
        <div class="cart-content">

        </div>
        <div class="total">
            <div class="total-title">Total: </div>
            <div class="total-price">₱0</div>
        </div>
        <!-- BUY BUTTON -->
        <button type="button" class="btn-buy">Checkout Now</button>
    </div>
</header>