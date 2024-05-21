function displayTable() {
    let btnUsers = document.getElementById("btnUsers");
    let btnEmployees = document.getElementById("btnEmployees");
    let btnProducts = document.getElementById("btnProducts");

    let userTable = document.getElementById("userTable");
    let employeeTable = document.getElementById("employeeTable");
    let productTable = document.getElementById("productTable");
    getUserTable();

    btnUsers.addEventListener("click", function () {
        userTable.classList.remove("d-none");
        employeeTable.classList.add("d-none");
        productTable.classList.add("d-none");

        btnProducts.classList.add("btn-outline-secondary");
        btnProducts.classList.remove("btn-secondary");
        btnEmployees.classList.add("btn-outline-secondary");
        btnEmployees.classList.remove("btn-secondary");

        btnUsers.classList.remove("btn-outline-secondary");
        btnUsers.classList.add("btn-secondary");
        getUserTable();
    });

    btnEmployees.addEventListener("click", function () {
        userTable.classList.add("d-none");
        employeeTable.classList.remove("d-none");
        productTable.classList.add("d-none");

        btnUsers.classList.add("btn-outline-secondary");
        btnUsers.classList.remove("btn-secondary");
        btnProducts.classList.add("btn-outline-secondary");
        btnProducts.classList.remove("btn-secondary");

        btnEmployees.classList.remove("btn-outline-secondary");
        btnEmployees.classList.add("btn-secondary");
        getEmployeeTable();
    });

    btnProducts.addEventListener("click", function () {
        userTable.classList.add("d-none");
        employeeTable.classList.add("d-none");
        productTable.classList.remove("d-none");

        btnUsers.classList.add("btn-outline-secondary");
        btnUsers.classList.remove("btn-secondary");
        btnEmployees.classList.add("btn-outline-secondary");
        btnEmployees.classList.remove("btn-secondary");

        btnProducts.classList.remove("btn-outline-secondary");
        btnProducts.classList.add("btn-secondary");
        loadProducts();
    });
}

displayTable();

async function getUserTable() {
    let userTable = document.getElementById("user-table");
    let tbody = userTable.querySelector("tbody");
    let role = "user";
    let result = await fetch(
        "http://localhost/coffee-shop-website/functions/get_users.php?role=" +
            role
    );
    let data = await result.json();
    tbody.innerHTML = "";
    for (let i = 0; i < data.length; i++) {
        let user = data[i];
        let tr = document.createElement("tr");

        tr.innerHTML = `
                <td scope="row">${user.name}</td>
                <td>${user.email}</td>
                <td>
                    <button
                        type="button"
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#view-user-modal"
                        onclick=viewUserInfo("${user.username}")
                    >
                        <i class="fa-regular fa-eye"></i>
                    </button>
                    <button
                        type="button"
                        class="btn btn-danger"
                        onclick=deleteUser("${user.username}")
                    >
                        <i
                            class="fa-regular far fa-trash-alt"
                        ></i>
                    </button>
                </td>
        `;
        tbody.appendChild(tr);
    }
}

async function getEmployeeTable() {
    let userTable = document.getElementById("employee-table");
    let tbody = userTable.querySelector("tbody");
    let role = "employee";
    let result = await fetch(
        "http://localhost/coffee-shop-website/functions/get_users.php?role=" +
            role
    );
    let data = await result.json();
    // console.log(data);
    tbody.innerHTML = "";
    for (let i = 0; i < data.length; i++) {
        let user = data[i];
        let tr = document.createElement("tr");

        tr.innerHTML = `
            <td scope="row">${user.id}</td>
                <td scope="row">${user.name}</td>
                <td scope="row">${user.email}</td>
                <td scope="row">1200000</td>
                <td scope="row">${user.role}</td>
                <td>
                    <button
                        type="button"
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#view-user-modal"
                        onclick=viewUserInfo("${user.username}")
                    >
                        <i class="fa-regular fa-eye"></i>
                    </button>

                    <button
                        type="button"
                        class="btn btn-warning"
                    >
                        <i
                            class="fa-regular far fa-edit"
                        ></i>
                    </button>

                    <button
                        type="button"
                        class="btn btn-danger"
                        onclick=deleteUser("${user.username}")

                    >
                        <i
                            class="fa-regular far fa-trash-alt"
                        ></i>
                    </button>
                </td>
        `;
        tbody.appendChild(tr);
    }
}

