// let mainNavs = document.getElementsByClassName('main-nav');
// let index = 0;
// for(let mainNav of mainNavs) {
// 	if(mainNav){
//     mainNav.classList.add('index-'+index);
//     document.querySelector('.index-'+index).addEventListener('click', toggleMainNav, false);
//     index++;
//   }
// }

// function toggleMainNav(event) {
// 	event.target.nextElementSibling.classList.toggle('hidden');
// };

// let subNavs = document.getElementsByClassName('sub-nav');
// let subnav_index = 0;
// for(let subNav of subNavs) {
// 	if(subNav){
//     subNav.classList.add('index-'+subnav_index);
//     document.querySelector('.index-'+subnav_index).addEventListener('click', toggleSubNav, false);
//     subnav_index++;
//   }
// }

// function toggleSubNav(event) {
// 	event.target.nextElementSibling.classList.toggle('hidden');
// };

// var mobilemenu = document.querySelector('.sm-nav-menu');

// function toggleSidebar() { 
// 	var sidebar = document.querySelector('.mobile-sidebar-menu');
// 	sidebar.classList.toggle('hidden');
// }

// mobilemenu.addEventListener('click', toggleSidebar, false);


let mainMenus = document.getElementsByClassName('main-menu-item');
let index = 0;
for(let mainMenu of mainMenus) {
	if(mainMenu){
    mainMenu.classList.add('index-'+index);
    document.querySelector('.index-'+index).addEventListener('click', toggleMainMenu, false);
    index++;
  }
}

function toggleMainMenu(event) {
	event.target.nextElementSibling.classList.toggle('hidden');
};

let subMenus = document.getElementsByClassName('menu-subitem');
for(let subMenu of subMenus) {
	if(subMenu){
    subMenu.classList.add('index-'+index);
    document.querySelector('.index-'+index).addEventListener('click', toggleSubMenu, false);
    index++;
  }
}

function toggleSubMenu(event) {
	event.target.nextElementSibling.classList.toggle('hidden');
};

var mobilemenu = document.querySelector('.sm-nav-menu');

function toggleSidebar() { 
	var sidebar = document.querySelector('.mobile-sidebar-menu');
	sidebar.classList.toggle('hidden');
}

mobilemenu.addEventListener('click', toggleSidebar, false);

var profilemenu = document.querySelector('.profile-menu');

function toggleProfileMenu() { 
	var floatingmenu = document.querySelector('.floating-profile-menu');
	floatingmenu.classList.toggle('hidden');
}

profilemenu.addEventListener('click', toggleProfileMenu, false);

$(document).ready(function () {
   $('.sub-menu-container > a').on("click", function (e) {
        e.preventDefault();
        $(this).toggleClass('sldm-open');
        $(this).parent().find('>ul').slideToggle(450);
    });

   $('.second-sub-menu-container > a').on("click", function (e) {
        e.preventDefault();
        $(this).toggleClass('sldm-open');
        $(this).parent().find('>ul').slideToggle(450);
    });

   $('.toggle-details').on('click', function (e) {
      e.preventDefault();
      $(this).siblings('.details').toggleClass('hidden');
   });
});