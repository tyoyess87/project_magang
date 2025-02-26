document.addEventListener("DOMContentLoaded", () => {
    const minusButton = document.getElementById("minus");
    const plusButton = document.getElementById("plus");
    const quantityInput = document.getElementById("quantity");
    const totalDisplay = document.getElementById("total");
  
    const pricePerUnit = 52.000;
  
    // Update total price
    const updateTotal = () => {
      const quantity = parseInt(quantityInput.value, 10);
      totalDisplay.textContent = `Total: $${quantity * pricePerUnit}`;
    };
  
    // Decrease quantity
    minusButton.addEventListener("click", () => {
      let quantity = parseInt(quantityInput.value, 10);
      if (quantity > 1) {
        quantity--;
        quantityInput.value = quantity;
        updateTotal();
      }
    });
  
    // Increase quantity
    plusButton.addEventListener("click", () => {
      let quantity = parseInt(quantityInput.value, 10);
      quantity++;
      quantityInput.value = quantity;
      updateTotal();
    });
  });
  