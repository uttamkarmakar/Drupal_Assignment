/**
 * @file
 * Some basic behaviors and utility functions for Views.
 */

(function ($, Drupal, drupalSettings) {
  /**
   * @namespace
   */
  Drupal.Views = {};

  /**
   * Helper function to parse a querystring.
   *
   * @param {string} query
   *   The querystring to parse.
   *
   * @return {object}
   *   A map of query parameters.
   */
  Drupal.Views.parseQueryString = function (query) {
    const args = {};
    const pos = query.indexOf('?');
    if (pos !== -1) {
      query = query.substring(pos + 1);
    }
    let pair;
    const pairs = query.split('&');
    for (let i = 0; i < pairs.length; i++) {
      pair = pairs[i].split('=');
      // Ignore the 'q' path argument, if present.
      if (pair[0] !== 'q' && pair[1]) {
        args[decodeURIComponent(pair[0].replace(/\+/g, ' '))] =
          decodeURIComponent(pair[1].replace(/\+/g, ' '));
      }
    }
    return args;
  };

  /**
   * Helper function to return a view's arguments based on a path.
   *
   * @param {string} href
   *   The href to check.
   * @param {string} viewPath
   *   The views path to check.
   *
   * @return {object}
   *   An object containing `view_args` and `view_path`.
   */
  Drupal.Views.parseViewArgs = function (href, viewPath) {
    const returnObj = {};
    const path = Drupal.Views.getPath(href);
    // Get viewPath url without baseUrl portion.
    const viewHref = Drupal.url(viewPath).substring(
      drupalSettings.path.baseUrl.length,
    );
    // Ensure we have a correct path.
    if (viewHref && path.substring(0, viewHref.length + 1) === `${viewHref}/`) {
      returnObj.view_args = decodeURIComponent(
        path.substring(viewHref.length + 1, path.length),
      );
      returnObj.view_path = path;
    }
    return returnObj;
  };

  /**
   * Strip off the protocol plus domain from an href.
   *
   * @param {string} href
   *   The href to strip.
   *
   * @return {string}
   *   The href without the protocol and domain.
   */
  Drupal.Views.pathPortion = function (href) {
    // Remove e.g. http://example.com if present.
    const protocol = window.location.protocol;
    if (href.substring(0, protocol.length) === protocol) {
      // 2 is the length of the '//' that normally follows the protocol.
      href = href.substring(href.indexOf('/', protocol.length + 2));
    }
    return href;
  };

  /**
   * Return the Drupal path portion of an href.
   *
   * @param {string} href
   *   The href to check.
   *
   * @return {string}
   *   An internal path.
   */
  Drupal.Views.getPath = function (href) {
    href = Drupal.Views.pathPortion(href);
    href = href.substring(drupalSettings.path.baseUrl.length, href.length);
    // 3 is the length of the '?q=' added to the url without clean urls.
    if (href.substring(0, 3) === '?q=') {
      href = href.substring(3, href.length);
    }
    const chars = ['#', '?', '&'];
    for (let i = 0; i < chars.length; i++) {
      if (href.indexOf(chars[i]) > -1) {
        href = href.substr(0, href.indexOf(chars[i]));
      }
    }
    return href;
  };
})(jQuery, Drupal, drupalSettings);
;
/**
 * @file
 * Handles AJAX fetching of views, including filter submission and response.
 */

