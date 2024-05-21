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
        console.error("Error loading products:", error);
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
                    <a class="btn add-cart" onclick="addToCart(${product.id})">Add to Cart</a>
                </div>
            </div><br />
            `;
            columns += column;
        }
        return columns;
    }
}

loadProducts();

async function addToCart(productID) {
    try {
        let response = await fetch(
            "http://localhost/coffee-shop-website/functions/add_to_cart.php",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    product_id: productID,
                    quantity: 1,
                }),
            }
        );
    } catch (error) {
        console.error("Error:", error);
    }
}

async function getUserID() {
    try {
        let response = await fetch(
            "http://localhost/coffee-shop-website/functions/get_user_id.php",
            {
                headers: {
                    "Content-Type": "application/json",
                },
            }
        );
        let responseData = await response.json();
        let userID = responseData.user_id;

        return userID;
    } catch (error) {
        console.error("Error:", error);
        return null;
    }
}

function searching(keyword) {
    let search = keyword.toLowerCase();
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

function displayFunctionButton() {
    let functionButton = document.getElementById("function-button");
    fetch("http://localhost/coffee-shop-website/functions/get_user_role.php", {
        headers: {
            "Content-Type": "application/json",
        },
    })
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            if (data.role === "admin") {
                functionButton.innerHTML = "Admin Panel";
                functionButton.setAttribute("href", "../manager/index.html");
            } else if (data.role === "employee") {
                functionButton.innerHTML = "Employee Panel";
                functionButton.setAttribute(
                    "href",
                    "../warranty/employeeWarranty.html"
                );
            } else {
                functionButton.innerHTML = "Warranty Panel";
                functionButton.setAttribute(
                    "href",
                    "../warranty/userWarranty.html"
                );
            }
        });
}

displayFunctionButton();

document.querySelector("#cart-btn").onclick = () => {
    window.location.href = "view_cart.html";
};
