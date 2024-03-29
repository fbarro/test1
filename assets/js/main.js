/**
* Template Name: HeroBiz - v2.2.0
* Template URL: https://bootstrapmade.com/herobiz-bootstrap-business-template/
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/
document.addEventListener('DOMContentLoaded', () => {
  "use strict";

  /**
   * Preloader
   */
  const preloader = document.querySelector('#preloader');
  if (preloader) {
    window.addEventListener('load', () => {
      preloader.remove();
    });
  }

  /**
   * Sticky header on scroll
   */
  const selectHeader = document.querySelector('#header');
  if (selectHeader) {
    document.addEventListener('scroll', () => {
      window.scrollY > 100 ? selectHeader.classList.add('sticked') : selectHeader.classList.remove('sticked');
    });
  }

  /**
   * Navbar links active state on scroll
   */
  let navbarlinks = document.querySelectorAll('#navbar .scrollto');

  function navbarlinksActive() {
    navbarlinks.forEach(navbarlink => {

      if (!navbarlink.hash) return;

      let section = document.querySelector(navbarlink.hash);
      if (!section) return;

      let position = window.scrollY;
      if (navbarlink.hash != '#header') position += 200;

      if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
        navbarlink.classList.add('active');
      } else {
        navbarlink.classList.remove('active');
      }
    })
  }
  window.addEventListener('load', navbarlinksActive);
  document.addEventListener('scroll', navbarlinksActive);

  /**
   * Function to scroll to an element with top ofset
   */
  function scrollto(el) {
    const selectHeader = document.querySelector('#header');
    let offset = 0;

    if (selectHeader.classList.contains('sticked')) {
      offset = document.querySelector('#header.sticked').offsetHeight;
    } else if (selectHeader.hasAttribute('data-scrollto-offset')) {
      offset = selectHeader.offsetHeight - parseInt(selectHeader.getAttribute('data-scrollto-offset'));
    }
    window.scrollTo({
      top: document.querySelector(el).offsetTop - offset,
      behavior: 'smooth'
    });
  }

  /**
   * Fires the scrollto function on click to links .scrollto
   */
  let selectScrollto = document.querySelectorAll('.scrollto');
  selectScrollto.forEach(el => el.addEventListener('click', function(event) {
    if (document.querySelector(this.hash)) {
      event.preventDefault();

      let mobileNavActive = document.querySelector('.mobile-nav-active');
      if (mobileNavActive) {
        mobileNavActive.classList.remove('mobile-nav-active');

        let navbarToggle = document.querySelector('.mobile-nav-toggle');
        navbarToggle.classList.toggle('bi-list');
        navbarToggle.classList.toggle('bi-x');
      }
      scrollto(this.hash);
    }
  }));

  /**
   * Scroll with ofset on page load with hash links in the url
   */
  window.addEventListener('load', () => {
    if (window.location.hash) {
      if (document.querySelector(window.location.hash)) {
        scrollto(window.location.hash);
      }
    }
  });

  /**
   * Mobile nav toggle
   */
  const mobileNavToogle = document.querySelector('.mobile-nav-toggle');
  if (mobileNavToogle) {
    mobileNavToogle.addEventListener('click', function(event) {
      event.preventDefault();

      document.querySelector('body').classList.toggle('mobile-nav-active');

      this.classList.toggle('bi-list');
      this.classList.toggle('bi-x');
    });
  }

  /**
   * Toggle mobile nav dropdowns
   */
  const navDropdowns = document.querySelectorAll('.navbar .dropdown > a');

  navDropdowns.forEach(el => {
    el.addEventListener('click', function(event) {
      if (document.querySelector('.mobile-nav-active')) {
        event.preventDefault();
        this.classList.toggle('active');
        this.nextElementSibling.classList.toggle('dropdown-active');

        let dropDownIndicator = this.querySelector('.dropdown-indicator');
        dropDownIndicator.classList.toggle('bi-chevron-up');
        dropDownIndicator.classList.toggle('bi-chevron-down');
      }
    })
  });

  /**
   * Auto generate the hero carousel indicators
   */
  let heroCarouselIndicators = document.querySelector('#hero .carousel-indicators');
  if (heroCarouselIndicators) {
    let heroCarouselItems = document.querySelectorAll('#hero .carousel-item')

    heroCarouselItems.forEach((item, index) => {
      if (index === 0) {
        heroCarouselIndicators.innerHTML += `<li data-bs-target="#hero" data-bs-slide-to="${index}" class="active"></li>`;
      } else {
        heroCarouselIndicators.innerHTML += `<li data-bs-target="#hero" data-bs-slide-to="${index}"></li>`;
      }
    });
  }

  /**
   * Scroll top button
   */
  const scrollTop = document.querySelector('.scroll-top');
  if (scrollTop) {
    const togglescrollTop = function() {
      window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
    }
    window.addEventListener('load', togglescrollTop);
    document.addEventListener('scroll', togglescrollTop);
    scrollTop.addEventListener('click', window.scrollTo({
      top: 0,
      behavior: 'smooth'
    }));
  }

  /**
   * Initiate glightbox
   */
  const glightbox = GLightbox({
    selector: '.glightbox'
  });

  /**
   * Porfolio isotope and filter
   */
  let portfolionIsotope = document.querySelector('.portfolio-isotope');

  if (portfolionIsotope) {

    let portfolioFilter = portfolionIsotope.getAttribute('data-portfolio-filter') ? portfolionIsotope.getAttribute('data-portfolio-filter') : '*';
    let portfolioLayout = portfolionIsotope.getAttribute('data-portfolio-layout') ? portfolionIsotope.getAttribute('data-portfolio-layout') : 'masonry';
    let portfolioSort = portfolionIsotope.getAttribute('data-portfolio-sort') ? portfolionIsotope.getAttribute('data-portfolio-sort') : 'original-order';

    window.addEventListener('load', () => {
      let portfolioIsotope = new Isotope(document.querySelector('.portfolio-container'), {
        itemSelector: '.portfolio-item',
        layoutMode: portfolioLayout,
        filter: portfolioFilter,
        sortBy: portfolioSort
      });

      let menuFilters = document.querySelectorAll('.portfolio-isotope .portfolio-flters li');
      menuFilters.forEach(function(el) {
        el.addEventListener('click', function() {
          document.querySelector('.portfolio-isotope .portfolio-flters .filter-active').classList.remove('filter-active');
          this.classList.add('filter-active');
          portfolioIsotope.arrange({
            filter: this.getAttribute('data-filter')
          });
          if (typeof aos_init === 'function') {
            aos_init();
          }
        }, false);
      });

    });

  }

  /**
   * Clients Slider
   */
  new Swiper('.clients-slider', {
    speed: 400,
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false
    },
    slidesPerView: 'auto',
    breakpoints: {
      320: {
        slidesPerView: 2,
        spaceBetween: 40
      },
      480: {
        slidesPerView: 3,
        spaceBetween: 60
      },
      640: {
        slidesPerView: 4,
        spaceBetween: 80
      },
      992: {
        slidesPerView: 6,
        spaceBetween: 120
      }
    }
  });

  /**
   * Testimonials Slider
   */
  new Swiper('.testimonials-slider', {
    speed: 600,
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false
    },
    slidesPerView: 'auto',
    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
      clickable: true
    }
  });

  /**
   * Testimonials Slider
   */
  new Swiper('.portfolio-details-slider', {
    speed: 600,
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false
    },
    slidesPerView: 'auto',
    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
      clickable: true
    }
  });

  /**
   * Animation on scroll function and init
   */
  function aos_init() {
    AOS.init({
      duration: 1000,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    });
  }
  window.addEventListener('load', () => {
    aos_init();
  });

});


