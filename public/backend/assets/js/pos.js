document.addEventListener("DOMContentLoaded", () => {
    const searchBox = document.getElementById("searchBox");
    const productGrid = document.getElementById("productGrid");
    const products = productGrid.children; // HTMLCollection
    const allCategoryBtn = document.getElementById("allCategoryBtn");
    const tbody = document.querySelector("table tbody");
    const totalPayableDiv = document.getElementById("totalPayable");

    // Inputs
    const taxInput = document.getElementById("taxInput");
    const discountInput = document.getElementById("discountInput");
    const shippingInput = document.getElementById("shippingInput");

    // Cart array
    const cartItems = [];

    // Payment Modal elements
    const paymentModal = document.getElementById("paymentModal");
    const closePaymentModalBtn = document.getElementById("closePaymentModal"); // Renamed for clarity
    const submitPaymentBtn = document.getElementById("submitPayment");
    const previewInvoiceBtn = document.getElementById("previewInvoiceBtn"); // New button

    const receivedAmountInput = document.getElementById("receivedAmount");
    const modalTotalProducts = document.getElementById("modalTotalProducts");
    const modalOrderTax = document.getElementById("modalOrderTax");
    const modalDiscount = document.getElementById("modalDiscount");
    const modalShipping = document.getElementById("modalShipping");
    const modalTotalPayable = document.getElementById("modalTotalPayable");
    const modalChangeDue = document.getElementById("modalChangeDue");

    const customerNameInput = document.getElementById("customerName");
    const customerPhoneInput = document.getElementById("customerPhone");
    const invoiceValidityInput = document.getElementById("invoiceValidity");
    const paymentMethodSelect = document.getElementById("paymentMethod");
    const paymentNotesTextarea = document.getElementById("paymentNotes");

    // Invoice Modal elements
    const invoiceModal = document.getElementById("invoiceModal");
    const closeInvoiceBtn = document.getElementById("closeInvoice");
    const invoiceContent = document.getElementById("invoiceContent");
    const printInvoiceBtn = document.getElementById("printInvoice");

    // Helper functions
    function formatCurrency(amount) {
        return `$ ${amount.toFixed(2)}`;
    }

    function calculateTotals() {
        const subtotalSum = cartItems.reduce(
            (sum, item) => sum + item.price * item.qty,
            0
        );
        const taxPercent = parseFloat(taxInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        const shipping = parseFloat(shippingInput.value) || 0;

        const taxAmount = (taxPercent / 100) * subtotalSum;
        let total = subtotalSum + taxAmount + shipping - discount;
        if (total < 0) total = 0;
        return { subtotalSum, taxAmount, discount, shipping, total };
    }

    // function updateCart() {
    //     tbody.innerHTML = "";
    //     if (cartItems.length === 0) {
    //         tbody.innerHTML = `<tr><td colspan="5" class="py-4 text-gray-500 text-center">No data Available</td></tr>`;
    //     }
    //     cartItems.forEach((item, index) => {
    //         const row = document.createElement("tr");
    //         row.className = "hover:bg-gray-50 transition duration-200";

    //         // Product
    //         const productTd = document.createElement("td");
    //         productTd.className = "p-2";
    //         productTd.textContent = item.name;
    //         row.appendChild(productTd);

    //         // Price
    //         const priceTd = document.createElement("td");
    //         priceTd.className = "p-2";
    //         priceTd.textContent = formatCurrency(item.price);
    //         row.appendChild(priceTd);

    //         // Quantity
    //         const qtyTd = document.createElement("td");
    //         qtyTd.className = "p-2";
    //         const qtyInput = document.createElement("input");
    //         qtyInput.type = "number";
    //         qtyInput.min = 1;
    //         qtyInput.value = item.qty;
    //         qtyInput.className = "w-16 border rounded p-1 text-center";
    //         qtyInput.style.width = "45px"; // custom width
    //         qtyInput.addEventListener("change", () => {
    //             const newQty = parseInt(qtyInput.value);
    //             if (!isNaN(newQty) && newQty > 0) {
    //                 item.qty = newQty;
    //             } else {
    //                 item.qty = 1; // Default to 1 if invalid input
    //                 qtyInput.value = 1;
    //             }
    //             updateCart();
    //         });
    //         qtyTd.appendChild(qtyInput);
    //         row.appendChild(qtyTd);

    //         // Subtotal
    //         const subtotal = item.price * item.qty;
    //         item.subtotal = subtotal; // Store subtotal in item for easier access if needed
    //         const subtotalTd = document.createElement("td");
    //         subtotalTd.className = "p-2";
    //         subtotalTd.textContent = formatCurrency(subtotal);
    //         row.appendChild(subtotalTd);

    //         // Remove button
    //         const removeTd = document.createElement("td");
    //         removeTd.className = "p-2";
    //         const removeBtn = document.createElement("button");
    //         removeBtn.textContent = "Remove";
    //         removeBtn.className =
    //             "bg-red-500 text-white px-2 py-2 mt-2 rounded text-xs";
    //         removeBtn.onclick = () => {
    //             cartItems.splice(index, 1);
    //             updateCart();
    //         };
    //         removeTd.appendChild(removeBtn);
    //         row.appendChild(removeBtn);

    //         tbody.appendChild(row);
    //     });

    //     const { total } = calculateTotals();
    //     totalPayableDiv.textContent = `Total Payable : ${formatCurrency(
    //         total
    //     )}`;
    // }

    // Function to add product to cart (called from onclick in HTML)
    window.addProductToCart = (name, price) => {
        const existing = cartItems.find((item) => item.name === name);
        if (existing) {
            existing.qty += 1;
        } else {
            cartItems.push({ name, price, qty: 1 });
        }
        updateCart();
    };

    // Event listeners for inputs that affect total
    [taxInput, discountInput, shippingInput].forEach((input) => {
        input.addEventListener("input", updateCart);
    });

    // Search filter
    searchBox.addEventListener("input", () => {
        const query = searchBox.value.toLowerCase();
        Array.from(products).forEach((card) => {
            const productName = card
                .querySelector("h3")
                .textContent.toLowerCase();
            if (productName.includes(query)) {
                card.style.display = "";
            } else {
                card.style.display = "none";
            }
        });
    });

    // Reset filter
    allCategoryBtn.addEventListener("click", () => {
        searchBox.value = "";
        Array.from(products).forEach((card) => {
            card.style.display = "";
        });
    });

    // Cancel button
    document.getElementById("cancelBtn").addEventListener("click", () => {
        cartItems.length = 0; // Clear the cart
        updateCart(); // Update display
        taxInput.value = 0;
        discountInput.value = 0;
        shippingInput.value = 0;
    });

    // Handle "Pay Now" button to show payment modal
    document.getElementById("payNowBtn").addEventListener("click", () => {
        if (cartItems.length === 0) {
            alert(
                "Please add products to the cart before proceeding to payment."
            );
            return;
        }
        const { total } = calculateTotals();

        // Set modal values
        modalTotalProducts.textContent = cartItems.length;
        modalOrderTax.textContent = (parseFloat(taxInput.value) || 0) + "%";
        modalDiscount.textContent = formatCurrency(
            parseFloat(discountInput.value) || 0
        );
        modalShipping.textContent = formatCurrency(
            parseFloat(shippingInput.value) || 0
        );
        modalTotalPayable.textContent = formatCurrency(total);

        // Pre-fill received amount with total
        receivedAmountInput.value = total.toFixed(2);
        modalChangeDue.textContent = formatCurrency(0); // Initialize change due

        paymentModal.classList.remove("hidden");
    });

    // Calculate change due in payment modal
    receivedAmountInput.addEventListener("input", () => {
        const receivedAmount = parseFloat(receivedAmountInput.value) || 0;
        const totalAmount =
            parseFloat(modalTotalPayable.textContent.replace("$", "").trim()) ||
            0;
        const changeDue = receivedAmount - totalAmount;
        modalChangeDue.textContent = formatCurrency(changeDue);
        if (changeDue < 0) {
            modalChangeDue.classList.add("text-red-500");
        } else {
            modalChangeDue.classList.remove("text-red-500");
        }
    });

    // Close payment modal
    closePaymentModalBtn.onclick = () => {
        paymentModal.classList.add("hidden");
    };

    // PREVIEW INVOICE BUTTON
    previewInvoiceBtn.onclick = () => {
        if (cartItems.length === 0) {
            alert(
                "The cart is empty. Please add items to preview the invoice."
            );
            return;
        }

        const customerName = customerNameInput.value.trim();
        const customerPhone = customerPhoneInput.value.trim();
        const invoiceValidity = invoiceValidityInput.value.trim();

        generateInvoice(customerName, invoiceValidity, customerPhone);
        invoiceModal.classList.remove("hidden");
        // Optionally, close payment modal if preview is in a separate window/tab
        // paymentModal.classList.add('hidden');
    };

    // SUBMIT PAYMENT BUTTON
    submitPaymentBtn.onclick = () => {
        const receivedAmount = parseFloat(receivedAmountInput.value) || 0;
        const totalAmount =
            parseFloat(modalTotalPayable.textContent.replace("$", "").trim()) ||
            0;
        const paymentMethod = paymentMethodSelect.value;

        if (receivedAmount < totalAmount) {
            alert("Received amount is less than total payable.");
            return;
        }

        if (paymentMethod === "") {
            alert("Please select a payment method.");
            return;
        }

        alert("Payment successful!");

        // Generate invoice content (this will be the final invoice)
        const customerName = customerNameInput.value.trim();
        const customerPhone = customerPhoneInput.value.trim();
        const invoiceValidity = invoiceValidityInput.value.trim();
        generateInvoice(customerName, invoiceValidity, customerPhone); // Re-generate to ensure latest data

        // Reset payment modal inputs
        receivedAmountInput.value = "";
        customerNameInput.value = "";
        customerPhoneInput.value = "";
        invoiceValidityInput.value = "";
        paymentMethodSelect.value = "";
        paymentNotesTextarea.value = "";

        // Reset cart and close payment modal
        cartItems.length = 0;
        updateCart();
        paymentModal.classList.add("hidden");

        // Show invoice modal (it will contain the final invoice)
        invoiceModal.classList.remove("hidden");
    };

    // Generate invoice function
    function generateInvoice(customerName, validity, phone) {
        invoiceContent.innerHTML = ""; // Clear previous invoice content
        const { taxAmount, discount, shipping, subtotalSum, total } =
            calculateTotals();

        createInvoice(
            invoiceContent,
            cartItems,
            parseFloat(taxInput.value) || 0,
            discount,
            shipping,
            customerName,
            validity,
            phone
        );
    }

    // Invoice creation function
    function createInvoice(
        container,
        cartItems,
        taxPercent = 0,
        discountVal = 0,
        shippingVal = 0,
        customerName = "",
        validity = "",
        phone = ""
    ) {
        const formatCurrency = (num) => `$${num.toFixed(2)}`;

        const div = document.createElement("div");
        div.className = "w-full bg-white text-xs print-area"; // Add print-area class
        div.style.border = "1px solid #ccc";

        // Calculate totals for invoice
        const subtotalSum = cartItems.reduce(
            (sum, item) => sum + item.price * item.qty,
            0
        );
        const taxAmount = (taxPercent / 100) * subtotalSum;
        const total = subtotalSum + taxAmount + shippingVal - discountVal;
        const totalDisplay = total < 0 ? 0 : total; // Ensure total doesn't go below zero

        // Get current date for Invoice Date
        const invoiceDate = new Date().toLocaleDateString();

        // Start building HTML with dynamic content
        div.innerHTML = `
  <div class="flex flex-col md:flex-row justify-between mb-4 p-4 print:p-0">
    <div class="w-1/3">
      <img src="backend/assets/logo/logo.jpg" class="w-22 h-20 object-contain rounded-full">
    </div>
    <div class="w-2/3 pl-4">
      <h1 class="text-2xl font-bold text-center mb-2">សៀមរាប ហ្គៀ</h1>
      <h3 class="text-lg text-center mb-4">SIEM REAP GEARS</h3>
    </div>
  </div>

  <div class="flex flex-col md:flex-row justify-between mb-4 p-4 print:p-0">
    <div class="w-full md:w-1/2 bg-gray-50 p-2 rounded shadow-sm md:mr-2 mb-2 md:mb-0">
      <h4 class=" mb-1">Company: SR Gears</h4>
      <h4 class=" mb-1 mt-2">Address:</h4>
      <p>#C02, St.Kompea Mother, MonduI I Village, Svay Dongkom Commune, SiemReap Town</p>
      <h4 class=" mb-1 mt-2">Tel: 098 222 500, 017 3000 31</h4>
    </div>
    <div class="w-full md:w-1/2 bg-gray-50 p-2 rounded shadow-sm md:ml-2">
      <h4 class=" mb-1">Quotation</h4>
      <p><span class="">Invoice Date:</span> ${invoiceDate}</p>
      <p><span class="">Customer Name:</span> ${
          customerName || "____________________"
      }</p>
      <p><span class="">Validity:</span> ${
          validity || "______________________________"
      }</p>
      <p><span class="">Phone:</span> ${
          phone || "_______________________________"
      }</p>
    </div>
  </div>

  <div class="overflow-x-auto mb-4 p-4 print:p-0">
    <table class="w-full border-collapse border border-gray-300 text-xs shadow-sm">
      <thead>
        <tr class="bg-gray-200">
          <th class="border border-gray-300 px-2 py-1 text-center">No</th>
          <th class="border border-gray-300 px-2 py-1 text-left">Product & Description</th>
          <th class="border border-gray-300 px-2 py-1 text-center">Price</th>
          <th class="border border-gray-300 px-2 py-1 text-center">QTY</th>
          <th class="border border-gray-300 px-2 py-1 text-center">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        ${cartItems
            .map(
                (item, i) => `
          <tr class="hover:bg-gray-100 transition-colors duration-300">
            <td class="border border-gray-300 px-2 py-1 text-center">${
                i + 1
            }</td>
            <td class="border border-gray-300 px-2 py-1">${item.name}</td>
            <td class="border border-gray-300 px-2 py-1 text-center">${formatCurrency(
                item.price
            )}</td>
            <td class="border border-gray-300 px-2 py-1 text-center">${
                item.qty
            }</td>
            <td class="border border-gray-300 px-2 py-1 text-center">${formatCurrency(
                item.price * item.qty
            )}</td>
          </tr>
        `
            )
            .join("")}
      </tbody>
    </table>
  </div>

  <div class="flex flex-col md:flex-row mb-4 gap-4 text-xs p-4 print:p-0">
    <div class="bg-gray-50 p-2 rounded shadow-sm md:w-1/2">
      <h4 class=" mb-1">Note: Before receiving the goods, you must check the quality and quantity that cannot be returned.</h4>
    </div>
    <div class="md:w-1/2 flex justify-end">
      <div class="w-48 bg-gray-50 p-2 rounded shadow-sm space-y-2">
        <div class="flex justify-between"><span class="">Subtotal:</span><span>${formatCurrency(
            subtotalSum
        )}</span></div>
        <div class="flex justify-between"><span class="">Tax (${taxPercent}%):</span><span>${formatCurrency(
            taxAmount
        )}</span></div>
        <div class="flex justify-between"><span class="">Shipping:</span><span>${formatCurrency(
            shippingVal
        )}</span></div>
        <div class="flex justify-between"><span class="">Discount:</span><span>${formatCurrency(
            discountVal
        )}</span></div>
        <div class="flex justify-between"><span class="">Total:</span><span>${formatCurrency(
            totalDisplay
        )}</span></div>
      </div>
    </div>
  </div>

  <div class="flex flex-col md:flex-row justify-between mt-8 px-4 text-xs print:p-0">
    <div class="w-full md:w-1/2 text-center mb-8 md:mb-0">
      <p>Customer Signature</p>
      <div class="mt-12 border-b-2 border-gray-400 mx-auto w-3/4"></div>
    </div>
    <div class="w-full md:w-1/2 text-center">
      <p>Seller Signature</p>
      <div class="mt-12 border-b-2 border-gray-400 mx-auto w-3/4"></div>
    </div>
  </div>
`;
        container.appendChild(div);
    }

    // Close invoice modal
    closeInvoiceBtn.onclick = () => {
        invoiceModal.classList.add("hidden");
    };

    // Print invoice
    printInvoiceBtn.onclick = () => {
        const printContent = invoiceContent.innerHTML;
        const originalBody = document.body.innerHTML;

        // Temporarily change body content to only the invoice for printing
        document.body.innerHTML = printContent;
        window.print();

        // Restore original content and reload to re-attach event listeners
        document.body.innerHTML = originalBody;
        window.location.reload();
    };

    // Initialize cart display
    updateCart();
});
