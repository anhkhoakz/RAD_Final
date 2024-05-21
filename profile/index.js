async function loadUserProfile() {
    let response = await fetch(
        "http://localhost/coffee-shop-website/profile/get_my_info.php"
    );

    let data = await response.json();
    displayUserProfile(data);
}

loadUserProfile();

function displayUserProfile(profile) {
    let username = document.getElementById("username");
    let name = document.getElementById("name");
    let email = document.getElementById("email");
    let phone = document.getElementById("phone-number");
    let address = document.getElementById("address");
    let password = document.getElementById("password");

    if (username) {
        username.value = profile.username ? profile.username : "";
        username.setAttribute("readonly", true);
    }
    if (name) {
        name.value = profile.name ? profile.name : "";
        name.setAttribute("readonly", true);
    }
    if (email) {
        email.value = profile.email ? profile.email : "";
        email.setAttribute("readonly", true);
    }
    if (phone) {
        phone.value = profile.phonenumber ? profile.phonenumber : "";
        phone.setAttribute("readonly", true);
    }
    if (address) {
        address.value = profile.address ? profile.address : "";
        address.setAttribute("readonly", true);
    }
    if (password) {
        password.value = "";
        password.setAttribute("readonly", true);
    }
}

function changeInformation() {
    let username = document.getElementById("username");
    let name = document.getElementById("name");
    let email = document.getElementById("email");
    let phone = document.getElementById("phone-number");
    let address = document.getElementById("address");
    let password = document.getElementById("password");

    username.removeAttribute("readonly");
    name.removeAttribute("readonly");
    email.removeAttribute("readonly");
    phone.removeAttribute("readonly");
    address.removeAttribute("readonly");
    password.removeAttribute("readonly");

    let changeButton = document.getElementById("btnChangeInformation");
    changeButton.innerHTML = "Save";
    changeButton.setAttribute("onclick", "saveInformation()");
}

async function saveInformation() {
    let username = document.getElementById("username");
    let name = document.getElementById("name");
    let email = document.getElementById("email");
    let phone = document.getElementById("phone-number");
    let address = document.getElementById("address");
    let password = document.getElementById("password");

    let usernameData = username.value;
    let nameData = name.value;
    let emailData = email.value;
    let phoneData = phone.value;
    let addressData = address.value;
    let passwordData = password.value;

    let data = {
        username: usernameData,
        name: nameData,
        email: emailData,
        phone: phoneData,
        address: addressData,
        password: passwordData,
    };

    let response = await fetch(
        "http://localhost/coffee-shop-website/profile/change_info.php",
        {
            method: "POST",
            body: JSON.stringify(data),
        }
    );

    let changeButton = document.getElementById("btnChangeInformation");
    changeButton.innerHTML = "Change information";
    changeButton.setAttribute("onclick", "changeInformation()");

    username.setAttribute("readonly", true);
    name.setAttribute("readonly", true);
    email.setAttribute("readonly", true);
    phone.setAttribute("readonly", true);
    address.setAttribute("readonly", true);
    password.setAttribute("readonly", true);
}

async function loadOrderHistory() {
    let response = await fetch(
        "http://localhost/coffee-shop-website/profile/get_history_purchased.php"
    );

    let data = await response.json();
    displayOrderHistory(data);
}

loadOrderHistory();

async function displayOrderHistory(orders) {
    let orderList = document.getElementById("orderList");
    orderList.innerHTML = "";

    for (let i = 0; i < orders.length; i++) {
        let tr = document.createElement("tr");
        let order = orders[i];

        let td = document.createElement("td");
        td.innerHTML = order.invoice_number;
        tr.appendChild(td);

        td = document.createElement("td");
        td.innerHTML = await displayProductName(order.id);
        tr.appendChild(td);

        td = document.createElement("td");
        td.innerHTML = order.quantity;
        tr.appendChild(td);

        td = document.createElement("td");
        td.innerHTML = order.subtotal_amount;
        tr.appendChild(td);

        td = document.createElement("td");
        let span = document.createElement("span");

        if (order.status == "processing") {
            // span.setAttribute("class", "badge badge-warning");
            span.innerHTML = "Processing";
        } else if (order.status == "completed") {
            // span.setAttribute("class", "badge badge-success");
            span.innerHTML = "Completed";
        } else if (order.status == "cancelled") {
            // span.setAttribute("class", "badge badge-danger");
            span.innerHTML = "Cancelled";
        }

        td.appendChild(span);
        tr.appendChild(td);

        // td = document.createElement("td");
        // td.innerHTML = `
        // <input type="range" min="1" max="5" step="1" name="rating" id="rating" value="${order.stars}" class="form-range" onchange(})>
        // `;
        // tr.appendChild(td);

        td = document.createElement("td");
        td.innerHTML = `
            <button 
                class="btn btn-info"
                data-bs-toggle="modal"
                data-bs-target="#rating-form"
                onclick="loadComment('${order.invoice_number}', '${order.id}')"
            >
                <i class="fa-solid fa-comment"></i>
            </button>
            <button 
                class="btn btn-danger"
                onclick="cancelOrder('${order.invoice_number}', '${order.id}')"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>`;
        tr.appendChild(td);

        orderList.appendChild(tr);
    }
}

async function displayProductName(id) {
    let response = await fetch(
        "http://localhost/coffee-shop-website/functions/get_product.php?id=" +
            id
    );

    let data = await response.json();
    return data.title;
}

function cancelOrder(invoiceNumber, productID) {
    let data = {
        order_id: invoiceNumber,
        product_id: productID,
    };

    fetch("http://localhost/coffee-shop-website/profile/cancel_order.php", {
        method: "POST",
        body: JSON.stringify(data),
    });

    loadOrderHistory();
}

function saveComment(invoice_number, product_id) {
    let rating = document.getElementById("rating-stars").value;
    let comment = document.getElementById("rating-comment").value;

    let data = {
        rating_star: rating,
        comment: comment,
        invoice_number: invoice_number,
        product_id: product_id,
    };

    fetch("http://localhost/coffee-shop-website/profile/submit_comment.php", {
        method: "POST",
        body: JSON.stringify(data),
    });

    $("#rating-form").modal("hide");
}

async function loadComment(invoice_number, product_id) {
    let response = await fetch(
        "http://localhost/coffee-shop-website/profile/get_comment.php?invoice_number=" +
            invoice_number +
            "&product_id=" +
            product_id
    );

    let data = await response.json();

    let rating_stars = document.getElementById("rating-stars");
    let rating_comment = document.getElementById("rating-comment");

    if (data.stars !== null && data.stars !== "" && data.stars !== undefined) {
        rating_stars.value = data.stars;
    } else {
        rating_stars.value = 0;
    }
    if (
        data.review !== null &&
        data.review !== "" &&
        data.review !== undefined
    ) {
        rating_comment.value = data.review;
    } else {
        rating_comment.value = "";
        rating_comment.placeholder = "Write your comment here...";
    }

    let submitButton = document.getElementById("btnSaveComment");
    submitButton.setAttribute(
        "onclick",
        `saveComment('${invoice_number}', '${product_id}')`
    );
}
