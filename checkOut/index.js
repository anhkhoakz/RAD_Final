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
                <td>
                    <input
                        type="number"
                        name=""
                        id=""
                        value="${quantity}"
                        min="1"
                        onchange="updateQuantity(${productID}, this.value)"
                        readonly
                    />
                </td>
                <td>$${productPrice * quantity}</td>
                
            </tr>
            `;
        }
    } catch (error) {
        console.error("Error:", error);
    }
}

getPurchased();

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
    let totalSection = document.getElementById("total");
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

    totalSection.value = total_price;
}

calculateTotal();

// document
//     .getElementById("checkout-form")
//     .addEventListener("submit", function (event) {
//         event.preventDefault();

//         let formData = new FormData(event.target);
//         let formValues = Object.fromEntries(formData.entries());
//         let formValuesJson = JSON.stringify(formValues);

//         fetch("http://localhost/coffee-shop-website/checkOut/check_out.php", {
//             method: "POST",
//             body: formValuesJson,
//         });
//     });

function checkOut() {
    let form = document.getElementById("checkout-form");
    let formData = new FormData(form);
    let formValues = Object.fromEntries(formData.entries());
    let formValuesJson = JSON.stringify(formValues);

    fetch("http://localhost/coffee-shop-website/checkOut/check_out.php", {
        method: "POST",
        body: formValuesJson,
    });

    alert("Order placed successfully!");

    window.location.href = "http://localhost/coffee-shop-website/users/";
}