async function createUser() {
    let name = document.getElementById("user-name").value;
    let username = document.getElementById("user-username").value;
    let email = document.getElementById("user-email").value;
    let role = document.getElementById("user-role").value.toLowerCase();

    let result = await fetch(
        "http://localhost/coffee-shop-website/functions/create_user.php",
        {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                name: name,
                username: username,
                email: email,
                role: role,
            }),
        }
    );

    let data = await result.json();
    if (!data.success) {
        alert(data.error);
        return;
    }

    alert("User created successfully!");

    document.getElementById("user-name").value = "";
    document.getElementById("user-username").value = "";
    document.getElementById("user-email").value = "";

    $("#add-user-modal").modal("hide");
    getUserTable();
}

async function createEmployee() {
    let name = document.getElementById("employee-name").value;
    let username = document.getElementById("employee-username").value;
    let email = document.getElementById("employee-email").value;
    let role = document.getElementById("employee-role").value.toLowerCase();
    let salary = document.getElementById("employee-salary").value;

    let result = await fetch(
        "http://localhost/coffee-shop-website/functions/create_user.php",
        {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                name: name,
                username: username,
                email: email,
                role: role,
            }),
        }
    );

    let data = await result.json();
    if (!data.success) {
        alert(data.error);
        return;
    }

    alert("User created successfully!");

    document.getElementById("employee-name").value = "";
    document.getElementById("employee-username").value = "";
    document.getElementById("employee-email").value = "";

    $("#add-employee-modal").modal("hide");
    getEmployeeTable();
}

async function loadProducts() {
    let productTable = document.getElementById("product-table");
    let tbody = productTable.querySelector("tbody");
    let result = await fetch(
        "http://localhost/coffee-shop-website/functions/load_products.php"
    );
    let data = await result.json();
    tbody.innerHTML = "";
    for (let i = 0; i < data.length; i++) {
        let product = data[i];
        let tr = document.createElement("tr");

        tr.innerHTML = `
            <td scope="row">${product.id}</td>
            <td scope="row">${product.title}</td>
            <td scope="row">${product.price}</td>
            <td scope="row">1000</td>
            <td>
                <button
                    type="button"
                    class="btn btn-primary"
                >
                    <i class="fa-regular fa-eye"></i>
                </button>

                <button
                    type="button"
                    class="btn btn-warning"
                >
                    <i
                        class="fa-regular far fa-edit"
                    ></i>
                </button>

                <button
                    type="button"
                    class="btn btn-danger"
                    onclick="deleteProduct(${product.id})"
                >
                    <i
                        class="fa-regular far fa-trash-alt"
                    ></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    }
}

async function deleteUser(username) {
    await fetch(
        "http://localhost/coffee-shop-website/functions/delete_user.php?username=" +
            username
    );
}

function deleteProduct(id) {
    fetch(
        "http://localhost/coffee-shop-website/functions/delete_product.php?product_id=" +
            id
    );
}

async function viewUserInfo(username) {
    let response = await fetch(
        "http://localhost/coffee-shop-website/functions/get_user_info.php?username=" +
            username
    );

    let data = await response.json();

    let nameInput = document.getElementById("view-name");
    let usernameInput = document.getElementById("view-username");
    let emailInput = document.getElementById("view-email");
    let roleInput = document.getElementById("view-role");

    nameInput.value = data.name;
    usernameInput.value = data.username;
    emailInput.value = data.email;
    roleInput.value = data.role;
}

function addProduct() {
    let form = document.getElementById("createProductForm");
    let formData = new FormData(form);

    fetch("http://localhost/coffee-shop-website/manager/add_product.php", {
        method: "POST",
        body: formData,
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(
                    "Network response was not ok " + response.statusText
                );
            }
            return response.text();
        })
        .then((data) => {
            console.log(data);
        })
        .catch((error) => {
            console.error(
                "There was a problem with the fetch operation:",
                error
            );
        });
}
