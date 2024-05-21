function getApplicationType() {
    let applicationType = document.getElementById("application-type").value;
    let selectedApplication = document.getElementById(
        "selected-application-type"
    );

    if (applicationType === "Choose application type") {
        selectedApplication.value = "";
        return;
    }
    selectedApplication.value = applicationType;
}

async function createApplication() {
    let applicationType = document.getElementById("application-type").value;
    let product = document.getElementById("product").value;
    let phoneNumber = document.getElementById("phonenumber").value;
    let reason = document.getElementById("reason").value;

    if (
        applicationType === "" ||
        product === "No product purchased" ||
        phoneNumber === "" ||
        reason === ""
    ) {
        alert("Please fill in all the fields");
        return;
    }

    let data = {
        application_type: applicationType,
        product: product,
        phonenumber: phoneNumber,
        message: reason,
    };

    await fetch(
        "http://localhost/coffee-shop-website/warranty/create_application.php",
        {
            method: "POST",
            body: JSON.stringify(data),
        }
    );

    document.getElementById("application-type").value =
        "Choose application type";
    document.getElementById("selected-application-type").value = "";
    document.getElementById("product").selectedIndex = 0;
    document.getElementById("phonenumber").value = "";
    document.getElementById("reason").value = "";

    alert("Application submitted successfully");

    let modal = bootstrap.Modal.getInstance(
        document.getElementById("application-form")
    );

    modal.hide();
}

async function getPurchased() {
    let productSection = document.getElementById("product");
    productSection.innerHTML = "";

    let response = await fetch(
        "http://localhost/coffee-shop-website/warranty/get_purchased.php"
    );

    let data = await response.json();

    if (data.length === 0) {
        let option = document.createElement("option");
        option.value = "No product purchased";
        option.innerHTML = "No product purchased";
        productSection.appendChild(option);
    } else {
        data.forEach((product) => {
            if (product === "" || product === null) {
                return;
            }
            let option = document.createElement("option");
            option.value = product;
            option.innerHTML = product;
            productSection.appendChild(option);
        });
    }
}
getPurchased();

async function getApplications() {
    let table = document.querySelector("table");
    tbody = table.querySelector("tbody");
    tbody.innerHTML = "";

    let response = await fetch(
        "http://localhost/coffee-shop-website/warranty/get_applications.php"
    );
    let applications = await response.json();

    applications.forEach((application) => {
        displayApplications(application);
    });
}

getApplications();

function displayApplications(application) {
    let table = document.querySelector("table");
    tbody = table.querySelector("tbody");

    let row = document.createElement("tr");

    let applicationId = document.createElement("td");
    applicationId.innerHTML = application.id;
    row.appendChild(applicationId);

    let applicationType = document.createElement("td");
    applicationType.innerHTML = application.application_type;
    row.appendChild(applicationType);

    let createdDate = document.createElement("td");
    createdDate.innerHTML = application.created_date;
    row.appendChild(createdDate);

    let feedbackDate = document.createElement("td");
    feedbackDate.innerHTML = "Not yet reviewed";
    row.appendChild(feedbackDate);

    // let product = document.createElement("td");
    // product.innerHTML = application.product;
    // row.appendChild(product);

    let status = document.createElement("td");
    status.innerHTML = application.status;
    row.appendChild(status);

    let resultNote = document.createElement("td");
    resultNote.innerHTML = "Not yet reviewed";
    row.appendChild(resultNote);

    // let phoneNumber = document.createElement("td");
    // phoneNumber.innerHTML = application.phonenumber;
    // row.appendChild(phoneNumber);

    // let reason = document.createElement("td");
    // reason.innerHTML = application.message;
    // row.appendChild(reason);

    let actions = document.createElement("td");
    actions.innerHTML = `
        <button class="btn btn-primary">
            <i class="fa-regular fa-eye"></i>
        </button>

        <button class="btn btn-primary bg-warning">
            <i class="fa-regular far fa-edit"></i>
        </button>

        <button 
            class="btn btn-primary bg-danger"
            onclick="deleteApplication('${application.id}')"
        >
            <i class="fa-regular far fa-trash-alt"></i>
        </button>
    `;
    row.appendChild(actions);

    tbody.appendChild(row);
}

function deleteApplication(id) {
    fetch(
        `http://localhost/coffee-shop-website/warranty/delete_application.php?id=${id}`
    );
    getApplications();
}
