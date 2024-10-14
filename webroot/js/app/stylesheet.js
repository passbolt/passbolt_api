/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.3.2
 */

(function () {
  class Stylesheet {
    constructor() {
      this.bindCallbacks();
      this.init();
    }

    /**
     * Bind callbacks
     */
    bindCallbacks() {
      this.setThemeFromOsPreference = this.setThemeFromOsPreference.bind(this);
    }

    /**
     * Initialise to get the theme from stylesheet or OS preference
     */
    init() {
      this.stylesheetTag = document.querySelector('#stylesheet-manager');
      if (this.isThemeDefined()) {
        this.theme = this.stylesheetTag.dataset.theme;
      } else {
        this.mediaQueryPreferColor = window.matchMedia('(prefers-color-scheme: dark)');
        this.setThemeFromOsPreference(this.mediaQueryPreferColor);
        this.mediaQueryPreferColor.addEventListener("change", this.setThemeFromOsPreference);
      }
      this.updateStylesWithUserPreferences();
    }

    /**
     * Update link reference with the theme
     */
    updateStylesWithUserPreferences() {
      if (!this.stylesheetTag) {
        return;
      }

      const baseUrl = document.querySelector("base").getAttribute("href").replace(/\/$/, '');
      const cssFile = this.stylesheetTag.dataset.file;
      const version = this.stylesheetTag.getAttribute("cache-version");

      this.getLinkTag().setAttribute("href", `${baseUrl}/css/themes/${this.theme}/${cssFile}?v=${version}`);
    }

    /**
     * Get link tag
     * @returns {Element}
     */
    getLinkTag() {
      let link = document.querySelector("#stylesheet");
      if (link) {
        return link;
      }

      link = document.createElement("link");
      link.setAttribute("id", "stylesheet");
      link.setAttribute("media", "all");
      link.setAttribute("rel", "stylesheet");

      document.querySelector("head").appendChild(link);

      return link;
    }

    /**
     * Is theme defined
     * @returns {boolean}
     */
    isThemeDefined() {
      return Boolean(this.stylesheetTag.dataset.theme);
    }

    /**
     * Set theme according to the OS preference
     * @param mediaQueryPreferColor
     */
    setThemeFromOsPreference(mediaQueryPreferColor) {
      this.theme = mediaQueryPreferColor.matches
        ? "midgar"
        : "default";
      this.updateStylesWithUserPreferences();
    }
  }
  new Stylesheet();
})();
