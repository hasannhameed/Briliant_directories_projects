<!DOCTYPE html>
<html>
<head>
    <title>Expense Tracker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Expense Tracker</h2>

    <form id="expense-form">
        <input type="text" id="title" placeholder="Expense Title" required>
        <input type="number" id="amount" placeholder="Amount" required>
        <button type="submit">Add Expense</button>
    </form>

    <h3>Expenses</h3>
    <ul id="expense-list"></ul>

    <h3>Total: ₹<span id="total">0</span></h3>
</div>

<script src="app.js"></script>
</body>
</html>
<style>




form input {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
}

button {
    width: 100%;
    padding: 10px;
    background: #000;
    color: #fff;
    border: none;
    cursor: pointer;
}

#expense-list {
    list-style: none;
    padding: 0;
}

#expense-list li {
    background: #eee;
    margin: 5px 0;
    padding: 10px;
    display: flex;
    justify-content: space-between;
}

</style>
<script>
	// Load existing expenses from localStorage
let expenses = JSON.parse(localStorage.getItem("expenses")) || [];

// DOM elements
const form = document.getElementById("expense-form");
const titleInput = document.getElementById("title");
const amountInput = document.getElementById("amount");
const expenseList = document.getElementById("expense-list");
const totalDisplay = document.getElementById("total");

// Display all expenses on load
displayExpenses();

form.addEventListener("submit", function (e) {
    e.preventDefault();

    const expense = {
        id: Date.now(),
        title: titleInput.value,
        amount: Number(amountInput.value)
    };

    expenses.push(expense);

    // Save to localStorage
    localStorage.setItem("expenses", JSON.stringify(expenses));

    displayExpenses();

    form.reset();
});

function displayExpenses() {
    expenseList.innerHTML = "";
    let total = 0;

    expenses.forEach(exp => {
        total += exp.amount;

        const li = document.createElement("li");
        li.innerHTML = `
            ${exp.title} - ₹${exp.amount}
            <button onclick="deleteExpense(${exp.id})">X</button>
        `;
        expenseList.appendChild(li);
    });

    totalDisplay.innerText = total;
}

function deleteExpense(id) {
    expenses = expenses.filter(exp => exp.id !== id);
    localStorage.setItem("expenses", JSON.stringify(expenses));
    displayExpenses();
}

</script>