!function(n){var t={};function s(e){if(t[e])return t[e].exports;var r=t[e]={i:e,l:!1,exports:{}};return n[e].call(r.exports,r,r.exports,s),r.l=!0,r.exports}s.m=n,s.c=t,s.d=function(e,r,n){s.o(e,r)||Object.defineProperty(e,r,{enumerable:!0,get:n})},s.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},s.t=function(r,e){if(1&e&&(r=s(r)),8&e)return r;if(4&e&&"object"==typeof r&&r&&r.__esModule)return r;var n=Object.create(null);if(s.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:r}),2&e&&"string"!=typeof r)for(var t in r)s.d(n,t,function(e){return r[e]}.bind(null,t));return n},s.n=function(e){var r=e&&e.__esModule?function(){return e.default}:function(){return e};return s.d(r,"a",r),r},s.o=function(e,r){return Object.prototype.hasOwnProperty.call(e,r)},s.p="",s(s.s="./src/Resources/assets/backend/javascript/be_member_import.js")}({"./node_modules/document-ready/index.js":function(module,exports,__webpack_require__){"use strict";eval("\n\nmodule.exports = ready\n\nfunction ready (callback) {\n  if (typeof document === 'undefined') {\n    throw new Error('document-ready only runs in the browser')\n  }\n  var state = document.readyState\n  if (state === 'complete' || state === 'interactive') {\n    return setTimeout(callback, 0)\n  }\n\n  document.addEventListener('DOMContentLoaded', function onLoad () {\n    callback()\n  })\n}\n\n\n//# sourceURL=webpack:///./node_modules/document-ready/index.js?")},"./node_modules/nodelist-foreach-polyfill/index.js":function(module,exports){eval("if (window.NodeList && !NodeList.prototype.forEach) {\r\n    NodeList.prototype.forEach = function (callback, thisArg) {\r\n        thisArg = thisArg || window;\r\n        for (var i = 0; i < this.length; i++) {\r\n            callback.call(thisArg, this[i], i, this);\r\n        }\r\n    };\r\n}\r\n\n\n//# sourceURL=webpack:///./node_modules/nodelist-foreach-polyfill/index.js?")},"./src/Resources/assets/backend/javascript/be_member_import.js":function(module,__webpack_exports__,__webpack_require__){"use strict";eval('__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _sass_be_member_import_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../sass/be_member_import.scss */ "./src/Resources/assets/backend/sass/be_member_import.scss");\n/* harmony import */ var _sass_be_member_import_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_sass_be_member_import_scss__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _importer__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./importer */ "./src/Resources/assets/backend/javascript/importer.js");\n\n\n\n//# sourceURL=webpack:///./src/Resources/assets/backend/javascript/be_member_import.js?')},"./src/Resources/assets/backend/javascript/importer.js":function(module,__webpack_exports__,__webpack_require__){"use strict";eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var nodelist_foreach_polyfill__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! nodelist-foreach-polyfill */ \"./node_modules/nodelist-foreach-polyfill/index.js\");\n/* harmony import */ var nodelist_foreach_polyfill__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(nodelist_foreach_polyfill__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var document_ready__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! document-ready */ \"./node_modules/document-ready/index.js\");\n/* harmony import */ var document_ready__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(document_ready__WEBPACK_IMPORTED_MODULE_1__);\nfunction _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError(\"Cannot call a class as a function\"); } }\n\nfunction _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if (\"value\" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }\n\nfunction _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }\n\n\n\n\nvar Importer =\n/*#__PURE__*/\nfunction () {\n  function Importer() {\n    _classCallCheck(this, Importer);\n\n    this.terminate = false;\n    this.loadWrapper = document.querySelector('#tl_load_action');\n    this.prepareWrapper = document.querySelector('#tl_prepare_action');\n    this.importWrapper = document.querySelector('#tl_import_action');\n    this.errortWrapper = document.querySelector('#error');\n    this.loadProgressbar = this.loadWrapper.querySelector('.progress-bar-inner');\n    this.prepareProgressbar = this.prepareWrapper.querySelector('.progress-bar-inner');\n    this.importProgressbar = this.importWrapper.querySelector('.progress-bar-inner');\n    this.debug = location.href.search('app_dev.php') > 1 ? '/app_dev.php' : '';\n  }\n\n  _createClass(Importer, [{\n    key: \"init\",\n    value: function init() {\n      var self = this;\n      document.querySelector('#terminate').addEventListener('click', function (event) {\n        event.preventDefault();\n        self.terminate = true;\n      });\n      this.load();\n    }\n  }, {\n    key: \"load\",\n    value: function load() {\n      if (this.terminate) {\n        return false;\n      }\n\n      var route = this.debug + '/contao/cb/member/import/load';\n      var self = this;\n      this.loadWrapper.classList.remove('not_active');\n      this.request(route, function (response) {\n        if (response.progress < 100) {\n          self.loadProgressbar.style.width = response.progress + '%';\n          self.load();\n          return false;\n        }\n\n        self.loadProgressbar.addEventListener('transitionend', function () {\n          self.prepare();\n        });\n        self.loadProgressbar.style.width = response.progress + '%';\n      });\n    }\n  }, {\n    key: \"prepare\",\n    value: function prepare() {\n      if (this.terminate) {\n        return false;\n      }\n\n      var route = this.debug + '/contao/cb/member/import/prepare';\n      var self = this;\n      this.prepareWrapper.classList.remove('not_active');\n      this.request(route, function (response) {\n        self.prepareProgressbar.style.width = response.progress + '%';\n\n        if (response.progress < 100) {\n          self.prepare();\n          return false;\n        }\n\n        self.prepareProgressbar.style.width = response.progress + '%';\n        self.prepareProgressbar.addEventListener('transitionend', function () {\n          self.import();\n        });\n      });\n    }\n  }, {\n    key: \"import\",\n    value: function _import() {\n      if (this.terminate) {\n        return false;\n      }\n\n      var route = this.debug + '/contao/cb/member/import/import';\n      var self = this;\n      this.importWrapper.classList.remove('not_active');\n      this.request(route, function (response) {\n        self.importProgressbar.style.width = response.progress + '%';\n\n        if (response.progress < 100) {\n          self.import();\n          return false;\n        }\n\n        self.importProgressbar.style.width = response.progress + '%';\n        self.importProgressbar.addEventListener('transitionend', function () {\n          self.finish();\n        });\n      });\n    }\n  }, {\n    key: \"finish\",\n    value: function finish() {\n      location.href = this.debug + '/contao?do=member';\n    }\n  }, {\n    key: \"request\",\n    value: function request(route, callback) {\n      if (this.terminate) {\n        return false;\n      }\n\n      var request = new XMLHttpRequest();\n      var self = this;\n\n      request.onreadystatechange = function () {\n        if (4 === request.readyState) {\n          var response = JSON.parse(request.responseText);\n\n          if (undefined === response.error) {\n            callback(response);\n            return true;\n          }\n\n          self.errortWrapper.innerHTML = response.error;\n        }\n      };\n\n      request.open('GET', route);\n      request.send();\n    }\n  }]);\n\n  return Importer;\n}();\n\ndocument_ready__WEBPACK_IMPORTED_MODULE_1___default()(function () {\n  var importer = new Importer();\n  importer.init();\n});\n\n//# sourceURL=webpack:///./src/Resources/assets/backend/javascript/importer.js?")},"./src/Resources/assets/backend/sass/be_member_import.scss":function(module,exports,__webpack_require__){eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./src/Resources/assets/backend/sass/be_member_import.scss?")}});