<html>

<head>
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
  <link
    rel="stylesheet"
    as="style"
    onload="this.rel='stylesheet'"
    href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Serif%3Awght%40400%3B500%3B700%3B900&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900" />

  <title>Profile</title>
  <link rel="icon" href="img/logo.webp" type="image/x-icon" loading="lazy">

  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="relative flex size-full min-h-screen flex-col bg-white group/design-root overflow-x-hidden" style='font-family: "Noto Serif", "Noto Sans", sans-serif;'>
    <div class="layout-container flex h-full grow flex-col">
      <div class="gap-1 px-6 flex flex-1 justify-center py-5">
        <div class="layout-content-container flex flex-col w-80">
          <div class="flex h-full min-h-[700px] flex-col justify-between bg-white p-4">
            <div class="flex flex-col gap-4">
              <div class="flex items-center gap-3">
                <div
                  class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
                  style='background-image: url("img/logo.webp");'></div>
                <h1 id="profile-name-hero" class="text-[#181411] text-base font-medium leading-normal"><?php echo $userName ? $userName : '&nbsp;'; ?></h1>
              </div>
              <div class="flex flex-col gap-2">
                <div class="flex items-center gap-3 px-3 py-2 rounded-lg bg-[#f4f2f0]">
                  <div class="text-[#181411]" data-icon="House" data-size="24px" data-weight="fill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M224,115.55V208a16,16,0,0,1-16,16H168a16,16,0,0,1-16-16V168a8,8,0,0,0-8-8H112a8,8,0,0,0-8,8v40a16,16,0,0,1-16,16H48a16,16,0,0,1-16-16V115.55a16,16,0,0,1,5.17-11.78l80-75.48.11-.11a16,16,0,0,1,21.53,0,1.14,1.14,0,0,0,.11.11l80,75.48A16,16,0,0,1,224,115.55Z"></path>
                    </svg>
                  </div>
                  <p class="text-[#181411] text-sm font-medium leading-normal">Overview</p>
                </div>
                <div class="flex items-center gap-3 px-3 py-2">
                  <div class="text-[#181411]" data-icon="Truck" data-size="24px" data-weight="regular">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M247.42,117l-14-35A15.93,15.93,0,0,0,218.58,72H184V64a8,8,0,0,0-8-8H24A16,16,0,0,0,8,72V184a16,16,0,0,0,16,16H41a32,32,0,0,0,62,0h50a32,32,0,0,0,62,0h17a16,16,0,0,0,16-16V120A7.94,7.94,0,0,0,247.42,117ZM184,88h34.58l9.6,24H184ZM24,72H168v64H24ZM72,208a16,16,0,1,1,16-16A16,16,0,0,1,72,208Zm81-24H103a32,32,0,0,0-62,0H24V152H168v12.31A32.11,32.11,0,0,0,153,184Zm31,24a16,16,0,1,1,16-16A16,16,0,0,1,184,208Zm48-24H215a32.06,32.06,0,0,0-31-24V128h48Z"></path>
                    </svg>
                  </div>
                  <p class="text-[#181411] text-sm font-medium leading-normal"><a href="#recent-orders">Orders</a></p>
                </div>
                <div class="flex items-center gap-3 px-3 py-2">
                  <div class="text-[#181411]" data-icon="CreditCard" data-size="24px" data-weight="regular">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M224,48H32A16,16,0,0,0,16,64V192a16,16,0,0,0,16,16H224a16,16,0,0,0,16-16V64A16,16,0,0,0,224,48Zm0,16V88H32V64Zm0,128H32V104H224v88Zm-16-24a8,8,0,0,1-8,8H168a8,8,0,0,1,0-16h32A8,8,0,0,1,208,168Zm-64,0a8,8,0,0,1-8,8H120a8,8,0,0,1,0-16h16A8,8,0,0,1,144,168Z"></path>
                    </svg>
                  </div>
                  <p class="text-[#181411] text-sm font-medium leading-normal"><a href="#payment-methods">Payment Methods</a></ p>
                </div>
                <div class="flex items-center gap-3 px-3 py-2">
                  <div class="text-[#181411]" data-icon="MapPin" data-size="24px" data-weight="regular">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M128,64a40,40,0,1,0,40,40A40,40,0,0,0,128,64Zm0,64a24,24,0,1,1,24-24A24,24,0,0,1,128,128Zm0-112a88.1,88.1,0,0,0-88,88c0,31.4,14.51,64.68,42,96.25a254.19,254.19,0,0,0,41.45,38.3,8,8,0,0,0,9.18,0A254.19,254.19,0,0,0,174,200.25c27.45-31.57,42-64.85,42-96.25A88.1,88.1,0,0,0,128,16Zm0,206c-16.53-13-72-60.75-72-118a72,72,0,0,1,144,0C200,161.23,144.53,209,128,222Z"></path>
                    </svg>
                  </div>
                  <p class="text-[#181411] text-sm font-medium leading-normal"><a href="#addresses">Addresses</a></p>
                </div>
                <div class="flex items-center gap-3 px-3 py-2">
                  <div class="text-[#181411]" data-icon="Gear" data-size="24px" data-weight="regular">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M128,80a48,48,0,1,0,48,48A48.05,48.05,0,0,0,128,80Zm0,80a32,32,0,1,1,32-32A32,32,0,0,1,128,160Zm88-29.84q.06-2.16,0-4.32l14.92-18.64a8,8,0,0,0,1.48-7.06,107.21,107.21,0,0,0-10.88-26.25,8,8,0,0,0-6-3.93l-23.72-2.64q-1.48-1.56-3-3L186,40.54a8,8,0,0,0-3.94-6,107.71,107.71,0,0,0-26.25-10.87,8,8,0,0,0-7.06,1.49L130.16,40Q128,40,125.84,40L107.2,25.11a8,8,0,0,0-7.06-1.48A107.6,107.6,0,0,0,73.89,34.51a8,8,0,0,0-3.93,6L67.32,64.27q-1.56,1.49-3,3L40.54,70a8,8,0,0,0-6,3.94,107.71,107.71,0,0,0-10.87,26.25,8,8,0,0,0,1.49,7.06L40,125.84Q40,128,40,130.16L25.11,148.8a8,8,0,0,0-1.48,7.06,107.21,107.21,0,0,0,10.88,26.25,8,8,0,0,0,6,3.93l23.72,2.64q1.49,1.56,3,3L70,215.46a8,8,0,0,0,3.94,6,107.71,107.71,0,0,0,26.25,10.87,8,8,0,0,0,7.06-1.49L125.84,216q2.16.06,4.32,0l18.64,14.92a8,8,0,0,0,7.06,1.48,107.21,107.21,0,0,0,26.25-10.88,8,8,0,0,0,3.93-6l2.64-23.72q1.56-1.48,3-3L215.46,186a8,8,0,0,0,6-3.94,107.71,107.71,0,0,0,10.87-26.25,8,8,0,0,0-1.49-7.06Zm-16.1-6.5a73.93,73.93,0,0,1,0,8.68,8,8,0,0,0,1.74,5.48l14.19,17.73a91.57,91.57,0,0,1-6.23,15L187,173.11a8,8,0,0,0-5.1,2.64,74.11,74.11,0,0,1-6.14,6.14,8,8,0,0,0-2.64,5.1l-2.51,22.58a91.32,91.32,0,0,1-15,6.23l-17.74-14.19a8,8,0,0,0-5-1.75h-.48a73.93,73.93,0,0,1-8.68,0,8,8,0,0,0-5.48,1.74L100.45,215.8a91.57,91.57,0,0,1-15-6.23L82.89,187a8,8,0,0,0-2.64-5.1,74.11,74.11,0,0,1-6.14-6.14,8,8,0,0,0-5.1-2.64L46.43,170.6a91.32,91.32,0,0,1-6.23-15l14.19-17.74a8,8,0,0,0,1.74-5.48,73.93,73.93,0,0,1,0-8.68,8,8,0,0,0-1.74-5.48L40.2,100.45a91.57,91.57,0,0,1,6.23-15L69,82.89a8,8,0,0,0,5.1-2.64,74.11,74.11,0,0,1,6.14-6.14A8,8,0,0,0,82.89,69L85.4,46.43a91.32,91.32,0,0,1,15-6.23l17.74,14.19a8,8,0,0,0,5.48,1.74,73.93,73.93,0,0,1,8.68,0,8,8,0,0,0,5.48-1.74L155.55,40.2a91.57,91.57,0,0,1,15,6.23L173.11,69a8,8,0,0,0,2.64,5.1,74.11,74.11,0,0,1,6.14,6.14,8,8,0,0,0,5.1,2.64l22.58,2.51a91.32,91.32,0,0,1,6.23,15l-14.19,17.74A8,8,0,0,0,199.87,123.66Z"></path>
                    </svg>
                  </div>
                  <p class="text-[#181411] text-sm font-medium leading-normal"><a href="#settings">Settings</a></p>
                </div>
                <div class="flex items-center gap-3 px-3 py-2">
                  <div class="text-[#181411]" data-icon="Question" data-size="24px" data-weight="regular">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M140,180a12,12,0,1,1-12-12A12,12,0,0,1,140,180ZM128,72c-22.06,0-40,16.15-40,36v4a8,8,0,0,0,16,0v-4c0-11,10.77-20,24-20s24,9,24,20-10.77,20-24,20a8,8,0,0,0-8,8v8a8,8,0,0,0,16,0v-.72c18.24-3.35,32-17.9,32-35.28C168,88.15,150.06,72,128,72Zm104,56A104,104,0,1,1,128,24,104.11,104.11,0,0,1,232,128Zm-16,0a88,88,0,1,0-88,88A88.1,88.1,0,0,0,216,128Z"></path>
                    </svg>
                  </div>
                  <p class="text-[#181411] text-sm font-medium leading-normal"><a href="#help">Help</a></p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
          <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#f4f2f0] px-10 py-3">
            <div class="flex items-center gap-8">
              <div class="flex items-center gap-4 text-[#181411]">
                <div class="size-4">
                  <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M12.0799 24L4 19.2479L9.95537 8.75216L18.04 13.4961L18.0446 4H29.9554L29.96 13.4961L38.0446 8.75216L44 19.2479L35.92 24L44 28.7521L38.0446 39.2479L29.96 34.5039L29.9554 44H18.0446L18.04 34.5039L9.95537 39.2479L4 28.7521L12.0799 24Z"
                      fill="currentColor"></path>
                  </svg>
                </div>
                <h2 class="text-[#181411] text-lg font-bold leading-tight tracking-[-0.015em]"><a href="index.php">Stetson Latam</a></h2>
              </div>
              <div class="flex items-center gap-9">
                <a class="text-[#181411] text-sm font-medium leading-normal" onclick="reloadPage()">Profile</a>
                <a class="text-[#181411] text-sm font-medium leading-normal" href="#">Settings</a>
              </div>
            </div>
            <div class="flex flex-1 justify-end gap-8">
              <label class="flex flex-col min-w-40 !h-10 max-w-64">
                <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
                  <div
                    class="text-[#7a7671] flex border-none bg-[#f3f2f2] items-center justify-center pl-4 rounded-l-lg border-r-0"
                    data-icon="MagnifyingGlass"
                    data-size="24px"
                    data-weight="regular">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z"></path>
                    </svg>
                  </div>
                  <input
                    placeholder="Search"
                    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#151514] focus:outline-0 focus:ring-0 border-none bg-[#f3f2f2] focus:border-none h-full placeholder:text-[#7a7671] px-4 rounded-l-none border-l-0 pl-2 text-base font-normal leading-normal"
                    value="" />
                </div>
              </label>
              <div class="flex gap-2">
                <button
                  id="logout-btn"
                  style="display:none;"
                  class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-[#f4f2f0] text-[#181411] gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
                  <div class="text-[#181411]" data-icon="SignOut" data-size="20px" data-weight="regular">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                      <path d="M216,128a8,8,0,0,1-8,8H104v16a8,8,0,0,1-13.66,5.66l-32-32a8,8,0,0,1,0-11.32l32-32A8,8,0,0,1,104,104v16h104A8,8,0,0,1,216,128ZM128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Z"></path>
                    </svg>
                  </div>
                </button>
                <button
                  id="user-btn"
                  class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-[#f4f2f0] text-[#181411] gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
                  <div class="text-[#181411]" data-icon="User" data-size="20px" data-weight="regular">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M230.92,212c-15.23-26.33-38.7-45.21-66.09-54.16a72,72,0,1,0-73.66,0C63.78,166.78,40.31,185.66,25.08,212a8,8,0,1,0,13.85,8c18.84-32.56,52.14-52,89.07-52s70.23,19.44,89.07,52a8,8,0,1,0,13.85-8ZM72,96a56,56,0,1,1,56,56A56.06,56.06,0,0,1,72,96Z"></path>
                    </svg>
                  </div>
                </button>
                <button
                  id="cart-btn"
                  class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-[#f4f2f0] text-[#181411] gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
                  <div class="text-[#181411]" data-icon="ShoppingBag" data-size="20px" data-weight="regular">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M216,40H40A16,16,0,0,0,24,56V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40Zm0,160H40V56H216V200ZM176,88a48,48,0,0,1-96,0,8,8,0,0,1,16,0,32,32,0,0,0,64,0,8,8,0,0,1,16,0Z"></path>
                    </svg>
                  </div>
                </button>
              </div>
          </header>
          <div class="flex flex-wrap justify-between gap-3 p-4">
            <p class="text-[#181411] tracking-light text-[32px] font-bold leading-tight min-w-72">Overview</p>
          </div>
          <div class="flex p-4 @container">
            <div class="flex w-full flex-col gap-4 @[520px]:flex-row @[520px]:justify-between @[520px]:items-center">
              <div class="flex gap-4">
                <div
                  class="bg-center bg-no-repeat aspect-square bg-cover rounded-full min-h-32 w-32"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBob1EGrJstHfosfZDim_LplEbLnOey2nN6bQd-RljtyfmwtpW8DezCD8j49vxdoNIPvVTjy6cIglBAJi-i4mZFA96cdwEROSGNMdnUvcIdbXxFntgFyDjdIEVs8KtDT6ElykLt9kUsF10DuGP51R4p7BF-xMJvICLAHwaTQTa1Dsl_RP5IRPlmTDYqCZzFy2OrnQu-OaMWTs9lEatZ10IinFaIzL2eLNmmr8QsZLiHHa1C8yBw1k5n8Ci9T0zCwrKuqwWNg_TJNmue");'></div>
                <div class="flex flex-col justify-center">
                  <h1 id="profile-name" class="text-[#181411] text-base font-medium leading-normal"><?php echo $userName ? $userName : '&nbsp;'; ?></h1>
                </div>
              </div>
            </div>
          </div>
          <section id="recent-orders">
            <h2 class="text-[#181411] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Recent Orders</h2>
            <div class="px-4 py-3 @container">
              <div class="flex overflow-hidden rounded-lg border border-[#e5e0dc] bg-white">
                <table class="flex-1">
                  <thead>
                    <tr class="bg-white">
                      <th class="table-47c2a25e-8e8a-467c-8886-8b69ef66eea4-column-120 px-4 py-3 text-left text-[#181411] w-[400px] text-sm font-medium leading-normal">Order</th>
                      <th class="table-47c2a25e-8e8a-467c-8886-8b69ef66eea4-column-240 px-4 py-3 text-left text-[#181411] w-[400px] text-sm font-medium leading-normal">Date</th>
                      <th class="table-47c2a25e-8e8a-467c-8886-8b69ef66eea4-column-360 px-4 py-3 text-left text-[#181411] w-60 text-sm font-medium leading-normal">Status</th>
                      <th class="table-47c2a25e-8e8a-467c-8886-8b69ef66eea4-column-480 px-4 py-3 text-left text-[#181411] w-[400px] text-sm font-medium leading-normal">Total</th>
                    </tr>
                  </thead>
                  <tbody id="orders-list">
                    <!-- Orders will be injected here by JS -->
                  </tbody>
                </table>
              </div>
              <style>
                @container(max-width:120px) {
                  .table-47c2a25e-8e8a-467c-8886-8b69ef66eea4-column-120 {
                    display: none;
                  }
                }

                @container(max-width:240px) {
                  .table-47c2a25e-8e8a-467c-8886-8b69ef66eea4-column-240 {
                    display: none;
                  }
                }

                @container(max-width:360px) {
                  .table-47c2a25e-8e8a-467c-8886-8b69ef66eea4-column-360 {
                    display: none;
                  }
                }

                @container(max-width:480px) {
                  .table-47c2a25e-8e8a-467c-8886-8b69ef66eea4-column-480 {
                    display: none;
                  }
                }
              </style>
            </div>
            <div class="flex px-4 py-3 justify-start">
              <button
                class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#f4f2f0] text-[#181411] text-sm font-bold leading-normal tracking-[0.015em]">
                <span class="truncate"><a href="myorders.php">View All Orders</a></span>
              </button>
            </div>
          </section>
          <section id="payment-methods">
            <h2 class="text-[#181411] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Payment Methods</h2>
            <div class="flex items-center gap-4 bg-white px-4 min-h-[72px] py-2 justify-between">
              <div class="flex items-center gap-4">
                <div class="bg-center bg-no-repeat aspect-video bg-contain h-6 w-10 shrink-0" style='background-image: url("/visa.svg");'></div>
                <div class="flex flex-col justify-center">
                  <p class="text-[#181411] text-base font-medium leading-normal line-clamp-1">Visa ...1234</p>
                  <p class="text-[#887563] text-sm font-normal leading-normal line-clamp-2">Expires 05/25</p>
                </div>
              </div>
              <div class="shrink-0">
                <div class="text-[#181411] flex size-7 items-center justify-center" data-icon="CaretRight" data-size="24px" data-weight="regular">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                    <path d="M181.66,133.66l-80,80a8,8,0,0,1-11.32-11.32L164.69,128,90.34,53.66a8,8,0,0,1,11.32-11.32l80,80A8,8,0,0,1,181.66,133.66Z"></path>
                  </svg>
                </div>
              </div>
            </div>
            <div class="flex items-center gap-4 bg-white px-4 min-h-[72px] py-2 justify-between">
              <div class="flex items-center gap-4">
                <div class="bg-center bg-no-repeat aspect-video bg-contain h-6 w-10 shrink-0" style='background-image: url("/mastercard.svg");'></div>
                <div class="flex flex-col justify-center">
                  <p class="text-[#181411] text-base font-medium leading-normal line-clamp-1">MasterCard ...5678</p>
                  <p class="text-[#887563] text-sm font-normal leading-normal line-clamp-2">Expires 08/26</p>
                </div>
              </div>
              <div class="shrink-0">
                <div class="text-[#181411] flex size-7 items-center justify-center" data-icon="CaretRight" data-size="24px" data-weight="regular">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                    <path d="M181.66,133.66l-80,80a8,8,0,0,1-11.32-11.32L164.69,128,90.34,53.66a8,8,0,0,1,11.32-11.32l80,80A8,8,0,0,1,181.66,133.66Z"></path>
                  </svg>
                </div>
              </div>
            </div>
            <div class="flex px-4 py-3 justify-start">
              <button
                class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#f4f2f0] text-[#181411] text-sm font-bold leading-normal tracking-[0.015em]">
                <span class="truncate">Add Payment Method</span>
              </button>
            </div>
          </section>
          <section id="addresses">
            <h2 class="text-[#181411] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Addresses</h2>
            <div class="flex gap-4 bg-white px-4 py-3 justify-between">
              <div class="flex items-start gap-4">
                <div class="text-[#181411] flex items-center justify-center rounded-lg bg-[#f4f2f0] shrink-0 size-12" data-icon="House" data-size="24px" data-weight="regular">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M218.83,103.77l-80-75.48a1.14,1.14,0,0,1-.11-.11,16,16,0,0,0-21.53,0l-.11.11L37.17,103.77A16,16,0,0,0,32,115.55V208a16,16,0,0,0,16,16H96a16,16,0,0,0,16-16V160h32v48a16,16,0,0,0,16,16h48a16,16,0,0,0,16-16V115.55A16,16,0,0,0,218.83,103.77ZM208,208H160V160a16,16,0,0,0-16-16H112a16,16,0,0,0-16,16v48H48V115.55l.11-.1L128,40l79.9,75.43.11.1Z"></path>
                  </svg>
                </div>
                <div class="flex flex-1 flex-col justify-center">
                  <p class="text-[#181411] text-base font-medium leading-normal">Home</p>
                  <p class="text-[#887563] text-sm font-normal leading-normal">Apt 3B</p>
                  <p class="text-[#887563] text-sm font-normal leading-normal">123 Main St, Anytown, CA 91234</p>
                </div>
              </div>
              <div class="shrink-0">
                <div class="text-[#181411] flex size-7 items-center justify-center" data-icon="CaretRight" data-size="24px" data-weight="regular">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                    <path d="M181.66,133.66l-80,80a8,8,0,0,1-11.32-11.32L164.69,128,90.34,53.66a8,8,0,0,1,11.32-11.32l80,80A8,8,0,0,1,181.66,133.66Z"></path>
                  </svg>
                </div>
              </div>
            </div>
            <div class="flex gap-4 bg-white px-4 py-3 justify-between">
              <div class="flex items-start gap-4">
                <div class="text-[#181411] flex items-center justify-center rounded-lg bg-[#f4f2f0] shrink-0 size-12" data-icon="Briefcase" data-size="24px" data-weight="regular">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M216,56H176V48a24,24,0,0,0-24-24H104A24,24,0,0,0,80,48v8H40A16,16,0,0,0,24,72V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V72A16,16,0,0,0,216,56ZM96,48a8,8,0,0,1,8-8h48a8,8,0,0,1,8,8v8H96ZM216,72v41.61A184,184,0,0,1,128,136a184.07,184.07,0,0,1-88-22.38V72Zm0,128H40V131.64A200.19,200.19,0,0,0,128,152a200.25,200.25,0,0,0,88-20.37V200ZM104,112a8,8,0,0,1,8-8h32a8,8,0,0,1,0,16H112A8,8,0,0,1,104,112Z"></path>
                  </svg>
                </div>
                <div class="flex flex-1 flex-col justify-center">
                  <p class="text-[#181411] text-base font-medium leading-normal">Work</p>
                  <p class="text-[#887563] text-sm font-normal leading-normal">Floor 2</p>
                  <p class="text-[#887563] text-sm font-normal leading-normal">456 Oak Ave, Anytown, CA 91234</p>
                </div>
              </div>
              <div class="shrink-0">
                <div class="text-[#181411] flex size-7 items-center justify-center" data-icon="CaretRight" data-size="24px" data-weight="regular">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                    <path d="M181.66,133.66l-80,80a8,8,0,0,1-11.32-11.32L164.69,128,90.34,53.66a8,8,0,0,1,11.32-11.32l80,80A8,8,0,0,1,181.66,133.66Z"></path>
                  </svg>
                </div>
              </div>
            </div>
            <div class="flex px-4 py-3 justify-start">
              <button
                class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#f4f2f0] text-[#181411] text-sm font-bold leading-normal tracking-[0.015em]">
                <span class="truncate">Add Address</span>
              </button>
            </div>
          </section>
        </div>
      </div>
      <?php include 'footer.php'; ?>
    </div>
  </div>
  <?php include 'modal.php'; ?>
  <script src="js/auth.js?v=<?php echo time(); ?>"></script>
  <script src="js/index.js?v=<?php echo time(); ?>"></script>
  <script src="js/profile.js?v=<?php echo time(); ?>"></script>
</body>

</html>