(function ($, Drupal, drupalSettings) {
  /**
   * Attaches the AJAX behavior to exposed filters forms and key View links.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches ajaxView functionality to relevant elements.
   */
  Drupal.behaviors.ViewsAjaxView = {};
  Drupal.behaviors.ViewsAjaxView.attach = function (context, settings) {
    if (settings && settings.views && settings.views.ajaxViews) {
      const {
        views: { ajaxViews },
      } = settings;
      Object.keys(ajaxViews || {}).forEach((i) => {
        Drupal.views.instances[i] = new Drupal.views.ajaxView(ajaxViews[i]);
      });
    }
  };
  Drupal.behaviors.ViewsAjaxView.detach = (context, settings, trigger) => {
    if (trigger === 'unload') {
      if (settings && settings.views && settings.views.ajaxViews) {
        const {
          views: { ajaxViews },
        } = settings;
        Object.keys(ajaxViews || {}).forEach((i) => {
          const selector = `.js-view-dom-id-${ajaxViews[i].view_dom_id}`;
          if ($(selector, context).length) {
            delete Drupal.views.instances[i];
            delete settings.views.ajaxViews[i];
          }
        });
      }
    }
  };

  /**
   * @namespace
   */
  Drupal.views = {};

  /**
   * @type {object.<string, Drupal.views.ajaxView>}
   */
  Drupal.views.instances = {};

  /**
   * JavaScript object for a certain view.
   *
   * @constructor
   *
   * @param {object} settings
   *   Settings object for the ajax view.
   * @param {string} settings.view_dom_id
   *   The DOM id of the view.
   */
  Drupal.views.ajaxView = function (settings) {
    const selector = `.js-view-dom-id-${settings.view_dom_id}`;
    this.$view = $(selector);

    // Retrieve the path to use for views' ajax.
    let ajaxPath = drupalSettings.views.ajax_path;

    // If there are multiple views this might've ended up showing up multiple
    // times.
    if (ajaxPath.constructor.toString().indexOf('Array') !== -1) {
      ajaxPath = ajaxPath[0];
    }

    // Check if there are any GET parameters to send to views.
    let queryString = window.location.search || '';
    if (queryString !== '') {
      // Remove the question mark and Drupal path component if any.
      queryString = queryString
        .slice(1)
        .replace(/q=[^&]+&?|&?render=[^&]+/, '');
      if (queryString !== '') {
        // If there is a '?' in ajaxPath, clean url are on and & should be
        // used to add parameters.
        queryString = (/\?/.test(ajaxPath) ? '&' : '?') + queryString;
      }
    }

    this.element_settings = {
      url: ajaxPath + queryString,
      submit: settings,
      setClick: true,
      event: 'click',
      selector,
      progress: { type: 'fullscreen' },
    };

    this.settings = settings;

    // Add the ajax to exposed forms.
    this.$exposed_form = $(
      `form#views-exposed-form-${settings.view_name.replace(
        /_/g,
        '-',
      )}-${settings.view_display_id.replace(/_/g, '-')}`,
    );
    once('exposed-form', this.$exposed_form).forEach(
      $.proxy(this.attachExposedFormAjax, this),
    );

    // Add the ajax to pagers.
    once(
      'ajax-pager',
      this.$view
        // Don't attach to nested views. Doing so would attach multiple behaviors
        // to a given element.
        .filter($.proxy(this.filterNestedViews, this)),
    ).forEach($.proxy(this.attachPagerAjax, this));

    // Add a trigger to update this view specifically. In order to trigger a
    // refresh use the following code.
    //
    // @code
    // $('.view-name').trigger('RefreshView');
    // @endcode
    const selfSettings = $.extend({}, this.element_settings, {
      event: 'RefreshView',
      base: this.selector,
      element: this.$view.get(0),
    });
    this.refreshViewAjax = Drupal.ajax(selfSettings);
  };

  /**
   * @method
   */
  Drupal.views.ajaxView.prototype.attachExposedFormAjax = function () {
    const that = this;
    this.exposedFormAjax = [];
    // Exclude the reset buttons so no AJAX behaviors are bound. Many things
    // break during the form reset phase if using AJAX.
    $(
      'input[type=submit], button[type=submit], input[type=image]',
      this.$exposed_form,
    )
      .not('[data-drupal-selector=edit-reset]')
      .each(function (index) {
        const selfSettings = $.extend({}, that.element_settings, {
          base: $(this).attr('id'),
          element: this,
        });
        that.exposedFormAjax[index] = Drupal.ajax(selfSettings);
      });
  };

  /**
   * @return {boolean}
   *   If there is at least one parent with a view class return false.
   */
  Drupal.views.ajaxView.prototype.filterNestedViews = function () {
    // If there is at least one parent with a view class, this view
    // is nested (e.g., an attachment). Bail.
    return !this.$view.parents('.view').length;
  };

  /**
   * Attach the ajax behavior to each link.
   */
  Drupal.views.ajaxView.prototype.attachPagerAjax = function () {
    this.$view
      .find(
        'ul.js-pager__items > li > a, th.views-field a, .attachment .views-summary a',
      )
      .each($.proxy(this.attachPagerLinkAjax, this));
  };

  /**
   * Attach the ajax behavior to a singe link.
   *
   * @param {string} [id]
   *   The ID of the link.
   * @param {HTMLElement} link
   *   The link element.
   */
  Drupal.views.ajaxView.prototype.attachPagerLinkAjax = function (id, link) {
    const $link = $(link);
    const viewData = {};
    const href = $link.attr('href');
    // Construct an object using the settings defaults and then overriding
    // with data specific to the link.
    $.extend(
      viewData,
      this.settings,
      Drupal.Views.parseQueryString(href),
      // Extract argument data from the URL.
      Drupal.Views.parseViewArgs(href, this.settings.view_base_path),
    );

    const selfSettings = $.extend({}, this.element_settings, {
      submit: viewData,
      base: false,
      element: link,
    });
    this.pagerAjax = Drupal.ajax(selfSettings);
  };

  /**
   * Views scroll to top ajax command.
   *
   * @param {Drupal.Ajax} [ajax]
   *   A {@link Drupal.ajax} object.
   * @param {object} response
   *   Ajax response.
   * @param {string} response.selector
   *   Selector to use.
   */
  Drupal.AjaxCommands.prototype.viewsScrollTop = function (ajax, response) {
    // Scroll to the top of the view. This will allow users
    // to browse newly loaded content after e.g. clicking a pager
    // link.
    const offset = $(response.selector).offset();
    // We can't guarantee that the scrollable object should be
    // the body, as the view could be embedded in something
    // more complex such as a modal popup. Recurse up the DOM
    // and scroll the first element that has a non-zero top.
    let scrollTarget = response.selector;
    while ($(scrollTarget).scrollTop() === 0 && $(scrollTarget).parent()) {
      scrollTarget = $(scrollTarget).parent();
    }
    // Only scroll upward.
    if (offset.top - 10 < $(scrollTarget).scrollTop()) {
      $(scrollTarget).animate({ scrollTop: offset.top - 10 }, 500);
    }
  };
})(jQuery, Drupal, drupalSettings);
;
