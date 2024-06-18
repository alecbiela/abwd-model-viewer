/* ========================================================================
 * Bootstrap: tab.js v3.4.1
 * https://getbootstrap.com/docs/3.4/javascript/#tabs
 * ========================================================================
 * Copyright 2011-2019 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


window.onload = ( () => {
  'use strict';

  // TAB CLASS DEFINITION
  // ====================

  class Tab{
    constructor(element) {
      this.element = $(element);
      this.VERSION = '3.4.1';
      this.TRANSITION_DURATION = 150;
    }

    show(){
      const $this    = this.element;
      const $ul      = $this.closest('div.nav-tabs');
      let selector = $this.data('target');
  
      if ($this.hasClass('active')) { return; }
  
      const $previous = $ul.find('.active:last');
      const hideEvent = $.Event('hide.bs.tab', {
        relatedTarget: $this[0]
      });
      const showEvent = $.Event('show.bs.tab', {
        relatedTarget: $previous[0]
      });
  
      $previous.trigger(hideEvent);
      $this.trigger(showEvent);
  
      if (showEvent.isDefaultPrevented() || hideEvent.isDefaultPrevented()){ return; }
  
      const $target = $(document).find(selector);
  
      this.activate($this, $ul);
      this.activate($target, $target.parent(), function () {
        $previous.trigger({
          type: 'hidden.bs.tab',
          relatedTarget: $this[0]
        });
        $this.trigger({
          type: 'shown.bs.tab',
          relatedTarget: $previous[0]
        });
      });
    }

    activate(element, container, callback){
      const $active    = container.find('> .active');
      const transition = callback && $.support.transition && ($active.length && $active.hasClass('fade') || !!container.find('> .fade').length);
  
      function next() {
        $active
          .removeClass('active')
          .find('> .dropdown-menu > .active')
          .removeClass('active')
          .end()
          .find('[data-toggle="tab"]')
          .attr('aria-expanded', false);
  
        element
          .addClass('active')
          .find('[data-toggle="tab"]')
          .attr('aria-expanded', true);
  
        if (transition) {
          element[0].offsetWidth = element[0].offsetWidth; // reflow for transition
          element.addClass('in');
        } else {
          element.removeClass('fade');
        }
  
        if(callback) { callback(); }
      }
  
      if($active.length && transition){
        $active.one('bsTransitionEnd', next).emulateTransitionEnd(Tab.TRANSITION_DURATION);
      } else { next(); }
  
      $active.removeClass('in');
    }
  }


  // TAB PLUGIN DEFINITION
  // =====================

  function Plugin(option) {
    return this.each(function () {
      const $this = $(this);
      let data  = $this.data('bs.tab');

      if (!data) { $this.data('bs.tab', (data = new Tab(this))); }
      if (typeof option === 'string') { data[option](); }
    });
  }

  const old = $.fn.tab;

  $.fn.tab             = Plugin;
  $.fn.tab.Constructor = Tab;


  // TAB NO CONFLICT
  // ===============

  $.fn.tab.noConflict = function () {
    $.fn.tab = old;
    return this;
  };

  // TAB DATA-API
  // ============

  const clickHandler = (e) => {
    e.preventDefault();
    Plugin.call($(this), 'show');
  };

  $(document)
    .on('click.bs.tab.data-api', '[data-toggle="tab"]', clickHandler);

  $("#nav-tab button").click(function (e) { e.preventDefault(); $(this).tab("show"); });
})();
