async function getPurchased() {
    try {
        let response = await fetch(
            "http://localhost/coffee-shop-website/functions/get_purchased.php"
        );

        let products = await response.json();

        let tbody = document.querySelector("tbody");
        tbody.innerHTML = "";

        for (let product of products) {
            let productID = product.product_id;
            let quantity = product.quantity;

            let productInfo = await getProductInformation(productID);
            let productData = await productInfo.json();
            let productName = productData.title;
            let productPrice = productData.price;

            tbody.innerHTML += `
            <tr class="">
                <td scope="row">
                    <img
                        src="${productData.img}"
                        class="img-fluid rounded-top"
                        alt="${productName}"
                    />
                </td>
                <td>${productName}</td>
                <td>$${productPrice}</td>
                <td>
                    <input
                        type="number"
                        name=""
                        id=""
                        value="${quantity}"
                        min="1"
                        onchange="updateQuantity(${productID}, this.value)"
                    />
                </td>
                <td>$${productPrice * quantity}</td>
                <td>
                    <button 
                        class="btn btn-danger" 
                        onclick="deleteProduct(${productID})"

                    >
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            `;
        }
    } catch (error) {
        console.error("Error:", error);
    }
}

getPurchased();

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

function getProductInformation(productId) {
    try {
        let response = fetch(
            "http://localhost/coffee-shop-website/functions/get_product.php?id=" +
                productId
        );

        return response;
    } catch (error) {
        console.error("Error:", error);
    }
}

async function calculateTotal() {
    let total_quantity_section = document.getElementById("total-quantity");
    let total_price_section = document.getElementById("total-price");

    let total_quantity = 0;
    let total_price = 0;

    let response = await fetch(
        "http://localhost/coffee-shop-website/functions/get_purchased.php"
    );

    let products = await response.json();

    for (let product of products) {
        let productID = product.product_id;
        let quantity = product.quantity;

        let productInfo = await getProductInformation(productID);
        let productData = await productInfo.json();
        let productPrice = productData.price;

        total_quantity += quantity;
        total_price += productPrice * quantity;
    }

    total_quantity_section.innerHTML = total_quantity;
    total_price_section.innerHTML = "$" + total_price;
}

calculateTotal();

async function updateQuantity(productID, quantity) {
    let userID = await getUserID();
    fetch(
        "http://localhost/coffee-shop-website/functions/update_quantity.php",
        {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                product_id: productID,
                quantity: quantity,
                user_id: userID,
            }),
        }
    ).then(() => {
        getPurchased();
        calculateTotal();
    });
}

async function deleteProduct(productID) {
    console.log("Deleted");
    let userID = await getUserID();
    fetch(
        "http://localhost/coffee-shop-website/functions/delete_selected_product.php",
        {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                product_id: productID,
                user_id: userID,
            }),
        }
    ).then(() => {
        getPurchased();
        calculateTotal();
    });
}

function purchase(button) {
    button.disabled = true;
    button.innerHTML = "Processing...";

    window.location.href =
        "http://localhost/coffee-shop-website/checkOut/index.html";
}
