window.ware = JSON.parse(localStorage.getItem('ware')) || [];

fetch('lebensmittel.php')
  .then(res => res.json())
  .then(produkte => {
    const container = document.getElementById('produktListe');

    produkte.forEach(p => {
      const artikel = document.createElement('article');
      artikel.className = 'product-grid';

      const existingItem = window.ware.find(w => w.title === p.name);
      const verbleibendeAnzahl = existingItem
        ? p.menge - existingItem.qty
        : p.menge;
      const istAusverkauft = verbleibendeAnzahl <= 0;

      artikel.innerHTML = `
        <div class="product-image">
          <span class="item">${p.name}${istAusverkauft ? '<span class="ausverkauft-badge" style="color: #ff0000; font-weight: 700;"> AUSVERKAUFT</span>' : ''}</span>
          <img src="${p.bild_url}" alt="${p.name}">
        </div>

        <div class="product-title">${p.name}</div>

        <div class="product-bottom">
          <div class="product-price">
            <span class="product-price-main">${p.preis} €</span>
            <span class="product-price-unit">${p.preis_pro_kg} €/Kg</span>
          </div>

          ${!istAusverkauft
            ? `<button class="product-btn" onclick="addToWare(this)" data-max="${p.menge}">In den Warenkorb</button>`
            : `<button class="product-btn" disabled>Ausverkauft</button>`
          }
        </div>
      `;

      container.appendChild(artikel);
    });
  });

function addToWare(button) {
  const card = button.closest(".product-grid");
  const item = {
    title: card.querySelector(".product-title").textContent,
    price: card.querySelector(".product-price-main").textContent,
    image: card.querySelector(".product-image img").src,
    qty: 1,
    maxQty: parseInt(button.dataset.max)
  };

  const existingItem = window.ware.find(w => w.title === item.title);

  if (existingItem) {
    if (existingItem.qty < existingItem.maxQty) {
      existingItem.qty++;

      if (existingItem.qty >= existingItem.maxQty) {
        button.disabled = true;
        button.textContent = "Ausverkauft";

        const badge = card.querySelector(".item");
        if (!badge.querySelector(".ausverkauft-badge")) {
          const ausverkauftSpan = document.createElement("span");
          ausverkauftSpan.textContent = " AUSVERKAUFT";
          ausverkauftSpan.style.color = "#ff0000";
          ausverkauftSpan.style.fontWeight = "700";
          ausverkauftSpan.className = "ausverkauft-badge";
          badge.appendChild(ausverkauftSpan);
        }
      }
    } else {
      alert("Maximale Menge erreicht!");
      return;
    }
  } else {
    window.ware.push(item);

    if (item.qty >= item.maxQty) {
      button.disabled = true;
      button.textContent = "Ausverkauft";

      const badge = card.querySelector(".item");
      if (!badge.querySelector(".ausverkauft-badge")) {
        const ausverkauftSpan = document.createElement("span");
        ausverkauftSpan.textContent = " AUSVERKAUFT";
        ausverkauftSpan.style.color = "#ff0000";
        ausverkauftSpan.style.fontWeight = "700";
        ausverkauftSpan.className = "ausverkauft-badge";
        badge.appendChild(ausverkauftSpan);
      }
    }
  }

  localStorage.setItem('ware', JSON.stringify(window.ware));
  updateCartCount();

  button.textContent = "✓ Hinzugefügt";
  setTimeout(() => {
    if (!button.disabled) {
      button.textContent = "In den Warenkorb";
    }
  }, 1000);
   }

function updateCartCount() {
  const count = window.ware.reduce((sum, item) => sum + item.qty, 0);
  const cartCountElement = document.getElementById("cart-count");
  if (cartCountElement) {
    cartCountElement.textContent = count;
  }
}

function searchProducts() {
  const text = document.getElementById("search-box").value.toLowerCase();
  document.querySelectorAll(".product-grid").forEach(card => {
    const title = card.querySelector(".product-title").textContent.toLowerCase();
    card.style.display = title.includes(text) ? "block" : "none";
  });
}

document.addEventListener('DOMContentLoaded', () => {
  const searchBtn = document.getElementById("search-btn");
  const searchBox = document.getElementById("search-box");

  if (searchBtn) {
    searchBtn.onclick = searchProducts;
  }

  if (searchBox) {
    searchBox.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') {
        searchProducts();
      }
    });
  }

  updateCartCount();
});
