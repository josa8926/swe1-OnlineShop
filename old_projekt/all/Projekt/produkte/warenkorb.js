window.ware = JSON.parse(localStorage.getItem('ware')) || [];
function zurueck() {
  const ref = document.referrer;

    if (ref.includes("asia.html")) {
        window.location.href = "asia.html";
    } 
    else if (ref.includes("afro.html")) {
        window.location.href = "afro.html";
    } 
    else {
        window.location.href = "index.html";
    }
}
function showWare() {
  const productItem = document.querySelector(".product-item");
  if (productItem) {
    productItem.style.display = "none";
  }

  const cartPage = document.getElementById("cart-page");
  if (cartPage) {
    cartPage.style.display = "block";
  }

  const product = document.querySelector(".product");
  if (product) {
    product.style.display = "none";
  }

  let html = "";

  if (window.ware.length === 0) {
    html = "<p>Dein Warenkorb ist leer.</p>";
  } else {
    window.ware.forEach((item, index) => {
      html += `
        <div class="cart-item">
          <img class="cart-img" src="${item.image}" alt="${item.title}">
          <div class="cart-title">${item.title}</div>
          <div class="cart-bottom">
            <button onclick="changeQty(${index}, -1)">➖</button>
            <span>${item.qty}</span>
            <button onclick="changeQty(${index}, 1)">➕</button>
            <div class="cart-price">${item.price}</div>
          </div>
        </div>
      `;
    });
  }

  const cartContent = document.getElementById("cart-content");
  if (cartContent) {
    cartContent.innerHTML = html;
  }

  const wieder = document.querySelector(".wieder");
  if (wieder) {
    wieder.style.display = "block";
  }

  calcTotal();
}

function changeQty(index, delta) {
  const item = window.ware[index];

  if (delta > 0 && item.qty >= item.maxQty) {
    alert("Maximale Menge erreicht!");
    return;
  }

  item.qty += delta;

  if (item.qty <= 0) {
    window.ware.splice(index, 1);
  }
localStorage.setItem('ware', JSON.stringify(window.ware));
  updateCartCount();
  showWare();
}

function updateCartCount() {
  const count = window.ware.reduce((sum, item) => sum + item.qty, 0);
  const cartCountElement = document.getElementById("cart-count");
  if (cartCountElement) {
    cartCountElement.textContent = count;
  }
}

function calcTotal() {
  let sum = 0;
  window.ware.forEach(item => {
    const price = parseFloat(item.price.replace("€", "").replace(",", ".").trim());
    sum += price * item.qty;
  });

  const summaryElement = document.getElementById("cart-summary");
  if (summaryElement) {
    summaryElement.textContent = "Gesamt: " + sum.toFixed(2).replace(".", ",") + " €";
  }

  const checkoutBtn = document.getElementById("checkout-btn");
  if (checkoutBtn) {
    checkoutBtn.style.display = window.ware.length > 0 ? "block" : "none";
  }
}

function checkout() {
  renderOrderSummary();

  const daten = document.querySelector(".daten");
  const headerActions = document.querySelector(".header-actions");
  const sectionTitle = document.querySelector(".section-title");
  const wieder = document.querySelector(".wieder");
  const checkoutBtn = document.getElementById("checkout-btn");

  if (daten) daten.style.display = "block";
  if (headerActions) headerActions.style.display = "none";
  if (sectionTitle) sectionTitle.style.display = "none";
  if (wieder) wieder.style.display = "none";
  if (checkoutBtn) checkoutBtn.style.display = "none";

  const cartItems = document.querySelectorAll(".cart-item button");
  cartItems.forEach(btn => btn.style.display = "none");
}

function renderOrderSummary() {
  let html = "<h3>Ihre Bestellung:</h3>";

  window.ware.forEach(item => {
    const price = parseFloat(item.price.replace("€", "").replace(",", ".").trim());
    const total = price * item.qty;

    html += `
      <div class="cart-item">
        <img class="cart-img" src="${item.image}" alt="${item.title}">
        <div class="cart-title">${item.title}</div>
        <div class="cart-bottom">
          <span>Menge: ${item.qty}</span>
          <div class="cart-price">${item.price} × ${item.qty} = ${total.toFixed(2).replace(".", ",")} €</div>
        </div>
      </div>
    `;
  });

  const cartContent = document.getElementById("cart-content");
  if (cartContent) {
    cartContent.innerHTML = html;
  }
}
function ende() {
  let a = document.getElementById("vorname").value.trim();
  let b = document.getElementById("nachname").value.trim();
  let c = document.getElementById("adresse").value.trim();
  let d = document.getElementById("ort").value.trim();
  let f = document.getElementById("nummer").value.trim();
  let g = document.getElementById("e-mail").value.trim();
  let i = document.getElementById("handy").value.trim();
  let s = document.getElementById("zusatz").value.trim();
  if(a === "" || b === "" || c === "" || d === "" || f === "" || g === "" || i === "") {
    alert("Eingabefelder bitte ausfüllen!");
    return;
  }

  const orderData = {
    vorname: a,
    nachname: b,
    adresse: c,
    ort: d,
    plz: f,
    email: g,
    handy: i,
    zusatz: s,
    produkte: window.ware
  };

  fetch("save_order.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(orderData)
  })
  .then(res => res.text())
  .then(msg => console.log(msg));

  alert("Zahlung erfolgreich! Danke " + a + " " + b);

  // Warenkorb leeren
  window.ware.length = 0;
localStorage.setItem('ware', JSON.stringify(window.ware));
  updateCartCount();

  // Formular zurücksetzen
  document.getElementById("vorname").value = "";
  document.getElementById("nachname").value = "";
  document.getElementById("adresse").value = "";
  document.getElementById("ort").value = "";
  //const zusatz = document.getElementById("zusatz");
  //if (zusatz) zusatz.value = "";
  document.getElementById("zusatz").value = "";
  document.getElementById("nummer").value = "";
  document.getElementById("e-mail").value = "";
  document.getElementById("handy").value = "";

   window.location.href = "index.html";
}

document.addEventListener('DOMContentLoaded', () => {
  updateCartCount();

  const cartPage = document.getElementById("cart-page");
  if (cartPage) {
    showWare();
  }
});
