// menu show
const showMenu = (toggleId, navId) => {
  const toggle = document.getElementById(toggleId);
  const nav = document.getElementById(navId);
  if (toggle && nav) {
    toggle.addEventListener("click", () => {
      nav.classList.toggle("show");
    });
  }
};

showMenu("nav-toggle", "nav-menu_ss");

var mainImg = document.getElementById("main-product-img");
var smallImg = document.getElementsByClassName("small-img");

for (let i = 0; i < smallImg.length; i++) {
  smallImg[i].onclick = function () {
    mainImg.src = smallImg[i].src;
  };
}

// navbar showing
window.addEventListener("load", function () {
  const header = document.querySelector(".nav_container");
  let prevScrollPos = window.scrollY;

  function isIndexPage() {
    return window.location.href.indexOf("index.php") > -1;
  }
  header.classList.toggle("on_top", window.scrollY < 300 && isIndexPage());

  window.onscroll = function () {
    const currentScrollPos = window.scrollY;

    header.classList.toggle("on_top", window.scrollY < 300 && isIndexPage());
    if (prevScrollPos > currentScrollPos) {
      header.style.top = "0px";
    } else {
      if (header.style.top != "-100px") {
        header.style.top = "-100px";
      }
    }

    prevScrollPos = currentScrollPos;
  };
});

// cookies
setCookie = (cName, cValue, expDays) => {
  let date = new Date();
  date.setTime(date.getTime() + expDays * 24 * 60 * 60 * 1000);
  const expires = "expires=" + date.toUTCString();
  document.cookie = cName + "=" + cValue + ";" + expDays + "; path=/";
};

getCookie = (cName) => {
  const name = cName + "=";
  const cDecoded = decodeURIComponent(document.cookie);
  const cArr = cDecoded.split("; ");
  let value;
  cArr.forEach((val) => {
    if (val.indexOf(name) === 0) value = val.substring(name.length);
  });

  return value;
};

document.querySelector("#cookies-btn").addEventListener("click", () => {
  document.querySelector("#cookies").style.display = "none";
  setCookie("cookie", true, 30);
});

cookieMessage = () => {
  if (!getCookie("cookie"))
    document.querySelector("#cookies").style.display = "block";
};

window.addEventListener("load", cookieMessage);

// // thank you popup
// document.getElementById("pay_btn").addEventListener("click", function () {
//   document.getElementsByClassName("blur")[0].classList.add("active_pop");
//   document
//     .getElementsByClassName("t_y_container")[0]
//     .classList.add("active_pop");
// });

// document
//   .getElementById("go_to_home_btn")
//   .addEventListener("click", async function () {
//     document
//       .getElementsByClassName("t_y_container")[0]
//       .classList.remove("active_pop");
//     await new Promise((resolve) => setTimeout(resolve, 700));
//     document.getElementsByClassName("blur")[0].classList.remove("active_pop");
//   });
