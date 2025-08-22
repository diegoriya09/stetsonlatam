<header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#ffffff] px-10 py-3">
        <div class="flex items-center gap-8">
          <div class="flex items-center gap-4 text-[#3c3737]">
            <div class="size-4">
              <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fill="currentColor"></path>
              </svg>
            </div>
            <h2 class="text-[#181411] text-lg font-bold leading-tight tracking-[-0.015em]"><a href="index.php">Stetson Latam</a></h2>
          </div>
          <div class="flex items-center gap-9">
            <a class="text-[#3c3737] text-sm font-medium leading-normal" href="hats.php">Hats</a>
            <a class="text-[#3c3737] text-sm font-medium leading-normal" href="caps.php">Caps</a>
          </div>
        </div>
        <div class="flex flex-1 justify-end gap-8">
          <label class="flex flex-col min-w-40 !h-10 max-w-64">
            <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
              <div
                class="text-[#3c3737] flex border-none bg-[#f1eeea] items-center justify-center pl-4 rounded-l-lg border-r-0"
                data-icon="MagnifyingGlass"
                data-size="24px"
                data-weight="regular">
                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                  <path
                    d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z"></path>
                </svg>
              </div>
              <div class="relative">
                <input
                  id="search-input"
                  name="q"
                  placeholder="Search..."
                  autocomplete="off"
                  class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#3c3737] focus:outline-0 focus:ring-0 border-none bg-[#f1eeea] focus:border-none h-full placeholder:text-[#3c3737] px-4 rounded-l-none border-l-0 pl-2 text-base font-normal leading-normal"
                  value="" />
                <div id="search-results" class="absolute top-full left-0 w-full bg-white border rounded-lg shadow-md hidden z-50 
                max-h-80 overflow-y-auto"></div>
              </div>
            </div>
          </label>
          <script>
            const input = document.getElementById("search-input");
            const resultsBox = document.getElementById("search-results");

            let controller = null;
            async function doSearch(q) {
              if (controller) controller.abort();
              controller = new AbortController();

              if (q.trim() === "") {
                resultsBox.classList.add("hidden");
                return;
              }

              try {
                const res = await fetch("php/search.php?q=" + encodeURIComponent(q), {
                  signal: controller.signal
                });
                if (!res.ok) throw new Error("HTTP " + res.status);
                const data = await res.json();

                if (!data.productos.length && !data.categorias.length) {
                  resultsBox.innerHTML = "<p class='p-2 text-gray-500'>No results found</p>";
                } else {
                  let html = "";
                  if (data.productos.length) {
                    html += "<h4 class='px-2 py-1 font-bold text-sm text-gray-600'>Products</h4>";
                    data.productos.forEach(p => {
                      html += `
            <a href="${p.url}" class="flex items-center gap-2 p-2 hover:bg-gray-100">
              <img src="${p.image}" class="w-10 h-10 object-contain rounded">
              <span>${p.title}</span>
            </a>
          `;
                    });
                  }
                  resultsBox.innerHTML = html;
                }

                resultsBox.classList.remove("hidden");
              } catch (err) {
                if (err.name !== "AbortError") console.error(err);
              }
            }

            let timer;
            input.addEventListener("input", () => {
              clearTimeout(timer);
              timer = setTimeout(() => doSearch(input.value), 300);
            });
          </script>
          <div class="flex gap-2">
            <button
              id="logout-btn"
              style="display:none;"
              class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-[#f1eeea] text-[#3c3737] gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
              <div class="text-[#3c3737]" data-icon="SignOut" data-size="24px" data-weight="regular">
                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                  <path d="M216,128a8,8,0,0,1-8,8H104v16a8,8,0,0,1-13.66,5.66l-32-32a8,8,0,0,1,0-11.32l32-32A8,8,0,0,1,104,104v16h104A8,8,0,0,1,216,128ZM128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Z"></path>
                </svg>
              </div>
            </button>
            <button
              id="user-btn"
              class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-[#f1eeea] text-[#3c3737] gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
              <div class="text-[#3c3737]" data-icon="User" data-size="20px" data-weight="regular">
                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                  <path
                    d="M230.92,212c-15.23-26.33-38.7-45.21-66.09-54.16a72,72,0,1,0-73.66,0C63.78,166.78,40.31,185.66,25.08,212a8,8,0,1,0,13.85,8c18.84-32.56,52.14-52,89.07-52s70.23,19.44,89.07,52a8,8,0,1,0,13.85-8ZM72,96a56,56,0,1,1,56,56A56.06,56.06,0,0,1,72,96Z"></path>
                </svg>
              </div>
            </button>
            <button
              id="cart-btn"
              class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-[#f1eeea] text-[#3c3737] gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
              <div class="text-[#3c3737]" data-icon="ShoppingBag" data-size="20px" data-weight="regular">
                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                  <path
                    d="M216,40H40A16,16,0,0,0,24,56V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40Zm0,160H40V56H216V200ZM176,88a48,48,0,0,1-96,0,8,8,0,0,1,16,0,32,32,0,0,0,64,0,8,8,0,0,1,16,0Z"></path>
                </svg>
              </div>
            </button>
            <a href="index.php">
              <div
                class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
                style='background-image: url("img/logo.webp");'></div>
            </a>
          </div>
        </div>
      </header>