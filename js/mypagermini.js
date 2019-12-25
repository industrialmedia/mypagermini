(function ($, Drupal) {
  "use strict";

  /* Сделать рабочим ajax для пейджера с div.link вместо ссылки */
  var ajaxViewAttachPagerAjax = Drupal.views.ajaxView.prototype.attachPagerAjax;
  Drupal.views.ajaxView.prototype.attachPagerAjax = function () {
    ajaxViewAttachPagerAjax.apply(this, arguments);
    this.$view.find('ul.js-pager__items > li > div.link').each($.proxy(this.attachPagerLinkAjax, this));
  };
  
})(jQuery, Drupal);
