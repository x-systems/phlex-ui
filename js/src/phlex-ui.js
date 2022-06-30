/* eslint-disable */
/* global _PHLEXVERSION_:true, __webpack_public_path__:true */
__webpack_public_path__ = window.__phlexBundlePublicPath === undefined ? '/public/' :  window.__phlexBundlePublicPath + '/';

import debounce from 'debounce';
import 'core-js/stable';
import phlexSemantic from 'phlex-semantic-ui';
import date from 'locutus/php/datetime/date';
import { tableDropdown } from './helpers/table-dropdown.helper';
import { plugin } from './plugin';
import { phlexOptions, phlexEventBus, phlexUtils } from './phlex-utils';
import dataService from './services/data.service';
import panelService from './services/panel.service';
import vueService from './services/vue.service';
import popupService from "./services/popup.service";

const phlex = { ...phlexSemantic };

// add version function to phlex.
phlex.version = () => _PHLEXVERSION_;
phlex.options = phlexOptions;
phlex.eventBus = phlexEventBus;
phlex.utils = phlexUtils;

phlex.debounce = (fn, value, immediate = false) => {
    const timeOut = phlex.options.get('debounceTimeout');
    return debounce(fn, timeOut !== null ? timeOut : value, immediate);
};

// Allow to register a plugin with jQuery;
phlex.registerPlugin = plugin;

phlex.phpDate = date;
phlex.dataService = dataService;
phlex.panelService = panelService;
phlex.tableDropdown = tableDropdown;
phlex.vueService = vueService;
phlex.popupService = popupService;

/**
 * Exporting services in order to be available globally
 * or by importing it into your own module.
 *
 * Available as a global Var: phlex.uploadService.fileUpload()
 * Available as an import:
 *  import phlex from phlex-js;
 *  phlex.uploadService.fileUpload();
 */
export default phlex;
