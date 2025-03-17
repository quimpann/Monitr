/* the purpose of this js is,
* ! strictly for expensetracker only, take it as logic handler of the page. 
? why separate? Im too lazi to read loads of code
* will handle:
* - Add expense, search query, edit, delete, add in the table
*/

console.log("expensetracker loaded");

/*
 * Modal for add expense
 */
function expenseOpenModal() {
  document.getElementById("expenseItemName").innerText="";
  document.getElementById("expenseModal").style.display = "flex";
}

function expenseCloseModal() {
  document.getElementById("expenseModal").style.display = "none";
}

function saveExpense() {
  let item = document.getElementById("expenseItemName").value;
  let category = document.getElementById("expenseCategory").value;
  let amount = document.getElementById("expenseAmount").value;
  let startDate = document.getElementById("expenseStartDate").value;
  let endDate = document.getElementById("expenseDeadline").value;

  if (!item || !amount) {
    alert("Please make sure to fill all fields.");
    return;
  }

  alert("Expense Added Successfully!");

  console.log("Expense successfully added! =>", {
    item,
    category,
    amount,
    startDate,
    endDate,
  });

  let expenseData = new FormData();
  expenseData.append("expenseItemName", item);
  expenseData.append("expenseCategory", category);
  expenseData.append("expenseAmount", amount);
  expenseData.append("expenseStartDate", startDate);
  expenseData.append("expenseDeadline", endDate);

  fetch("../backend/pages/expenseTracker.php", {
    method: "POST",
    body: expenseData
  })
  .then(response => response.text())
  .then(data => {
    
    alert("Expense saved: " + data);

  item.value="";
  category.value = "";
  amount.value = "";
  startDate.value ="";
  endDate.value = "";

  expenseCloseModal();
})
  .catch(error => console.error("Error:", error));

  
}

/*
 * Modal => Validations 
 */

document.addEventListener("DOMContentLoaded", function () {
  function Validation(inputBorder, condition, errorMsg) {
    if (condition) {
      inputBorder.style.border = "2px solid red";
      alert(errorMsg);
    } else {
      inputBorder.style.border = "2px solid green";
    }
  }
  
  document
    .getElementById("expenseAmount")
    .addEventListener("input", function () {
      let amount = this.value;

      Validation(
        this,
        isNaN(amount) || parseFloat(amount) <= 0,
        "Amount must be a number and greater than 0."
      );
    });

  let startDate = "";

  document
    .getElementById("expenseStartDate")
    .addEventListener("change", function () {
      startDate = this.value;
      let today = new Date().toISOString().split("T")[0];

      Validation(this, startDate < today, "Start date cannot be in the past.");
    });

  document
    .getElementById("expenseDeadline")
    .addEventListener("change", function () {
      let endDate = this.value;

      Validation(
        this,
        endDate < startDate,
        "Deadline cannot be before start date."
      );
    });
});


document.addEventListener("DOMContentLoaded", () => {});