var $j = jQuery.noConflict();
jQuery(document).ready(function(){
  let countryEmployerObj = $j("#countryEmployer");
  let countryEmployer1Obj = $j("#countryEmployer1");
  let countryEmployer2Obj = $j("#countryEmployer2");

  $j.getJSON('countries.json', function(data) {         
    countryEmployerObj.empty();
    countryEmployer1Obj.empty();
    countryEmployer2Obj.empty();
    countryEmployerObj.append("<option selected value=''>Choose Country</option>");
    countryEmployer1Obj.append("<option selected value=''>Choose Country</option>");
    countryEmployer2Obj.append("<option selected value=''>Choose Country</option>");
    for(let i = 0; i < data.length; i++){
      let country = data[i];

      countryEmployerObj.append("<option value='"+country.text+"'>"+country.text+"</option>");
      countryEmployer1Obj.append("<option value='"+country.text+"'>"+country.text+"</option>");
      countryEmployer2Obj.append("<option value='"+country.text+"'>"+country.text+"</option>");
    }
  });
  
  $j('.input-group.date').datepicker({
      autoclose: true,
      format: "mm/dd/yyyy"
   });
  
  // $j('#positionApplied').on('change', function() {
	//   if(this.value=='CARETAKER'){
	// 	  $j('#caretakerPart').removeClass('d-none');
	//   } else {
	// 	  $j('#caretakerPart').addClass('d-none');
	//   }
  // });

  let type = '';
  $j.each(document.location.search.substr(1).split('&'),function(c,q){
    let i = q.split('=');
    if(i && i[0]==='type'){
      type = i[1]
    }
  });
  // console.log(type);
  $j('#type').val(type);
});

