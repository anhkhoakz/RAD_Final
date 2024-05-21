async function loadProducts() {
    try {
        let response = await fetch(
            "http://localhost/coffee-shop-website/functions/load_products.php"
        );
        if (!response.ok) {
            throw new Error("Failed to fetch products");
        }
        let products = await response.json();
        displayProducts(products);
    } catch (error) {
        // console.error("Error loading products:", error);
    }
}

function displayProducts(products) {
    let menuSection = document.getElementById("menu");
    let containerSection = menuSection.querySelector(".container");
    containerSection.innerHTML = generateRows(products);

    function generateRows(products) {
        let rows = "";
        for (let i = 0; i < products.length; i += 3) {
            let row = `
            <div class="row">
                ${generateColumns(products.slice(i, i + 3))}
            </div><br />
            `;
            rows += row;
        }
        return rows;
    }

    function generateColumns(products) {
        let columns = "";
        for (let product of products) {
            let column = `
            <div class="col-md-4">
                <div class="box">
                    <img src="${product.img}" alt="" class="product-img">
                    <h3 class="product-title">${product.title}</h3>
                    <div class="price">$${product.price}</div>
                    <a class="btn add-cart" onclick="redirectCart(${product.id})">Add to Cart</a>
                </div>
            </div><br />
            `;
            columns += column;
        }
        return columns;
    }
}

loadProducts();

// function redirectCart(productID) {
//     let username = sessionStorage.getItem("username");
//     console.log(username);
//     if (!username) {
//         alert(
//             "You are not logged in. Please log into your account and try again."
//         );
//         window.location.href =
//             "http://localhost/coffee-shop-website/users/login.php";
//     } else {
//         addToCart(productID);
//     }
// }

function addToCart(productID) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let product = cart.find((product) => product.id === productID);
    if (product) {
        product.quantity++;
    } else {
        cart.push({ id: productID, quantity: 1 });
    }
    localStorage.setItem("cart", JSON.stringify(cart));

    // console.log(cart);
}

function displaySearchForm() {
    let navbar = document.querySelector(".navbar");
    let cartItem = document.querySelector(".cart");
    let searchForm = document.querySelector(".search-form");

    searchForm.classList.toggle("active");
    navbar.classList.remove("active");
    cartItem.classList.remove("active");
}

function searching(keyword) {
    let search = keyword.trim().toLowerCase();
    let products = fetch(
        "http://localhost/coffee-shop-website/functions/searching.php?search=" +
            search
    );
    products
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            displayProducts(data);
        });
}
