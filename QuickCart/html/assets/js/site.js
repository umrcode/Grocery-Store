/* site.js - handles include loading, product data loading, cart operations */
async function includeFragments(){
  const nodes = Array.from(document.querySelectorAll('[data-include]'));
  const promises = nodes.map(async el=>{
    try{
      const res = await fetch(el.getAttribute('data-include'));
      if(res.ok) el.innerHTML = await res.text();
    }catch(e){ /* ignore */ }
  });
  await Promise.all(promises);
}

// Simple cart using localStorage
const Cart = {
  get(){ return JSON.parse(localStorage.getItem('qc_cart')||'[]'); },
  save(items){ localStorage.setItem('qc_cart', JSON.stringify(items)); updateCartBadge(); },
  add(product){ const items=this.get(); const found=items.find(i=>i.id===product.id); if(found){ found.qty+=1; } else { items.push({...product, qty:1}); } this.save(items); },
  remove(id){ let items=this.get().filter(i=>i.id!==id); this.save(items); },
  clear(){ this.save([]); }
}

function updateCartBadge(){ const cnt = Cart.get().reduce((s,i)=>s+i.qty,0); const el=document.querySelectorAll('.cart-count'); el.forEach(x=>x.textContent=cnt); }

async function loadProducts(){
  try{
    const res = await fetch('assets/data/products.json');
    if(!res.ok) return [];
    return await res.json();
  }catch(e){ return []; }
}

async function renderProductsList(containerSelector){
  let products = await loadProducts();
  const params = new URLSearchParams(location.search); const q = params.get('q');
  if(q){ const s=q.toLowerCase(); products = products.filter(p => p.title.toLowerCase().includes(s) || p.short.toLowerCase().includes(s) || p.description.toLowerCase().includes(s)); }
  const container = document.querySelector(containerSelector);
  if(!container) return;
  if(products.length===0){ container.innerHTML = '<p class="text-muted">No products found.</p>'; return; }
  container.innerHTML = products.map(p=>`
    <div class="col-sm-6 col-md-4">
      <div class="card mb-3">
        <img src="${p.image}" class="card-img-top" alt="${p.title}">
        <div class="card-body">
          <h5 class="card-title">${p.title}</h5>
          <p class="card-text text-muted">${p.short}</p>
          <div class="d-flex justify-content-between align-items-center">
            <strong>₱${p.price.toFixed(2)}</strong>
            <div>
              <a href="product.html?id=${p.id}" class="btn btn-sm btn-outline-primary">View</a>
              <button class="btn btn-sm btn-primary add-cart" data-id="${p.id}">Add</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  `).join('');
  container.querySelectorAll('.add-cart').forEach(btn=>btn.addEventListener('click', async e=>{
    const id = btn.getAttribute('data-id'); const products = await loadProducts(); const p = products.find(x=>x.id==id); if(p) Cart.add({id:p.id, title:p.title, price:p.price});
  }));
}

async function renderProductDetails(){
  const params = new URLSearchParams(location.search); const id = params.get('id'); if(!id) return;
  const products = await loadProducts(); const p = products.find(x=>x.id==id); if(!p) return;
  const target = document.querySelector('#product-detail'); if(!target) return;
  target.innerHTML = `
    <div class="row">
      <div class="col-md-5">
        <img src="${p.image}" class="img-fluid" alt="${p.title}">
      </div>
      <div class="col-md-7">
        <h2>${p.title}</h2>
        <p class="text-muted">${p.short}</p>
        <p>${p.description}</p>
        <h4>₱${p.price.toFixed(2)}</h4>
        <button class="btn btn-primary" id="addToCartBtn">Add to cart</button>
      </div>
    </div>
  `;
  document.querySelector('#addToCartBtn').addEventListener('click', ()=>{ Cart.add({id:p.id,title:p.title,price:p.price}); });
}

async function renderCartPage(){
  const items = Cart.get(); const target = document.querySelector('#cart-items'); if(!target) return;
  if(items.length===0){ target.innerHTML = '<p>Your cart is empty.</p>'; return; }
  target.innerHTML = `
    <table class="table">
      <thead><tr><th>Item</th><th>Qty</th><th>Price</th><th></th></tr></thead>
      <tbody>
        ${items.map(i=>`<tr><td>${i.title}</td><td>${i.qty}</td><td>₱${(i.price*i.qty).toFixed(2)}</td><td><button class="btn btn-sm btn-danger remove-item" data-id="${i.id}">Remove</button></td></tr>`).join('')}
      </tbody>
    </table>
    <div class="d-flex justify-content-between align-items-center"><strong>Total:</strong><h4>₱${items.reduce((s,i)=>s+i.price*i.qty,0).toFixed(2)}</h4></div>
    <a href="checkout.html" class="btn btn-success mt-3">Proceed to Checkout</a>
  `;
  target.querySelectorAll('.remove-item').forEach(b=>b.addEventListener('click', e=>{ Cart.remove(b.getAttribute('data-id')); renderCartPage(); }));
}

// Initialize
document.addEventListener('DOMContentLoaded', async ()=>{
  await includeFragments();
  updateCartBadge();
  // run page-specific renders
  if(document.querySelector('#products-grid')) renderProductsList('#products-grid');
  if(document.querySelector('#product-detail')) renderProductDetails();
  if(document.querySelector('#cart-items')) renderCartPage();
});
