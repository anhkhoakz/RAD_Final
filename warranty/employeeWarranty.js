async function getApplications() {
    let table = document.querySelector("table");
    tbody = table.querySelector("tbody");
    tbody.innerHTML = "";

    let response = await fetch(
        "http://localhost/coffee-shop-website/warranty/get_application_waiting.php"
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