const validFields = {'positionApplied' : 'Position Applied For',
'lastName' : 'Last Name', 'firstName' : 'First Name','middleName' : 'Middle Name',
'passportNo' : 'Passport No', 'birthdate' : 'Birth Date', 'age' : 'Age',
'placeOfBirth' :'Place Of Birth', 'address' : 'Address', 'mobileNo' : 'Mobile No',
'height' :'Height', 'weight' : 'Weight',
'fatherName' :"Father's Name", 'motherName' : "Mother\'s Name", 
'noOfBrothers' :"No Of Brothers", 'noOfSisters' : 'No Of Sisters', 'spouseName' :"Husband's Name",
'noOfChildren' : 'No. of Children', 'ageOfEldestChild' : 'Age Of Eldest Child', 'ageOfYoungestChild' : 'Age Of Youngest Child',
'nameOfSchool' : 'Name Of School (High School)', 'yearGraduated' : 'Year Graduated',
'nameOfSchool2' : 'Name Of School (College/Vocational)', 'course' : 'Course', 'yearGraduated' : 'Year Graduated',
'nameOfEmployer' :'Name Of Employer', 'position' : 'Position', 'yearFrom' : 'Year (From)', 'yearTo' : 'Year (To)', 'countryEmployer' : 'Country',
'nameOfEmployer1' :'Name Of Employer~NR', 'position1' : 'Position~NR', 'yearFrom1' : 'Year (From)~NR', 'yearTo1' : 'Year (To)~NR', 'countryEmployer1' : 'Country~NR',
'nameOfEmployer2' :'Name Of Employer~NR', 'position2' : 'Position~NR', 'yearFrom2' : 'Year (From)~NR', 'yearTo2' : 'Year (To)~NR', 'countryEmployer2' : 'Country~NR'};

(function() {
 
  'use strict';
  $j(window).on('load', function() {
      $j('.needs-validation').on('submit', function(e) {
        
        if (!this.checkValidity()) {
          e.preventDefault();
          e.stopPropagation();
        } else {
          submitContactForm(this, e);
        }

        $j(this).addClass('was-validated');
      });
  });
})();

function submitContactForm(formData, event){
    event.preventDefault();
    event.stopPropagation();

    // let captchaResp = grecaptcha.getResponse();
    // if(captchaResp==''){
    //   alert("Captcha verification failed, please try again.");
    //   return false;
    // }

    // $j('#captcha').val(captchaResp);
    // formData.append('captcha', grecaptcha.getResponse());
    var fd = new FormData(formData);
    loadingBlockShow();
    $j.ajax({
      type:'POST',
      url:'submit_form.php',
      data:  new FormData(formData),
      contentType: false,
      cache: false,
      processData:false,
      beforeSend: function () {
          $j('.submitBtn').attr("disabled","disabled");
      },
      success:function(msg){
        console.log(msg);
          if(msg == 'ok'){
              alert('Thanks for submitting your application to us, we\'ll get back to you soon');
              $j("#profileform")[0].reset(); 
              $j("#myModal").modal('hide');
          } else {
            alert(msg);
          }

          $j('#submitBtn').removeAttr("disabled");
          loadingBlockHide();
         
      },
      error: function (jqXHR, textStatus, errorThrown) { 
         $j('.submitBtn').removeAttr("disabled");
         alert('Something went wrong, please try again.');
         loadingBlockHide();
      }
  });
}

