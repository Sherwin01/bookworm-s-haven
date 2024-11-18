// Fetch the book data
fetch('fetch_books.php')
  .then(response => response.json())
  .then(data => {
    // Best Sellers
    const bestSellersContainer = document.querySelector('.carousel-inner');
    data.bestSellers.forEach((book, index) => {
      const isActive = index === 0 ? 'active' : '';
      const bookItem = `
        <div class="carousel-item ${isActive}">
          <div class="d-flex justify-content-center">
            <div class="card" style="width: 18rem;">
              <img src="images/${book.image || 'default.jpg'}" class="card-img-top" alt="${book.title}">
              <div class="card-body">
                <h5 class="card-title">${book.title}</h5>
                <p class="card-text">${book.author}</p>
                <p class="card-text">$${book.price.toFixed(2)}</p>
                <a href="fetch_book.php?book_id=${book._id}" class="btn btn-primary">Add to Cart</a>
              </div>
            </div>
          </div>
        </div>
      `;
      bestSellersContainer.innerHTML += bookItem;
    });

    // All Products
    const allProductsContainer = document.querySelector('#all-products-container');
    data.allBooks.forEach(book => {
      const productItem = `
        <div class="col-md-3 mb-4">
          <div class="card">
            <img src="images/${book.image || 'default.jpg'}" class="card-img-top" alt="${book.title}">
            <div class="card-body">
              <h5 class="card-title">${book.title}</h5>
              <p class="card-text">${book.author}</p>
              <p class="card-text">$${book.price.toFixed(2)}</p>
              <a href="fetch_book.php?book_id=${book._id}" class="btn btn-primary">Add to Cart</a>
            </div>
          </div>
        </div>
      `;
      allProductsContainer.innerHTML += productItem;
    });
  })
  .catch(error => console.error('Error fetching book data:', error));
