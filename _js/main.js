ready(function () {
  if (window.dl) {
    pushPageView();
  }
});

var pushPageView = function () {
  var pageView = {
    event: 'pageView',
    page: preparePage(window.dl.page),
    user: window.dl.user,
  };
  console.log(pageView); // remove
  dataLayer.push(pageView);
};

var preparePage = function (page) {
  page.path = window.location.pathname;
  page.url = window.location.href;
  if (window.location.search || window.location.hash) {
   page.params = window.location.search + window.location.hash;
  }
  return page;
};